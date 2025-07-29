<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Job;
use Illuminate\Support\Str;
use App\Mail\JobAppliedMail;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;
use App\Models\JobApplicationAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $activeJobId = $request->query('job_id');
        $showCount = (int) $request->query('show_count', 5);
        $showMoreStep = 5;

        $user = auth()->user();
        $isJobSeeker = $user && $user->role === 'job_seeker';

        $jobsQuery = Job::where('status', 'active')
            ->where('is_approved', true)
            ->whereDate('deadline', '>=', Carbon::today());

        $recommendedJobs = collect();

        if ($isJobSeeker) {
            // Collect basic user profile data
            $userSkills = explode(',', strtolower($user->skills ?? ''));
            $userCity = strtolower($user->city ?? '');
            $userEducation = strtolower($user->education ?? '');
            $userExperience = strtolower($user->experience ?? '');

            // Run job matching logic
            $recommendedJobs = $jobsQuery->get()->filter(function ($job) use ($userSkills, $userCity, $userEducation, $userExperience) {
                $jobSkills = explode(',', strtolower($job->skills ?? ''));
                $jobLocation = strtolower($job->location ?? '');
                $jobEducation = strtolower($job->education ?? '');
                $jobExperience = strtolower($job->experience ?? '');

                $skillMatch = count(array_intersect($userSkills, $jobSkills));
                $locationMatch = $userCity && $jobLocation && str_contains($jobLocation, $userCity);
                $educationMatch = $userEducation && $jobEducation && str_contains($userEducation, $jobEducation);
                $experienceMatch = $userExperience && $jobExperience && str_contains($userExperience, $jobExperience);

                return $skillMatch || $locationMatch || $educationMatch || $experienceMatch;
            });
        }

        $recommendedJobs = $recommendedJobs->values(); // reindex collection

        if ($isJobSeeker && $recommendedJobs->isNotEmpty()) {
            $jobs = $recommendedJobs;
        } else {
            // fallback to general jobs if no recommendations found
            $jobs = $jobsQuery->get();
        }

        $totalJobs = $jobs->count();
        $displayJobs = $jobs->take($showCount);
        $canShowMore = $showCount < $totalJobs;

        $appliedJobIds = auth()->check()
            ? JobApplication::where('user_id', auth()->id())->pluck('job_id')->toArray()
            : [];

        $showAll = false;

        return view('home', compact(
            'jobs',
            'activeJobId',
            'showCount',
            'totalJobs',
            'displayJobs',
            'canShowMore',
            'showAll',
            'showMoreStep',
            'appliedJobIds'
        ));
    }


    public function show($id)
    {
        $job = Job::where('id', $id)
            ->where('status', 'active')
            ->where('is_approved', true)
            ->whereDate('deadline', '>=', \Carbon\Carbon::today())
            ->firstOrFail();
        return view('partials.job-detail', ['job' => $job]);
    }

    public function share($id)
    {
        $job = Job::where('id', $id)->where('status', 'active')->where('is_approved', true)->first();

        if (!$job || Carbon::parse($job->deadline)->lt(Carbon::today())) {
            return redirect()->route('home')->with('error', 'This job is no longer available.');
        }

        return view('partials.job-share', ['job' => $job]);
    }



    public function fullView($id)
    {
        $job = Job::where('id', $id)
            ->where('status', 'active')
            ->where('is_approved', true)
            ->whereDate('deadline', '>=', Carbon::today())
            ->firstOrFail();

        // ✅ Track unique views using session
        $viewKey = 'job_viewed_' . $job->id;
        if (!session()->has($viewKey)) {
            $job->increment('views');
            session()->put($viewKey, true);
        }

        // 🧠 Handle skills parsing
        $skillsRaw = property_exists($job, 'skills') ? $job->skills : (is_array($job) && isset($job['skills']) ? $job['skills'] : []);
        if (is_array($skillsRaw)) {
            $skills = $skillsRaw;
        } elseif (is_string($skillsRaw)) {
            $decoded = json_decode($skillsRaw, true);
            $skills = is_array($decoded) ? $decoded : array_filter(array_map('trim', preg_split('/[;,]/', $skillsRaw)));
        } else {
            $skills = [];
        }

        $initialSkills = array_slice($skills, 0, 4);
        $remainingSkills = array_slice($skills, 4);

        $relatedJobs = Job::where('industry', $job->industry)
            ->where('id', '!=', $job->id)
            ->whereDate('deadline', '>=', Carbon::today())
            ->where('status', 'active')
            ->where('is_approved', true)
            ->limit(5)
            ->get();

        return view('job.full-view', [
            'job' => $job,
            'skills' => $skills,
            'initialSkills' => $initialSkills,
            'remainingSkills' => $remainingSkills,
            'relatedJobs' => $relatedJobs,
        ]);
    }


    public function applyView(Request $request, $id)
    {
        $job = Job::with('additionalQuestions')->where([
            ['id', $id],
            ['is_approved', 1],
            ['status', 'active'],
        ])->where(function ($query) {
            $query->whereNull('deadline')->orWhere('deadline', '>=', now());
        })->first();

        if (!$job) {
            return back()->with('error', 'Job is not available for application.');
        }

        // Check if already applied
        $alreadyApplied = JobApplication::where('job_id', $id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyApplied) {
            return redirect()->back()->with('error', 'You Already Applied This Job Browse For New Job');
        }
        return view('apply.job_apply', compact('job', 'alreadyApplied'));
    }

    public function applySubmit(Request $request, $id)
    {
        $job = Job::with('additionalQuestions')->where([
            ['id', $id],
            ['is_approved', 1],
            ['status', 'active'],
        ])->where(function ($query) {
            $query->whereNull('deadline')->orWhere('deadline', '>=', now());
        })->first();

        if (!$job) {
            return back()->with('error', 'Job is not available for application.');
        }

        // Prevent duplicate application
        if (JobApplication::where('job_id', $id)->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Validation
        $rules = [
            'first_name'   => 'required|string|max:191',
            'last_name'    => 'required|string|max:191',
            'email'        => 'required|email|max:191',
            'city'         => 'required|string|max:191',
            'country'      => 'required|string|max:10',
            'phone'        => 'required|string|max:20',
            'resume'       => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string|max:2048',
        ];
        foreach ($job->additionalQuestions as $q) {
            if ($q->is_required) {
                $rules["questions.{$q->id}"] = 'required';
            }
        }
        $validated = $request->validate($rules);

        // Resume upload
        $resumePath = Auth::user()->resume;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
            Auth::user()->update(['resume' => $resumePath]);
        }

        $application = JobApplication::create([
            'job_id'      => $job->id,
            'user_id'     => Auth::id(),
            'first_name'  => $validated['first_name'],
            'last_name'   => $validated['last_name'],
            'email'       => $validated['email'],
            'city'        => $validated['city'],
            'country_code' => $validated['country'],
            'phone'       => $validated['phone'],
            'resume'      => $resumePath,
            'cover_letter' => $request->input('cover_letter'),
            'status'      => 'submitted',
        ]);

        // Additional questions
        if ($job->additionalQuestions) {
            foreach ($job->additionalQuestions as $q) {
                $ans = $request->input("questions.{$q->id}");
                if (is_array($ans)) $ans = json_encode($ans);
                JobApplicationAnswer::create([
                    'application_id' => $application->id,
                    'question_id'    => $q->id,
                    'answer'         => $ans,
                ]);
            }
        }

        $smtpEnabled = setting('mail_enabled', '1') === '1';
        if ($smtpEnabled) {
            Mail::to(Auth::user()->email)->send(new JobAppliedMail($job, Auth::user()));
        }
        return redirect()->route('job.apply.success', $job->id)->with('success', 'Application submitted successfully!');
    }
    public function applySuccess()
    {
        $user = Auth::user();
        $latestApplication = JobApplication::where('user_id', $user->id)->latest()->first();
        if (!$latestApplication) {
            return redirect()->route('home')->with('error', 'You have not applied to any job.');
        }
        return view('apply.job_apply_success');
    }

    public function saved(Request $request)
    {
        $user = Auth::user();

        // Get job IDs from localStorage (sent via query string)
        $ids = $request->input('ids', []);
        if (!is_array($ids)) {
            $ids = explode(',', $ids); // in case it's passed as comma-separated
        }

        $savedJobs = Job::whereIn('id', $ids)->get(); // Replace `Job` with your model

        $appliedJobs = JobApplication::with('job')
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($app) {
                $job = $app->job;
                return (object)[
                    'id' => $job->id ?? null,
                    'title' => $job->title ?? '-',
                    'company' => $job->company ?? '-',
                    'location' => $job->location ?? '-',
                    'deadline' => $job->deadline ?? null,
                    'applied_on' => $app->created_at?->format('l, d M Y') ?? '-',
                    'status' => ['label' => 'Applied', 'class' => 'bg-primary'],
                ];
            });


        $interviewJobs = JobApplication::with('job')
            ->where('user_id', $user->id)
            ->where('status', 'interview')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($app) {
                $job = $app->job;
                return (object)[
                    'id' => $job->id ?? null,
                    'title' => $job->title ?? '-',
                    'company' => $job->company ?? '-',
                    'location' => $job->location ?? '-',
                    'deadline' => $job->deadline ?? null,
                    'applied_on' => $app->updated_at?->format('l, d M Y') ?? '-',
                    'status' => ['label' => 'Interview', 'class' => 'bg-info'],
                ];
            });



        $statuses = [
            ['label' => 'Applied', 'class' => 'bg-primary'],
            ['label' => 'Application viewed', 'class' => 'bg-success'],
            ['label' => 'Not selected by employer', 'class' => 'bg-danger'],
            ['label' => 'Under Review', 'class' => 'bg-warning text-dark'],
        ];

        return view('saved', compact('savedJobs', 'appliedJobs', 'interviewJobs', 'statuses'));
    }

    public function search(Request $request)
    {
        $query = Job::query();

        // Filter for active and unexpired jobs
        $query->where('status', 'active')
            ->where('deadline', '>=', now())
            ->where('is_approved', true);

        if ($request->has('q')) {
            $searchQuery = $request->input('q');
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                    ->orWhere('company', 'like', "%{$searchQuery}%")
                    ->orWhere('skills', 'like', "%{$searchQuery}%");
            });
        }

        if ($request->has('form')) {
            $location = $request->input('form');
            $query->where('location', 'like', "%{$location}%");
        }
        // Additional Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('education')) {
            $query->where('education', $request->education);
        }

        if ($request->filled('min_salary')) {
            $query->where('salary', '>=', (int) $request->min_salary);
        }

        if ($request->filled('posted_within')) {
            $days = (int) $request->posted_within;
            $query->where('created_at', '>=', now()->subDays($days));
        }

        $activeJobId = $request->query('job_id');
        $showCount = (int) $request->query('show_count', 5);
        $showMoreStep = 5;
        $appliedJobIds = auth()->check()
            ? JobApplication::where('user_id', auth()->id())->pluck('job_id')->toArray()
            : [];
        $jobs = $query->get();
        $totalJobs = $jobs->count();
        $displayJobs = $jobs->take($showCount);
        $canShowMore = $showCount < $totalJobs;

        $activeJob = $activeJobId ? $jobs->firstWhere('id', $activeJobId) : $jobs->first();

        return view('search', compact('jobs', 'displayJobs', 'activeJob', 'activeJobId', 'canShowMore', 'showCount', 'showMoreStep', 'totalJobs', 'appliedJobIds'));
    }
}

<?php

namespace App\Http\Controllers\Recruiter;

use App\Models\Job;
use App\Models\UserBlock;
use App\Models\UserReport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JobAdditionalQuestion;
use Illuminate\Support\Facades\Validator;

class RecruiterJobController extends Controller
{
    //My All Jobs
    public function allJobs()
    {
        $user = Auth::user();
        $jobs = Job::where('recruiter_id', $user->id)->with('reviews')
            ->latest()
            ->paginate(10); // Adjust number per page if needed
        return view('recruiter.dashboard.jobs.all_jobs', compact('jobs'));
    }
    // Create Job 
    public function createJob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',

            'salary_from' => 'required|numeric|min:0',
            'salary_to' => 'required|numeric|gt:salary_from',

            'type' => 'required|array|min:1',
            'type.*' => 'string',

            'shift' => 'required|array|min:1',
            'shift.*' => 'string',

            'industry' => 'nullable|string|max:255',
            'is_remote' => 'required|in:0,1',

            'employment_level' => 'required|array|min:1',
            'employment_level.*' => 'string',

            'skills' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'responsibilities' => 'required|string',
            'benefits' => 'nullable|string',
            'application_url' => 'nullable|url',
            'deadline' => 'required|date|after_or_equal:today',
            'experience' => 'nullable|string|max:255',

            'company_logo' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            // // ✅ Additional Questions
            // 'questions' => 'nullable|array',
            // 'questions.*.question' => 'required_with:questions|string|max:500',
            // 'questions.*.type' => 'required_with:questions|in:text,textarea,radio,checkbox',
            // 'questions.*.is_required' => 'required_with:questions|in:0,1',
            // 'questions.*.options' => 'nullable|string',
        ], [
            'title.required' => 'Job title is required.',
            'company.required' => 'Company name is required.',
            'salary_from.required' => 'Salary from is required.',
            'salary_to.required' => 'Salary to is required.',
            'salary_to.gt' => 'Salary to must be greater than salary from.',
            'type.required' => 'Please select at least one job type.',
            'shift.required' => 'Please select at least one job shift.',
            'employment_level.required' => 'Please select at least one employment level.',
            'skills.required' => 'Skills field is required.',
            'description.required' => 'Description is required.',
            'requirements.required' => 'Requirements field is required.',
            'responsibilities.required' => 'Responsibilities field is required.',
            'deadline.required' => 'Application deadline is required.',
            'deadline.after_or_equal' => 'Deadline must be today or a future date.',
            'company_logo.image' => 'Company logo must be an image.',
            'cover_image.image' => 'Cover image must be an image.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('current_step', 4);
        }

        // ✅ Save files
        $logoPath = null;
        $coverPath = null;

        if ($request->hasFile('company_logo')) {
            $logo = $request->file('company_logo');
            $logoName = Str::uuid() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('assets/company_logos'), $logoName);
            $logoPath = 'assets/company_logos/' . $logoName;
        }

        if ($request->hasFile('cover_image')) {
            $cover = $request->file('cover_image');
            $coverName = Str::uuid() . '.' . $cover->getClientOriginalExtension();
            $cover->move(public_path('assets/cover_images'), $coverName);
            $coverPath = 'assets/cover_images/' . $coverName;
        }

        try {
            $job = Job::create([
                'title' => $request->title,
                'company' => $request->company,
                'location' => $request->location,
                'salary' => '₹' . number_format($request->salary_from) . ' - ₹' . number_format($request->salary_to),
                'type' => json_encode($request->type),
                'shift' => json_encode($request->shift),
                'industry' => $request->industry,
                'is_remote' => $request->is_remote,
                'employment_level' => json_encode($request->employment_level),
                'skills' => $request->skills,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'responsibilities' => $request->responsibilities,
                'benefits' => $request->benefits,
                'application_url' => $request->application_url,
                'deadline' => $request->deadline,
                'experience' => $request->experience,
                'education' => json_encode($request->education),
                'company_logo' => $logoPath,
                'cover_image' => $coverPath,
                'recruiter_id' => Auth::id(),
            ]);
            if (!empty($request->questions) && is_array($request->questions)) {
                foreach ($request->questions as $q) {
                    if (
                        is_array($q) &&
                        isset($q['text'], $q['type'])
                    ) {
                        JobAdditionalQuestion::create([
                            'job_id' => $job->id,
                            'question' => $q['text'],
                            'type' => $q['type'],
                            'is_required' => isset($q['optional']) && $q['optional'] == '1' ? 0 : 1,
                            'options' => in_array($q['type'], ['radio', 'checkbox']) ? $q['options'] ?? null : null,
                        ]);
                    }
                }
            }


            return redirect()->back()->with('success', 'Job posted successfully!');
        } catch (\Exception $e) {
            Log::error('Job creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Something went wrong. Please try again.')->with('current_step', 4);
        }
    }

    // My Job Change Status 
    public function toggleStatus($id)
    {
        $job = Job::where('id', $id)
            ->where('recruiter_id', auth()->id())
            ->firstOrFail();

        $job->status = $job->status === 'active' ? 'inactive' : 'active';
        $job->save();

        return back()->with('status', 'Job status updated successfully.');
    }

    // Delete My Job
    public function destroy($id)
    {
        $job = Job::where('id', $id)
            ->where('recruiter_id', auth()->id())
            ->firstOrFail();

        $job->delete();

        return back()->with('status', 'Job deleted successfully.');
    }

    // Edit/Update My Jobs
    public function edit($id)
    {
        $job = Job::where('id', $id)
            ->where('recruiter_id', auth()->id())
            ->with('additionalQuestions')
            ->firstOrFail();

        return view('recruiter.dashboard.jobs.edit', compact('job'));
    }
    // Update Submit My Jobs
    public function update(Request $request, $id)
    {
        $job = Job::where('id', $id)
            ->where('recruiter_id', auth()->id())
            ->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'salary' => 'nullable|string',
            'shift' => 'required|string',
            'type' => 'required|string',
            'skills' => 'nullable|string',
            'status' => 'in:active,inactive',
            'cover_image' => 'nullable|image|max:2048', // 2MB
        ]);

        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('job_images', 'public');
            $job->cover_image = 'storage/' . $imagePath;
        }

        $job->fill([
            'title' => $validated['title'],
            'company' => $validated['company'],
            'location' => $validated['location'],
            'salary' => $validated['salary'],
            'shift' => $validated['shift'],
            'type' => $validated['type'],
            'status' => $validated['status'],
            'skills' => json_encode(array_map('trim', explode(',', $validated['skills'] ?? ''))),
        ])->save();

        $existingIds = [];

        // Loop through submitted questions
        if (!empty($request->questions) && is_array($request->questions)) {
            foreach ($request->questions as $q) {
                if (
                    is_array($q) &&
                    isset($q['text'], $q['type'])
                ) {
                    // Determine if this is an update or a new create
                    if (!empty($q['id'])) {
                        // Update existing question
                        $question = JobAdditionalQuestion::where('id', $q['id'])->where('job_id', $job->id)->first();
                        if ($question) {
                            $question->update([
                                'question' => $q['text'],
                                'type' => $q['type'],
                                'is_required' => isset($q['optional']) && $q['optional'] == '1' ? 0 : 1,
                                'options' => in_array($q['type'], ['radio', 'checkbox']) ? $q['options'] ?? null : null,
                            ]);
                            $existingIds[] = $question->id;
                        }
                    } else {
                        // Create new question
                        $new = JobAdditionalQuestion::create([
                            'job_id' => $job->id,
                            'question' => $q['text'],
                            'type' => $q['type'],
                            'is_required' => isset($q['optional']) && $q['optional'] == '1' ? 0 : 1,
                            'options' => in_array($q['type'], ['radio', 'checkbox']) ? $q['options'] ?? null : null,
                        ]);
                        $existingIds[] = $new->id;
                    }
                }
            }
        }

        // Optionally delete removed questions
        JobAdditionalQuestion::where('job_id', $job->id)
            ->whereNotIn('id', $existingIds)
            ->delete();



        return redirect()->route('recruiter.all_jobs')->with('status', 'Job updated successfully.');
    }

    // View My Job 
    public function show($id)
    {
        $job = Job::with('additionalQuestions', 'reviews.user')->where('id', $id)
            ->where(function ($query) {
                $user = auth()->user();

                if ($user->role === 'recruiter') {
                    $query->where('recruiter_id', $user->id);
                } elseif ($user->role === 'recruiter_assistant') {
                    $query->where('recruiter_id', $user->parent_id);
                }
            })
            ->firstOrFail();


        return view('recruiter.dashboard.jobs.show', compact('job'));
    }

    public function view_user()
    {
        $mainRecruiterId = auth()->id();

        // Get IDs of sub-recruiters (assistants)
        $subRecruiterIds = \App\Models\User::where('parent_id', $mainRecruiterId)->pluck('id')->toArray();

        // Include both main and sub recruiters
        $recruiterIds = array_merge([$mainRecruiterId], $subRecruiterIds);

        // Get user_ids who applied to jobs of these recruiters
        $userIds = JobApplication::whereHas('job', function ($q) use ($recruiterIds) {
            $q->whereIn('recruiter_id', $recruiterIds);
        })
            ->pluck('user_id')
            ->unique();

        // Fetch those users with job applications to these recruiters' jobs
        $users = \App\Models\User::with(['jobApplications' => function ($q) use ($recruiterIds) {
            $q->whereHas('job', function ($q2) use ($recruiterIds) {
                $q2->whereIn('recruiter_id', $recruiterIds);
            })
                ->latest();
        }, 'jobApplications.job'])
            ->whereIn('id', $userIds)
            ->paginate(20);

        return view('recruiter.dashboard.create.applied_user.index', compact('users'));
    }


    public function reportUser($id)
    {
        // Example: Save the report to the database (optional)
        UserReport::create([
            'to_user_id' => $id,
            'from_user_id' => auth()->id(),
            'reason' => request('reason', 'No reason given'),
            'details' => request('details', null),
        ]);
        return back()->with('success', 'User reported!');
    }

    public function blockUser($id)
    {
        UserBlock::firstOrCreate([
            'from_user_id' => auth()->id(),
            'to_user_id' => $id,
        ]);
        return back()->with('success', 'User blocked for your jobs!');
    }

    public function unblockUser($id)
    {
        UserBlock::where([
            'from_user_id' => auth()->id(),
            'to_user_id' => $id,
        ])->delete();
        return back()->with('success', 'User unblocked for your jobs!');
    }
}

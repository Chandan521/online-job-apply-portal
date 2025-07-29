<?php

namespace App\Http\Controllers\Admin;

use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobManagement extends Controller
{
    public function index()
    {
        $jobs = Job::where('is_approved', false)->with('recruiter')->latest()->paginate(10);
        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $recruiters = User::where('role', 'recruiter')->get();
        return view('admin.jobs.create', compact('recruiters'));
    }

    public function store(Request $request)
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

            'company_logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
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
            'company_logo.required' => 'Company logo is required.',
            'cover_image.required' => 'Cover image is required.',
            'company_logo.image' => 'Company logo must be an image.',
            'cover_image.image' => 'Cover image must be an image.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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
            Job::create([
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

            return redirect()->back()->with('success', 'Job posted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function edit(Job $job)
    {
        $recruiters = User::where('role', 'recruiter')->get();
        // dd($recruiters->toArray());

        return view('admin.jobs.edit', compact('job', 'recruiters'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string',
            'location' => 'required|string',
            'salary' => 'nullable|string',
            'recruiter_id' => 'required|exists:users,id',
            'type' => 'required',
            'shift' => 'nullable',
            'deadline' => 'nullable|date',
            'is_remote' => 'boolean',
        ]);
        $job->update($validated);
        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }

    public function toggleStatus(Job $job)
    {
        $job->status = !$job->status;
        $job->save();
        return back()->with('success', 'Job status updated.');
    }
    public function approve(Job $job)
    {
        $job->is_approved = true;
        $job->save();

        return back()->with('success', 'Job approved successfully.');
    }

    public function reject(Job $job)
    {
        $job->delete(); // or set a status column like `is_rejected` if you want to soft reject
        return redirect()->route('admin.jobs.index')->with('error', 'Job rejected and removed.');
    }
}

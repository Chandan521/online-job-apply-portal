@extends('admin.layout.app')

@section('title', 'Edit Job')

@section('page-title', 'Edit Job')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.jobs.update', $job->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Job Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $job->title) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Job Type</label>
                    <select name="type" class="form-control" required>
                        @if (old('type', $job->type) == '')
                            <option value="" selected>Select Job Type</option>
                        @endif
                        <option value="full-time" {{ old('type', $job->type) == 'full-time' ? 'selected' : '' }}>Full Time
                        </option>
                        <option value="part-time" {{ old('type', $job->type) == 'part-time' ? 'selected' : '' }}>Part Time
                        </option>
                        <option value="contract" {{ old('type', $job->type) == 'contract' ? 'selected' : '' }}>Contract
                        </option>
                        <option value="freelance" {{ old('type', $job->type) == 'freelance' ? 'selected' : '' }}>Freelance
                        </option>
                    </select>


                </div>
                <div class="mb-3">
                    <label class="form-label">Recruiter</label>
                    <select name="recruiter_id" class="form-control" required>
                        @if (empty(old('recruiter_id', $job->recruiter_id)))
                            <option value="" selected disabled>Select Recruiter</option>
                        @endif
                        @foreach ($recruiters as $recruiter)
                            <option value="{{ $recruiter['id'] }}"
                                {{ old('recruiter_id', $job->recruiter_id) == $recruiter['id'] ? 'selected' : '' }}>
                                {{ $recruiter['name'] }}
                            </option>
                        @endforeach
                    </select>


                </div>

                <div class="mb-3">
                    <label class="form-label">Company</label>
                    <input type="text" name="company" class="form-control" value="{{ old('company', $job->company) }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control"
                        value="{{ old('location', $job->location) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Salary</label>
                    <input type="text" name="salary" class="form-control" value="{{ old('salary', $job->salary) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Skills</label>
                    <input type="text" name="skills" class="form-control" value="{{ old('skills', $job->skills) }}">
                    <small class="text-muted">Comma-separated (e.g., PHP, Laravel, MySQL)</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deadline</label>
                    <input type="date" name="deadline" class="form-control"
                        value="{{ old('deadline', $job->deadline ? $job->deadline->format('Y-m-d') : '') }}">
                </div>
                <div class="form-check">
                    <input type="hidden" name="is_remote" value="0"> <!-- handles unchecked case -->
                    <input type="checkbox" name="is_remote" value="1" class="form-check-input" id="isRemote"
                        {{ old('is_remote', $job->is_remote) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isRemote">Remote Job</label>
                </div>


                <div class="mb-3">
                    <label class="form-label">Company Logo</label>
                    <input type="file" name="company_logo" class="form-control">
                    @if ($job->company_logo)
                        <img src="{{ asset('/' . $job->company_logo) }}" width="100" class="mt-2">
                    @else
                        {{-- Show default or generated avatar --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($job->company_name ?? 'Company') }}&background=0D8ABC&color=fff"
                            width="100" class="mt-2 rounded">
                    @endif

                </div>

                <div class="mb-3">
                    <label class="form-label">Cover Image</label>
                    <input type="file" name="cover_image" class="form-control">
                    @if ($job->cover_image)
                        <img src="{{ asset('/' . $job->cover_image) }}" width="100" class="mt-2">
                    @else
                        {{-- Show default or generated avatar image --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($job->title) }}&background=random&color=fff"
                            width="100" class="mt-2 rounded">
                    @endif

                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.jobs.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Job</button>
                </div>
            </form>
        </div>
    </div>
@endsection

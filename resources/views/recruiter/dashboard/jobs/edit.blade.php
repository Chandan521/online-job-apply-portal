@extends('recruiter.layout.dashboard_layout')

@section('title', 'Edit Job')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Edit Job - {{ $job->title }}</h2>

        <form action="{{ route('recruiter.jobs.update', $job->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label">Job Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $job->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company</label>
                        <input type="text" name="company" class="form-control @error('company') is-invalid @enderror"
                            value="{{ old('company', $job->company) }}">
                        @error('company')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                            value="{{ old('location', $job->location) }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Salary</label>
                        <input type="text" name="salary" class="form-control @error('salary') is-invalid @enderror"
                            value="{{ old('salary', $job->salary) }}">
                        @error('salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Shift</label>
                        <input type="text" name="shift" class="form-control @error('shift') is-invalid @enderror"
                            value="{{ old('shift', $job->shift) }}">
                        @error('shift')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <input type="text" name="type" class="form-control @error('type') is-invalid @enderror"
                            value="{{ old('type', $job->type) }}">
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Employment Level</label>
                        <input type="text" name="employment_level"
                            class="form-control @error('employment_level') is-invalid @enderror"
                            value="{{ old('employment_level', $job->employment_level) }}">
                        @error('employment_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Industry</label>
                        <input type="text" name="industry" class="form-control @error('industry') is-invalid @enderror"
                            value="{{ old('industry', $job->industry) }}">
                        @error('industry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Skills (comma-separated)</label>
                        <input type="text" name="skills" class="form-control @error('skills') is-invalid @enderror"
                            value="{{ old('skills', is_array($job->skills) ? implode(',', $job->skills) : implode(',', json_decode($job->skills) ?? [])) }}">
                        @error('skills')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $job->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Requirements</label>
                        <textarea name="requirements" class="form-control @error('requirements') is-invalid @enderror" rows="3">{{ old('requirements', $job->requirements) }}</textarea>
                        @error('requirements')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Responsibilities</label>
                        <textarea name="responsibilities" class="form-control @error('responsibilities') is-invalid @enderror" rows="3">{{ old('responsibilities', $job->responsibilities) }}</textarea>
                        @error('responsibilities')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Benefits</label>
                        <textarea name="benefits" class="form-control @error('benefits') is-invalid @enderror" rows="3">{{ old('benefits', $job->benefits) }}</textarea>
                        @error('benefits')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Application URL</label>
                        <input type="url" name="application_url"
                            class="form-control @error('application_url') is-invalid @enderror"
                            value="{{ old('application_url', $job->application_url) }}">
                        @error('application_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="date" name="deadline"
                            class="form-control @error('deadline') is-invalid @enderror"
                            value="{{ old('deadline', $job->deadline) }}">
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', $job->status) == 'active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="inactive"
                                    {{ old('status', $job->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label class="form-label">Remote</label>
                            <select name="is_remote" class="form-select @error('is_remote') is-invalid @enderror">
                                <option value="1" {{ old('is_remote', $job->is_remote) == '1' ? 'selected' : '' }}>
                                    Yes</option>
                                <option value="0" {{ old('is_remote', $job->is_remote) == '0' ? 'selected' : '' }}>No
                                </option>
                            </select>
                            @error('is_remote')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Education</label>
                        <input type="text" name="education"
                            class="form-control @error('education') is-invalid @enderror"
                            value="{{ old('education', $job->education) }}">
                        @error('education')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Experience</label>
                        <input type="text" name="experience"
                            class="form-control @error('experience') is-invalid @enderror"
                            value="{{ old('experience', $job->experience) }}">
                        @error('experience')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company Logo</label>
                        <input type="file" name="company_logo" accept="image/*"
                            class="form-control @error('company_logo') is-invalid @enderror"
                            onchange="loadPreview(this, 'logoPreview')">
                        @error('company_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            @if ($job->company_logo)
                                <img id="logoPreview" src="{{ asset($job->company_logo) }}" width="120"
                                    class="img-thumbnail">
                            @else
                                <div class="text-muted">Company Logo Not Set</div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Cover Image</label>
                        <input type="file" name="cover_image" accept="image/*"
                            class="form-control @error('cover_image') is-invalid @enderror"
                            onchange="loadPreview(this, 'coverPreview')">
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            @if ($job->cover_image)
                                <img id="coverPreview" src="{{ asset($job->cover_image) }}" width="120"
                                    class="img-thumbnail">
                            @else
                                <div class="text-muted">Cover Image Not Set</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- ✅ Inserted Additional Questions Step Here -->
            <div class="step-section mt-4" data-step="3">
                <h4 class="mb-3">Additional Questions</h4>
                <div id="question-container">
                    @foreach ($job->additionalQuestions as $index => $question)
                        <div class="card mb-3 p-3 position-relative question-box" data-index="{{ $index }}">
                            <div class="mb-2">
                                <label>Question <span class="text-danger">*</span></label>
                                <input type="text" name="questions[{{ $index }}][text]" class="form-control"
                                    value="{{ $question->question }}" required>
                            </div>

                            <div class="mb-2">
                                <label>Question Type <span class="text-danger">*</span></label>
                                <select name="questions[{{ $index }}][type]" class="form-control"
                                    onchange="toggleOptions(this, {{ $index }})" required>
                                    <option value="text" {{ $question->type == 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="textarea" {{ $question->type == 'textarea' ? 'selected' : '' }}>
                                        Textarea</option>
                                    <option value="radio" {{ $question->type == 'radio' ? 'selected' : '' }}>Multiple
                                        Choice (Single Answer)</option>
                                    <option value="checkbox" {{ $question->type == 'checkbox' ? 'selected' : '' }}>
                                        Multiple Choice (Multiple Answers)</option>
                                </select>
                            </div>

                            <div class="mb-2" id="options-{{ $index }}"
                                style="{{ in_array($question->type, ['radio', 'checkbox']) ? '' : 'display: none;' }}">
                                <label>Options (Comma-separated)</label>
                                <input type="text" name="questions[{{ $index }}][options]"
                                    class="form-control" value="{{ $question->options }}">
                            </div>

                            <div class="mb-2">
                                <label>Is this question optional?</label>
                                <select name="questions[{{ $index }}][optional]" class="form-control">
                                    <option value="0" {{ $question->is_required == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $question->is_required == 1 ? 'selected' : '' }}>Yes
                                    </option>
                                </select>
                            </div>

                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                onclick="deleteQuestion(this)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-outline-primary" onclick="addQuestion()">+ Add Question</button>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Update Job</button>
                <a href="{{ route('recruiter.all_jobs') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('job_edit_scripts')
    <script>
        function loadPreview(input, previewId) {
            const file = input.files[0];
            if (file) {
                const preview = document.getElementById(previewId);
                preview.src = URL.createObjectURL(file);
                preview.onload = () => URL.revokeObjectURL(preview.src);
            }
        }
    </script>
    <script>
    let questionCount = {{ $job->additionalQuestions->count() ?? 0 }};

    function addQuestion() {
        const container = document.getElementById('question-container');

        const questionBox = document.createElement('div');
        questionBox.className = 'card mb-3 p-3 position-relative question-box';
        questionBox.setAttribute('data-index', questionCount);

        questionBox.innerHTML = `
        <div class="mb-2">
            <label>Question <span class="text-danger">*</span></label>
            <input type="text" name="questions[${questionCount}][text]" class="form-control" required>
        </div>

        <div class="mb-2">
            <label>Question Type <span class="text-danger">*</span></label>
            <select name="questions[${questionCount}][type]" class="form-control" required onchange="toggleOptions(this, ${questionCount})">
                <option value="text">Text</option>
                <option value="textarea">Textarea</option>
                <option value="radio">Multiple Choice (Single Answer)</option>
                <option value="checkbox">Multiple Choice (Multiple Answers)</option>
            </select>
        </div>

        <div class="mb-2" id="options-${questionCount}" style="display: none;">
            <label>Options (Comma-separated)</label>
            <input type="text" name="questions[${questionCount}][options]" class="form-control" placeholder="e.g. Option A, Option B, Option C">
        </div>

        <div class="mb-2">
            <label>Is this question optional?</label>
            <select name="questions[${questionCount}][optional]" class="form-control">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" onclick="deleteQuestion(this)">
            <i class="fas fa-trash-alt"></i>
        </button>
        `;

        container.appendChild(questionBox);
        questionCount++;
    }

    function deleteQuestion(button) {
        const box = button.closest('.question-box');
        box.remove();
    }

    function toggleOptions(select, index) {
        const optionsBox = document.getElementById(`options-${index}`);
        optionsBox.style.display = (select.value === 'radio' || select.value === 'checkbox') ? 'block' : 'none';
    }
</script>
@endpush

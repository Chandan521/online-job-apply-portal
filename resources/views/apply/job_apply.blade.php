@extends('layouts.app')
@section('job_apply_style')
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
        }

        .application-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            background: white;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            padding: 15px;
        }

        @media (min-width: 768px) {
            body {
                padding: 20px;
            }

            .application-container {
                border-radius: 12px;
                padding: 30px;
                margin: 20px auto;
            }
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        @media (min-width: 576px) {
            .header-section {
                padding: 30px 20px;
                border-radius: 10px;
                margin-bottom: 30px;
            }
        }

        .company-logo {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        @media (min-width: 576px) {
            .company-logo {
                width: 70px;
                height: 70px;
                border-radius: 8px;
            }
        }

        .progress-container {
            margin-bottom: 30px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            min-width: 500px;
        }

        .progress-bar {
            position: absolute;
            height: 4px;
            background-color: var(--primary-color);
            top: 20px;
            left: 0;
            z-index: 1;
            transition: width 0.5s ease;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
            min-width: 80px;
        }

        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            color: #6c757d;
            border: 2px solid #dee2e6;
        }

        .step.completed .step-icon,
        .step.active .step-icon {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .step.completed .step-icon {
            background-color: #28a745;
            border-color: #28a745;
        }

        .step-label {
            font-size: 12px;
            font-weight: 500;
            color: #6c757d;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (min-width: 576px) {
            .step-label {
                font-size: 14px;
            }
        }

        .step.active .step-label,
        .step.completed .step-label {
            color: var(--primary-color);
            font-weight: 600;
        }

        .step.completed .step-label {
            color: #28a745;
        }

        .form-section {
            padding: 10px 0;
        }

        @media (min-width: 768px) {
            .form-section {
                padding: 20px 0;
            }
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-card {
            background-color: var(--light-bg);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: var(--card-shadow);
            border: 1px solid #e9ecef;
        }

        @media (min-width: 768px) {
            .form-card {
                padding: 25px;
                margin-bottom: 25px;
                border-radius: 10px;
            }
        }

        .btn-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-navigation .btn {
            flex: 1 1 auto;
            min-width: 120px;
        }

        .additional-question {
            background: white;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary-color);
        }

        @media (min-width: 768px) {
            .additional-question {
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 20px;
            }
        }

        .question-text {
            font-weight: 600;
            margin-bottom: 12px;
            color: #343a40;
            font-size: 15px;
        }

        @media (min-width: 768px) {
            .question-text {
                font-size: 16px;
                margin-bottom: 15px;
            }
        }

        .resume-preview {
            height: 200px;
            background-color: white;
            border: 2px dashed #dee2e6;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        @media (min-width: 768px) {
            .resume-preview {
                height: 300px;
                border-radius: 8px;
                margin-bottom: 15px;
            }
        }

        .resume-placeholder {
            text-align: center;
            color: #6c757d;
            padding: 0 15px;
        }

        .resume-actions {
            display: flex;
            gap: 8px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .resume-actions .btn {
            font-size: 14px;
            padding: 5px 10px;
        }

        @media (min-width: 576px) {
            .resume-actions .btn {
                font-size: 15px;
                padding: 6px 12px;
            }
        }

        .review-item {
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .job-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border: 1px solid #dee2e6;
        }

        @media (min-width: 768px) {
            .job-card {
                padding: 20px;
                margin-top: 20px;
                border-radius: 10px;
            }
        }

        .job-highlight {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            font-size: 15px;
        }

        .job-highlight i {
            width: 25px;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        @media (min-width: 768px) {
            .job-highlight {
                margin-bottom: 12px;
                font-size: 16px;
            }

            .job-highlight i {
                width: 30px;
                font-size: 1.2rem;
            }
        }

        .alert {
            border-radius: 6px;
            margin-bottom: 15px;
            padding: 12px 15px;
            font-size: 14px;
        }

        @media (min-width: 768px) {
            .alert {
                border-radius: 8px;
                margin-bottom: 25px;
                padding: 15px 20px;
                font-size: 16px;
            }
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 15px;
        }

        @media (min-width: 768px) {
            .form-label {
                margin-bottom: 8px;
                font-size: 16px;
            }
        }

        .form-control,
        .form-select {
            padding: 8px 12px;
            font-size: 15px;
        }

        @media (min-width: 768px) {

            .form-control,
            .form-select {
                padding: 10px 14px;
                font-size: 16px;
            }
        }

        .input-group-text {
            font-size: 15px;
            padding: 8px 12px;
        }

        @media (min-width: 768px) {
            .input-group-text {
                font-size: 16px;
                padding: 10px 14px;
            }
        }

        .btn {
            font-size: 15px;
            padding: 8px 16px;
        }

        @media (min-width: 768px) {
            .btn {
                font-size: 16px;
                padding: 10px 20px;
            }
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-check {
            margin-bottom: 10px;
        }

        .form-check-input {
            margin-top: 0.2em;
        }

        /* Modal adjustments for mobile */
        .modal-content {
            border-radius: 10px;
        }

        .modal-body {
            padding: 20px;
        }

        @media (min-width: 576px) {
            .modal-body {
                padding: 30px;
            }
        }
    </style>
@endsection
{{-- Job Apply Page --}}
@section('content')
    <div class="application-container">
            <form action="{{ route('jobs.apply.submit', $job->id) }}" method="POST" ... novalidate enctype="multipart/form-data"
                id="jobApplyForm">
                @csrf
                <!-- Header Section -->
                <div class="header-section text-center">
                    <h1 class="mb-3" style="font-size: 1.5rem;">Application for <strong>{{ $job->title }}</strong></h1>
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <img src="{{ $job->company_logo ? asset('/' . $job->company_logo) : 'https://images.unsplash.com/photo-1560179707-f14e90ef3623?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=200&q=80' }}"
                            alt="{{ $job->company ?? 'Company' }}" class="company-logo me-2 me-sm-3">
                        <div class="text-start">
                            <h5 class="mb-0" style="font-size: 1.1rem;">{{ $job->company ?? 'Tech Innovations Inc.' }}
                            </h5>
                            <div class="d-flex align-items-center text-white-50" style="font-size: 0.85rem;">
                                <i class="bi bi-geo-alt me-1"></i>
                                <span>{{ $job->location }}</span>
                                <i class="bi bi-cash-coin ms-2 me-1"></i>
                                <span>{{ $job->salary }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-white-50" style="font-size: 0.85rem;">
                        <i class="bi bi-clock me-1"></i>
                        <span>Deadline: {{ \Carbon\Carbon::parse($job->deadline)->format('d M Y') }}</span>

                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress-steps">
                        <div class="progress-bar" style="width: 0%;"></div>
                        <div class="step completed">
                            <div class="step-icon"><i class="bi bi-person"></i></div><span class="step-label">Personal
                                Info</span>
                        </div>
                        <div class="step">
                            <div class="step-icon"><i class="bi bi-file-earmark"></i></div><span
                                class="step-label">Resume</span>
                        </div>
                        <div class="step">
                            <div class="step-icon"><i class="bi bi-chat-square-text"></i></div><span
                                class="step-label">Questions</span>
                        </div>
                        <div class="step">
                            <div class="step-icon"><i class="bi bi-eye"></i></div><span class="step-label">Review</span>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <!-- Step 1: Personal Information -->
                    <div class="step-content active" id="step1">
                        <h3 class="mb-3" style="font-size: 1.3rem;">Personal Information</h3>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i> Please fill in your personal information. All fields are
                            required.
                        </div>
                        <div class="form-card">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                        id="firstName" name="first_name"
                                        value="{{ old('first_name', explode(' ', Auth::user()->name)[0] ?? '') }}"
                                        placeholder="Enter your first name" required>
                                    @error('first_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="lastName" name="last_name"
                                        value="{{ old('last_name', explode(' ', Auth::user()->name)[1] ?? '') }}"
                                        placeholder="Enter your last name" required>
                                    @error('last_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ Auth::user()->email }}" placeholder="{{ Auth::user()->email }}" required
                                    readonly>
                                <div class="form-text">We'll send application updates to this email.</div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label">Country *</label>
                                    <select class="form-select @error('country') is-invalid @enderror" id="country"
                                        name="country" required>
                                        <option value="">Select your country</option>
                                        <option value="IN"
                                            {{ old('country', Auth::user()->country) == 'IN' ? 'selected' : '' }}>India
                                        </option>
                                        <option value="UK"
                                            {{ old('country', Auth::user()->country) == 'UK' ? 'selected' : '' }}>United
                                            Kingdom</option>
                                        <option value="CA"
                                            {{ old('country', Auth::user()->country) == 'CA' ? 'selected' : '' }}>Canada
                                        </option>
                                        <option value="AU"
                                            {{ old('country', Auth::user()->country) == 'AU' ? 'selected' : '' }}>Australia
                                        </option>
                                        <option value="DE"
                                            {{ old('country', Auth::user()->country) == 'DE' ? 'selected' : '' }}>Germany
                                        </option>
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city', Auth::user()->city) }}"
                                        placeholder="Enter your city" required>
                                    @error('city')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                        placeholder="+91 XXX XXX XXXX" required>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="btn-navigation">
                            <div></div>
                            <button class="btn btn-primary" type="button" onclick="showStep(2)">Next: Resume <i
                                    class="bi bi-arrow-right ms-1 ms-sm-2"></i></button>
                        </div>
                    </div>
                    <!-- Step 2: Resume Upload -->
                    <div class="step-content" id="step2">
                        <h3 class="mb-3" style="font-size: 1.3rem;">Upload Your Resume</h3>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-circle me-2"></i> Please upload your resume in PDF, DOC or DOCX
                            format (max 5MB)
                        </div>
                        <div class="form-card">
                            <div class="mb-3">
                                <label for="resumeUpload" class="form-label">Upload New Resume *</label>
                                <input class="form-control @error('resume') is-invalid @enderror" type="file"
                                    id="resumeUpload" name="resume" accept=".pdf,.doc,.docx">
                                @error('resume')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Resume Preview</label>
                                <div class="resume-preview" id="resume-preview-container">
                                    @if (Auth::user()->resume)
                                        <iframe id="resume-preview-iframe"
                                            src="{{ asset('storage/' . Auth::user()->resume) }}" width="100%"
                                            height="300px" style="border: 1px solid #ccc;"></iframe>
                                    @else
                                        <div class="resume-placeholder text-center" id="resume-placeholder">
                                            <i class="bi bi-file-earmark-text display-4 mb-3 text-muted"></i>
                                            <p class="mb-2">Upload a resume to preview it here</p>
                                            <small class="text-muted">Supported formats: PDF, DOC, DOCX</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="resume-actions mt-2">
                                <button class="btn btn-outline-primary" type="button" id="zoom-btn"
                                    {{ Auth::user()->resume ? '' : 'disabled' }}>
                                    <i class="bi bi-zoom-in me-1"></i> Zoom
                                </button>
                                <button class="btn btn-outline-primary" type="button" id="fullscreen-btn"
                                    {{ Auth::user()->resume ? '' : 'disabled' }}>
                                    <i class="bi bi-arrows-fullscreen me-1"></i> Fullscreen
                                </button>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="coverLetter" name="has_cover_letter"
                                    {{ old('cover_letter') || Auth::user()->cover_letter ? 'checked' : '' }}>
                                <label class="form-check-label" for="coverLetter">Include a cover letter</label>
                            </div>
                            <div class="mt-2" id="coverLetterSection"
                                style="{{ old('cover_letter') || Auth::user()->cover_letter ? '' : 'display: none;' }}">
                                <label for="coverLetterText" class="form-label">Cover Letter</label>
                                <textarea class="form-control @error('cover_letter') is-invalid @enderror" id="coverLetterText" name="cover_letter"
                                    rows="4" placeholder="Write your cover letter here...">{{ old('cover_letter', Auth::user()->cover_letter) }}</textarea>
                                @error('cover_letter')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="btn-navigation">
                            <button class="btn btn-outline-secondary" type="button" onclick="showStep(1)">
                                <i class="bi bi-arrow-left me-1 me-sm-2"></i> Back
                            </button>
                            <button class="btn btn-primary" type="button" onclick="showStep(3)">
                                Next: Questions <i class="bi bi-arrow-right ms-1 ms-sm-2"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Step 3: Additional Questions -->
                    <div class="step-content" id="step3">
                        <h3 class="mb-3" style="font-size: 1.3rem;">Additional Questions</h3>
                        @if ($job->additionalQuestions && $job->additionalQuestions->count())
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i> The recruiter has requested additional information
                                for this position.
                            </div>
                            <div class="form-card">
                                @foreach ($job->additionalQuestions as $index => $question)
                                    @php
                                        $type = $question->type;
                                        $isRequired = $question->is_required;
                                        $inputName = "questions[{$question->id}]";
                                        $requiredAttr = $isRequired ? 'required' : '';
                                        $label = $index + 1 . '. ' . ($question->question ?? 'Question not found');
                                        $options = $question->options
                                            ? explode(',', trim($question->options, "\""))
                                            : [];
                                    @endphp
                                    <div class="mb-3 additional-question" data-question-id="{{ $question->id }}">
                                        <label class="form-label"><strong>{{ $label }} @if ($isRequired)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </strong></label>
                                        @switch($type)
                                            @case('text')
                                                <input type="text" name="{{ $inputName }}"
                                                    class="form-control @error('questions.' . $question->id) is-invalid @enderror"
                                                    {{ $requiredAttr }} value="{{ old('questions.' . $question->id) }}">
                                                @error('questions.' . $question->id)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            @break

                                            @case('textarea')
                                                <textarea name="{{ $inputName }}" class="form-control @error('questions.' . $question->id) is-invalid @enderror"
                                                    rows="3" {{ $requiredAttr }}>{{ old('questions.' . $question->id) }}</textarea>
                                                @error('questions.' . $question->id)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            @break

                                            @case('radio')
                                                @foreach ($options as $option)
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input @error('questions.' . $question->id) is-invalid @enderror"
                                                            type="radio" name="{{ $inputName }}"
                                                            value="{{ trim($option) }}"
                                                            id="{{ $inputName }}_{{ $loop->index }}" {{ $requiredAttr }}
                                                            {{ old('questions.' . $question->id) == trim($option) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="{{ $inputName }}_{{ $loop->index }}">{{ trim($option) }}</label>
                                                    </div>
                                                @endforeach
                                                @error('questions.' . $question->id)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            @break

                                            @case('checkbox')
                                                @foreach ($options as $option)
                                                    <div class="form-check">
                                                        <input
                                                            class="form-check-input @error('questions.' . $question->id) is-invalid @enderror"
                                                            type="checkbox" name="{{ $inputName }}[]"
                                                            value="{{ trim($option) }}"
                                                            id="{{ $inputName }}_{{ $loop->index }}"
                                                            @if (is_array(old('questions.' . $question->id)) && in_array(trim($option), old('questions.' . $question->id))) checked @endif>
                                                        <label class="form-check-label"
                                                            for="{{ $inputName }}_{{ $loop->index }}">{{ trim($option) }}</label>
                                                    </div>
                                                @endforeach
                                                @error('questions.' . $question->id)
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            @break

                                            @default
                                                <p class="text-danger">Unsupported or missing question type. (ID:
                                                    {{ $question->id }})</p>
                                        @endswitch
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-secondary">No additional questions for this job.</div>
                        @endif
                        <div class="btn-navigation">
                            <button class="btn btn-outline-secondary" type="button" onclick="showStep(2)">
                                <i class="bi bi-arrow-left me-1 me-sm-2"></i> Back
                            </button>
                            <button class="btn btn-primary" type="button" onclick="showStep(4)">
                                Next: Review <i class="bi bi-arrow-right ms-1 ms-sm-2"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Step 4: Review and Submit -->
                    <div class="step-content" id="step4">
                        <h3 class="mb-3" style="font-size: 1.3rem;">Review Your Application</h3>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Please review all information before submitting.
                        </div>
                        <div class="form-card">
                            <h5 class="mb-2" style="font-size: 1.1rem;">Personal Information</h5>
                            <div class="review-item">
                                <div class="row">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <div class="text-muted small">Full Name</div>
                                        <div id="review-name">-</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-muted small">Email</div>
                                        <div id="review-email">-</div>
                                    </div>
                                </div>
                            </div>
                            <div class="review-item">
                                <div class="row">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <div class="text-muted small">Location</div>
                                        <div id="review-location">-</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="text-muted small">Phone</div>
                                        <div id="review-phone">-</div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mb-2 mt-3" style="font-size: 1.1rem;">Resume</h5>
                            <div class="review-item">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-file-earmark-pdf text-danger me-2"></i>
                                    <div>
                                        <div id="review-resume">No resume uploaded</div>
                                    </div>
                                </div>
                            </div>
                            <h5 class="mb-2 mt-3" style="font-size: 1.1rem;">Additional Questions</h5>
                            <div id="review-additional-questions"></div>
                        </div>
                        <div class="job-card">
                            <h5 class="mb-2" style="font-size: 1.1rem;">Job Details</h5>
                            <div class="job-highlight"><i class="bi bi-briefcase"></i>
                                <div>{{ $job->title }}</div>
                            </div>
                            <div class="job-highlight"><i class="bi bi-building"></i>
                                <div>{{ $job->company }}</div>
                            </div>
                            <div class="job-highlight"><i class="bi bi-geo-alt"></i>
                                <div>{{ $job->location }}</div>
                            </div>
                            <div class="job-highlight"><i class="bi bi-cash-coin"></i>
                                <div>{{ $job->salary }}</div>
                            </div>
                            <div class="job-highlight"><i class="bi bi-clock"></i>
                                <div>Deadline: {{ $job->deadline }}</div>
                            </div>
                        </div>
                        <div class="form-check mt-3 mb-3">
                            <input class="form-check-input" type="checkbox" id="consent" required>
                            <label class="form-check-label small" for="consent">
                                I confirm all information is accurate and complete. *
                            </label>
                        </div>
                        <div class="btn-navigation">
                            <button class="btn btn-outline-secondary" type="button" onclick="showStep(3)">
                                <i class="bi bi-arrow-left me-1 me-sm-2"></i> Back
                            </button>
                            <button class="btn btn-success" type="submit">
                                <i class="bi bi-send me-1 me-sm-2"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        
    </div>
@endsection

@section('job_apply_script')
<script>
    function showStep(stepNumber) {
        document.querySelectorAll('.step-content').forEach(step => step.classList.remove('active'));
        document.getElementById('step' + stepNumber).classList.add('active');
        const progress = (stepNumber - 1) * 33.33;
        document.querySelector('.progress-bar').style.width = progress + '%';
        const steps = document.querySelectorAll('.step');
        steps.forEach((step, index) => {
            step.classList.remove('completed', 'active');
            if (index < stepNumber - 1) step.classList.add('completed');
            else if (index === stepNumber - 1) step.classList.add('active');
        });
        if (stepNumber === 4) populateReviewData();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Cover letter toggle
    document.getElementById('coverLetter').addEventListener('change', function () {
        document.getElementById('coverLetterSection').style.display = this.checked ? 'block' : 'none';
    });

    // Resume preview
    document.getElementById('resumeUpload').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const previewContainer = document.getElementById('resume-preview-container');
        if (file && file.type === 'application/pdf') {
            const url = URL.createObjectURL(file);
            previewContainer.innerHTML =
                `<iframe src="${url}" width="100%" height="300px" style="border: 1px solid #ccc;"></iframe>`;
        } else if (file && (file.name.endsWith('.doc') || file.name.endsWith('.docx'))) {
            previewContainer.innerHTML =
                `<div class="resume-placeholder text-center"><i class="bi bi-file-earmark-word display-4 mb-3 text-primary"></i><p class="mb-2">${file.name}</p><small class="text-muted">.doc/.docx preview not supported here, but will be uploaded.</small></div>`;
        } else {
            previewContainer.innerHTML =
                `<div class="resume-placeholder text-center"><i class="bi bi-file-earmark-x display-4 mb-3 text-danger"></i><p class="mb-2">Unsupported file type</p></div>`;
        }
    });

    // Populate review data (personal info, resume, additional questions)
    function populateReviewData() {
        document.getElementById('review-name').textContent =
            (document.getElementById('firstName').value || '-') + ' ' +
            (document.getElementById('lastName').value || '-');
        document.getElementById('review-email').textContent = document.getElementById('email').value || '-';
        document.getElementById('review-location').textContent =
            (document.getElementById('city').value || '-') + ', ' +
            (document.getElementById('country').options[document.getElementById('country').selectedIndex]?.text || '-');
        document.getElementById('review-phone').textContent = document.getElementById('phone').value || '-';

        // Resume info
        const resumeFile = document.getElementById('resumeUpload').files[0];
        if (resumeFile) {
            document.getElementById('review-resume').textContent = resumeFile.name;
        } else {
            @if (Auth::user()->resume)
            document.getElementById('review-resume').textContent = "{{ basename(Auth::user()->resume) }}";
            @else
            document.getElementById('review-resume').textContent = "No resume uploaded";
            @endif
        }

        // Additional Questions - Use IIFE to prevent variable name collision
        let reviewQs = '';
        @foreach ($job->additionalQuestions as $question)
            @php
                $qid = $question->id;
                $type = $question->type;
                $label = $question->question;
            @endphp
            reviewQs += (function(){
                var answer = '-';
                if ("{{ $type }}" === "checkbox") {
                    var checked = Array.from(document.querySelectorAll('input[name="questions\\[{{ $qid }}\\]\\[\\]"]:checked')).map(cb => cb.value);
                    answer = checked.length ? checked.join(', ') : "-";
                } else if ("{{ $type }}" === "radio") {
                    var checked = document.querySelector('input[name="questions\\[{{ $qid }}\\]"]:checked');
                    answer = checked ? checked.value : "-";
                } else if ("{{ $type }}" === "textarea" || "{{ $type }}" === "text") {
                    var v = document.querySelector('[name="questions\\[{{ $qid }}\\]"]');
                    answer = v && v.value ? v.value : "-";
                } else if ("{{ $type }}" === "select") {
                    var v = document.querySelector('[name="questions\\[{{ $qid }}\\]"]');
                    answer = v ? v.options[v.selectedIndex].text : "-";
                }
                return `<div class="review-item"><div class="text-muted small">{{ $label }}</div><div>${answer}</div></div>`;
            })();
        @endforeach
        document.getElementById('review-additional-questions').innerHTML = reviewQs;
    }

    document.getElementById('jobApplyForm').addEventListener('submit', function (e) {
        if (!document.getElementById('consent').checked) {
            e.preventDefault();
            alert('Please confirm that all information is accurate before submitting.');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        showStep(1);
        // Hide cover letter if not checked
        if (!document.getElementById('coverLetter').checked) {
            document.getElementById('coverLetterSection').style.display = "none";
        }
    });
</script>
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong>{{ setting('site_name', config('app.name')) }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection

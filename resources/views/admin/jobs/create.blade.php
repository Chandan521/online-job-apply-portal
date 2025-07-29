@extends('admin.layout.app')

@section('title', 'Create Job')
@section('page-title', 'Post New Job')

@push('create_job_styles')
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 25%;
            position: relative;
            z-index: 1;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            border: 3px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .step-label {
            margin-top: 0.5rem;
            font-weight: 600;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .step.active .step-icon {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .step.active .step-label {
            color: var(--primary-color);
        }

        .step.completed .step-icon {
            background-color: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .step.completed .step-label {
            color: var(--success-color);
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 12.5%;
            right: 12.5%;
            height: 4px;
            background-color: #e9ecef;
            z-index: 0;
        }

        .progress-line {
            position: absolute;
            top: 25px;
            left: 12.5%;
            height: 4px;
            background-color: var(--primary-color);
            z-index: 0;
            transition: width 0.3s ease;
        }

        .form-step-box {
            border: 1px solid var(--border-color);
            padding: 2.5rem;
            border-radius: 1rem;
            /* background-color: #fff; */
            box-shadow: var(--card-shadow-lg);
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
        }

        .img-preview {
            max-width: 100%;
            min-width: 150px;
            min-height: 100px;
            height: auto;
            margin-top: 1rem;
            border: 2px dashed var(--border-color);
            padding: 0.5rem;
            border-radius: 0.5rem;
            object-fit: contain;
            background-color: var(--light-color);
            display: none;
        }

        .step-section {
            display: none;
            animation: fadeIn 0.5s;
        }

        .step-section.active {
            display: block;
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

        .btn {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }

        #prevBtn,
        #nextBtn,
        #submitBtn {
            min-width: 140px;
        }
    </style>
@endpush

@section('content')
    <div class="container my-5">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">@yield('page-title', 'Post New Job')</h4>
            </div>
            <div class="card-body">
                <div class="step-indicator" id="stepIndicator">
                    <div class="step active" data-step="0">
                        <div class="step-icon">1</div>
                        <div class="step-label">Job Info</div>
                    </div>
                    <div class="step" data-step="1">
                        <div class="step-icon">2</div>
                        <div class="step-label">Details</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-icon">3</div>
                        <div class="step-label">Media</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-icon">4</div>
                        <div class="step-label">Preview</div>
                    </div>
                    <div class="progress-line" id="progressLine"></div>
                </div>

                <form method="POST" action="{{ route('admin.jobs.store') }}" enctype="multipart/form-data" id="jobForm"
                    novalidate>
                    @csrf
                    <div class="form-step-box">
                        <div class="step-section active" data-step="0">@include('admin.jobs.jobs_step.step1')</div>
                        <div class="step-section" data-step="1">@include('admin.jobs.jobs_step.step2')</div>
                        <div class="step-section" data-step="2">@include('admin.jobs.jobs_step.step3')</div>
                        <div class="step-section" data-step="3">@include('admin.jobs.jobs_step.step4')</div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary d-none" id="prevBtn"><i
                                class="bi bi-arrow-left"></i> Back</button>
                        <button type="button" class="btn btn-primary" id="nextBtn">Next <i
                                class="bi bi-arrow-right"></i></button>
                        <button type="submit" class="btn btn-success d-none" id="submitBtn"><i class="bi bi-check-lg"></i>
                            Post Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('create_job_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const steps = document.querySelectorAll('.step-section');
            const stepIndicators = document.querySelectorAll('.step');
            const form = document.getElementById('jobForm');
            const progressLine = document.getElementById('progressLine');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            let currentStep = 0;

            const updatePreview = () => {
                // This function will be expanded to update the preview in step 4
                document.getElementById('preview_title').textContent = form.title.value;
                document.getElementById('preview_company').textContent = form.company.value;
                document.getElementById('preview_location').textContent = form.location.value;
                document.getElementById('preview_salary').textContent =
                    `₹${form.salary_from.value} - ₹${form.salary_to.value}`;
                document.getElementById('preview_type').textContent = Array.from(form.querySelectorAll(
                    'input[name="type[]"]:checked')).map(el => el.value).join(', ');
                document.getElementById('preview_shift').textContent = Array.from(form.querySelectorAll(
                    'input[name="shift[]"]:checked')).map(el => el.value).join(', ');
                document.getElementById('preview_industry').textContent = form.industry.value;
                document.getElementById('preview_employment_level').textContent = Array.from(form
                    .querySelectorAll('input[name="employment_level[]"]:checked')).map(el => el.value).join(
                    ', ');
                document.getElementById('preview_is_remote').textContent = form.is_remote.value == '1' ? 'Yes' :
                    'No';
                document.getElementById('preview_skills').textContent = form.skills.value;
                document.getElementById('preview_description').textContent = form.description.value;
                document.getElementById('preview_requirements').textContent = form.requirements.value;
                document.getElementById('preview_responsibilities').textContent = form.responsibilities.value;
                document.getElementById('preview_benefits').textContent = form.benefits.value;
                document.getElementById('preview_application_url').textContent = form.application_url.value;
                document.getElementById('preview_deadline').textContent = form.deadline.value;
                document.getElementById('preview_experience').textContent = form.experience.value;
                document.getElementById('preview_education').textContent = Array.from(form.querySelectorAll(
                    'input[name="education[]"]:checked')).map(el => el.value).join(', ');
            };

            const renderStep = () => {
                steps.forEach((step, index) => {
                    step.classList.toggle('active', index === currentStep);
                });

                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentStep);
                    indicator.classList.toggle('completed', index < currentStep);
                });

                const progressPercentage = (currentStep / (steps.length - 1)) * 75;
                progressLine.style.width = `${progressPercentage}%`;

                prevBtn.classList.toggle('d-none', currentStep === 0);
                nextBtn.classList.toggle('d-none', currentStep === steps.length - 1);
                submitBtn.classList.toggle('d-none', currentStep !== steps.length - 1);

                if (currentStep === steps.length - 1) {
                    updatePreview();
                }
            };

            nextBtn.addEventListener('click', () => {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    renderStep();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    renderStep();
                }
            });

            form.addEventListener('submit', (e) => {
                // All fields are enabled by default now, no need to re-enable
            });

            renderStep();
        });

        function previewImage(event, previewId) {
            const input = event.target;
            const preview = document.getElementById(previewId);
            const previewContainer = document.getElementById('preview_' + previewId.replace('Preview', ''));

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    // Step 3 image (logoPreview / coverPreview)
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                        preview.style.display = 'block'; // ensure visible even if d-none removed
                    }

                    // Step 4 image (preview_logo / preview_cover)
                    if (previewContainer) {
                        previewContainer.src = e.target.result;
                        previewContainer.classList.remove('d-none');
                        previewContainer.style.display = 'block';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush

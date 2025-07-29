@extends('recruiter.layout.dashboard_layout')

@section('title', 'Create Job')

@push('create_job_styles')
    <style>
        .question-box {
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            padding: 1rem;
            position: relative;
        }

        .question-box .btn-danger {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
        }


        .progress-bar {
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-step-box {
            border: 1px solid #dee2e6;
            padding: 2rem;
            border-radius: 0.5rem;
            background-color: #ffffff;
        }

        [data-theme="dark"] .form-step-box {
            background-color: #1e1e1e;
            border-color: #333;
        }

        .form-label {
            font-weight: 500;
        }

        .img-preview {
            min-width: 120px;
            min-height: 120px;
            max-width: 100%;
            max-height: 300px;
            object-fit: contain;
            border: 1px solid #ccc;
            padding: 4px;
            background-color: #f9f9f9;
        }

        .step-section {
            display: none;
        }

        .step-section.active {
            display: block;
        }
    </style>
@endpush


@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container my-5">
        <h2 class="mb-4 fw-bold">Create Job</h2>

        <div class="progress mb-4" style="height: 25px;">
            <div class="progress-bar" role="progressbar" style="width: 25%;" id="progressBar">Step 1 of 4</div>
        </div>

        <form method="POST" action="{{ route('create_job') }}" enctype="multipart/form-data" id="jobForm">
            @csrf

            <div class="form-step-box shadow-sm" id="stepContainer">
                <div class="step-section active" data-step="0">@include('recruiter.dashboard.create.steps.step1')</div>
                <div class="step-section" data-step="1">@include('recruiter.dashboard.create.steps.step2')</div>
                <div class="step-section" data-step="2">@include('recruiter.dashboard.create.steps.step3')</div>
                <!-- ✅ Inserted Additional Questions Step Here -->
                <div class="step-section" data-step="3">
                    <h4 class="mb-3">Additional Questions</h4>
                    <div id="question-container"></div>
                    <button type="button" class="btn btn-outline-primary" onclick="addQuestion()">+ Add Question</button>
                </div>
                <div class="step-section" data-step="4">@include('recruiter.dashboard.create.steps.step4')</div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary d-none" id="prevBtn">Back</button>
                <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                <button type="submit" class="btn btn-success d-none" id="submitBtn">Post Job</button>
            </div>
        </form>
    </div>
@endsection

@push('create_job_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const steps = document.querySelectorAll('.step-section');
            const form = document.getElementById('jobForm');
            const progress = document.getElementById('progressBar');
            const prev = document.getElementById('prevBtn');
            const next = document.getElementById('nextBtn');
            const submit = document.getElementById('submitBtn');
            let currentStep = {{ session('current_step', 0) }};

            const renderStep = () => {
                steps.forEach((step, index) => {
                    const isActive = index === currentStep;
                    step.classList.toggle('active', isActive);
                    step.querySelectorAll('input, select, textarea').forEach(input => {
                        if (input.type !== 'file') input.disabled = !isActive;
                    });
                });

                progress.style.width = ((currentStep + 1) / steps.length) * 100 + '%';
                progress.textContent = `Step ${currentStep + 1} of ${steps.length}`;

                prev.classList.toggle('d-none', currentStep === 0);
                next.classList.toggle('d-none', currentStep === steps.length - 1);
                submit.classList.toggle('d-none', currentStep !== steps.length - 1);

                if (currentStep === steps.length - 1) fillPreviewStep();
            };

            next.addEventListener('click', () => {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    renderStep();
                }
            });

            prev.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    renderStep();
                }
            });

            form.addEventListener('submit', (e) => {
                form.querySelectorAll('input, select, textarea').forEach(input => input.disabled = false);
            });

            renderStep();
        });

        function previewImage(event, id) {
            const file = event.target.files[0];
            const preview = document.getElementById(id);
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('d-none');
            } else {
                preview.src = '';
                preview.classList.add('d-none');
            }
        }

        function fillPreviewStep() {
            const form = document.getElementById('jobForm');

            const logoInput = form.querySelector('input[name="company_logo"]');
            const coverInput = form.querySelector('input[name="cover_image"]');

            if (logoInput?.disabled) logoInput.disabled = false;
            if (coverInput?.disabled) coverInput.disabled = false;

            const logoPreview = document.getElementById('preview_logo');
            if (logoInput?.files.length > 0) {
                const reader = new FileReader();
                reader.onload = e => {
                    logoPreview.src = e.target.result;
                    logoPreview.classList.remove('d-none');
                };
                reader.readAsDataURL(logoInput.files[0]);
            }

            const coverPreview = document.getElementById('preview_cover');
            if (coverInput?.files.length > 0) {
                const reader = new FileReader();
                reader.onload = e => {
                    coverPreview.src = e.target.result;
                    coverPreview.classList.remove('d-none');
                };
                reader.readAsDataURL(coverInput.files[0]);
            }

            const fields = [
                'title', 'company', 'location', 'industry', 'description',
                'requirements', 'responsibilities', 'benefits',
                'application_url', 'deadline', 'experience', 'education'
            ];

            fields.forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                const output = document.getElementById(`preview_${field}`);
                if (output) {
                    if (input?.tagName === 'SELECT') {
                        output.textContent = input.options[input.selectedIndex]?.text || 'NULL';
                    } else {
                        output.textContent = input?.value?.trim() || 'NULL';
                    }
                }
            });

            const salaryFrom = form.querySelector('[name="salary_from"]');
            const salaryTo = form.querySelector('[name="salary_to"]');
            const salaryOutput = document.getElementById('preview_salary');
            if (salaryFrom && salaryTo && salaryOutput) {
                const fromVal = salaryFrom.value ? `₹${parseInt(salaryFrom.value).toLocaleString()}` : null;
                const toVal = salaryTo.value ? `₹${parseInt(salaryTo.value).toLocaleString()}` : null;
                salaryOutput.textContent = (fromVal && toVal) ? `${fromVal} to ${toVal} / month` : 'NULL';
            }

            const jobTypeValues = Array.from(form.querySelectorAll('input[name="type[]"]:checked')).map(cb => cb.value);
            document.getElementById('preview_type').textContent = jobTypeValues.length ? jobTypeValues.join(', ') : 'NULL';

            const shiftValues = Array.from(form.querySelectorAll('input[name="shift[]"]:checked')).map(cb => cb.value);
            document.getElementById('preview_shift').textContent = shiftValues.length ? shiftValues.join(', ') : 'NULL';

            const levelValues = Array.from(form.querySelectorAll('input[name="employment_level[]"]:checked')).map(cb => cb
                .value);
            document.getElementById('preview_employment_level').textContent = levelValues.length ? levelValues.join(', ') :
                'NULL';

            const isRemote = form.querySelector('[name="is_remote"]');
            document.getElementById('preview_is_remote').textContent = isRemote?.options[isRemote.selectedIndex]?.text ||
                'NULL';

            const skillsInput = form.querySelector('[name="skills"]');
            document.getElementById('preview_skills').textContent = skillsInput?.value.trim() || 'NULL';

            // ✅ Additional Questions Preview
            const previewContainer = document.getElementById('preview_questions');
            previewContainer.innerHTML = ''; // Clear preview
            const questionBoxes = form.querySelectorAll('.question-box');

            if (questionBoxes.length > 0) {
                questionBoxes.forEach((box, index) => {
                    const qText = box.querySelector(`input[name^="questions["][name$="[text]"]`)?.value.trim();
                    const qTypeSelect = box.querySelector(`select[name^="questions["][name$="[type]"]`);
                    const qType = qTypeSelect?.options[qTypeSelect.selectedIndex]?.text;
                    const qRequiredSelect = box.querySelector(`select[name^="questions["][name$="[optional]"]`);
                    const isOptional = qRequiredSelect?.value === "1";

                    if (qText) {
                        const p = document.createElement('p');
                        p.innerHTML =
                            `<strong>Q${index + 1}: ${qText}</strong> <span class="badge bg-secondary">${qType}</span> <span class="badge ${isOptional ? 'bg-success' : 'bg-danger'}">${isOptional ? 'Optional' : 'Required'}</span>`;
                        previewContainer.appendChild(p);
                    }
                });
            }

            if (previewContainer.innerHTML.trim() === '') {
                previewContainer.innerHTML = '<p class="text-muted">No additional questions added.</p>';
            }
        }

        // ✅ Question Management
        let questionCount = 0;

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

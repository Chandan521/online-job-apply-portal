@php
    // Always treat $job as object for all fields in job-detail partial
    if (is_array($job)) {
        $job = (object) $job;
    }
    $showApplyButton = $showApplyButton ?? true;

    // Use the controller-provided $skills, $initialSkills, $remainingSkills if available
    if (isset($initialSkills) && isset($remainingSkills)) {
        // Use values from controller
    } else {
        // Fallback for direct/legacy usage
        $skills = isset($skills) ? $skills : (is_array($job->skills) ? $job->skills : (array) $job->skills);
        if (is_string($skills)) {
            $decoded = json_decode($skills, true);
            $skills = is_array($decoded) ? $decoded : array_filter(array_map('trim', preg_split('/[;,]/', $skills)));
        }
        $initialSkills = array_slice($skills, 0, 4);
        $remainingSkills = array_slice($skills, 4);
    }
@endphp

{{-- Optional Cover Image Banner --}}
@if (!empty($job->cover_image))
    <div class="w-100"
        style="height: 180px; background: url('{{ filter_var($job->cover_image, FILTER_VALIDATE_URL) ? $job->cover_image : (Illuminate\Support\Facades\Storage::exists($job->cover_image) ? Storage::url($job->cover_image) : asset($job->cover_image)) }}') center center/cover no-repeat; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
    </div>
@else
    <div class="w-100 bg-light d-flex align-items-center justify-content-center"
        style="height: 180px; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
        <i class="bi bi-image fs-1 text-muted"></i>
    </div>
@endif

<div class="border rounded shadow-sm d-flex flex-column" style="max-height: 900px; height: auto; overflow: auto;">
    {{-- Sticky Top Section --}}
    <div class="p-3 border-bottom bg-white sticky-top z-2 d-flex align-items-center gap-3" style="min-height: 110px;">
        {{-- Optional Company Logo --}}
        @if (!empty($job->company_logo))
            <img src="{{ filter_var($job->company_logo, FILTER_VALIDATE_URL) ? $job->company_logo : (Illuminate\Support\Facades\Storage::exists($job->company_logo) ? Storage::url($job->company_logo) : asset($job->company_logo)) }}"
                alt="{{ $job->company }} Logo" class="rounded"
                style="width: 56px; height: 56px; object-fit: contain; background: #f8f9fa; border: 1px solid #eee;">
        @else
            <div class="rounded bg-light d-flex align-items-center justify-content-center"
                style="width: 56px; height: 56px; border: 1px solid #eee;">
                <i class="bi bi-building fs-3 text-muted"></i>
            </div>
        @endif
        <div style="flex:1">
            <h4 class="mb-1">{{ $job->title }}</h4>
            <div id="companyLocationBlock">
                <h6 class="text-muted mb-0" id="jobCompany">{{ $job->company }}</h6>
                <p class="mb-0 small" id="jobLocation"><i class="bi bi-geo-alt"></i> {{ $job->location }}</p>
            </div>
            <p class="mb-2"><strong>Salary:</strong> {{ $job->salary }}</p>
            <div class="mb-2">
                @if ($showApplyButton)
                    @php
                        $deadlinePassed = !empty($job->deadline) && \Carbon\Carbon::parse($job->deadline)->isPast();
                        $isInactive = isset($job->status) && strtolower($job->status) !== 'active';
                    @endphp

                    @if ($deadlinePassed || $isInactive)
                        <div class="alert alert-warning p-1 px-2 mb-2 d-inline-block" style="font-size: 0.875rem;">
                            This job is no longer accepting applications.
                        </div>
                    @else
                        <a href="{{ route('job.apply', [
                            $job->id,
                            urlencode($job->title),
                            urlencode($job->company ?? ($job->company_name ?? '')),
                            urlencode($job->location),
                            urlencode($job->salary),
                        ]) }}"
                            class="btn btn-primary btn-sm">Apply Now</a>
                    @endif
                @endif

                <button class="btn btn-outline-secondary btn-sm save-job-btn" data-job-id="{{ $job->id }}"
                    title="Save Job">
                    <i class="bi bi-bookmark"></i>
                </button>

                <button class="btn btn-outline-secondary btn-sm"
                    onclick="navigator.clipboard.writeText('{{ url('/job/' . $job->id . '/view') }}');this.textContent='Copied!';"
                    title="Copy full job view link"><i class="bi bi-clipboard"></i></button>
                <a href="{{ route('job.share', $job->id) }}" class="btn btn-outline-secondary btn-sm"
                    title="Share this job"><i class="bi bi-share"></i></a>

                <a href="{{ route('job.full-view', $job->id) }}" class="btn btn-outline-secondary btn-sm"
                    title="View this job"><i class="bi bi-eye"></i></a>
            </div>
        </div>
    </div>

    {{-- Scrollable Details Section --}}
    <div class="overflow-auto p-3" style="flex: 1; max-height: 700px; min-height: 350px;"
        onscroll="toggleCompanyLayout(this)">
        <hr>
        {{-- SKILLS SECTION --}}
        @php
            $skills = [];
            if (is_string($job->skills)) {
                $skills = array_filter(array_map('trim', explode(',', $job->skills)));
            }
            $initialSkills = array_slice($skills, 0, 4);
            $remainingSkills = array_slice($skills, 4);
        @endphp

        <h5 class="mb-3">Skills</h5>
        <div class="mb-3">
            <div class="d-flex flex-wrap gap-2">
                @foreach ($initialSkills as $skill)
                    <span class="badge bg-light text-dark border">{{ $skill }}</span>
                @endforeach
                @if (count($remainingSkills) > 0)
                    <a href="javascript:void(0)" onclick="toggleSkills(this)"
                        class="badge bg-primary text-white text-decoration-none border">Show more</a>
                @endif
            </div>
            <div id="extraSkills" class="d-none mt-2 d-flex flex-wrap gap-2">
                @foreach ($remainingSkills as $skill)
                    <span class="badge bg-light text-dark border">{{ $skill }}</span>
                @endforeach
            </div>
        </div>

        <hr>

        <h5>Job Details</h5>
        <p class="small">Here’s how the job details align with your profile.</p>

        <ul class="list-unstyled">
            <li>
    <strong>Job type:</strong>
    @foreach (json_decode($job->type, true) as $type)
        <span class="badge bg-primary me-1">{{ $type }}</span>
    @endforeach
</li>

           <li>
    <strong>Shift:</strong>
    @foreach (json_decode($job->shift, true) as $shift)
        <span class="badge bg-secondary me-1">{{ $shift }}</span>
    @endforeach
</li>

        </ul>

        <hr>

        <h5>Full Job Description</h5>
        <p class="small">
            We are looking for a self-motivated developer who writes clean and efficient code. You will work on
            improving our product codebase and should be confident in handling the complete software development
            lifecycle. The ideal candidate should be ready to take the lead in deciding the architecture and structure
            of the product.
        </p>

        <ul>
            <li>Develop and integrate user-facing elements</li>
            <li>Write efficient, testable PHP modules</li>
            <li>Handle REST APIs and integrate external systems</li>
            <li>Knowledge of CodeIgniter, MongoDB, MVC, etc.</li>
            <li>Version control with Git or SVN</li>
        </ul>

        <p class="small"><strong>Job Types:</strong> Full-time, Fresher</p>
        <p class="small"><strong>Location Type:</strong> {{ $job->location }}</p>
        <p class="small">
    <strong>Schedule:</strong>
    @foreach (json_decode($job->shift, true) as $shift)
        <span class="badge bg-info text-dark me-1">{{ $shift }}</span>
    @endforeach
</p>

        <p class="small"><strong>Contact:</strong> +91 8108325237</p>

        <hr>

        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-flag-fill text-danger"></i>
            <a href="#" class="text-danger small">Report this job</a>
        </div>
    </div>
</div>

<script>
    function toggleSkills(link) {
        const extra = document.getElementById('extraSkills');
        if (extra.classList.contains('d-none')) {
            extra.classList.remove('d-none');
            extra.classList.add('d-flex');
            link.textContent = 'Show less';
        } else {
            extra.classList.remove('d-flex');
            extra.classList.add('d-none');
            link.textContent = 'Show more';
        }
    }


    function toggleCompanyLayout(container) {
        const company = document.getElementById('jobCompany');
        const location = document.getElementById('jobLocation');
        const parent = document.getElementById('companyLocationBlock');

        if (container.scrollTop > 20) {
            parent.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'gap-2');
            company.classList.add('mb-0', 'me-2');
            location.classList.add('mb-0');
        } else {
            parent.classList.remove('d-flex', 'justify-content-between', 'align-items-center', 'gap-2');
            company.classList.remove('mb-0', 'me-2');
            location.classList.remove('mb-0');
        }
    }

    function copyJobLink(btn, link) {
        navigator.clipboard.writeText(link).then(function() {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-clipboard-check"></i> Copied!';
            setTimeout(() => {
                btn.innerHTML = original;
            }, 1200);
        });
    }
</script>

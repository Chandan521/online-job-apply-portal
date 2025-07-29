{{-- Support Component --}}
<div class="bg-light rounded shadow-sm p-4 mb-5" style="background-color: rgba(128,128,128,0.1);">
        <div class="row g-4">
            {{-- Left: We're here to help --}}
            <div class="col-md-6">
                <h5 class="fw-bold mb-3">We're here to help</h5>
                <p class="text-muted">
                    Visit our Help Centre for answers to common questions or contact us directly.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-primary">Help Center</a>
                    <a href="#" class="btn btn-outline-secondary">Contact Support</a>
                </div>
            </div>

            {{-- Right: Footer-like Columns --}}
            <div class="col-md-6">
                <div class="row">
                    <div class="col-6 col-md-4 mb-3">
                        <h6 class="fw-bold"> {{ setting('site_name' ?? 'Name Not Set') }}</h6>
                        <ul class="list-unstyled small">
                            <li><a href="#" class="text-muted text-decoration-none">About  {{ setting('site_name' ?? 'Name Not Set') }}</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Press</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Security</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Terms</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Privacy Centre</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">About ESG</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Accessibility</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Work at  {{ setting('site_name' ?? 'Name Not Set') }}</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Countries</a></li>
                        </ul>
                    </div>

                    <div class="col-6 col-md-4 mb-3">
                        <h6 class="fw-bold">Jobseekers</h6>
                        <ul class="list-unstyled small">
                            <li><a href="#" class="text-muted text-decoration-none">Post your CV</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Career advice</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Salaries</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Browse jobs</a></li>
                        </ul>
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <h6 class="fw-bold">Resources</h6>
                        <ul class="list-unstyled small">
                            <li><a href="#" class="text-muted text-decoration-none">How to hire employees</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Write job descriptions</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Hiring with  {{ setting('site_name' ?? 'Name Not Set') }}</a></li>
                            <li><a href="#" class="text-muted text-decoration-none">Interview guide</a></li>
                            <li><a href="#" class="text-muted text-decoration-none"> {{ setting('site_name' ?? 'Name Not Set') }} Events</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
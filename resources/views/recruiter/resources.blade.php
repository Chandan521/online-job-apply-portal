@extends('recruiter.layout.app')

@section('title', 'Resources')

@section('content')
    This Is product 
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-white">
        <div class="container">
            <div class="row justify-content-center mb-2">
                <div class="col-md-auto">
                    <strong class="text-dark">{{ setting('site_name' ?? 'Name Not Set') }}</strong> &copy; {{ date('Y') }}. All
                    rights reserved.
                </div>
                <div class="col-md-auto">
                    <span class="d-block d-md-inline mt-2 mt-md-0">
                        | Empowering recruiters to find the right talent with confidence.
                    </span>
                </div>
            </div>
            <div class="small text-secondary">
                <span class="d-block d-md-inline">Need help? Visit our <a href="{{ route('pages.show', 'help') }}"
                        class="text-decoration-none text-primary">Employer Help Center</a>.</span>
            </div>
        </div>
    </footer>
@endsection

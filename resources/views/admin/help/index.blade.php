@extends('admin.layout.app')

@section('title', 'Admin - Help Center')

@section('page-title', 'Help Center')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center bg-primary text-white">
                        <h2>Developer Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ asset('storage/profile_photos/developer.jpg') }}" alt="Developer Avatar"
                                class="rounded-circle avatar-lg img-thumbnail mb-3">
                            <h3>Mr. Chandan Mondal</h3>
                            <p class="text-muted">Full Stack Developer</p>
                        </div>

                        <hr>

                        <h5>About Me</h5>
                        <p>
                            I’m Chandan Mondal, a professional PHP/Laravel developer with 4 years of experience in building
                            dynamic, secure, and user-friendly web applications. I specialize in backend development using
                            Laravel, database management with MySQL, and creating responsive interfaces with HTML, CSS,
                            Bootstrap, and JavaScript.

                            I’ve worked on projects ranging from admin panels and job portals to e-commerce platforms and
                            institute management systems. My focus is on clean code, scalable architecture, and delivering
                            reliable solutions tailored to business needs.
                        </p>

                        <h5>Contact Information</h5>
                        <ul class="list-unstyled">
                            <li><strong>Email:</strong> <a
                                    href="mailto:chandanmondal0021@gmail.com">chandanmondal0021@gmail.com</a>
                            </li>
                            <li><strong>Website:</strong> <a href="https://www.instagram.com/chandan_521m/" target="_blank">ChandanMondal</a>
                            </li>
                            <li><strong>GitHub:</strong> <a href="https://github.com/Chandan521"
                                    target="_blank">github.com/Chandan521</a></li>
                        </ul>

                        <h5>Disclaimer</h5>
                        <p class="text-danger bg-dark p-3">
                            This is a static information page. For any technical support or issues with the application,
                            please contact the administrator.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('dashboard-scripts')
    <style>
        .avatar-lg {
            height: 150px;
            width: 150px;
        }
    </style>
@endpush

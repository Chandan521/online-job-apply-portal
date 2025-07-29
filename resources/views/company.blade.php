@extends('layouts.app')

@section('title', 'Company')

{{-- @php
    // Sample company data
    $companies = [
        ['name' => 'Company A', 'rating' => 4.3, 'reviews' => 1120],
        ['name' => 'Company B', 'rating' => 3.7, 'reviews' => 850],
        ['name' => 'Company C', 'rating' => 4.8, 'reviews' => 1400],
        ['name' => 'Company D', 'rating' => 2.9, 'reviews' => 430],
        ['name' => 'Company E', 'rating' => 3.2, 'reviews' => 990],
        ['name' => 'Company F', 'rating' => 5.0, 'reviews' => 2100],
    ];
@endphp --}}

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                {{-- Page Title --}}
                <h2 class="mb-4">Find great places to work</h2>
                <p class="text-muted">Get access to millions of company reviews</p>

                {{-- Search Form --}}
                <form class="row g-2 mb-5" method="GET" action="{{ route('company') }}">
                    <div class="col-12 col-md-9">
                        <input type="text" name="q" class="form-control form-control-lg"
                            placeholder="Company name or job title" value="{{ request('q') }}">
                    </div>
                    <div class="col-12 col-md-3">
                        <button class="btn btn-primary btn-lg w-100" type="submit">Find Company</button>
                    </div>
                </form>



                {{-- Popular Companies Section --}}
                <h4 class="mb-3">Popular Companies</h4>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($companies as $company)
                        <div class="col">
                            <div class="border p-3 rounded h-100 d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    @php
                                        $logo = $company->company_logo
                                            ? asset('/' . $company->company_logo)
                                            : 'https://ui-avatars.com/api/?name=' .
                                                urlencode($company->company) .
                                                '&background=random';
                                    @endphp
                                    <img src="{{ $logo }}" class="me-3 rounded" width="50" height="50"
                                        alt="Logo">
                                    <div>
                                        <h6 class="mb-0">{{ $company->company ?? 'N/A' }}</h6>
                                        <div class="text-warning small">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($company->avg_rating >= $i)
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif ($company->avg_rating >= $i - 0.5)
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                            <span class="text-muted">({{ $company->total_reviews }} reviews)</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    @php
                                        $salaryRange = explode(
                                            '-',
                                            str_replace(['₹', ',', ' '], '', $company->salary ?? ''),
                                        );
                                        $minSalary = isset($salaryRange[0])
                                            ? number_format((int) $salaryRange[0])
                                            : null;
                                        $maxSalary = isset($salaryRange[1])
                                            ? number_format((int) $salaryRange[1])
                                            : null;
                                    @endphp

                                    @if ($minSalary && $maxSalary)
                                        <p class="mb-1 small text-muted">Salary: ₹{{ $minSalary }} - ₹{{ $maxSalary }}
                                        </p>
                                    @else
                                        <p class="mb-1 small text-muted">Salary: Not specified</p>
                                    @endif


                                    <div class="d-flex gap-3 flex-wrap mt-2">
                                        <a href="{{ route('job.full', $company->id) }}"
                                            class="text-decoration-none small">Open jobs</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>

            </div>
        </div>
    </div>
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-5 border-top small bg-light">
        © {{ date('Y') }} <strong> {{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection

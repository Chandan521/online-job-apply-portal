@extends('layouts.app')

@section('title', 'Salary Insights')
@section('salaries_css')
<style>
.salary-hero {
    max-width: 900px;
    margin: 2.5rem auto 1.5rem auto;
    border-radius: 18px;
    background: linear-gradient(90deg, #3a86ff 0%, #8338ec 100%);
    color: #fff;
    box-shadow: 0 6px 32px rgba(58,134,255,0.09);
    padding: 2.5rem 1.5rem 2rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.salary-hero h1 {
    font-weight: 800;
    font-size: 2.3rem;
    letter-spacing: 0.02em;
    color: #fff;
}
.salary-hero p {
    font-size: 1.15rem;
    margin-bottom: 0;
    color: #e0e3e9;
}
.salary-hero .icon-briefcase {
    font-size: 2.6rem;
    margin-bottom: 1rem;
    color: #ffd60a;
    filter: drop-shadow(0 2px 8px #0003);
}
.salary-table-holder {
    max-width: 980px;
    margin: 0 auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(58,134,255,0.07);
    padding: 2rem 1.3rem;
}
.salary-table-holder .table {
    margin-bottom: 0;
}
.salary-table-holder thead th {
    font-weight: 700;
    background: #f6f8fa;
    border-bottom: 2px solid #e3e6ea;
    letter-spacing: 0.02em;
}
.salary-table-holder tbody tr {
    transition: background 0.16s;
}
.salary-table-holder tbody tr:hover {
    background: #f8fafd;
}
.salary-range {
    font-weight: 500;
    color: #3a86ff;
    background: #e7f4ff;
    padding: 0.35em 0.85em;
    border-radius: 1em;
    font-size: 1.07em;
    letter-spacing: 0.01em;
    display: inline-block;
}
@media (max-width: 767.98px) {
    .salary-hero, .salary-table-holder {
        padding: 1.5rem 0.5rem;
    }
    .salary-table-holder .table thead { font-size: 0.98em; }
}
</style>
@endsection

@section('content')
<div class="salary-hero mb-4">
    <div class="icon-briefcase mb-2">
        <i class="bi bi-cash-coin"></i>
    </div>
    <h1 class="mb-2">Salary Insights <span style="font-size:1.2rem;">for Top Job Titles</span></h1>
    <p>
        Explore real salary trends for the most in-demand roles.<br>
        See how pay ranges differ by role and make smarter career or hiring decisions.
    </p>
</div>

<div class="salary-table-holder mb-5">
    @if($jobs->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col"><i class="bi bi-briefcase"></i> Job Title</th>
                        <th scope="col"><i class="bi bi-collection"></i> # Jobs Posted</th>
                        <th scope="col"><i class="bi bi-graph-up-arrow"></i> Salary Range</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs as $job)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $job->title }}</span>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                                    {{ $job->job_count }}
                                </span>
                            </td>
                            <td>
                                <span class="salary-range">
                                    {{ number_format($job->min_salary) }} – {{ number_format($job->max_salary) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-end text-muted small mt-2">
                <i class="bi bi-info-circle"></i>
                Data is based on current job postings and may vary by location, seniority, and industry.
            </div>
        </div>
    @else
        <div class="alert alert-info text-center my-5">
            <i class="bi bi-emoji-frown display-6"></i><br>
            No salary data available yet.
        </div>
    @endif
</div>
@endsection

@section('footer')
<footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
    © {{ date('Y') }} <strong>{{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
    <span class="d-block d-md-inline mt-1 mt-md-0">| Built with <span style="color: #ff006e;">&#10084;</span> for job seekers and employers.</span>
</footer>
@endsection
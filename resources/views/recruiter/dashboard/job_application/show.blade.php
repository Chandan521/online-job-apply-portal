@extends('recruiter.layout.dashboard_layout')

@section('title', 'Application Details')

@section('content')

    <style>
        /* Light theme (default) */
        body[data-theme="light"] {
            background-color: #ffffff;
            color: #212529;
        }

        body[data-theme="light"] .table {
            background-color: #ffffff;
            color: #212529;
            border-color: #dee2e6;
        }

        body[data-theme="light"] .table th,
        body[data-theme="light"] .table td {
            background-color: #ffffff;
        }

        body[data-theme="light"] .badge {
            color: #fff;
        }

        /* Dark theme */
        body[data-theme="dark"] {
            background-color: #121212;
            color: #f1f1f1;
        }

        body[data-theme="dark"] .container {
            background-color: #1e1e1e;
        }

        body[data-theme="dark"] .table {
            background-color: #1e1e1e;
            color: #f1f1f1;
            border-color: #444;
        }

        body[data-theme="dark"] .table th,
        body[data-theme="dark"] .table td {
            background-color: #1e1e1e;
            border-color: #444;
        }

        body[data-theme="dark"] .badge.bg-primary {
            background-color: #0d6efd;
            color: #fff;
        }

        body[data-theme="dark"] .badge.bg-success {
            background-color: #198754;
            color: #fff;
        }

        body[data-theme="dark"] .badge.bg-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        body[data-theme="dark"] .img-thumbnail {
            background-color: #2c2c2c;
            border-color: #444;
        }

        body[data-theme="dark"] .btn-secondary {
            background-color: #444;
            color: #fff;
            border-color: #555;
        }

        body[data-theme="dark"] a {
            color: #0d6efd;
        }
    </style>


    <div class="container mt-4">
        <h2 class="mb-4">Application Details - {{ $application->first_name }} {{ $application->last_name }}</h2>

        <div class="row">
            {{-- Left Column --}}
            <div class="col-md-6">
                {{-- Candidate Info --}}
                <div class="card mb-4">
                    <div class="card-header">Candidate Info</div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $application->first_name }} {{ $application->last_name }}</p>
                        <p><strong>Email:</strong> {{ $application->email }}</p>
                        <p><strong>Phone:</strong> {{ $application->country_code }} {{ $application->phone }}</p>
                        <p><strong>City:</strong> {{ $application->city }}</p>
                        <p><strong>Applied For:</strong> {{ $application->job->title ?? '-' }}</p>
                        <p><strong>Applied On:</strong> {{ $application->created_at->format('d M Y') }}</p>
                    </div>
                </div>

                {{-- Application Status --}}
                <div class="card">
                    <div class="card-header">Application Status</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('applications.status', $application->id) }}">
                            @csrf
                            @method('PUT')

                            @php
                                $allowedStatuses = [
                                    'under_review',
                                    'shortlisted',
                                    'interview',
                                    'selected',
                                    'rejected',
                                    'hired',
                                ];
                                $currentStatus = $application->status;
                                $isWithdrawn = $currentStatus === 'withdrawn';
                            @endphp

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>

                                @if ($isWithdrawn)
                                    <select class="form-select" disabled>
                                        <option selected disabled>Withdrawn</option>
                                    </select>
                                @else
                                    <select name="status" id="status" class="form-select">
                                        @if (!in_array($currentStatus, $allowedStatuses))
                                            <option value="{{ $currentStatus }}" selected disabled>
                                                {{ ucwords(str_replace('_', ' ', $currentStatus)) }} (Locked)
                                            </option>
                                        @endif
                                        @foreach ($allowedStatuses as $status)
                                            <option value="{{ $status }}"
                                                {{ $currentStatus === $status ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            @if (!$isWithdrawn)
                                <button class="btn btn-primary" type="submit">Update Status</button>
                            @endif
                        </form>

                        {{-- Interview Scheduling Form --}}
                        <div id="interviewScheduleForm" class="card mt-4" style="display: none;">
                            <div class="card-header">Schedule Interview</div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('recruiter.schedule.interview') }}">
                                    @csrf
                                    <input type="hidden" name="job_application_id" value="{{ $application->id }}">
                                    <input type="hidden" name="job_id" value="{{ $application->job_id }}">

                                    <div class="mb-3">
                                        <label for="interview_datetime" class="form-label">Date & Time</label>
                                        <input type="datetime-local" name="interview_datetime" class="form-control"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="mode" class="form-label">Mode</label>
                                        <select name="mode" class="form-select" required>
                                            <option value="online">Online</option>
                                            <option value="in-person">In-person</option>
                                            <option value="phone">Phone</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Location / Meeting Link</label>
                                        <input type="text" name="location" class="form-control"
                                            placeholder="Zoom/Google Meet link or address">
                                    </div>

                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="3"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success">Schedule Interview</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>






                {{-- Messages --}}
                <div class="card mt-4">
                    <div class="card-header">Messages</div>
                    <div class="card-body" id="messageContainer" style="max-height: 400px; overflow-y: auto;">
                        @forelse ($application->messages as $msg)
                            @include('recruiter.components.message-bubble', ['chat' => $msg])
                        @empty
                            <div class="text-muted">No messages yet.</div>
                        @endforelse
                    </div>
                    <div class="card-footer">
                        <form id="messageForm">
                            @csrf
                            <div class="d-flex gap-2">
                                <textarea name="message" id="messageInput" rows="1" class="form-control" placeholder="Type a message..." required></textarea>
                                <input type="file" id="fileInput" name="file" class="d-none">
                                <button type="button" class="btn btn-light"
                                    onclick="document.getElementById('fileInput').click()">
                                    <i class="bi bi-paperclip"></i>
                                </button>
                                <button type="submit" class="btn btn-secondary">Send</button>
                            </div>
                            <div id="file-preview" class="mt-2"></div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-md-6">
                {{-- Resume Viewer --}}
                <div class="card mb-4">
                    <div class="card-header">Resume</div>
                    <div class="card-body">
                        @if ($application->resume)
                            @php $resumeUrl = '/storage/' . ltrim($application->resume, '/'); @endphp

                            <div id="pdf-toolbar" class="mb-2">
                                <button onclick="zoomIn()">Zoom In</button>
                                <button onclick="zoomOut()">Zoom Out</button>
                                <a href="{{ $resumeUrl }}" download target="_blank">Download</a>
                            </div>

                            <canvas id="pdf-canvas" style="border:1px solid #ccc; width: 100%;"></canvas>

                            <script>
                                const url = "{{ $resumeUrl }}";
                                let pdfDoc = null,
                                    pageNum = 1,
                                    scale = 1.2;
                                const canvas = document.getElementById('pdf-canvas');
                                const ctx = canvas.getContext('2d');

                                pdfjsLib.getDocument(url).promise.then(pdf => {
                                    pdfDoc = pdf;
                                    renderPage(pageNum);
                                });

                                function renderPage(num) {
                                    pdfDoc.getPage(num).then(page => {
                                        const viewport = page.getViewport({
                                            scale
                                        });
                                        canvas.height = viewport.height;
                                        canvas.width = viewport.width;
                                        page.render({
                                            canvasContext: ctx,
                                            viewport
                                        });
                                    });
                                }

                                function zoomIn() {
                                    scale += 0.2;
                                    renderPage(pageNum);
                                }

                                function zoomOut() {
                                    if (scale > 0.4) {
                                        scale -= 0.2;
                                        renderPage(pageNum);
                                    }
                                }
                            </script>
                        @else
                            <div class="text-muted">Resume not uploaded.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('recruiter_msg_scripts')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.min.js"></script>
    <script>
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env('PUSHER_APP_KEY') }}',
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            forceTLS: true
        });

        const container = document.getElementById('messageContainer');

        if (container) {
            container.scrollTop = container.scrollHeight;
        }

        Echo.private(`job-application.{{ $application->id }}`)
            .listen('.ApplicationMessageSent', (e) => {
                const msg = e.message;
                const isRecruiter = msg.sender.id === {{ auth()->id() }};
                const seen = msg.is_read ? true : false;

                let bubbleClass = isRecruiter ? 'chat-bubble recruiter bg-gradient-primary' :
                    'chat-bubble bg-gradient-light';
                let alignClass = isRecruiter ? 'justify-content-end' : 'justify-content-start';
                let name = isRecruiter ? 'You' : (msg.sender.name || 'Candidate');
                let fileHtml = '';
                if (msg.file_path) {
                    if (/\.(jpeg|jpg|gif|png|bmp|svg|webp)$/i.test(msg.file_path)) {
                        fileHtml =
                            `<img src="${msg.file_path}" class="mt-2" style="max-width:180px;border-radius:0.4rem;margin-top:0.5rem;box-shadow:0 1px 6px rgba(0,0,0,0.07);" alt="Attachment">`;
                    } else {
                        fileHtml = `
                            <a href="${msg.file_path}" target="_blank" class="file-link mt-2" style="margin-top:0.5rem;display:inline-flex;align-items:center;background:rgba(0,123,255,0.07);padding:0.3rem 0.8rem;border-radius:0.4rem;color:#0056b3;font-size:0.92rem;text-decoration:none;transition:background 0.18s;">
                                <i class="bi bi-file-earmark-text" style="margin-right:0.5rem;font-size:1.3em;"></i>
                                View File
                            </a>
                        `;
                    }
                }
                let statusHtml = '';
                if (isRecruiter) {
                    if (seen) {
                        statusHtml =
                            `<span class="status-tick"><span class="tick tick-blue">&#10003;</span><span class="tick tick-blue">&#10003;</span><span class="tick-label">Read</span></span>`;
                    } else {
                        statusHtml =
                            `<span class="status-tick"><span class="tick tick-grey">&#10003;</span><span class="tick tick-grey">&#10003;</span><span class="tick-label" style="background:#f1f2f6;color:#adb5bd;border:1px solid #e2e8f0;">Delivered</span></span>`;
                    }
                }
                const msgHtml = `
                <div class="d-flex ${alignClass}">
                    <div class="${bubbleClass}">
                        <span class="sender-name">${name}</span>
                        ${msg.message ? `<div>${msg.message}</div>` : ''}
                        ${fileHtml ? `<div>${fileHtml}</div>` : ''}
                        <div class="timestamp-row">
                            <span>${new Date(msg.created_at).toLocaleString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true }).replace(',', '')}</span>
                            ${statusHtml}
                        </div>
                    </div>
                </div>
                `;
                container.insertAdjacentHTML('beforeend', msgHtml);
                container.scrollTop = container.scrollHeight;
            });

        document.getElementById('messageForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const input = document.getElementById('messageInput');
            const fileInput = document.getElementById('fileInput');
            const file = fileInput.files[0];
            const message = input.value.trim();

            if (!message && !file) return; // nothing to send

            const formData = new FormData();
            formData.append('message', message);

            if (file) {
                formData.append('file', file);
            }

            fetch("{{ route('job_application.message', $application->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        document.getElementById('fileInput').value = '';
                        document.getElementById('file-preview').innerHTML = '';
                    }
                })
                .catch(console.error);
        });

        const fileInput = document.getElementById('fileInput');
        const filePreview = document.getElementById('file-preview');

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        let preview;
                        if (file.type.startsWith('image/')) {
                            preview = `<img src="${e.target.result}" class="img-thumbnail" width="100">`;
                        } else {
                            preview = `
                            <div class="d-flex align-items-center">
                                <i class="bi bi-file-earmark-text fs-2"></i>
                                <span class="ms-2">${file.name}</span>
                            </div>
                        `;
                        }
                        filePreview.innerHTML = preview;
                    }
                    reader.readAsDataURL(file);
                } else {
                    filePreview.innerHTML = '';
                }
            });
        }
    </script>

    {{-- JavaScript to toggle the interview form --}}
    <script>
        $(document).ready(function() {
            const $status = $('#status');
            const $interviewForm = $('#interviewScheduleForm');

            // Always hide interview form initially
            $interviewForm.hide();

            // Show if recruiter selects 'interview'
            $status.on('change', function() {
                const selected = $(this).val();
                if (selected === 'interview') {
                    $interviewForm.slideDown();
                } else {
                    $interviewForm.slideUp();
                }
            });
        });
    </script>
@endpush

@extends('layouts.app')

@section('title', 'Messages - ')

@section('content')
    <div class="container-fluid py-4 px-3 px-md-5">
        <div class="row g-0">
            <!-- Left: Conversations List -->
            <div class="col-12 col-md-4 col-lg-3 border-end px-3 mb-3 mb-md-0" style="max-height: 80vh; overflow-y: auto;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Messages</h5>
                    <span class="badge bg-success small">Online status: On</span>
                </div>

                <div class="mb-3">
                    <select class="form-select">
                        <option>Inbox</option>
                        <option>Archived</option>
                    </select>
                </div>

                <div class="list-group" id="conversationList">
                    @php
                        $visibleApplications = $applications->filter(function ($app) {
                            return $app->messages->contains(function ($msg) {
                                return $msg->sender_id !== auth()->id();
                            });
                        });
                    @endphp

                    @forelse ($visibleApplications as $app)
                        @php
                            $msg = $app->messages->last();
                            $unreadCount = $app->messages
                                ->where('sender_id', '!=', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        <a href="{{ route('user.conversations.show', $app->id) }}"
                            class="list-group-item list-group-item-action d-flex gap-3 rounded @if ($selectedApplication && $selectedApplication->id === $app->id) border-primary border-2 shadow-sm bg-light @endif"
                            data-app-id="{{ $app->id }}">

                            @if ($app->job->company_logo)
                                <img src="{{ asset($app->job->company_logo) }}" style="width: 40px; height: 40px;"
                                    class="bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" />
                            @else
                                <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 40px; height: 40px;">
                                    <i class="bi bi-buildings"></i>
                                </div>
                            @endif


                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $app->job->title ?? 'Unknown Company' }}</div>
                                <div class="">{{ $app->job->company ?? 'Unknown Company' }}</div>
                                
                                <div class="text-muted small">{{ Str::limit($msg->message ?? '', 40) }}</div>
                            </div>
                            <div class="text-end text-muted small">
                                {{ $msg ? $msg->created_at->format('d M') : '' }}
                                <span class="badge bg-danger ms-2 unread-badge"
                                    style="display: {{ $unreadCount > 0 ? 'inline-block' : 'none' }}">{{ $unreadCount }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="text-muted">No messages from recruiters yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Middle: Chat Box -->
            <div class="col-12 col-md-8 col-lg-6 border-end px-3 px-md-4">
                @if ($selectedApplication)
                    <!-- Company/job info always visible at top -->
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded"
                        style="background-color: #f8f9fa;">
                        <div class="d-flex align-items-center">
                            @if ($selectedApplication->job->company_logo)
                                <img src="{{ asset('/' . $selectedApplication->job->company_logo) }}"
                                    alt="{{ $selectedApplication->job->company }}" class="rounded-circle me-3"
                                    style="width: 50px; height: 50px;">
                            @else
                                <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-circle me-3"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi bi-buildings fs-4"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-0">{{ $selectedApplication->job->title ?? 'Job Title' }}</h5>
                                <p class="mb-0 text-muted">{{ $selectedApplication->job->company ?? 'Company' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('job.full-view', $selectedApplication->job->id) }}"
                            class="btn btn-outline-primary">View Job</a>
                    </div>

                    @php
                        $recruiterHasMessaged = $selectedApplication->messages->contains(function ($msg) {
                            return $msg->sender_id !== auth()->id();
                        });
                    @endphp

                    @if ($recruiterHasMessaged)
                        <div class="list-group mb-4" id="messageContainer" style="max-height:55vh; overflow-y:auto;">
                            @foreach ($selectedApplication->messages as $chat)
                                @include('components.message-bubble', ['chat' => $chat])
                            @endforeach
                        </div>

                        <!-- Reply Form -->
                        <form id="replyForm" method="POST" action="javascript:void(0);"
                            data-action="{{ route('user.conversations.send', $selectedApplication->id) }}">
                            @csrf
                            <div class="mb-2">
                                <textarea name="message" id="messageInput" class="form-control bg-light border-0 shadow-sm" rows="2"
                                    placeholder="Write your message..."></textarea>
                            </div>
                            <div id="file-preview" class="mt-2"></div>
                            <div class="d-flex justify-content-between align-items-center">
                                <input type="file" id="fileInput" name="file" class="d-none">
                                <button type="button" class="btn btn-light"
                                    onclick="document.getElementById('fileInput').click()">
                                    <i class="bi bi-paperclip"></i>
                                </button>
                                <button type="submit" class="btn btn-success px-4">Send</button>
                            </div>
                        </form>
                    @else
                        <div class="list-group mb-4 text-center text-muted py-5" id="messageContainer"
                            style="max-height:55vh; overflow-y:auto;">
                            <i class="bi bi-clock-history fs-1"></i>
                            <p class="mt-3">Waiting for recruiter to message you first<br>
                                <small>You’ll be able to view and reply once contacted.</small>
                            </p>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-chat-square-dots fs-1"></i>
                        <p class="mt-3">You have messages<br><small>Select a conversation to read</small></p>
                    </div>
                @endif
            </div>

            <!-- Right: Job Details -->
            <div class="d-none d-lg-block col-lg-3 px-3" style="max-height: 80vh; overflow-y: auto;">
                @if ($selectedApplication && $selectedApplication->job)
                    <div class="border p-3 rounded shadow-sm">
                        <h6>{{ $selectedApplication->job->title }}</h6>
                        <p class="mb-0 text-muted">{{ $selectedApplication->job->company }}</p>
                        <small class="text-muted">{{ $selectedApplication->job->location }}</small>
                        <hr>
                        <p class="mb-1"><strong>Job type:</strong></p>
                        <ul class="small">
                            @foreach (explode(',', $selectedApplication->job->type ?? '') as $type)
                                <li>{{ trim($type) }}</li>
                            @endforeach
                        </ul>
                        <p class="mb-1"><strong>Salary:</strong></p>
                        <p class="small">{{ $selectedApplication->job->salary ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Job shift:</strong></p>
                        <ul class="small">
                            @foreach (explode(',', $selectedApplication->job->shift ?? '') as $shift)
                                <li>{{ trim($shift) }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong>{{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection

@push('scripts')
    @if ($selectedApplication)
        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('messageContainer');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });

            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('PUSHER_APP_KEY') }}',
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                encrypted: true
            });

            const container = document.getElementById('messageContainer');
            if (container) {
                window.Echo.private(`job-application.{{ $selectedApplication->id }}`)
                    .listen('.ApplicationMessageSent', function(e) {
                        const msg = e.message;
                        const isUser = msg.sender.id === {{ auth()->id() }};
                        const alignmentClass = isUser ? 'justify-content-end' : 'justify-content-start';
                        const bubbleClass = isUser ? 'jobseeker-chat-bubble user' : 'jobseeker-chat-bubble';
                        const name = isUser ? 'You' : (msg.sender.name || 'Recruiter');
                        const time = new Date(msg.created_at).toLocaleString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        }).replace(',', '');
                        let fileHtml = '';
                        if (msg.file_path) {
                            if (/\.(jpeg|jpg|gif|png|bmp|svg|webp)$/i.test(msg.file_path)) {
                                fileHtml =
                                    `<img src="${msg.file_path}" class="mt-2" alt="Attachment" style="max-width:180px;border-radius:0.4rem;margin-top:0.5rem;box-shadow:0 1px 6px rgba(0,0,0,0.07);">`;
                            } else {
                                fileHtml = `
                        <a href="${msg.file_path}" target="_blank" class="file-link mt-2" style="margin-top:0.5rem;display:inline-flex;align-items:center;background:rgba(56,189,248,0.10);padding:0.34rem 0.9rem;border-radius:0.4rem;color:#0e7490;font-size:0.96rem;text-decoration:none;transition:background 0.18s;">
                            <i class="bi bi-file-earmark-text" style="margin-right:0.5rem;font-size:1.3em;"></i>
                            View File
                        </a>
                    `;
                            }
                        }
                        const msgHtml = `
                <div class="d-flex mb-3 ${alignmentClass}">
                    <div class="${bubbleClass}">
                        <span class="sender-name">${name}</span>
                        ${msg.message ? `<div>${msg.message}</div>` : ''}
                        ${fileHtml ? `<div>${fileHtml}</div>` : ''}
                        <span class="timestamp">${time}</span>
                    </div>
                </div>
            `;
                        container.insertAdjacentHTML('beforeend', msgHtml);
                        container.scrollTop = container.scrollHeight;
                        const activeLink = document.querySelector(
                            `#conversationList [data-app-id="{{ $selectedApplication->id }}"] .unread-badge`);
                        if (activeLink) {
                            activeLink.textContent = '0';
                            activeLink.style.display = 'none';
                        }
                    });
            }

            const replyForm = document.getElementById('replyForm');
            const fileInput = document.getElementById('fileInput');
            const filePreview = document.getElementById('file-preview');
            const messageInput = document.getElementById('messageInput');

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

            if (replyForm) {
                replyForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const message = messageInput.value.trim();
                    const file = fileInput.files[0];

                    if (!message && !file) {
                        alert('Please enter a message or attach a file.');
                        return;
                    }

                    const formData = new FormData();
                    formData.append('message', message);
                    if (file) {
                        formData.append('file', file);
                    }

                    fetch(this.getAttribute('data-action'), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(async res => {
                            let data;
                            try {
                                data = await res.json();
                            } catch {
                                data = {};
                            }
                            if (res.ok && data.success) {
                                messageInput.value = '';
                                fileInput.value = '';
                                filePreview.innerHTML = '';
                            } else if (data && data.error) {
                                alert(data.error);
                            } else if (data && data.errors && data.errors.file && Array.isArray(data.errors
                                    .file)) {
                                alert(data.errors.file[0]);
                            } else {
                                alert('Something went wrong. Message not sent.');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Message failed to send.');
                        });
                });
            }
        </script>
    @endif
@endpush

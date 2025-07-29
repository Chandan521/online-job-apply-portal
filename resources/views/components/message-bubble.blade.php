@php
    $isUser = $chat->sender_id === auth()->id();
    $alignClass = $isUser ? 'justify-content-end' : 'justify-content-start';
    $bubbleClass = $isUser ? 'jobseeker-chat-bubble user' : 'jobseeker-chat-bubble';
    $time = \Carbon\Carbon::parse($chat->created_at)->format('d M Y h:i A');
    $isImage = $chat->file_path && preg_match('/\.(jpeg|jpg|png|gif|bmp|svg|webp)$/i', $chat->file_path);
    $name = $chat->sender->name ?? ($isUser ? 'You' : 'Recruiter');
    $seen = isset($chat->is_read) && $chat->is_read;
@endphp

<style>
    .jobseeker-chat-bubble {
        border-radius: 1.2rem 1.2rem 0.4rem 1.2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.07);
        padding: 0.8rem 1.1rem;
        max-width: 75%;
        word-break: break-word;
        position: relative;
        margin-bottom: 0.3rem;
        background: linear-gradient(135deg, #f3f4f6 80%, #dbeafe 100%);
        color: #1e293b;
    }
    .jobseeker-chat-bubble.user {
        background: linear-gradient(135deg, #22d3ee 70%, #38bdf8 100%);
        color: #fff;
        border-bottom-right-radius: 1.2rem;
        border-bottom-left-radius: 0.4rem;
        text-align: right;
    }
    .jobseeker-chat-bubble .sender-name {
        font-size: 0.83rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
        opacity: 0.78;
    }
    .jobseeker-chat-bubble .timestamp-row {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 4px;
        margin-top: 0.3rem;
        font-size: 0.75rem;
        opacity: 0.7;
        width: 100%;
    }
    .jobseeker-chat-bubble img {
        max-width: 180px;
        border-radius: 0.4rem;
        margin-top: 0.5rem;
        box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    }
    .jobseeker-chat-bubble .file-link {
        margin-top: 0.5rem;
        display: inline-flex;
        align-items: center;
        background: rgba(56,189,248,0.10);
        padding: 0.34rem 0.9rem;
        border-radius: 0.4rem;
        color: #0e7490;
        font-size: 0.96rem;
        text-decoration: none;
        transition: background 0.18s;
    }
    .jobseeker-chat-bubble .file-link:hover {
        background: rgba(56,189,248,0.23);
        color: #155e75;
        text-decoration: underline;
    }
    .jobseeker-chat-bubble .file-link i {
        margin-right: 0.5rem;
        font-size: 1.3em;
    }
    .status-tick {
        display: inline-flex;
        align-items: center;
        margin-left: 6px;
        font-size: 1em;
        vertical-align: middle;
        line-height: 1;
        font-weight: bold;
    }
    .status-tick .tick {
        font-size: 1.15em;
        margin-left: 1px;
        margin-right: 1px;
    }
    .tick-grey {
        color: #adb5bd;
        filter: drop-shadow(0px 0px 2px #fff);
    }
    .tick-blue {
        color: #38bdf8;
        filter: drop-shadow(0px 0px 2px #fff);
    }
    .tick-label {
        margin-left: 2px;
        font-size: 0.72em;
        font-weight: 500;
        letter-spacing: 0.01em;
        padding: 1px 6px;
        border-radius: 9px;
        background: #e9ecef;
        color: #38bdf8;
        display: inline-block;
        opacity: 0.9;
    }
    .jobseeker-chat-bubble.user .tick-label {
        background: #1cc8ee;
        color: #fff;
        opacity: 1;
    }
    .jobseeker-chat-bubble.user .status-tick .tick-grey,
    .tick-grey {
        color: #e2e8f0;
    }
</style>

<div class="d-flex mb-3 {{ $alignClass }}">
    <div class="{{ $bubbleClass }}">
        <span class="sender-name">{{ $isUser ? 'You' : $name }}</span>
        @if ($chat->message)
            <div>{{ $chat->message }}</div>
        @endif

        @if ($chat->file_path)
            <div>
                @if ($isImage)
                    <img src="{{ $chat->file_path }}" class="mt-2" alt="Attachment">
                @else
                    <a href="{{ $chat->file_path }}" target="_blank" class="file-link mt-2">
                        <i class="bi bi-file-earmark-text"></i>
                        View File
                    </a>
                @endif
            </div>
        @endif

        <div class="timestamp-row">
            <span>{{ $time }}</span>
            @if($isUser)
                @if($seen)
                    <span class="status-tick">
                        <span class="tick tick-blue">&#10003;</span>
                        <span class="tick tick-blue">&#10003;</span>
                        <span class="tick-label">Read</span>
                    </span>
                @else
                    <span class="status-tick">
                        <span class="tick tick-grey">&#10003;</span>
                        <span class="tick tick-grey">&#10003;</span>
                        <span class="tick-label" style="background:#f1f2f6;color:#adb5bd;">Delivered</span>
                    </span>
                @endif
            @endif
        </div>
    </div>
</div>
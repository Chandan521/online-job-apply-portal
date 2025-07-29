@php
    $sender = $chat->sender ?? null;
    $isRecruiter = $sender && isset($sender->role) ? $sender->role === 'recruiter' : $chat->sender_id === auth()->id();
    $isImage = $chat->file_path && preg_match('/\.(jpeg|jpg|png|gif|bmp|svg|webp)$/i', $chat->file_path);
    $bubbleBg = $isRecruiter ? 'bg-gradient-primary' : 'bg-gradient-light';
    $alignClass = $isRecruiter ? 'justify-content-end' : 'justify-content-start';
    $name = $sender->name ?? 'You';
    $seen = isset($chat->is_read) && $chat->is_read;
@endphp

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 60%, #00c6ff 100%);
        color: #fff;
    }
    .bg-gradient-light {
        background: #f8f9fa;
        color: #343a40;
    }
    .chat-bubble {
        border-radius: 1.1rem 1.1rem 0.3rem 1.1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 0.75rem 1rem;
        max-width: 75%;
        word-break: break-word;
        position: relative;
        margin-bottom: 0.2rem;
        transition: background 0.24s, box-shadow 0.24s;
    }
    .chat-bubble.recruiter {
        border-bottom-right-radius: 0.3rem;
        border-bottom-left-radius: 1.1rem;
        text-align: right;
    }
    .chat-bubble .sender-name {
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
        opacity: 0.9;
    }
    .chat-bubble .timestamp-row {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
        margin-top: 0.32rem;
        font-size: 0.75rem;
        opacity: 0.7;
        width: 100%;
    }
    .chat-bubble img {
        max-width: 180px;
        border-radius: 0.4rem;
        margin-top: 0.5rem;
        box-shadow: 0 1px 6px rgba(0,0,0,0.07);
    }
    .chat-bubble .file-link {
        margin-top: 0.5rem;
        display: inline-flex;
        align-items: center;
        background: rgba(0,123,255,0.07);
        padding: 0.3rem 0.8rem;
        border-radius: 0.4rem;
        color: #0056b3;
        font-size: 0.92rem;
        text-decoration: none;
        transition: background 0.18s;
    }
    .chat-bubble .file-link:hover {
        background: rgba(0,123,255,0.18);
        color: #003768;
        text-decoration: underline;
    }
    .chat-bubble .file-link i {
        margin-right: 0.5rem;
        font-size: 1.3em;
    }
    .status-tick {
        display: inline-flex;
        align-items: center;
        margin-left: 8px;
        font-size: 1em;
        vertical-align: middle;
        line-height: 1;
        font-weight: bold;
    }
    .status-tick .tick {
        font-size: 1.08em;
        margin-left: 1.5px;
        margin-right: 1.5px;
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
        margin-left: 4px;
        font-size: 0.74em;
        font-weight: 600;
        letter-spacing: 0.01em;
        padding: 2px 8px 2px 8px;
        border-radius: 10px;
        background: #fafafa;
        color: #38bdf8;
        display: inline-block;
        opacity: 0.94;
        border: 1px solid #bae6fd;
    }
    .chat-bubble.recruiter .tick-label {
        background: #38bdf8;
        color: #fff;
        border: 1px solid #0ea5e9;
        opacity: 1;
    }
</style>

<div class="d-flex {{ $alignClass }}">
    <div class="chat-bubble {{ $isRecruiter ? 'recruiter bg-gradient-primary' : 'bg-gradient-light' }}">
        <span class="sender-name">{{ $isRecruiter ? 'You' : $name }}</span>
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
            <span>{{ \Carbon\Carbon::parse($chat->created_at)->format('d M Y h:i A') }}</span>
            @if($isRecruiter)
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
                        <span class="tick-label" style="background:#f1f2f6;color:#adb5bd;border:1px solid #e2e8f0;">Delivered</span>
                    </span>
                @endif
            @endif
        </div>
    </div>
</div>
@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Reschedule Interview</h3>
    <form method="POST" action="{{ route('interviews.reschedule.submit', $interview->id) }}">
        @csrf

        <div class="mb-3">
            <label>Date & Time</label>
            <input type="datetime-local" name="interview_datetime" class="form-control" value="{{ $interview->interview_datetime->format('Y-m-d\TH:i') }}" required>
        </div>

        <div class="mb-3">
            <label>Mode</label>
            <select name="mode" class="form-select">
                <option value="online" {{ $interview->mode == 'online' ? 'selected' : '' }}>Online</option>
                <option value="in-person" {{ $interview->mode == 'in-person' ? 'selected' : '' }}>In-person</option>
                <option value="phone" {{ $interview->mode == 'phone' ? 'selected' : '' }}>Phone</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Location / Link</label>
            <input type="text" name="location" class="form-control" value="{{ $interview->location }}">
        </div>

        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control">{{ $interview->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Interview</button>
    </form>
</div>
@endsection

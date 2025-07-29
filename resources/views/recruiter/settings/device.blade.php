<div class="row g-4">
    @foreach ($devices as $device)
        <div class="col-md-6">
            <div class="card shadow-sm border-0 {{ $device->is_current_device ? 'bg-light' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h5 class="card-title mb-1">
                                <i class="fas {{ get_device_icon($device->os ?? '') }} text-primary me-2"></i>
                                {{ $device->device_type ?? ($device->os . ' Device') }}
                            </h5>
                            <small class="text-muted">
                                {{ $device->browser ?? 'Browser' }} on {{ $device->os ?? 'Unknown OS' }}
                            </small>
                        </div>
                        <div>
                            @if (!empty($device->is_current_device))
                                <span class="badge bg-success">This Device</span>
                            @else
                                <form action="{{ route('recruiter.device.logout', $device->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        Sign Out
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <p class="mb-1"><strong>Date Logged In:</strong>
                        {{ \Carbon\Carbon::parse($device->login_at ?? now())->format('F d, Y') }}
                    </p>
                    <p class="mb-1"><strong>IP Address:</strong> {{ $device->ip_address ?? 'Unknown' }}</p>
                    <p class="mb-0"><strong>Location:</strong> {{ $device->location ?? 'Unknown' }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

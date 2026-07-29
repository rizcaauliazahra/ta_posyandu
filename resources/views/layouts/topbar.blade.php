        <nav class="topbar px-4 py-3 d-flex justify-content-between align-items-center position-relative">
            <div>
                <small class="text-muted">{{ now()->translatedFormat('d F Y') }}</small>
            </div>
            <div class="position-absolute top-50 start-50 translate-middle fw-semibold fs-5">
                {{ $title ?? 'Dashboard' }}
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge" style="background-color: #ef4444; padding: 8px 12px; border-radius: 8px; font-weight: 500;">{{ auth()->user()->role->label }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" style="border-radius: 8px;"><i class="bi bi-box-arrow-right"></i></button>
                </form>
            </div>
        </nav>

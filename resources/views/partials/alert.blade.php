@if (session('success') || session('error') || session('warning') || session('info'))
    @php
        $type = 'info';
        $message = '';
        if (session('success')) { $type = 'success'; $message = session('success'); }
        elseif (session('error')) { $type = 'error'; $message = session('error'); }
        elseif (session('warning')) { $type = 'warning'; $message = session('warning'); }
        elseif (session('info')) { $type = 'info'; $message = session('info'); }

        $iconMap = [
            'success' => 'fa-circle-check',
            'error' => 'fa-triangle-exclamation',
            'warning' => 'fa-circle-exclamation',
            'info' => 'fa-circle-info'
        ];
        $icon = $iconMap[$type] ?? 'fa-circle-info';
    @endphp
<div class="toast-container">
    <div class="toast toast-{{ $type }}" role="alert">
        <div class="toast-icon">
            <i class="fa-solid {{ $icon }}"></i>
        </div>
        <div class="toast-content">
            {{ $message }}
        </div>
        <div class="toast-close">
            <i class="fa-solid fa-xmark"></i>
        </div>
    </div>
</div>
@endif

@once
<link rel="stylesheet" href="{{ asset('css/sidokspi-feedback.css') }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
<script src="{{ asset('js/sidokspi-feedback.js') }}" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const success = {{ Illuminate\Support\Js::from(session('success')) }};
        const error = {{ Illuminate\Support\Js::from(session('error')) }};
        const errors = {{ Illuminate\Support\Js::from($errors->all()) }};
        if (error || errors.length) window.SIDFeedback.show('error', error || errors.join('\n'));
        else if (success) window.SIDFeedback.show('success', success);
    });
</script>
@endonce

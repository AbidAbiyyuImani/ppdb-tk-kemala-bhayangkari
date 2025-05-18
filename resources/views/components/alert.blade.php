@props([
    'types' => ['success', 'error'],
    'redirect' => null
])

@foreach ($types as $type)
    @if (session($type))
        <div id="flash-message-{{ $type }}" class="fixed top-18 sm:top-4 right-2 sm:right-4 left-auto z-50 px-4 py-2 rounded-sm shadow-sm transition-opacity duration-500 alert-{{ $type }} ml-2 sm:ml-0 mb-2" >
            <div class="flex items-center space-x-2">
                <i class="fas {{ $type === 'success' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                <span class="text-sm">{{ session($type) }}</span>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const flash = document.getElementById('flash-message-{{ $type }}');
                if (flash) {
                    setTimeout(() => {
                        flash.classList.add('opacity-0');
                        setTimeout(() => {
                            flash.remove();
                            @if (!empty($redirect))
                                window.location.href = "{{ $redirect }}";
                            @endif
                        }, 500);
                    }, 3000);
                }
            });
        </script>
    @endif
@endforeach
@if (session('success'))
<div class="rounded-md bg-green-50 p-4 mb-6 alert-dismissible">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-check-circle text-green-400"></i>
        </div>
        <div class="ml-3 flex-grow">
            <p class="text-sm font-medium text-green-800">
                {{ session('success') }}
            </p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button type="button" class="close-alert inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 focus:ring-offset-green-50">
                    <span class="sr-only">Dismiss</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@if (session('warning'))
<div class="rounded-md bg-yellow-50 p-4 mb-6 alert-dismissible">
    <div class="flex">
        <div class="ml-3 flex-grow">
            <p class="text-sm font-medium text-yellow-800">
                {{ session('warning') }}
            </p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button type="button" class="close-alert inline-flex rounded-md p-1.5 text-yellow-500 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-yellow-600 focus:ring-offset-2 focus:ring-offset-yellow-50">
                    <span class="sr-only">Dismiss</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@if (session('error'))
<div class="rounded-md bg-red-50 p-4 mb-6 alert-dismissible">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-circle text-red-400"></i>
        </div>
        <div class="ml-3 flex-grow">
            <p class="text-sm font-medium text-red-800">
                {{ session('error') }}
            </p>
        </div>
        <div class="ml-auto pl-3">
            <div class="-mx-1.5 -my-1.5">
                <button type="button" class="close-alert inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50">
                    <span class="sr-only">Dismiss</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Script untuk menutup alert
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        
        alerts.forEach(alert => {
            const closeButton = alert.querySelector('.close-alert');
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 300);
                });
            }
            
            // Auto-hide alert after 5 seconds
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        });
    });
</script>
@endpush

<style>
    .alert-dismissible {
        transition: opacity 0.3s ease-in-out;
    }
</style>



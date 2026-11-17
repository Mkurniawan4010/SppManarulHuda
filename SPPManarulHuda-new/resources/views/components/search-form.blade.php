@props(['route', 'placeholder' => 'Cari...'])

<div class="mb-6">
    <form action="{{ route($route) }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="w-full sm:w-96">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
            <div class="relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       name="search" 
                       id="search" 
                       class="block w-full pl-10 pr-12 py-2 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm"
                       placeholder="{{ $placeholder }}"
                       value="{{ request('search') }}">
                @if(request('search'))
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <a href="{{ route($route) }}" class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        <div class="flex gap-2">
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-search mr-2"></i>
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route($route) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-undo mr-2"></i>
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>
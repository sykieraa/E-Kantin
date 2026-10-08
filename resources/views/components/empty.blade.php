<div class="card px-6 py-12 text-center">
    <p class="text-base font-semibold text-gray-800">{{ $title }}</p>
    <p class="mx-auto mt-1 max-w-sm text-sm text-gray-500">{{ $message }}</p>
    @isset($action)
        <a href="{{ $action }}" class="btn-primary mt-5">{{ $actionLabel }}</a>
    @endisset
</div>

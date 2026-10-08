@if (!empty($image))
    <img src="{{ asset('storage/'.$image) }}" alt="{{ $name }}" class="h-full w-full object-cover">
@else
    <div class="grid h-full w-full place-items-center bg-brand-50 text-lg font-bold text-brand-300">
        {{ Str::upper(Str::substr($name, 0, 2)) }}
    </div>
@endif

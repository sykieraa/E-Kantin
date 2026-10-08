@php
    $labels = \App\Models\Order::statusOptions();
    $next = $order->allowedNextStatuses();
@endphp

@if (count($next) > 0)
    <div class="mt-3 flex flex-wrap gap-2 border-t border-gray-100 pt-3">
        @foreach ($next as $status)
            <form method="POST" action="{{ route('seller.orders.status', $order) }}"
                  @if ($status === 'cancelled') data-confirm="Batalkan pesanan ini? Stok akan dikembalikan." @endif>
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $status }}">
                <button type="submit" class="{{ $status === 'cancelled' ? 'btn-danger' : 'btn-primary' }} px-3 py-2 text-xs">
                    {{ $status === 'cancelled' ? 'Batalkan' : 'Tandai '.Str::lower($labels[$status]) }}
                </button>
            </form>
        @endforeach
    </div>
@endif

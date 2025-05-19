@foreach ($availableCottages as $cottage)
    @include('components.cottage-card', [
        'cottage' => $cottage,
        'bookingDate' => $bookingDate->format('Y-m-d'),
    ])
@endforeach

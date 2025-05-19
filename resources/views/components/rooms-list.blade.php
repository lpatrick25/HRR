@foreach ($availableRooms as $room)
    @include('components.room-card', [
        'room' => $room,
        'reservedDates' => $reservedDates,
        'checkIn' => $checkInDate->format('Y-m-d'),
        'checkOut' => $checkOutDate->format('Y-m-d'),
    ])
@endforeach

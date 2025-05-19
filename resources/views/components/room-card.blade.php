<div class="col-lg-12 mb-5">
    <div class="card shadow-sm p-4">
        <div class="row">
            <!-- Image Gallery Section -->
            <div class="col-lg-5">
                <div class="position-relative mb-3">
                    <img src="{{ asset($room->picture) }}" class="img-fluid rounded main-room-image" alt="Room Image"
                        style="cursor: pointer;" data-toggle="modal" data-target="#imageModal"
                        onclick="showImageModal('{{ asset($room->picture) }}', '{{ $room->room_name }}', '₱{{ number_format($room->room_rate, 2) }}', '{{ $room->room_capacity }} persons')">
                </div>
                <div class="row g-2" id="roomPicturesHtml">
                    <div class="d-flex flex-wrap justify-content-center">
                        @php $totalImages = count($room->pictures) + 1; @endphp
                        @foreach (array_merge([['picture' => $room->picture]], $room->pictures->toArray()) as $index => $picture)
                            <div
                                class="
                                @if ($totalImages == 1) col-12
                                @elseif ($totalImages == 2) col-6
                                @elseif ($totalImages == 3) col-4
                                @else col-3 @endif
                                mb-2 d-flex justify-content-center">
                                <img src="{{ asset($picture['picture']) }}" class="img-fluid rounded room-thumbnail"
                                    style="cursor: pointer; width: 100%; max-width: {{ $totalImages == 1 ? '250px' : '150px' }}; height: 100px; object-fit: cover;"
                                    onclick="swapMainImage('{{ asset($picture['picture']) }}', '{{ $room->room_name }}', '₱{{ number_format($room->room_rate, 2) }}', '{{ $room->room_capacity }}', event)">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Room Details Section -->
            <div class="col-lg-7">
                <div class="resort__details__text">
                    <div class="resort__details__title mb-3">
                        <h3 class="fw-bold">{{ $room->room_name }}</h3>
                        <p class="text-muted">{{ $room->hotelType->type_description }}</p>
                    </div>

                    <div class="resort__details__widget mb-3">
                        <ul class="list-unstyled">
                            <li><strong>Type:</strong> {{ $room->hotelType->type_name }}</li>
                            <li><strong>Rate:</strong> ₱{{ number_format($room->room_rate, 2) }}</li>
                            <li><strong>Capacity:</strong> {{ $room->room_capacity }} persons</li>
                        </ul>
                    </div>

                    <div class="section-title mb-3">
                        <h5 class="fw-semibold">Amenities</h5>
                    </div>
                    <ul class="list-unstyled d-flex flex-wrap gap-2">
                        @foreach ($room->amenities as $amenity)
                            <li class="badge bg-light text-dark border">{{ $amenity->amenity }}</li>
                        @endforeach
                    </ul>

                    <hr>

                    <!-- Booking & Availability Buttons -->
                    <div class="row">
                        <div class="col-md-6 d-grid mb-2">
                            <button type="button" class="btn btn-primary"
                                onclick="view_room('{{ encrypt($room->id) }}')">
                                <i class="fa fa-plus me-2"></i> Book Now
                            </button>
                        </div>
                        <div class="col-md-6 d-grid mb-2">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="viewReservations('{{ encrypt($room->id) }}', '{{ $room->room_name }}', '{{ $room->hotelType->type_name }}')">
                                <i class="fa fa-calendar me-2"></i> View Availability
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 3 Modal for Enlarged Image with Room Info + Book Button -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content position-relative">
                <div class="modal-body text-center p-0">
                    <!-- Room Info Box (Upper Left Corner) -->
                    <div class="room-info-box"
                        style="position: absolute; top: 10px; left: 10px; background-color: rgba(0, 0, 0, 0.7); color: #fff; padding: 10px; border-radius: 5px;">
                        <h5 id="modalRoomName" class="m-0 text-left" style="color: #fff"></h5>
                        <p class="m-0 text-left" id="modalRoomRate" style="color: #fff"></p>
                        <p class="m-0 text-left" id="modalRoomCapacity" style="color: #fff"></p>
                    </div>

                    <!-- Book Now Button (Upper Right Corner) -->
                    <button id="modalBookButton" class="btn btn-primary"
                        style="position: absolute; top: 10px; right: 10px;">
                        <i class="fa fa-plus"></i> Book Now
                    </button>

                    <!-- Enlarged Image -->
                    <img id="modalImage" class="img-responsive" src="" alt="Enlarged Room Image"
                        style="width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript for Image Swapping & Modal Info -->
    <script>
        function swapMainImage(imageSrc, roomName, roomRate, roomCapacity, event) {
            // Find the closest parent card
            var cardElement = event.target.closest('.col-lg-5');

            if (cardElement) {
                var mainImage = cardElement.querySelector('.main-room-image');

                if (mainImage) {
                    mainImage.src = imageSrc;
                    mainImage.setAttribute('onclick',
                        `showImageModal('${imageSrc}', '${roomName}', '${roomRate}', '${roomCapacity}')`);
                }
            }
        }

        function showImageModal(imageSrc, roomName, roomRate, roomCapacity) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('modalRoomName').innerText = roomName;
            document.getElementById('modalRoomRate').innerText = 'Rate: ' + roomRate;
            document.getElementById('modalRoomCapacity').innerText = 'Capacity: ' + roomCapacity;

            $('#imageModal').modal('show');
        }
    </script>
</div>

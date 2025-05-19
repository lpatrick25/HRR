<div class="thumbnail-container"
    style="
        max-height: 300px; /* Adjust height as needed */
        overflow-y: auto;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    ">
    @forelse ($pictures as $picture)
        <div class="thumbnail"
            style="
                position: relative;
                width: 150px; /* Adjust thumbnail size as needed */
                height: 150px;
                background-color: #f9f9f9;
                border-radius: 6px;
                overflow: hidden;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            ">
            <img src="{{ asset($picture->picture) }}" alt="Cottage Picture"
                style="
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                ">

            <!-- Delete Button (Upper Right Corner) -->
            <button type="button" class="btn btn-danger btn-xs"
                style="
                    position: absolute;
                    top: 5px;
                    right: 5px;
                    border-radius: 50%;
                    width: 25px;
                    height: 25px;
                    line-height: 20px;
                    font-size: 14px;
                    padding: 0;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
                "
                onclick="trash_picture('{{ $picture->id }}')">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    @empty
        <div class="text-center" style="width: 100%;">
            <img class="profile-user-post-img" src="{{ asset('img/profile/1.jpg') }}" alt=""
                style="width: 100%; height: 250px;">
            <p class="text-muted">No pictures available</p>
        </div>
    @endforelse
</div>

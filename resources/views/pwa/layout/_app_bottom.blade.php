<div class="appBottomMenu">
    <a href="{{ route('pwa.get.home') }}" class="item active">
        <div class="col">
            <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="home outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="{{ route('pwa.bookingList') }}" class="item">
        <div class="col">
            <ion-icon name="document-text-outline" role="img" class="md hydrated"
                aria-label="document text outline"></ion-icon>
            <strong>Bookings</strong>
        </div>
    </a> 
    <a href="{{ route('pwa.get.search') }}" class="item">
        <div class="action-button large blk">
            <!-- <ion-icon class="blk" name="share-social-outline"></ion-icon>       -->
            <img class="text-center pt-04" src="{{ url('pwa') }}/assets/img/share.png" alt=""
                width="40">
        </div>
        <strong class="sear">Search</strong>
    </a>
    <a href="#" class="item">
        <div class="col">
            <ion-icon name="mail-outline"></ion-icon>
            <strong>Inbox</strong>
        </div>
    </a>
    <a href="{{ route('pwa.get.profile') }}" class="item">
        <div class="col">
            <ion-icon name="person-circle-outline" role="img" class="md hydrated"
                aria-label="person circle outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
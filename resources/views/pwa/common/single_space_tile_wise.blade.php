<li class="space-item-wrapper splide__slide splide__slide--clone" aria-hidden="true" tabindex="-1" style="margin-right: 16px; width: 156px;">
    <span class="icon mt-10">
        <ion-icon class="icon mr-10 add-to-fav  @if ($row->isFavourite()) 'active' @endif  " name="heart"
            space_id={{ $row->id }}>
            <div class='red-bg'></div>
        </ion-icon>
    </span>
    <a href="{{ url('m/space-details') . '/' . $row->id }}">
        <div class="blog-card">
            <!-- <div class="img-wrapper"> -->
            <a href="{{ url('m/space-details') . '/' . $row->id }}">
                <div class="space-img-elem" style="background-image: url('{{ get_file_url($row->image_id , 'full')}}')"></div>
            </a>
            <!-- </div> -->
            @include('pwa.common.rating', ['type' => 'tiles', 'rating' => $row->review_score])
            <!-- <div class="mt-10">
      <h4 class="inline rating">8.8</h4>
      <div class="stars lh-16 m-10 lh-16 inline"> <span class="star on lh-16"></span>
          <span class="star on"></span>
          <span class="star on"></span>
          <span class="star on"></span>
          <span class="star"></span>
        </div>
      </div> -->
            <div class="text">
                <a href="{{ url('m/space-details') . '/' . $row->id }}">
                    <h3 class="title">{{ $row->title }}</h3>
                </a>
                <div class="listing-info">
                    <div class="left-data address">
                        <?php
                        $distance = $row->addressWithDistance(false);
                        ?>
                        <a href="{{ $distance['link'] }}" class="inline">
                           <ion-icon name="location-outline"></ion-icon>
                       </a>
                        <a href="{{ $distance['link'] }}" target="_blank">
                            <p class="inline f-11 vl-m light">{!! $distance['title'] !!}
                            </p>
                        </a>
                    </div>
                    <div class="right inline fl-r  col-4 px-0">
                        <div class="price inline fl-r fw-b  f-20">
                            ${{ $row->price }}</div><br>
                        <p class=" fl-r fw-b f-8 mb-0 lh-10 p-h">PER HOUR</p>
                    </div>
                </div>
            </div>
        </div>
    </a>
</li>

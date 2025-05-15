@foreach($booking_list as $row)
   <a href="#" class="item booking-item row">
      <div class="detail col-9 ">
         <img src="{{ get_file_url($row->objectDetails->image_id , 'full')}}" alt="img" class="image-block imaged w48">

         <div class="row">
            <strong class="text-bold f-12">{{$row->objectDetails->title ?? ''}}</strong>
            <div class="col-6">
               <div class=" pr-0">

                <?php 
                    $mapLink = 'https://www.google.com/maps/place/' . trim(urlencode($row->objectDetails->address));
                ?>
                <!-- <a href="{{$mapLink}}" target="_blank"> -->
                  <ion-icon name="map-outline" class="m-b-5"></ion-icon>
                  <p class="inline f-r lh-16 font-weight-normal">{{$row->objectDetails->address ?? ''}}</p>
                <!-- </a> -->
               </div>
            </div>
            <!-- <div class="col-6">
               <div class="stars lh-16 m-10 lh-16"> <span class="star on lh-16"></span>
                  <span class="star on"></span>
                  <span class="star on"></span>
                  <span class="star on"></span>
                  <span class="star"></span>
               </div>
               <p class="pl-05 f-r lh-16 text-primary">4 Reviews</p>
            </div> -->
         </div>
      </div>
      <div class="right col-3 pr-0">
         <p class="price f-16">${{$row->total ?? ''}}</p>
         <!-- <p class="f-r lh-16 font-weight-normal">July 21, 2021</p> -->
         <p class="f-r lh-16 font-weight-normal">{{$row->start_date}}</p>

         <p class="f-r lh-16 font-weight-normal">Booking #{{$row->id}}</p>
      </div>
   </a> 
@endforeach
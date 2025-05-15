<?php
$publicView = $publicView ?? true;
?>
<div class="item booking-item space-item-wrapper row">
   <div class="detail col-9 ">
      <a href="{{url('m/booking-details').'/'.$row->booking_id}}"  >
         <div class="space-img-elem" style="background-image: url('{{ get_file_url($row->objectDetails->image_id , 'full')}}')"></div>
      </a>
      <div class="row">
         <a href="{{url('m/booking-details').'/'.$row->booking_id}}"  >
            <strong class="text-bold f-12">{{$row->objectDetails->title ?? ''}}</strong>
         </a>
         <div class="col-6">
            <div class=" pr-0"> 

             <?php   
                 $distance = $row->objectDetails->addressWithDistance();
             ?>
             <a href={{ $distance['link'] }} target="_blank" > 
               <ion-icon name="map-outline" class="m-b-5"></ion-icon>
               <p class="inline f-r lh-16 font-weight-normal">{!!$distance['address']!!}</p>
             </a>
            </div>
         </div>


         @include('pwa.common.rating' , ["type" => "listing" , "rating" => $row->objectDetails->review_score])
         
         <!-- <div class="col-6">
          <div class="stars lh-16 m-10 lh-16"> <span class="star on lh-16"></span>
               <span class="star on"></span>
               <span class="star on"></span>
               <span class="star on"></span>
               <span class="star"></span>
            </div>
            <p class="pl-05 f-r lh-16 text-primary">4 Reviews</p> 

         </div>-->
      </div>
   </div>
   <div class="right col-3 pr-0">
      <p class="price f-16">${{$row->total ?? ''}}</p>
      <!-- <p class="f-r lh-16 font-weight-normal">July 21, 2021</p> -->
      <p class="f-r lh-16 font-weight-normal">{{\App\Helpers\CodeHelper::printDate($row->start_date)}}</p>

      <p class="f-r lh-16 font-weight-normal">Booking #{{$row->id}}</p>
   </div>
</div> 
<!-- <hr class="solid"> -->
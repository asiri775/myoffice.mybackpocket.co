<div class="item row space-item-wrapper">
   <div class="detail col-9 ">
      <a href="{{url('m/space-details/').'/'.$row->id}}">
         <div class="space-img-elem" style="background-image: url('{{ get_file_url($row->image_id , 'full')}}')"></div>
      </a>
      <div class="row">
         <a href="{{url('m/space-details/').'/'.$row->id}}">
            <strong class="text-bold">{{$row->title}}</strong>
         </a>
         <div class="col-12">
            <div class=" pr-0">
               <?php
                  $distance = $row->addressWithDistance(true, true);
                  ?>                     
               <a href={{ $distance['link'] }} target="_blank" > 
                  <i class="fas fa-map-marked"></i>
                  <p class="inline">{!!$distance['title']!!}</p>
               </a>
            </div>
             
         </div>
        
         @include('pwa.common.rating' , ["type" => "listing" , "rating" => $row->review_score, 'topRated' => $row->isTopRated()])
      </div>
   </div>
   <div class="right col-3 pr-0">
      <?php 
         if($row->hourly && $row->hourly > 0){
             $price = $row->hourly;
             $type = 'PER HOUR';
         }elseif($row->daily && $row->daily > 0){
             $price = $row->daily;
             $type = 'Daily';
         }
         elseif($row->weekly && $row->weekly > 0){
             $price = $row->weekly;
             $type = 'Weekly';
         }
         elseif($row->monthly && $row->monthly > 0){
             $price = $row->monthly;
             $type = 'Montly';
         }else{
             $price = $row->price;
             $type = 'PER HOUR';
         }
         ?>
      <div class="price text-center f-30">${{$price}}</div>
      <p class="text-center f-r lh-16">{{$type}}</p>
   </div>
</div>
<hr class="solid">
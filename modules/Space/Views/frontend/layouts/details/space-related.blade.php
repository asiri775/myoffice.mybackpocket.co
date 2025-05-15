@if(count($space_related) > 0)
    <div class="bravo-list-space">
        <div class="list-item">
            <div class="row">
                @foreach($space_related as $k=>$item)
                    <div class="col-lg-4 col-md-6">
                        <div class="item-loop ">
                            @if($item->is_featured)
                                <div class="featured">
                                    Featured
                                </div>
                            @endif
                            <div class="thumb-image ">
                                <a href="{{$item->getDetailUrl($include_param ?? true)}}">
                                    @if($item->image_url)
                                        @if(!empty($disable_lazyload))
                                            <img src="{{$item->image_url}}"
                                                 class="img-responsive lazy loaded" alt="">
                                        @else
                                            {!! get_image_tag($item->image_id,'medium',['class'=>'img-responsive','alt'=>$item->title]) !!}
                                        @endif
                                    @endif
                                </a>
                                <div class="price-wrapper">
                                    <div class="price">
                                        @php
                                        $priceDetails= $item->getDefaultPrice($item->id);  
                                       @endphp
                                        <span class="onsale">{{ ($priceDetails['discountPrice']!='0.00')?$priceDetails['discountPrice'] :'' }}</span>
                                        <span class="text-price">{{ $priceDetails['price'] }}
                                    </div>
                                </div>
                                <div class="service-wishlist {{$item->isWishList()}}" data-id="{{ $item->id }}"
                                     data-type="{{ $item->type }}">
                                    <i class="fa fa-heart"></i>
                                </div>
                            </div>
                            <div class="item-title">
                                <a href="{{$item->getDetailUrl($include_param ?? true)}}">
                                    {{ $item->title }}
                                </a>
                                @if($item->display_sale_price)
                                    <div style="background-color: #ffc107; z-index: 10"
                                         class="sale_info">{{ round(($item->price-$item->sale_price)/$item->price*100) }}%
                                    </div>
                                @endif
                            </div>
                            <div class="location">
                                @if(!empty($row->address))
                                @php 
                                $location = explode(",", $row->address);
                                @endphp
                                {{(count($location)>1)?$location[1]:$location[0]}}
                            @endif
                            </div>
                            @php
                                $reviewData = $item->getScoreReview();
                                $score_total = $reviewData['score_total'];
                            @endphp
                            <div class="service-review">
                                <div class="star-rate" style="height:20px;">
                                    @for($number = 1; $number <= $reviewData['score_total'];  $number++)
                                        <i class="fa fa-star yellowtext"></i>
                                    @endfor
                                </div>
                                <span class="rate">
															<span class="star-div">{{ $score_total }}</span><span
                                        class="rate-text">Excellent</span>
														</span>
                                <span class="review">
															@if($reviewData['total_review'] > 1)
                                        {{ __(":number Reviews",["number"=>$reviewData['total_review'] ]) }}
                                    @else
                                        {{ __(":number Review",["number"=>$reviewData['total_review'] ]) }}
                                    @endif
														</span>
                            </div>
                            <div class="amenities">
                                <span class="amenity total" data-toggle="tooltip" title=""
                                      data-original-title="Desks">
                                    <img src="{{ asset('images/desk-chair.png') }}"> {{ $item->desk }}
                                </span>
                                <span class="amenity bed" data-toggle="tooltip" title=""
                                      data-original-title="Seats">
                                    <img src="{{ asset('images/armchair.png') }}"> {{ $item->seat }}
                                </span>
                                <span class="amenity size" data-toggle="tooltip" title=""
                                      data-original-title="Parking">
															<img src="{{ asset('images/parking-area.png') }}">
															Yes
														</span>
                                <span class="amenity size" data-toggle="tooltip" title=""
                                      data-original-title="RapidBook">
															<img src="{{ asset('images/open-book.png')  }}">
															{{ $item->rapidbook == 1 ? 'Yes' : 'No' }}
														</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif


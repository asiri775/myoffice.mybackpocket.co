<div class="space-div">
    <div class="container sharespacething">
        <h2>Share Your Space, It's a Good Thing.</h2>
        <div class="semidescription">
            Maximize underutilized desks, offices and meeting rooms. Increase your bottom line easily and hassle free.
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="inner">
                    <img src="images/target.png" class="image">
                    <div class="space-content">
                        <h4>Maximize your space</h4>
                        <div class="description">
                            Utilize every single office and work area to maximize cost efficiency.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="inner">
                    <img src="images/revenue.png" class="image">
                    <div class="space-content">
                        <h4>Added Revenue</h4>
                        <div class="description">
                            Create a new revenue stream from extra workspaces and facilities.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="inner">
                    <img src="images/internet.png" class="image">
                    <div class="space-content">
                        <h4>Networking Opportunity</h4>
                        <div class="description">
                            Increase foot traffic, and network with entrepreneurs from similar industries and grow your
                            contacts.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="inner">
                    <img src="images/money-bag.png" class="image">
                    <div class="space-content">
                        <h4>Reduce Costs</h4>
                        <div class="description">
                            Share not only your workspace but services, to further reduce your operational costs.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tcenter">
            <a class="btn yellowbtn" href="">Sign Up as a host</a>
        </div>
    </div>
</div>
@if ($list_item)
    <div class="bravo-testimonial">
        <div class="container">
            {{-- <div class="row">
                @foreach ($list_item as $item)
                    <?php $avatar_url = get_file_url($item['avatar'], 'full'); ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="item has-matchHeight">
                            <div class="text-left"><i class="icofont-quote-left"></i></div>
                            <p>
                                {{$item['desc']}}
                            </p>
                            <div class="author">
                                <img src="{{$avatar_url}}" alt="{{$item['name']}}">
                                <div class="author-meta">
                                    <h4>{{$item['name']}}</h4>
                                    @if ($item['number_star'])
                                        <div class="star">
                                            @for ($i = 0; $i < $item['number_star']; $i++)
                                                <i class="fa fa-star"></i>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right"><i class="icofont-quote-right"></i></div>
                        </div>
                    </div>
                @endforeach
            </div> --}}
            <div class="row">
                <div class="col-md-6 col-lg-3">
                    <div class="item has-matchHeight">
                        <div class="text-left"><i class="icofont-quote-left"></i></div>
                        <p>
                            With MyOffice, we are able to maximize our space usage, be more cost-efficient
                        </p>
                        <div class="author">
                            <img src="{{asset('images/testi-1.png')}}"
                                alt="Eva Hicks">
                            <div class="author-meta">
                                <h4>Eva Hicks</h4>
                                <div class="star">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="text-right"><i class="icofont-quote-right"></i></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="item has-matchHeight">
                        <div class="text-left"><i class="icofont-quote-left"></i></div>
                        <p>
                            The platform is convenient, and we are able to manage our bookings with ease. I highly
                            recommend using MyOffice for your spare space!
                        </p>
                        <div class="author">
                            <img src="{{asset('images/testi-2.png')}}"
                                alt="Donald Wolf">
                            <div class="author-meta">
                                <h4>Donald Wolf</h4>
                                <div class="star">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="text-right"><i class="icofont-quote-right"></i></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="item has-matchHeight">
                        <div class="text-left"><i class="icofont-quote-left"></i></div>
                        <p>
                            For startups like us, every dollar counts. With MyOffice, we are able to forego the large
                            overhead and use our funds to get our business up and running
                        </p>
                        <div class="author">
                            <img src="{{asset('images/testi-3.png')}}"
                                alt="Charlie Harrington">
                            <div class="author-meta">
                                <h4>Charlie Harrington</h4>
                                <div class="star">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="text-right"><i class="icofont-quote-right"></i></div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="item has-matchHeight">
                        <div class="text-left"><i class="icofont-quote-left"></i></div>
                        <p>
                            As a retired accountant, I only need a space to work on a part time basis, for some clients.
                            With MyOffice, I can find a convenient location almost anywhere, to work by myself or with
                            my clients. I love it!
                        </p>
                        <div class="author">
                            <img src="{{asset('images/testi-4.png')}}"
                                alt="Jessica Albright">
                            <div class="author-meta">
                                <h4>Jessica Albright</h4>
                                <div class="star">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <div class="text-right"><i class="icofont-quote-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="btn btn-set shere-btn-1">
                        <a href="{{ url('page/share-your-space') }}">Share your Office</a>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="btn btn-set shere-btn-2">
                        <a href="{{ url('page/guests-how-it-works') }}">Be Our Guest</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<div class="brands">
    <img src="{{ asset('images/brands.png') }}" alt="brands" />
</div>

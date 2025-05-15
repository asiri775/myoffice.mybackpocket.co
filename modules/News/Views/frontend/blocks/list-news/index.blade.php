<div class="container howworks howworks-section">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            @if ($title)
                <h2>{{ $title }}</h2>
            @endif
            {{-- <div class="row">
                    @foreach ($rows as $row)
                            @include('News::frontend.blocks.list-news.loop')
                    @endforeach
                </div> --}}
            <div class="row">
                <div class="col-xs-12 col-sm-4 mb-5">
                    <div class="image" style="background-image:url('{{ asset('images/howitworks-1.jpg') }}">
                    </div>
                    <h3>Find the YOUR Space</h3>
                    <div class="description">
                        <p>Explore the marketplace to find a space that fits your needs. From urban lofts and studios to
                            modern meeting spaces, we've got you covered.</p>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4 mb-5">
                    <div class="image" style="background-image:url('{{ asset('images/howitworks-2.jpg') }}">
                    </div>
                    <h3>Connect With Hosts</h3>
                    <div class="description">
                        <p>Communicate directly with our Hosts, to ensure you find the right space that meets your
                            needs. With our easy to use website or our Mobile App, you can easily fine tune your search,
                            and book your space.</p>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4 mb-5">
                    <div class="image" style="background-image:url('{{ asset('images/howitworks-3.jpg') }}">
                    </div>
                    <h3>Book and Go!</h3>
                    <div class="description">
                        <p>With just a few clicks, you can conveniently book your space, and manage your account. Let’s
                            Get to Work!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

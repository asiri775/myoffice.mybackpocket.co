<style>
    #notFoundSearch {
        padding-bottom: 30px;
        text-align: center;
        display: block;
        width: 100%;
    }

    div#notFoundSearch .list-item {
        margin-top: -10px !important;
    }
</style>

<div class="bravo-list-item bravo-list-item-ajax @if (!$rows->count()) not-found @endif">

    <div class="topbar-search mt-5 mb-5">
        <span
            class="count-string">{{ __('Showing :from - :to of :total Spaces', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span>
        <div class="control">
            @include('Space::frontend.layouts.search.orderby')
        </div>
    </div>

    @if ($rows->count())
        <div class="list-item">
            <div class="row">
                @foreach ($rows as $row)
                    <div class="col-xl-4 col-lg-6 col-sm-6 col-md-6">
                        @include('Space::frontend.layouts.search.loop-gird')
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bravo-pagination" style="padding-bottom: 0">
            <div class="search-list">{{ $rows->total() }} Listings | Page {{ $rows->currentPage() }}
                of {{ $rows->lastPage() }}</div>
            {{ $rows->appends(array_merge(request()->query(), ['_ajax' => 1]))->links() }}
        </div>
    @else
        <div class="list-item">
            <div class="not-found-box">
                <h3 class="n-title">{{ __("We couldn't find any spaces.") }}</h3>
                <p class="p-desc">{{ __('Try changing your filter criteria') }}</p>
                {{-- <a href="#" onclick="return false;" click="" class="btn btn-danger">{{__("Clear Filters")}}</a> --}}
            </div>
        </div>
    @endif

    <div id="notFoundSearch">
        <div class="list-item">
            <div class="not-found-box">
                <h3 class="n-title">{{ __('Not found, what you are looking for?') }}</h3>
                <a href="javascript:;" data-toggle="modal" data-target="#contactSearchModal"
                    class="btn yellowbtn">{{ __('Contact Us') }}</a>
            </div>
        </div>

        <div class="modal modal-myoffice fade" tabindex="-1" role="dialog" id="contactSearchModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content ">

                    <form method="post" action="{{ route('contact.store') }}" class="bravo-contact-block-form">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h5 style="font-family:Montserrat;font-size:16pt;font-weight:900;"
                                class="modal-title text-center w-100">
                                CONTACT
                                <hr>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="contact-form contactPageData">
                                <div class="contact-form">
                                    <input type="hidden" name="title" value="Query related to Search">
                                    <div class="form-group">
                                        <input type="text" value="" placeholder=" {{ __('Name') }} "
                                            name="name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" value="" placeholder="{{ __('Email') }}"
                                            name="email" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <textarea name="message" cols="40" rows="10" class="form-control textarea" placeholder="{{ __('Message') }}"></textarea>
                                    </div>
                                    <div class="form-group">
                                        {{ recaptcha_field('contact') }}
                                    </div>
                                </div>
                            </div>
                            <div class="message_box"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"
                                data-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" style="margin-top:-1px;"
                                class="btn btn-primary btn-su">{{ __('Send Message') }}
                                <i class="fa icon-loading fa-spinner fa-spin fa-fw" style="display: none"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

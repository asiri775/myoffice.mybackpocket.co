@extends('layouts.common_home')
@section('head')
	<style type="text/css">
		.bravo-contact-block .section{
			padding: 10px 0 !important;
		}
	</style>
@endsection
@section('content')
<div class="layout1">
	<div class=" container container-fixed-lg">
	@include("Contact::frontend.blocks.contact.index")
</div>
</div>
<div class="brands">
	<img src="{{asset("images/brands.png")}}" alt="brands" />
</div>
@endsection

@section('footer')

@endsection


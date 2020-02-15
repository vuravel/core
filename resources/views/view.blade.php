@extends($extends)

<?php 

	$object = $object();

?>

@if($object->hasMetaTags())
	
	@section('metaTags')

		@if($object->metaTags('title'))
			<title>{{ $object->metaTags('title') }}</title>
		@endif

		@if($object->metaTags('description'))
			<meta name="description" content="{{ $object->metaTags('description') }}">
		@endif

		@if($object->metaTags('keywords'))
			<meta name="keywords" content="{{ $object->metaTags('keywords') }}">
		@endif

	@endsection

@endif

@section('content')
	<div class="container">
		<vl-{{$partial}} :vcomponent="{{ $object }}"></vl-{{$partial}}>
	</div>
@endsection

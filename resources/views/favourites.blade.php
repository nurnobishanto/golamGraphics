@if($allsettings->maintenance_mode == 0)
<!DOCTYPE HTML>
<html lang="en">
<head>
<title>{{ $allsettings->site_title }} - {{ __('Favourites') }}</title>
@include('meta')
@include('style')
</head>
<body>
@include('header')
@if($addition_settings->subscription_mode == 0)
	@include('my-favourite')
@else
	@if(Auth::user()->user_type == 'vendor')
        @include('my-favourite')
   @elseif(Auth::user()->user_type == 'customer')
        @include('my-favourite')
   @else
        @include('not-found')
   @endif
@endif
@include('footer')
@include('script')
</body>
</html>
@else
@include('503')
@endif
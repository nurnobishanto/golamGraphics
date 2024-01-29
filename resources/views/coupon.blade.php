@if($allsettings->maintenance_mode == 0)
<!DOCTYPE HTML>
<html lang="en">
<head>
<title>{{ $allsettings->site_title }} - @if(Auth::user()->user_type == 'vendor') {{ __('Coupon') }} @else {{ __('404 Not Found') }} @endif</title>
@include('meta')
@include('style')
</head>
<body>
@include('header')
@if($addition_settings->subscription_mode == 0)
	@include('my-coupon')
@else
	@if(Auth::user()->user_type == 'vendor')
        @if(Auth::user()->user_subscr_date >= date('Y-m-d'))
            @include('my-coupon')
        @else
            @include('expired')
        @endif
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
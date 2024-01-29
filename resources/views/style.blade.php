@include('version')
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="{{ $allsettings->site_title }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="_token" content="{{csrf_token()}}" />
<meta http-equiv="Cache-control" content="public">
@if($allsettings->site_favicon != '')
<link rel="apple-touch-icon" href="{{ url('/') }}/public/storage/settings/{{ $allsettings->site_favicon }}">
<link rel="shortcut icon" href="{{ url('/') }}/public/storage/settings/{{ $allsettings->site_favicon }}">
@endif
@if($view_name == 'index')
<link rel="stylesheet" media="screen" href="{{ URL::to('resources/views/theme/css/vendor.min.css') }}">
<link rel="stylesheet" media="screen" href="{{ URL::to('resources/views/theme/css/theme.min.css') }}">
@include('dynamic')
<link href="{{ URL::to('resources/views/theme/cookie/cookiealert.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/animate/aos.css') }}" />
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/autosearch/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/validate/themes/red/red.css') }}" />
@else
<link rel="stylesheet" media="screen" href="{{ URL::to('resources/views/theme/css/vendor.min.css') }}">
<link rel="stylesheet" media="screen" href="{{ URL::to('resources/views/theme/css/theme.min.css') }}">
@include('dynamic')
<link href="{{ URL::to('resources/views/theme/cookie/cookiealert.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/animate/aos.css') }}" />
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/autosearch/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/validate/themes/red/red.css') }}" />
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/pagination/pagination.css') }}">
<link type="text/css" href="{{ URL::to('resources/views/theme/countdown/jquery.countdown.css?v=1.0.0.0') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ URL::to('resources/views/theme/video/video.css') }}">
<link rel="stylesheet" href="{{ URL::to('resources/views/admin/template/datepicker/picker.css') }}">
<link href="{{ asset('resources/views/admin/template/dragdrop/css/jquery.filer.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/mp3/css/stylised.css') }}" />
@if($view_name == 'messages')
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/chatbox/chat.css') }}">
@endif
<link href="{{ URL::to('resources/views/theme/emojione/emojione.picker.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ URL::to('resources/views/admin/template/dropzone/min/dropzone.min.css')}}">
@endif
@if($current_locale == 'ar')
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/css/rtl.css') }}" />
@endif
<link rel="stylesheet" href="{{ asset('resources/views/assets/style.css') }}">
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/stripe/stripe.css') }}">
@if($addition_settings->google_ads == 1)
<!-- google ads -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
<!-- google ads -->
@endif
@if($additional->shop_search_type == 'ajax')
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/filter/jplist.core.min.css') }}">
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/filter/jplist.jquery-ui-bundle.min.css') }}">
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/filter/jquery-ui.css') }}" />
@endif
@if($additional->header_layout == 'layout_two')
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/menu/layout_two.css') }}" />
@if($current_locale == 'ar')
<link rel="stylesheet" href="{{ URL::to('resources/views/theme/menu/rtl.css') }}" />
@endif
@endif
@laravelPWA
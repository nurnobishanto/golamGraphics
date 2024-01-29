@if($allsettings->maintenance_mode == 0)
<!DOCTYPE HTML>
<html lang="en">
<head>
<title>{{ $allsettings->site_title }} - {{ $page['page']->page_title }}</title>
@if($page['page']->page_allow_seo == 1)
<meta name="Keywords" content="{{ $page['page']->page_seo_keyword }}">
<meta name="Description" content="{{ $page['page']->page_seo_desc }}">
@else
@include('meta')
@endif
@include('style')
</head>
<body>
@include('header')
<section class="bg-position-center-top" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">
      <div class="py-4">
        <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ __('Home') }}</a></li>
              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $page['page']->page_title }}</li>
            </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0 text-white">{{ $page['page']->page_title }}</h1>
        </div>
      </div>
      </div>
    </section>
<div class="container py-5 mt-md-2 mb-2">
      @if(in_array('pages',$top_ads))
      <div class="row">
          <div class="col-lg-12 mb-4" align="center">
             @php echo html_entity_decode($addition_settings->top_ads); @endphp
          </div>
       </div>   
       @endif 
      <div class="row">
        <div class="col-lg-12" data-aos="fade-up" data-aos-delay="200">
          <div class="font-size-md">@php echo html_entity_decode($page['page']->page_desc); @endphp</div>
         </div>
      </div>
      @if(in_array('pages',$bottom_ads))
       <div class="row">
          <div class="col-lg-12 mb-4" align="center">
             @php echo html_entity_decode($addition_settings->bottom_ads); @endphp
          </div>
       </div>   
       @endif
    </div>
@include('footer')
@include('script')
</body>
</html>
@else
@include('503')
@endif
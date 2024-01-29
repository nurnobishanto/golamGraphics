<div class="page-title-overlap pt-4" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">
      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ __('Home') }}</a></li>
              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ __('Favourites') }}</li>
              </li>
             </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0 text-white">{{ __('My Favourites') }}</h1>
        </div>
      </div>
    </div>
<div class="container mb-5 pb-3">
      <div class="bg-light box-shadow-lg rounded-lg overflow-hidden">
        <div class="row">
          <!-- Sidebar-->
          <aside class="col-lg-4">
            <!-- Account menu toggler (hidden on screens larger 992px)-->
            <div class="d-block d-lg-none p-4">
            <a class="btn btn-outline-accent d-block" href="#account-menu" data-toggle="collapse"><i class="dwg-menu mr-2"></i>{{ __('Account menu') }}</a></div>
            <!-- Actual menu-->
            @if(Auth::user()->id != 1)
            @include('dashboard-menu')
            @endif
          </aside>
          <!-- Content-->
          <section class="@if(Auth::user()->id == 1) col-lg-12 pl-4 @else col-lg-8 @endif pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 pl-lg-0 pr-xl-5">
              <h2 class="h3 pt-2 pb-4 mb-0 text-center text-sm-left border-bottom">{{ __('My Favourites') }}<span class="badge badge-secondary font-size-sm text-body align-middle ml-2">{{ count($fav['item']) }}</span></h2>
              <!-- Product-->
                @php $no = 1; @endphp
                @foreach($fav['item'] as $featured)
                @php
                $price = Helper::price_info($featured->item_flash,$featured->regular_price);
                @endphp
              <div class="media d-block d-sm-flex align-items-center py-4 border-bottom">
              <a class="d-block position-relative mb-3 mb-sm-0 mr-sm-4 mx-auto cart-img" href="{{ url('/favourites') }}/{{ base64_encode($featured->fav_id) }}/{{ base64_encode($featured->item_id) }}" onClick="return confirm('{{ __('Are you sure you want to remove from favourites?') }}');">
              @if($featured->item_preview!='')
              <img class="lazy rounded-lg" width="200" height="155" src="{{ Helper::Image_Path($featured->item_preview,'no-image.png') }}"  alt="{{ $featured->item_name }}">
              @else
              <img class="lazy rounded-lg" width="200" height="155" src="{{ url('/') }}/public/img/no-image.png"  alt="{{ $featured->item_name }}">
              @endif
              <span class="close-floating" data-toggle="tooltip" title="Remove from Favorites"><i class="dwg-close"></i></span></a>
                <div class="media-body text-center text-sm-left">
                  <h3 class="h6 product-title mb-2"><a href="{{ URL::to('/item') }}/{{ $featured->item_slug }}">
				  @if($addition_settings->item_name_limit != 0)
				  {{ mb_substr($featured->item_name,0,$addition_settings->item_name_limit,'utf-8').'...' }}
				  @else
				  {{ $featured->item_name }}	  
				  @endif
				  </a></h3>
                  <div class="d-inline-block text-accent">{{ Helper::price_format($allsettings->site_currency_position,$price,$currency_symbol,$multicurrency) }}</div><a class="d-inline-block text-accent font-size-ms border-left ml-2 pl-2" href="{{ URL::to('/shop') }}/item-type/{{ $featured->item_type }}">{{ Helper::ItemTypeIdGetData($featured->item_type_id) }}</a>
                  <div class="form-inline pt-2">
                    {{ mb_substr($featured->item_shortdesc,0,60,'utf-8').'...' }}
                    <a class="btn btn-primary btn-sm mx-auto mx-sm-0 my-2" href="{{ URL::to('/item') }}/{{ $featured->item_slug }}"><i class="dwg-cart mr-1"></i>{{ __('View Product') }}</a></div>
                </div>
              </div>
              @php $no++; @endphp
              @endforeach
            </div>
          </section>
        </div>
      </div>
    </div>
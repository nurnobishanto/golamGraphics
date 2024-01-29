<div class="page-title-overlap pt-4" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">
      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ __('Home') }}</a></li>
              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ __('Manage Items') }}</li>
              </li>
             </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0 text-white">{{ __('Manage Items') }}</h1>
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
          <section class="col-lg-8 pt-lg-4 pb-4 mb-3">
            <div class="pt-2 px-4 pl-lg-0 pr-xl-5">
              <div class="row border-bottom">
              <h2 class="h3 pt-2 pb-4 mb-0 col pull-left">{{ __('Manage Items') }} <?php /*?><span class="badge badge-secondary font-size-sm text-body align-middle ml-2">{{ count($itemData['item']) }}</span><?php */?></h2>         
              <div class="col pull-right">
              <button onClick="meFunction()" class="btn btn-primary btn-sm dropbtn"><span class="dwg-add"></span> {{ __('Upload Item') }}</button>
                            <div id="myDropdown" class="dropdown-content">
                                @foreach($viewitem['type'] as $item_type)
                                @php $encrypted = $encrypter->encrypt($item_type->item_type_id); @endphp
                                <a href="{{ URL::to('/upload-item') }}/{{ $encrypted }}">{{ $item_type->item_type_name }}</a>
                                @endforeach
                            </div>
               </div>             
              </div>
              <!-- Product-->
                @php $no = 1; @endphp
                @foreach($itemData['item'] as $featured)
                @php
                $price = Helper::price_info($featured->item_flash,$featured->regular_price);
                @endphp
              <div class="media d-block d-sm-flex align-items-center py-4 border-bottom">
              @if($featured->item_preview!='')
              <img class="lazy rounded-lg mr-sm-4 mx-auto cart-img" width="200" height="155" src="{{ Helper::Image_Path($featured->item_preview,'no-image.png') }}"  alt="{{ $featured->item_name }}">
              @else
              <img class="lazy rounded-lg mr-sm-4 mx-auto cart-img" width="200" height="155" src="{{ url('/') }}/public/img/no-image.png"  alt="{{ $featured->item_name }}">
              @endif
              @php $encrypted = $encrypter->encrypt($featured->item_token); @endphp
              <span class="close-floating" data-toggle="tooltip" title="{{ __('Remove from favourites') }}"><i class="dwg-close"></i></span>
                <div class="media-body text-center text-sm-left">
                  <h3 class="h6 product-title mb-2"><a href="{{ URL::to('/item') }}/{{ $featured->item_slug }}">
				  @if($addition_settings->item_name_limit != 0)
				  {{ mb_substr($featured->item_name,0,$addition_settings->item_name_limit,'utf-8').'...' }}
				  @else
				  {{ $featured->item_name }}	  
				  @endif
				  </a> @if($featured->item_status == 0) <span class="badge badge-pill badge-danger pull-right">{{ __('UnApproved') }}</span> @endif</h3>
                  <div class="d-inline-block text-accent">@if($featured->free_download == 0){{ Helper::price_format($allsettings->site_currency_position,$price,$currency_symbol,$multicurrency) }}@else<span>{{ __('Free') }}</span>@endif</div><a class="d-inline-block text-accent font-size-ms border-left ml-2 pl-2" href="{{ URL::to('/shop') }}/item-type/{{ $featured->item_type }}">{{ Helper::ItemTypeIdGetData($featured->item_type_id) }}</a>
                  <div class="form-inline pt-2">
                    {{ mb_substr($featured->item_shortdesc,0,60,'utf-8').'...' }}
                  </div>
                  <div class="mt-2">
                    <a href="{{ URL::to('/edit-item') }}/{{ $featured->item_token }}" class="btn btn-success btn-sm mr-1"><i class="dwg-edit mr-1"></i>{{ __('Edit') }}</a>
                    <a class="btn btn-primary btn-sm mr-1" href="{{ URL::to('/manage-item') }}/{{ $encrypted }}" onClick="return confirm('{{ __('Are you sure you want to delete?') }}');"><i class="dwg-trash mr-1"></i>{{ __('Delete') }}</a>
                    <a href="{{ URL::to('/download') }}/{{ $featured->item_token }}" class="btn btn-info btn-sm mr-3"><i class="dwg-download mr-1"></i>{{ __('Download') }}</a>
                    </div>
                </div>
              </div>
              @php $no++; @endphp
              @endforeach
            </div>
            <div class="mt-2">
            {{ $itemData['item']->links('pagination::bootstrap-4') }}
            </div>
          </section>
        </div>
      </div>
    </div>
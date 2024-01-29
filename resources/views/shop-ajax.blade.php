<div id="demo">
<div class="row mx-n2">
<div class="col-lg-4"></div>
<div class="col-lg-8 jplist-panel">
<div class="row">
<div class="col-lg-4"></div>
<div class="col-lg-4 mb-2">
<div class="widget cz-filter">
                <div class="input-group-overlay input-group-sm mb-2">
                  <select 
                  class="cz-filter-search form-control form-control-sm appended-form-control" 
                  data-control-type="sort-select" 
						   data-control-name="sort" 
						   data-control-action="sort">
                  <option data-path=".like" data-order="asc" data-type="number">{{ __('Price : Low to High') }}</option>
                  <option data-path=".like" data-order="desc" data-type="number">{{ __('Price : High to low') }}</option>
                 </select>            
                </div>
              </div>
              </div>
<div class="col-lg-4 mb-2">              
              <div class="widget cz-filter">
                <div class="input-group-overlay input-group-sm mb-2">
                  <select 
                  class="cz-filter-search form-control form-control-sm appended-form-control" 
                  data-control-type="sort-select" 
						   data-control-name="sort" 
						   data-control-action="sort">
                  <option data-path=".popular-items" data-order="desc" data-type="number">{{ __('Popular Items') }}</option>
                  <option data-path=".new-items" data-order="desc" data-type="number">{{ __('New Items') }}</option>
				  <option data-path=".free-items" data-order="desc" data-type="number">{{ __('Free Items') }}</option>
                 </select>            
                </div>
              </div>
              </div>
    </div>          
</div>
</div>
<div class="row pt-3 mx-n2">
         <div class="col-lg-4 jplist-panel">
          <!-- Sidebar-->
          <div class="cz-sidebar rounded-lg box-shadow-lg" id="shop-sidebar">
            <div class="cz-sidebar-header box-shadow-sm">
              <button class="close ml-auto" type="button" data-dismiss="sidebar" aria-label="Close"><span class="d-inline-block font-size-xs font-weight-normal align-middle">{{ __('Close sidebar') }}</span><span class="d-inline-block align-middle ml-2" aria-hidden="true">&times;</span></button>
            </div>
            <div class="cz-sidebar-body" data-simplebar data-simplebar-auto-hide="true">
              
              <!-- Filter by Brand-->
              <div class="widget cz-filter mb-4 pb-4 border-bottom">
                <h3 class="widget-title">{{ __('Item Type') }}</h3>
                <div class="input-group-overlay input-group-sm mb-2">
                  <input class="cz-filter-search form-control form-control-sm appended-form-control" type="text" placeholder="{{ __('Search') }}">
                  <div class="input-group-append-overlay"><span class="input-group-text"><i class="czi-search"></i></span></div>
                </div>
                @if(count($getWell['type']) != 0)
                <div 
                    class="jplist-group"
                    data-control-type="checkbox-group-filter"
						   data-control-action="filter"
						   data-control-name="themes">
                <ul class="widget-list cz-filter-list list-unstyled pt-1" style="max-height: 12rem;" data-simplebar data-simplebar-auto-hide="false">
                  @foreach($getWell['type'] as $value)
                  <li class="cz-filter-item d-flex justify-content-between align-items-center">  
                     <div class="custom-control custom-checkbox">
                      <input id="{{ $value->item_type_slug }}" data-path=".{{ $value->item_type_slug }}" type="checkbox" class="custom-control-input">
                      <label class="custom-control-label cz-filter-item-text" for="{{ $value->item_type_slug }}">{{ $value->item_type_name }}</label>
                    </div>
                  </li>
                  @endforeach
                 </ul>
                 </div>
                @endif 
              </div>
			  <!-- Categories-->
              <div class="widget cz-filter mb-4 pb-4 border-bottom">
                <h3 class="widget-title">{{ __('Categories') }}</h3>
                <div class="input-group-overlay input-group-sm mb-2">
                  <input class="cz-filter-search form-control form-control-sm appended-form-control" type="text" placeholder="{{ __('Search') }}">
                  <div class="input-group-append-overlay"><span class="input-group-text"><i class="dwg-search"></i></span></div>
                </div>
                @if(count($category['view']) != 0)
                <div 
                    class="jplist-group"
                    data-control-type="checkbox-group-filter"
						   data-control-action="filter"
						   data-control-name="categorysearch">
                <ul class="widget-list cz-filter-list list-unstyled pt-1" style="max-height: 12rem;" data-simplebar data-simplebar-auto-hide="false">
                  @foreach($category['view'] as $cat)
                  <li class="cz-filter-item d-flex justify-content-between align-items-center">
                      <div class="custom-control custom-checkbox">
                      <input id="{{ 'category_'.$cat->cat_id }}" data-path=".{{ 'category_'.$cat->cat_id }}" type="checkbox" class="custom-control-input" >
                      <label class="custom-control-label cz-filter-item-text" for="{{ 'category_'.$cat->cat_id }}">{{ $cat->category_name }}</label>
                      @foreach($cat->subcategory as $sub_category)
                      <br/>
                      <span class="ml-2"><input id="{{ 'subcategory_'.$sub_category->subcat_id }}" data-path=".{{ 'subcategory_'.$sub_category->subcat_id }}" type="checkbox" class="custom-control-input" >
                      <label class="custom-control-label cz-filter-item-text" for="{{ 'subcategory_'.$sub_category->subcat_id }}">{{ $sub_category->subcategory_name }}</label>
                      </span>
                      @endforeach
                    </div>
                  </li>
                  @endforeach 
                </ul>
                </div>
                @endif
              </div>
              
              <!-- Price range-->
              @if(count($itemData['item']) != 0)
              <div class="widget mb-4 pb-4 border-bottom">
                <h3 class="widget-title">{{ __('Price') }}</h3>
                <div class="cz-range-slider" data-start-min="{{ $minprice['price']->regular_price }}" data-start-max="{{ $maxprice['price']->extended_price }}" data-min="{{ $allsettings->site_range_min_price }}" data-max="{{ $allsettings->site_range_max_price }}" data-step="1">
                  <div class="demo">
                      <input type="text" id="amount" class="range-price" />
                       <div id="slider-range"></div>
                        </div>
                  <div id="slider-range-min"></div>
                 </div>
              </div>
              @endif
             @if(in_array('shop',$sidebar_ads))
           <div class="mt-4" align="center">
           @php echo html_entity_decode($addition_settings->sidebar_ads); @endphp
           </div>
           @endif
              <!-- Filter by Brand-->
           </div>
          </div>
        </div>
        <div class="col-lg-8">
         <div class="row pt-2 mx-n2 flash-sale list items box">
         @if(in_array('shop',$top_ads))
          <div class="mt-2 mb-2" align="center">
             @php echo html_entity_decode($addition_settings->top_ads); @endphp
          </div>
          @endif
        <!-- Product-->
        @if(count($itemData['item']) != 0)
        @php $no = 1; @endphp
        @foreach($itemData['item'] as $featured)
        @php
        $price = Helper::price_info($featured->item_flash,$featured->regular_price);
        $count_rating = Helper::count_rating($featured->ratings);
        @endphp
        <div class="col-lg-4 col-md-4 col-sm-6 px-2 mb-3 list-item box" data-price="{{ $price }}">
          <!-- Product-->
          <div class="card product-card-alt">
            <div class="product-thumb">
              @if(Auth::guest()) 
              <a class="btn-wishlist btn-sm" href="{{ url('/') }}/login"><i class="dwg-heart"></i></a>
              @endif
              @if (Auth::check())
              @if($featured->user_id != Auth::user()->id)
              <a class="btn-wishlist btn-sm" href="{{ url('/item') }}/{{ base64_encode($featured->item_id) }}/favorite/{{ base64_encode($featured->item_liked) }}"><i class="dwg-heart"></i></a>
              @endif
              @endif
              <div class="product-card-actions"><a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ URL::to('/item') }}/{{ $featured->item_slug }}"><i class="dwg-eye"></i></a>
              @php
              $checkif_purchased = Helper::if_purchased($featured->item_token);
              @endphp
              @if($checkif_purchased == 0)
              @if($featured->free_download == 0)
              @if (Auth::check())
              @if(Auth::user()->id != 1 && $featured->user_id != Auth::user()->id)
              <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ URL::to('/add-to-cart') }}/{{ $featured->item_slug }}"><i class="dwg-cart"></i></a>
              @endif
              @else
              <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ URL::to('/add-to-cart') }}/{{ $featured->item_slug }}"><i class="dwg-cart"></i></a>
              @endif
              @else
              @if(Auth::guest())
              <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ URL::to('/login') }}"><i class="dwg-download"></i></a>
              @else
              <a class="btn btn-light btn-icon btn-shadow font-size-base mx-2" href="{{ URL::to('/item') }}/download/{{ base64_encode($featured->item_token) }}"><i class="dwg-download"></i></a>
              @endif
              @endif 
              @endif
              </div><a class="product-thumb-overlay" href="{{ URL::to('/item') }}/{{ $featured->item_slug }}"></a>
                            @if($featured->item_preview!='')
                            <img class="lazy" width="256" height="200" src="{{ Helper::Image_Path($featured->item_preview,'no-image.png') }}"  alt="{{ $featured->item_name }}">
                            @else
                            <img class="lazy" width="256" height="200" src="{{ url('/') }}/public/img/no-image.png"  alt="{{ $featured->item_name }}">
                            @endif
            </div>
            <div class="card-body">
              <div class="d-flex flex-wrap justify-content-between align-items-start pb-2">
              <span class="{{ $featured->item_type }}" style="display:none;">{{ $featured->item_type }}</span>
              <span class="{{ $featured->item_type_cat_id }}" style="display:none;">{{ $featured->item_type_cat_id }}</span>
              <span class="popular-items" style="display:none;">{{ $featured->item_liked }}</span>
              <span class="new-items" style="display:none;">{{ $featured->item_id }}</span>
              <span class="free-items" style="display:none;">{{ $featured->free_download }}</span>
              <span class="like" style="display:none;">{{ Helper::price_format($allsettings->site_currency_position,$price,$currency_symbol,$multicurrency) }}</span>
              <div class="text-muted font-size-xs mr-1"><a class="product-meta font-weight-medium" href="{{ URL::to('/shop') }}/item-type/{{ $featured->item_type }}">{{ Helper::ItemTypeIdGetData($featured->item_type_id) }}</a></div>
                <div class="star-rating">
                    @if($count_rating == 0)
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    @endif
                    @if($count_rating == 1)
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    @endif
                    @if($count_rating == 2)
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    @endif
                    @if($count_rating == 3)
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star"></i>
                    <i class="sr-star dwg-star"></i>
                    @endif
                    @if($count_rating == 4)
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star"></i>
                    @endif
                    @if($count_rating == 5)
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    <i class="sr-star dwg-star-filled active"></i>
                    @endif
                </div>
               </div>
              <h3 class="product-title font-size-sm mb-2"><a href="{{ URL::to('/item') }}/{{ $featured->item_slug }}">
			  @if($addition_settings->item_name_limit != 0)
			  {{ mb_substr($featured->item_name,0,$addition_settings->item_name_limit,'utf-8').'...' }}
		      @else
			  {{ $featured->item_name }}	  
			  @endif
			  </a></h3>
              <div class="card-footer d-flex align-items-center font-size-xs">
              <a class="blog-entry-meta-link" href="{{ URL::to('/user') }}/{{ $featured->username }}">
                    <div class="blog-entry-author-ava">
                    @if($featured->user_photo!='')
                    <img class="lazy" width="26" height="26" src="{{ url('/') }}/public/storage/users/{{ $featured->user_photo }}"  alt="{{ $featured->username }}">
                    @else
                    <img class="lazy" width="26" height="26" src="{{ url('/') }}/public/img/no-user.png"   alt="{{ $featured->username }}">
                    @endif
                    </div>
					@if($addition_settings->author_name_limit != 0)
					{{ mb_substr($featured->username,0,$addition_settings->author_name_limit,'utf-8') }}
				    @else
				    {{ $featured->username }}	  
				    @endif 
					@if($addition_settings->subscription_mode == 1) @if($featured->user_document_verified == 1) <span class="badges-success"><i class="dwg-check-circle danger"></i> {{ __('verified') }}</span>@endif @endif</a>
                  <div class="ml-auto text-nowrap"><i class="dwg-time"></i> {{ date('d M Y',strtotime($featured->updated_item)) }}</div>
                </div>
              <div class="d-flex flex-wrap justify-content-between align-items-center">
                @if($featured->file_type == 'serial') 
                @php
                if($featured->item_delimiter == 'comma')
                {
                $result_count = substr_count($featured->item_serials_list, ",");
                }
                else
                {
                $result_count = substr_count($featured->item_serials_list, "\n");
                }
                @endphp
                <div class="font-size-sm mr-2"><i class="dwg-cart text-muted mr-1"></i>{{ $result_count }}<span class="font-size-xs ml-1">{{ __('Stock') }}</span>
                </div>
                @else
                <div class="font-size-sm mr-2">
                @if($addition_settings->item_sale_count == 1)
                <i class="dwg-download text-muted mr-1"></i>{{ $featured->item_sold }}<span class="font-size-xs ml-1">{{ __('Sales') }}</span>
                @endif
                </div>
                @endif
                <div>
                @if($featured->free_download == 0)
                @if($featured->item_flash == 1)<del class="price-old">{{ Helper::price_format($allsettings->site_currency_position,$featured->regular_price,$currency_symbol,$multicurrency) }}</del>@endif <span class="bg-faded-accent text-accent rounded-sm py-1 px-2">{{ Helper::price_format($allsettings->site_currency_position,$price,$currency_symbol,$multicurrency) }}</span>
                @else
                <span class="price-badge rounded-sm py-1 px-2">{{ __('Free') }}</span> 
                @endif
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Product-->
        @php $no++; @endphp
	    @endforeach
        
       </div>
       <div class="row">
                <div class="col-md-12" align="right">
                <div class="jplist-panel box panel-top">						
							
						<div 
						   class="jplist-label customlable" 
						   data-type="Page {current} of {pages}" 
						   data-control-type="pagination-info" 
						   data-control-name="paging" 
						   data-control-action="paging">
						</div>	

						<div 
						   class="jplist-pagination" 
						   data-control-type="pagination" 
						   data-control-name="paging" 
						   data-control-action="paging"
						   data-items-per-page="{{ $allsettings->site_item_per_page }}">
						</div>			
						
					</div>
                    <!--<div class="pagination-area">
                           <div class="turn-page" id="pager"></div>
                        </div>-->
                </div>
            </div>
       @if(in_array('shop',$bottom_ads))
       <div class="mt-3 mb-4 pb-4" align="center">
         @php echo html_entity_decode($addition_settings->bottom_ads); @endphp
       </div>
       @endif
       @else
       <div>{{ __('No product found') }}</div>
       @endif
       </div>
        </div>
      </div>  
      
      
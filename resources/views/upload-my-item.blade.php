<div class="page-title-overlap pt-4" style="background-image: url('{{ url('/') }}/public/storage/settings/{{ $allsettings->site_banner }}');">
      <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
              <li class="breadcrumb-item"><a class="text-nowrap" href="{{ URL::to('/') }}"><i class="dwg-home"></i>{{ __('Home') }}</a></li>
              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ __('Upload Item') }}</li>
              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ $type_name->item_type_name }}</li>
              </li>
             </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0 text-white">{{ __('Upload Item') }} <span class="dwg-arrow-right"></span> {{ $type_name->item_type_name }}</h1>
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
              <!-- Product-->
            <div class="row">
             <div class="col-sm-12 mb-1">
              <div class="alert alert-info alert-with-icon font-size-sm mb-4" role="alert">
                <div class="alert-icon-box"><i class="alert-icon dwg-announcement"></i></div> <b>{{ __('Copyright Note') }}</b><br/>{{ __('You should include details of source files you have used in the Comments for the Reviewer section of the form.') }} {{ __('If your file does not meet these copyright standards, it will be rejected.') }}
              </div>
              </div>
              <div class="col-sm-12 mb-1">
              <div class="alert alert-info alert-with-icon font-size-sm mb-4" role="alert">
                <div class="alert-icon-box"><i class="alert-icon dwg-announcement"></i></div><b>{{ __('Allowed Files') }} :</b> {{ $additional->item_file_extension }}<br/><b>{{ __('Image Upload') }} :</b> jpeg, jpg, png, webp (Upload Thumbnail, Upload Preview, Upload Screenshots)<?php /*?>@if(Auth::user()->user_subscr_space_level == 'limited') <br/><b>Available Storage Space : </b> {{ Auth::user()->user_subscr_space }} {{ Auth::user()->user_subscr_space_type }} @endif | <?php */?>
                @if($addition_settings->subscription_mode == 1)
                <?php /*?>@if(Auth::user()->user_subscr_space_level == 'limited')<br/><span class="red-color"><b>{{ __('Used Storage Space') }} : </b>{{ Helper::formatSizeUnits(Helper::available_space(Auth::user()->id)) }}</span>@endif<?php */?>
                @endif
              </div>
              </div>
              <div class="col-sm-12 mb-1">
              <h4 class="mt-4">{{ __('Upload Files') }} @if($demo_mode == 'on')<span class="require">- This is Demo version. So Maximum 1MB Allowed</span>@endif</h4>
              </div>
             <div class="col-sm-12 mb-1">
             <div class="form-group">
               <form action="{{route('fileupload')}}" class='dropzone' method="post" enctype="multipart/form-data">
               <input type="hidden" name="item_token" value="">
               </form>
             </div>
             </div>
             </div>
             <form action="{{ route('upload-item') }}" class="setting_form" id="item_form" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div id="display_message"></div>
              <div class="row" id="hide_message"> 
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Upload Thumbnail') }} <span class="require">*</span> ({{ __('Size') }} : 80x80px)</label>
                  <div class="custom_upload">
                  <select id="item_thumbnail1" name="item_thumbnail1" class="form-control" data-bvalidator="required">
                  <option value=""></option>
                  @foreach($getdata1['first'] as $get)
                  <option value="{{ $get->item_file_name }}">{{ $get->original_file_name }}</option>
                  @endforeach
                  </select>
                 </div>
                </div>
              </div> 
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Upload Preview') }} <span class="require">*</span> ({{ __('Size') }} : 361x230px)</label>
                  <div class="custom_upload">
                     <select name="item_preview1" id="item_preview1" class="form-control" data-bvalidator="required">
                     <option value=""></option>
                     @foreach($getdata2['second'] as $get)
                     <option value="{{ $get->item_file_name }}">{{ $get->original_file_name }}</option>
                     @endforeach
                     </select>
                 </div>
                </div>
              </div>
              
              @if($additional->show_screenshots == 1)
              <div class="col-sm-6">
                <div class="form-group upload_wrapper">
                  <label for="account-fn">{{ __('Upload Screenshots (multiple)') }} ({{ __('Size') }} : 750x430px)</label>
                    <select id="item_screenshot1" name="item_screenshot[]" class="form-control" multiple>
                    @foreach($getdata4['four'] as $get)
                    <option value="{{ $get->item_file_name }}">{{ $get->original_file_name }}</option>
                    @endforeach
                    </select>
                </div>
              </div>
              @endif
              @if($additional->show_video == 1)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Preview Type (optional)') }}</label>
                  <select name="video_preview_type1" id="video_preview_type1" @if ($errors->has('video_file')) class="form-control border-color" @else class="form-control" @endif>
                   <option value=""></option>
                   <option value="youtube">{{ __('Youtube') }}</option>
                   <option value="mp4">{{ __('MP4') }}</option>
                   <option value="mp3">{{ __('MP3') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6" id="youtube">
                <div class="form-group">
                  <label for="account-fn">{{ __('Youtube Video URL') }} <span class="require">*</span></label>
                  <input type="text" id="video_url1" name="video_url1" class="form-control" data-bvalidator="required">
                  <small>({{ __('example') }} : https://www.youtube.com/watch?v=C0DPdy98e4c)</small>
                </div>
              </div>
              <div class="col-sm-6" id="mp4">
                <div class="form-group">
                  <label for="account-fn">{{ __('Upload MP4 Video') }} <span class="require">*</span> ({{ __('MP4 - file only') }} @if($addition_settings->subscription_mode == 1) @if(Auth::user()->user_subscr_space_level == 'limited')| Max Size : {{ Auth::user()->user_subscr_space }} {{ Auth::user()->user_subscr_space_type }} @endif @endif)</label>
                    <select id="video_file1" name="video_file1" class="form-control" data-bvalidator="required">
                    <option value=""></option>
                    @foreach($getdata5['five'] as $get)
                    <option value="{{ $get->item_file_name }}">{{ $get->original_file_name }}</option>
                    @endforeach
                    </select>
                </div>
              </div>
              <div class="col-sm-6" id="mp3">
                <div class="form-group">
                  <label for="account-fn">{{ __('Upload MP3') }} <span class="require">*</span></label>
                    <select id="audio_file1" name="audio_file1" class="form-control" data-bvalidator="required">
                    <option value=""></option>
                    @foreach($getdata6['six'] as $get)
                    <option value="{{ $get->item_file_name }}">{{ $get->original_file_name }}</option>
                    @endforeach
                    </select>
                </div>
              </div>
              @endif
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Upload Main File Type') }} <span class="require">*</span></label>
                  <select name="file_type1" id="file_type1" class="form-control" data-bvalidator="required">
                  <option value=""></option>
                  <option value="file">{{ __('File') }}</option>
                  <option value="link">{{ __('Link / URL') }}</option>
                  <option value="serial">{{ __('License Keys / Serial Numbers') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6" id="main_file">
                <div class="form-group">
                  <label for="account-fn">{{ __('Upload Main File') }} <span class="require">*</span> (@if($addition_settings->subscription_mode == 1) @if(Auth::user()->user_subscr_space_level == 'limited')| Max Size : {{ Auth::user()->user_subscr_space }} {{ Auth::user()->user_subscr_space_type }} @endif @endif)</label>
                      <select name="item_file1" id="item_file1" class="form-control" data-bvalidator="required">
                      <option value=""></option>
                      @foreach($getdata3['third'] as $get)
                      <option value="{{ $get->item_file_name }}">{{ $get->original_file_name }}</option>
                      @endforeach
                      </select>
                </div>
              </div>
              <div class="col-sm-6" id="main_link">
                <div class="form-group">
                  <label for="account-fn">{{ __('Main File Link/URL') }} <span class="require">*</span></label>
                  <input type="text" id="item_file_link1" name="item_file_link1" class="form-control" data-bvalidator="required,url">
                </div>
              </div>
              </div>
              
              <div class="row"> 
              <div class="col-sm-6" id="main_delimiter">
                <div class="form-group">
                  <label for="account-fn">{{ __('Delimiter') }} <span class="require">*</span></label>
                  <select name="item_delimiter" id="item_delimiter1" class="form-control" data-bvalidator="required">
                    <option value=""></option>
                    <option value="comma">{{ __('Comma') }}</option>
                    <option value="newline">{{ __('New Line') }}</option>
                 </select>
                </div>
              </div>
              <div class="col-sm-6" id="main_serials">
                <div class="form-group">
                  <label for="account-fn">{{ __('Serials List') }} <span class="require">*</span></label>
                  <textarea name="item_serials_list" id="item_serials_list" rows="6"  class="form-control" data-bvalidator="required"></textarea>
                  <small id="hint_line" class="require">({{ __('Enter available license / serials keys, one per line') }})<br/></small>
                  <small id="hint_comma" class="require">({{ __('Enter available license / serials keys, separated by comma') }})</small>
                </div>
              </div>
              <div class="col-sm-12 mt-4 mb-1">
              <h4>{{ __('Name & Description') }}</h4>
              </div>
              <input type="hidden" name="item_type" value="{{ $type_name->item_type_slug }}">
              <input type="hidden" name="type_id" value="{{ $type_id }}"> 
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="account-fn">{{ __('Item Name') }} <span class="require">*</span> ({{ __('Max 100 characters') }})</label>
                  <input type="text" id="item_name" name="item_name" @if ($errors->has('item_name')) class="form-control border-color" @else class="form-control" @endif data-bvalidator="required,maxlen[100]">
                  @if ($errors->has('item_name'))
                  <span class="help-block">
                     <span class="red">{{ $errors->first('item_name') }}</span>
                  </span>
                 @endif
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="account-fn">{{ __('Short Description') }}</label>
                  <textarea name="item_shortdesc" rows="6"  class="form-control"></textarea>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="account-fn">{{ __('Description') }} <span class="require">*</span></label>
                  <textarea name="item_desc" id="summary-ckeditor" rows="6"  @if ($errors->has('item_desc')) class="form-control border-color" @else class="form-control" @endif data-bvalidator="required"></textarea>
                  @if ($errors->has('item_desc'))
                  <span class="help-block">
                     <span class="red">{{ $errors->first('item_desc') }}</span>
                  </span>
                 @endif
                </div>
              </div>
              
              <div class="col-sm-12 mt-4 mb-1">
              <h4 class="mt-4">{{ __('Category & Attributes') }}</h4>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-email">{{ __('Select Category') }} <span class="require">*</span></label>
                  <select name="item_category" id="item_category" class="form-control" data-bvalidator="required">
                  <option value="">{{ __('Select') }}</option>
                  @foreach($re_categories['menu'] as $menu)
                  <option value="category_{{ $menu->cat_id }}">{{ $menu->category_name }}</option>
                  @foreach($menu->subcategory as $sub_category)
                  <option value="subcategory_{{$sub_category->subcat_id}}"> - {{ $sub_category->subcategory_name }}</option>
                  @endforeach  
                  @endforeach
                  </select>
                </div>
              </div>
              @foreach($attribute['fields'] as $attribute_field)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-email">{{ $attribute_field->attr_label }} <span class="require">*</span></label>
                  @php $field_value=explode(',',$attribute_field->attr_field_value); @endphp
                  @if($attribute_field->attr_field_type == 'multi-select')
                  <div class="select-wrap select-wrap2">
                  <select  name="attributes_{{ $attribute_field->attr_id }}[]" class="form-control" multiple="multiple" data-bvalidator="required">
                  @foreach($field_value as $field)
                  <option value="{{ $field }}">{{ $field }}</option>
                  @endforeach
                  </select>
                  </div>
                  @endif
                  @if($attribute_field->attr_field_type == 'single-select')
                  <div class="select-wrap select-wrap2">
                  <select name="attributes_{{ $attribute_field->attr_id }}[]" class="form-control" data-bvalidator="required">
                  <option value=""></option>
                  @foreach($field_value as $field)
                  <option value="{{ $field }}">{{ $field }}</option>
                  @endforeach
                  </select>
                  <span class="lnr lnr-chevron-down"></span>
                  </div>
                  @endif
                  @if($attribute_field->attr_field_type == 'textbox')
                  <input name="attributes_{{ $attribute_field->attr_id }}[]" type="text" class="form-control" data-bvalidator="required">
                  @endif
                </div>
              </div>
              @endforeach
              @if($additional->show_moneyback == 1)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Do you offer money back guarantee') }}? <span class="require">*</span></label>
                  <select name="seller_money_back" id="seller_money_back" class="form-control" data-bvalidator="required">
                  <option value=""></option>
                  <option value="1">{{ __('Yes') }}</option>
                  <option value="0">{{ __('No') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6" id="back_money">
                <div class="form-group">
                  <label for="account-fn">{{ __('How many days to money back') }}?</label>
                  <input type="text" id="seller_money_back_days" name="seller_money_back_days" class="form-control" data-bvalidator="min[1]">
                  <small>(days)</small>
                </div>
              </div>
              @else
              <input type="hidden" name="seller_money_back" value="0" />
              @endif
              @if($additional->show_refund_term == 1)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Refund Terms') }}</label>
                  <textarea name="seller_refund_term"  rows="6"  class="form-control"></textarea>
                </div>
              </div>
              @else
              <input type="hidden" name="seller_refund_term" value="" />
              @endif
              @if($additional->show_demo_url == 1)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Demo URL') }}</label>
                  <input type="text" id="demo_url" name="demo_url" class="form-control" data-bvalidator="url">
                </div>
              </div>
              @endif
              @if($additional->show_flash_sale == 1)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Apply for flash sale?') }}</label>
                  <select name="item_flash_request" id="item_flash_request" class="form-control">
                  <option value=""></option>
                  <option value="1">{{ __('Yes') }}</option>
                  <option value="0">{{ __('No') }}</option>
                  </select>
                  <small>({{ __("If your item is selected, we will put it on sale for just one week for only 50% of it's original price.") }})</small>
                </div>
              </div>
              @else
              <input type="hidden" name="item_flash_request" value="0" />
              @endif
              @if($additional->show_tags == 1)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Tags') }}</label>
                  <textarea name="item_tags" id="item_tags" rows="6" class="form-control"></textarea>
                  <small>({{ __('Maximum of 15 keywords. Keywords should all be in lowercase and separated by commas. ex: shopping, blog, forum....ect') }})</small>
                </div>
              </div>
              @endif
              @if($additional->show_feature_update == 1 || $additional->show_item_support == 1 || $additional->show_free_download == 1)
              <div class="col-sm-12 mt-4 mb-1">
              <h4 class="mt-4">{{ __('Support & Updates') }}</h4>
              </div>
              @endif
              @if($additional->show_free_download == 1)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Apply For Free Download?') }} <span class="require">*</span></label>
                  <select name="free_download" id="free_download" class="form-control" data-bvalidator="required">
                  <option value=""></option>
                  <option value="1">{{ __('Yes') }}</option>
                  <option value="0">{{ __('No') }}</option>
                  </select>
                  <small>({{ __("if 'Yes' means all user will allowed free download this product") }})</small>
                </div>
              </div>
              @else
              <input type="hidden" name="free_download" value="0" />
              @endif
              @if($additional->show_feature_update == 1)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Feature Update') }} <span class="require">*</span></label>
                  <select name="future_update" id="future_update" class="form-control" data-bvalidator="required">
                  <option value=""></option>
                  <option value="1">{{ __('Yes') }}</option>
                  <option value="0">{{ __('No') }}</option>
                  </select>
                </div>
              </div>
              @else
              <input type="hidden" name="future_update" value="0" />
              @endif
              @if($additional->show_item_support == 1)
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Item Support') }} <span class="require">*</span></label>
                  <select name="item_support" id="item_support" class="form-control" data-bvalidator="required">
                  <option value=""></option>
                  <option value="1">{{ __('Yes') }}</option>
                  <option value="0">{{ __('No') }}</option>
                  </select>
                  <small>({{ __('If item support "YES" selected Regular license price must be entered') }})</small>
                </div>
              </div>
              @else
              <input type="hidden" name="item_support" value="0" />
              @endif
              @if($addition_settings->subscription_mode == 1)
              <div class="col-sm-6" id="subscription_box">
                <div class="form-group">
                  <label for="account-fn">{{ __('Subscription Item') }}? <span class="require">*</span></label>
                  <select name="subscription_item" id="subscription_item" class="form-control" data-bvalidator="required">
                  <option value=""></option>
                  <option value="1">{{ __('Yes') }}</option>
                  <option value="0">{{ __('No') }}</option>
                  </select>
                  <small>({{ __("if 'Yes' means subscription user will allowed free download this product") }})</small>
                </div>
              </div>
              @else
              <input type="hidden" name="subscription_item" value="0" />
              @endif
              <div class="col-sm-12 mt-4 mb-1">
              <h4 class="mt-4">{{ __('Seo') }}</h4>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="account-fn">{{ __('Allow Seo') }}? <span class="require">*</span></label>
                  <select name="item_allow_seo" id="page_allow_seo" class="form-control" data-bvalidator="required">
                  <option value=""></option>
                  <option value="1">{{ __('Yes') }}</option>
                  <option value="0">{{ __('No') }}</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6" id="ifseo1">
                <div class="form-group">
                  <label for="account-fn">{{ __('SEO Meta Keywords') }} ({{ __('max 160 chars') }}) <span class="require">*</span></label>
                  <textarea name="item_seo_keyword" id="page_seo_keyword" rows="6" class="form-control" data-bvalidator="required"></textarea>
                </div>
              </div>
              <div class="col-sm-6" id="ifseo2">
                <div class="form-group">
                  <label for="account-fn">{{ __('SEO Meta Description') }} ({{ __('max 160 chars') }}) <span class="require">*</span></label>
                  <textarea name="item_seo_desc" id="page_seo_desc" rows="6" class="form-control" data-bvalidator="required"></textarea>
                </div>
              </div>
              <div class="col-sm-12 mt-4 mb-1" id="pricebox">
              <h4 class="mt-4">{{ __('Price') }}</h4>
              </div>
              <div class="col-sm-6 mb-1" id="pricebox_left">
                    <label class="font-weight-medium" for="unp-standard-price">{{ __('Regular License') }} ({{ $additional->regular_license }} {{ __('Support') }}) <span class="require">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">{{ $allsettings->site_currency }}</span></div>
                      <input type="text" id="regular_price" name="regular_price" class="form-control" data-bvalidator="required,min[1]">
                    </div>
              </div>
              @if($additional->show_extended_license == 1)
              <div class="col-sm-6 mb-1" id="pricebox_right">
                    <label class="font-weight-medium" for="unp-standard-price">{{ __('Extended License') }} ({{ $additional->extended_license }} {{ __('Support') }})</label>
                    <div class="input-group">
                      <div class="input-group-prepend"><span class="input-group-text">{{ $allsettings->site_currency }}</span></div>
                      <input type="text" id="extended_price" name="extended_price" class="form-control" data-bvalidator="min[1]">
                    </div>
              </div>
              @else
              <input type="hidden" name="extended_price" value="0">
              @endif
              <div class="col-sm-12 mt-4 mb-1">
              <h4 class="mt-4">{{ __('Message to the Reviewer') }}</h4>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="account-fn">{{ __('Comments') }}</label>
                  <textarea name="item_reviewer" id="item_reviewer" rows="6" class="form-control"></textarea>
                </div>
              </div>
              <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
              <div class="col-12 pt-3 mt-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                @if($allsettings->item_approval == 0)
                <button class="btn btn-primary btn-block" type="submit"><i class="dwg-cloud-upload font-size-lg mr-2"></i>{{ __('Submit Review') }}</button>
                @else
                <button class="btn btn-primary btn-block" type="submit"><i class="dwg-cloud-upload font-size-lg mr-2"></i>{{ __('Submit') }}</button>
                @endif
                </div>
              </div>
            </div>
          </form>  
            </div>
          </section>
        </div>
      </div>
    </div>
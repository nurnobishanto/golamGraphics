<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<head>
@include('admin.stylesheet')
</head>
<body>
@include('admin.navigation')
<!-- Right Panel -->
    @if(in_array('settings',$avilable)) 
    <div id="right-panel" class="right-panel">
       @include('admin.header')
       <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>{{ __('Preferred Settings') }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    
                </div>
            </div>
        </div>
        @if (session('success'))
            <div class="col-sm-12">
                <div class="alert  alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="col-sm-12">
                <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="col-sm-12">
             <div class="alert  alert-danger alert-dismissible fade show" role="alert">
             @foreach ($errors->all() as $error)
             {{$error}}
             @endforeach
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
             </div>
            </div>   
         @endif
         <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">
                  <div class="col-md-12">
                      <div class="card">
                           @if($demo_mode == 'on')
                           @include('admin.demo-mode')
                           @else
                           <form action="{{ route('admin.preferred-settings') }}" method="post" id="setting_form" enctype="multipart/form-data">
                           {{ csrf_field() }}
                           @endif
                           <div class="col-md-6">
                           <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                       <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">{{ __('Blog') }} <span class="require">*</span></label>
                                                                                                
                                                <select name="site_blog_display" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="1" @if($setting['setting']->site_blog_display == 1) selected @endif>{{ __('Enable') }}</option>
                                                <option value="0" @if($setting['setting']->site_blog_display == 0) selected @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">{{ __('Features') }} <span class="require">*</span></label>
                                                                                                
                                                <select name="site_features_display" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="1" @if($setting['setting']->site_features_display == 1) selected @endif>{{ __('Enable') }}</option>
                                                <option value="0" @if($setting['setting']->site_features_display == 0) selected @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                              <label for="product_approval" class="control-label mb-1">Subscription<span class="require">*</span></label><br/>
                                              <select name="subscription_mode" class="form-control" required>
                                                        <option value=""></option>
                                                        <option value="1" @if($additional['setting']->subscription_mode == 1) selected @endif>{{ __('Enable') }}</option>
                                                        <option value="0" @if($additional['setting']->subscription_mode == 0) selected @endif>{{ __('Disable') }}</option>
                                              </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">{{ __('Verify Purchase') }} <span class="require">*</span></label>
                                                                                                
                                                <select name="verify_mode" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="1" @if($additional['setting']->verify_mode == 1) selected @endif>{{ __('Enable') }}</option>
                                                <option value="0" @if($additional['setting']->verify_mode == 0) selected @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">{{ __('Google Ads') }} <span class="require">*</span></label>
                                                                                                
                                                <select name="google_ads" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="1" @if($additional['setting']->google_ads == 1) selected @endif>{{ __('Enable') }}</option>
                                                <option value="0" @if($additional['setting']->google_ads == 0) selected @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                            
                                            <?php /*?><input type="hidden" name="subscription_mode" value="0"><?php */?>
                                       </div>
                                </div>
                             </div>
                            </div>
                            <div class="col-md-6">
                             <div class="card-body">
                                <!-- Credit Card -->
                                <div id="pay-invoice">
                                    <div class="card-body">
                                          <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">{{ __('NEWSLETTER') }} <span class="require">*</span></label>
                                                <select name="site_newsletter_display" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="1" @if($setting['setting']->site_newsletter_display == 1) selected @endif>{{ __('Enable') }}</option>
                                                <option value="0" @if($setting['setting']->site_newsletter_display == 0) selected @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">{{ __('Start Selling') }} <span class="require">*</span></label>
                                                                                                
                                                <select name="site_selling_display" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="1" @if($setting['setting']->site_selling_display == 1) selected @endif>{{ __('Enable') }}</option>
                                                <option value="0" @if($setting['setting']->site_selling_display == 0) selected @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">{{ __('Refund Request') }} <span class="require">*</span></label>
                                                                                                
                                                <select name="refund_mode" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="1" @if($additional['setting']->refund_mode == 1) selected @endif>{{ __('Enable') }}</option>
                                                <option value="0" @if($additional['setting']->refund_mode == 0) selected @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="site_title" class="control-label mb-1">{{ __('Conversation') }} <span class="require">*</span></label>
                                                                                                
                                                <select name="conversation_mode" class="form-control" data-bvalidator="required">
                                                <option value=""></option>
                                                <option value="1" @if($additional['setting']->conversation_mode == 1) selected @endif>{{ __('Enable') }}</option>
                                                <option value="0" @if($additional['setting']->conversation_mode == 0) selected @endif>{{ __('Disable') }}</option>
                                                </select>
                                            </div>
                                           <input type="hidden" name="sid" value="1">
                                     </div>
                                </div>
                            </div>
                           </div>
                           <div class="col-md-12 no-padding">
                             <div class="card-footer">
                                 <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fa fa-dot-circle-o"></i> {{ __('Submit') }}</button>
                                 <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-ban"></i> {{ __('Reset') }} </button>
                             </div>
                             </div>
                            </form>
                         </div> 
                     </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
    </div><!-- /#right-panel -->
    @else
    @include('admin.denied')
    @endif
    <!-- Right Panel -->
   @include('admin.javascript')
</body>
</html>
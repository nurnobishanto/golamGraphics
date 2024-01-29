@if($allsettings->maintenance_mode == 0)
<!DOCTYPE HTML>
<html lang="en">
<head>
<title>{{ $allsettings->site_title }} - {{ __('Contact') }}</title>
@include('meta')
@include('style')
@if($additional->site_google_recaptcha == 1)
{!! RecaptchaV3::initJs() !!}
@endif
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
              <li class="breadcrumb-item text-nowrap active" aria-current="page">{{ __('Contact') }}</li>
            </ol>
          </nav>
        </div>
        <div class="order-lg-1 pr-lg-4 text-center text-lg-left">
          <h1 class="h3 mb-0 text-white">{{ __('Contact') }}</h1>
        </div>
      </div>
      </div>
    </section>
   <!-- Outlet stores-->
    <div class="container px-0" id="map">
      @if(in_array('contact',$top_ads))
      <div class="row">
          <div class="col-lg-12 mt-4" align="center">
             @php echo html_entity_decode($addition_settings->top_ads); @endphp
          </div>
       </div>   
       @endif
      <div class="row">
        <div class="col-lg-5 px-4 px-xl-5 py-5">
          <form method="POST" action="{{ route('contact') }}" id="contact_form"  class="needs-validation mb-3" novalidate>
          @csrf
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="cf-name">{{ __('Name') }} <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="from_name" name="from_name" data-bvalidator="required">
                  <div class="invalid-feedback">{{ __('Please fill in you full name') }}</div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="cf-email">{{ __('Email') }} <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" id="cf-email" name="from_email" data-bvalidator="email,required">
                  <div class="invalid-feedback">{{ __('Please provide valid email address') }}</div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="cf-message">{{ __('Message') }} <span class="text-danger">*</span></label>
              <textarea class="form-control" id="cf-message" rows="6" name="message_text" data-bvalidator="required"></textarea>
              <div class="invalid-feedback">{{ __('Please write a message') }}</div>
            </div>
            @if($additional->site_google_recaptcha == 1)
            <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                            {!! RecaptchaV3::field('register') !!}
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                        </div>
            @endif
            <button class="btn btn-primary" type="submit">{{ __('Submit') }}</button>
          </form>
        </div>
        <div class="col-lg-7 px-4 px-xl-5 py-5">
           <div class="row">
           @if($additional->site_address_display == 1)
           <div class="col-md-6 mb-grid-gutter">
           <div class="card">
            <div class="card-body text-center"><i class="dwg-location h3 mt-2 mb-4 text-primary"></i>
              <h3 class="h6 mb-2">{{ __('Office Address') }}</h3>
              <p class="font-size-sm text-muted">{{ $allsettings->office_address }}</p>
             </div>
            </div>
            </div>
            @endif
            @if($additional->site_email_display == 1)
            <div class="col-md-6 mb-grid-gutter"><a class="card" href="mailto:{{ $allsettings->office_email }}">
          <div class="card-body text-center"><i class="dwg-mail h3 mt-2 mb-4 text-primary"></i>
              <h3 class="h6 mb-3">{{ __('Email') }}</h3>
              <p class="font-size-sm text-muted">{{ $allsettings->office_email }}</p>
             </div>
            </a>
          </div>
          @endif
          @if($additional->site_phone_display == 1)
          <div class="col-md-6 mb-grid-gutter"><a class="card" href="tel:{{ $allsettings->office_phone }}">
            <div class="card-body text-center"><i class="dwg-phone h3 mt-2 mb-4 text-primary"></i>
              <h3 class="h6 mb-2">{{ __('Phone Number') }}</h3>
              <p class="font-size-sm text-muted">{{ $allsettings->office_phone }}</p>
            </div></a>
            </div>
            @endif
          </div>
        </div>
        </div>
        @if(in_array('contact',$bottom_ads))
        <div class="row">
          <div class="col-lg-12 mt-2 mb-2" align="center">
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
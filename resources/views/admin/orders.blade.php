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
    @if(in_array('items',$avilable)) 
    <div id="right-panel" class="right-panel">

        
                       @include('admin.header')
                       

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>{{ __('Orders') }}</h1>
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
                    <div class="col-md-3 ml-auto">
                    <form action="{{ route('admin.orders') }}" method="post" id="setting_form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                    <input id="search" name="search" type="text" class="move-bars" value="{{ $search }}" placeholder="{{ __('Order ID') }} OR {{ __('Buyer') }}">
                    
                    <button type="submit" name="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Search
                    </button>
                    
                    </div>
                    </form>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">{{ __('Orders') }}</strong>
                            </div>
                            <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sno') }}</th>
                                            <th>{{ __('Order ID') }}</th>
                                            <th>{{ __('Buyer') }}</th>
                                            <th>{{ __('Vendor Amount') }}</th>
                                            <th>{{ __('Admin Amount') }}</th>
                                            <th>{{ __('Processing Fee') }}</th>
                                            <th>VAT Fee</th>
                                            <th>{{ __('Payment Type') }}</th>
                                            <th>{{ __('Payment ID') }}</th>
                                            <th>{{ __('Complete Payment? (Localbank Only)') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($itemData['item'] as $order)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $order->purchase_token }} </td>
                                            <td><a href="{{ URL::to('/user') }}/{{ $order->username }}" target="_blank" class="blue-color">{{ $order->username }}</a></td>
                                            <td>{{ Helper::plan_format($allsettings->site_currency_position,$order->vendor_amount,$order->currency_type) }} </td>
                                            <td>{{ Helper::plan_format($allsettings->site_currency_position,$order->admin_amount,$order->currency_type) }}</td>
                                            <td>{{ Helper::plan_format($allsettings->site_currency_position,$order->processing_fee,$order->currency_type) }}</td>
                                            <td>{{ Helper::plan_format($allsettings->site_currency_position,$order->vat_price,$order->currency_type) }}</td>
                                            <td>{{ $order->payment_type }}</td>
                                            <td>@if($order->payment_token != ""){{ $order->payment_token }}@else <span>---</span> @endif</td>
                                            <td>@if($order->payment_type == 'localbank' && $order->payment_status == 'pending') <a href="orders/{{ base64_encode($order->purchase_token) }}" class="blue-color"onClick="return confirm('{{ __('Are you sure click to complete payment') }}?');">{{ __('Click to Complete Payment') }}?</a> @else <span>---</span> @endif</td>
                                            <td>
                                            <a href="order-details/{{ $order->purchase_token }}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>&nbsp; {{ __('View More') }}</a>
                                            @if($demo_mode == 'on') 
                                            <a href="demo-mode" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp; {{ __('Delete') }}</a>
                                            @else
                                            <a href="orders/delete/{{ $encrypter->encrypt($order->purchase_token) }}" class="btn btn-danger btn-sm" onClick="return confirm('{{ __('Are you sure you want to delete') }}?');"><i class="fa fa-trash"></i>&nbsp;{{ __('Delete') }}</a>
                                            @endif
                                            </td>
                                        </tr>
                                        
                                        @php $no++; @endphp
                                   @endforeach     
                                        
                                    </tbody>
                                </table>
                                <div>
                                {{ $itemData['item']->links('pagination::bootstrap-4') }}
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

 
                </div>
            </div>
        </div>


    </div>
    @else
    @include('admin.denied')
    @endif
    


   @include('admin.javascript')


</body>

</html>

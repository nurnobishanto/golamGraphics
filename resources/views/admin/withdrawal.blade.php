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
    @if(in_array('withdrawal',$avilable))
    <div id="right-panel" class="right-panel">

        
                       @include('admin.header')
                       

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>{{ __('Withdrawal Request') }}</h1>
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

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">{{ __('Withdrawal Request') }}</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sno') }}</th>
                                            <th>{{ __('User Name') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Withdrawal Type') }}</th>
                                            <th>{{ __('Email') }} / {{ __('Bank Details') }} / {{ __('UPI') }} / {{ __('Paytm') }} / {{ __('Crypto Address') }}</th>
                                            <?php /*?><th>{{ __('Paypal Email') }}</th>
                                            <th>{{ __('Stripe Email') }}</th>
                                            <th>{{ __('Paystack Email') }}</th>
                                            <th width="200">{{ __('Bank Details') }}</th><?php */?>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($itemData['item'] as $withdraw)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td><a href="{{ URL::to('/user') }}/{{ $withdraw->username }}" target="_blank" class="blue-color">{{ $withdraw->username }}</a></td>
                                            <td>{{ $withdraw->wd_date }} </td>
                                            <td>{{ $withdraw->withdraw_type }} </td>
                                            <td>
                                            @if($withdraw->paypal_email != "") {{ $withdraw->paypal_email }}@endif
                                            @if($withdraw->stripe_email != "") {{ $withdraw->stripe_email }}@endif
                                            @if($withdraw->paystack_email != "") {{ $withdraw->paystack_email }}@endif
                                            @if($withdraw->payfast_email != "") {{ $withdraw->payfast_email }}@endif
                                            @if($withdraw->skrill_email != ""){{ $withdraw->skrill_email }}@endif
                                            @if($withdraw->upi_id != ""){{ $withdraw->upi_id }}@endif
                                            @if($withdraw->paytm_no != ""){{ $withdraw->paytm_no }}@endif
                                            @if($withdraw->bank_details != "") @php echo nl2br($withdraw->bank_details); @endphp @endif
                                            @if($withdraw->crypto_address != ""){{ $withdraw->crypto_address }}@endif
                                            </td>
                                            <td>{{ Helper::plan_format($allsettings->site_currency_position,$withdraw->wd_amount,$allsettings->site_currency_symbol) }}</td>
                                            <td>
                                            <span class="badge badge-success">{{ $withdraw->wd_status }}</span>
                                            </td>
                                            <td>
                                            @if($withdraw->wd_status == 'pending')
                                            <a href="{{ URL::to('/admin/withdrawal') }}/{{ $withdraw->wd_id }}/{{ $withdraw->wd_user_id }}" class="btn btn-success btn-sm" onClick="return confirm('{{ __('Are you sure you want to complete withdrawal request') }}?');"><i class="fa fa-money"></i>&nbsp; {{ __('Complete Withdrawal') }}</a>
                                            @endif
                                            @if($demo_mode == 'on') 
                                            <a href="{{ URL::to('/admin/demo-mode') }}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;{{ __('Delete') }}</a>
                                            @else
                                            <a href="{{ URL::to('/admin/withdrawal') }}/{{ $withdraw->wd_id }}" class="btn btn-danger btn-sm" onClick="return confirm('{{ __('Are you sure you want to delete') }}?');"><i class="fa fa-trash"></i>&nbsp;{{ __('Delete') }}</a>@endif
                                            </td>
                                        </tr>
                                        
                                        @php $no++; @endphp
                                   @endforeach     
                                        
                                    </tbody>
                                </table>
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

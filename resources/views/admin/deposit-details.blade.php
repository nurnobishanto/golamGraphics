@include('version')
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
    @if(in_array('deposit',$avilable))
    <div id="right-panel" class="right-panel">

        
                       @include('admin.header')
                       

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>{{ __('Deposit Details') }}</h1>
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
        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">{{ __('Deposit Details') }}</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sno') }}</th>
                                            <th>{{ __('Depositor') }}</th>
                                            <th>{{ __('Deposit Id') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Payment ID') }}</th>
                                            <th>{{ __('Payment Type') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Complete Payment? (Localbank Only)') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($depositData['view'] as $deposit)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td><a href="{{ url('/user') }}/{{ $deposit->username }}" target="_blank" class="black-color">{{ $deposit->username }}</a> </td>
                                            <td>{{ $deposit->purchase_token }}</td>
                                            <td>{{ Helper::plan_format($allsettings->site_currency_position,$deposit->deposit_price,$deposit->currency_type) }}</td>
                                            <td>@if($deposit->payment_token != ""){{ $deposit->payment_token }}@else<span>---</span>@endif</td>
                                            <td>{{ $deposit->payment_type }}</td>
                                            <td>{{ date('d M Y', strtotime($deposit->payment_date)) }}</td>
                                            <td>@if($deposit->payment_type == 'localbank' && $deposit->payment_status == 'pending') <a href="{{ url('/admin/deposit-payment') }}/{{ base64_encode($deposit->purchase_token) }}" class="blue-color"onClick="return confirm('{{ __('Are you sure click to complete payment') }}?');">{{ __('Click to Complete Payment') }}?</a> @else <span>---</span> @endif</td>
                                            
                                            <td>@if($deposit->payment_status == 'completed') <span class="badge badge-success">{{ __('Completed') }}</span> @else <span class="badge badge-danger">{{ __('Pending') }}</span> @endif</td>
                                            <td>
                                            @if($demo_mode == 'on') 
                                            <a href="demo-mode" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;{{ __('Delete') }}</a>
                                            @else
                                            <a href="deposit-details/{{ $deposit->dd_id }}" class="btn btn-danger btn-sm" onClick="return confirm('{{ __('Are you sure you want to delete?') }}');">
                                            <i class="fa fa-trash"></i>&nbsp;{{ __('Delete') }}</a>
                                            @endif
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

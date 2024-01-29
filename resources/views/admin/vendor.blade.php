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
    @if(Auth::user()->id == 1)
    <div id="right-panel" class="right-panel">

        
                       @include('admin.header')
                       

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>{{ __('Vendors') }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <a href="{{ url('/admin/add-vendor') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> {{ __('Add Vendor') }}</a>
                        </ol>
                    </div>
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
                    <div class="col-md-3 ml-auto">
                    <form action="{{ route('admin.vendor') }}" method="post" id="setting_form" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                    <input id="search" name="search" type="text" class="move-bars" value="{{ $search }}" placeholder="{{ __('Name') }} OR {{ __('Email') }}">
                    
                    <button type="submit" name="submit" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Search
                    </button>
                    
                    </div>
                    </form>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">{{ __('Vendors') }}</strong>
                            </div>
                            <div class="card-body">
                                <table id="example" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sno') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            @if($addition_settings->subscription_mode == 0)
                                            <th>{{ __('Email') }}</th>
                                            @endif
                                            <th>{{ __('Photo') }}</th>
                                            @if($addition_settings->subscription_mode == 1)
                                            <th>{{ __('Membership') }}</th>
                                            <?php /*?><th>{{ __('Subscription Id') }}<br/><small>({{ __('localbank only') }})</small></th>
                                            <th>{{ __('Payment Approval') }}?<br/><small>({{ __('localbank only') }})</small></th><?php */?>
                                            <th>{{ __('Account Verification') }}</th>
                                            @endif
                                            <th>{{ __('Email Verified') }}</th>
                                            <th>{{ __('User Type') }}</th>
                                            <th>{{ __('Earnings') }}</th>
                                            @if($addition_settings->subscription_mode == 1)
                                            <th>{{ __('Subscription Details') }}</th> 
                                            <th>{{ __('Payment Status') }}</th>
                                            @endif
                                            @if($additional->conversation_mode == 1)
                                            <th>{{ __('Conversation') }}</th>
                                            @endif
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                     @php $no = 1; @endphp
                                    @foreach($itemData['item'] as $user)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $user->username }}</td>
                                            @if($addition_settings->subscription_mode == 0)
                                            <td>{{ $user->email }}</td>
                                            @endif
                                            <td>@if($user->user_photo != '') <img class="lazy userphoto" width="50" height="50" src="{{ url('/') }}/public/storage/users/{{ $user->user_photo }}"  alt="{{ $user->name }}" />@else <img class="lazy userphoto" width="50" height="50" src="{{ url('/') }}/public/img/no-user.png"  alt="{{ $user->name }}" />  @endif</td>
                                            @if($addition_settings->subscription_mode == 1)
                                            <td>{{ $user->user_subscr_type }} @if($user->user_subscr_date < date('Y-m-d'))<span class="badge badge-danger">{{ __('expired') }}</span>@endif</td>
                                            <?php /*?><td>@if($user->user_purchase_token != '') {{ $user->user_purchase_token }} @else <span>---</span> @endif</td>
                                            <td>@if($user->user_purchase_token != '') <a href="{{ URL::to('/admin/customer') }}/{{ $user->user_token }}/{{ $user->user_subscr_id }}" class="btn btn-success btn-sm" onClick="return confirm('{{ __('Are you sure you want to complete subscription payment') }}?');"><i class="fa fa-money"></i>&nbsp; {{ __('Waiting for approval') }}</a> @else <span>---</span> @endif</td><?php */?>
                                            <td>@if($user->user_document_verified == 1) <span class="badge badge-success">{{ __('verified') }}</span> @else <span class="badge badge-danger">{{ __('unverified') }}</span> @endif</td>
                                            @endif
                                            <td>@if($user->verified == 1) <span class="badge badge-success">{{ __('verified') }}</span> @else <span class="badge badge-danger">{{ __('unverified') }}</span> @endif</td>
                                            <td>@if($user->exclusive_author == 1) <span class="badge badge-success">{{ __('Exclusive User') }}</span> @else <span class="badge badge-danger">{{ __('Non Exclusive User') }}</span> @endif</td>
                                            
                                            <td>{{ Helper::plan_format($allsettings->site_currency_position,$user->earnings,$allsettings->site_currency_symbol) }}</td>
                                            @if($addition_settings->subscription_mode == 1)
                                            <td><a href="subscription-payment-details/{{ $user->user_token }}" class="btn btn-info btn-sm"><i class="fa fa-id-card"></i> {{ __('view') }}</a></td>
                                            <td>@if($user->user_subscr_payment_status == 'completed') <span class="badge badge-success">{{ __('Completed') }}</span> @else <span class="badge badge-danger">{{ __('Pending') }}</span> @endif</td>
                                            @endif
                                            @if($additional->conversation_mode == 1)
                                            <td><a href="conversation/{{ $user->username }}" class="btn btn-primary btn-sm"><i class="fa fa-comments-o"></i> {{ __('Conversation') }}</a></td>
                                            @endif
                                            <td><a href="edit-vendor/{{ $user->user_token }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>&nbsp; {{ __('Edit') }}</a>
                                            @if($demo_mode == 'on') 
                                            <a href="demo-mode" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;{{ __('Delete') }}</a>
                                            @else 
                                            <a href="vendor/{{ $user->user_token }}" class="btn btn-danger btn-sm" onClick="return confirm('{{ __('Are you sure you want to delete') }}?');"><i class="fa fa-trash"></i>&nbsp;{{ __('Delete') }}</a>@endif
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

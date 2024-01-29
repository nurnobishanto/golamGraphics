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
                        <h1>{{ __('Country Settings') }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <a href="{{ url('/admin/add-country') }}" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> {{ __('Add Country') }}</a>
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
                <div class="col-md-12">
                  <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Default VAT(%)</strong>
                            </div>
                             <div class="card-body">
                                 @if($demo_mode == 'on')
                                 @include('admin.demo-mode')
                                 @else
                                 <form action="{{ route('admin.vat') }}" method="post" id="setting_form" enctype="multipart/form-data">
                                 {{ csrf_field() }}
                                 @endif
                                  
                                 <div class="col-md-6">
                                 
                                   
                                  <div class="form-group">
                                                <label for="name" class="control-label mb-1">Default VAT(%) </label>
                                                <input id="default_vat_price" name="default_vat_price" type="text" class="form-control" value="{{ $additional->default_vat_price }}" data-bvalidator="number,min[0],required">
                                            </div> 
                                            
                                      </div>
                                      
                                      <div class="col-md-6">      
                                            
                                             
                                        </div>
                                        
                                        
                                        <div class="col-md-12">    
                                
                                                        <button type="submit" name="submit" class="btn btn-primary btn-sm">
                                                            <i class="fa fa-dot-circle-o"></i> {{ __('Submit') }}
                                                        </button>
                                                       
                                                 
                                                 </div>   
                     </form>
                     </div>
                     </div>                   
                </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">{{ __('Country') }}</strong>
                            </div>
                            <div class="card-body">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Sno') }}</th>
                                            <th>{{ __('Name') }}</th>
                                            <th>VAT(%)</th>
                                            <th>{{ __('Flag') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($country['data'] as $country)
                                        <tr>
                                            <td>{{ $no }}</td>
                                            <td>{{ $country->country_name }}</td>
                                            <td>{{ $country->vat_price }}%</td>
                                            <td>@if($country->country_badges != '')
                                                <img  class="lazy" width="50" height="50" src="{{ url('/') }}/public/storage/flag/{{ $country->country_badges }}"  />@else <img class="lazy" width="50" height="50" src="{{ url('/') }}/public/img/no-image.jpg"  />  @endif</td>
                                            
                                            <td><a href="edit-country/{{ $country->country_id }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i>&nbsp; {{ __('Edit') }}</a> 
                                            @if($demo_mode == 'on') 
                                            <a href="demo-mode" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>&nbsp;{{ __('Delete') }}</a>
                                            @else
                                            <a href="country-settings/{{ $country->country_id }}" class="btn btn-danger btn-sm" onClick="return confirm('{{ __('Are you sure you want to delete') }}?');"><i class="fa fa-trash"></i>&nbsp;{{ __('Delete') }}</a>@endif
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

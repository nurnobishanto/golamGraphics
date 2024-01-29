@if($additional->header_layout == 'layout_one')
@include('header-layout-one')
@else
@include('header-layout-two')
@endif
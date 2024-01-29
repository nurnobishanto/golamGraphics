<header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">
                       <code>{{ Helper::Current_Version() }}</code> 
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="javascript:void();" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            
                            
                        @if(Auth::user()->user_photo != '')
                        <img class="lazy user-avatar rounded-circle" width="32" height="32" src="{{ url('/') }}/public/storage/users/{{ Auth::user()->user_photo }}"  alt="{{ Auth::user()->name }}" />@else <img class="lazy user-avatar rounded-circle" width="32" height="32" src="{{ url('/') }}/public/img/no-user.png"  alt="{{ Auth::user()->name }}"/>  @endif
                        
                        </a>
<div class="user-menu dropdown-menu">
                            <a class="nav-link" href="{{ url('/admin/edit-profile') }}"><i class="fa fa-user"></i> {{ __('My Profile') }}</a>
                            @if(in_array('settings',$avilable)) 
                            <a class="nav-link" href="{{ url('/admin/general-settings') }}"><i class="fa fa-cog"></i> {{ __('Settings') }}</a>
                            @endif
                            <a class="nav-link" href="{{ url('/logout') }}"><i class="fa fa-power-off"></i> {{ __('Logout') }}</a>
                        </div>
                        </div>
                        @if($allsettings->multi_language == 1)
                       <div class="language-select dropdown" id="language-select">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"  id="language" aria-haspopup="true" aria-expanded="true">
                            <span class="fa fa-language"></span> {{ $current_locale }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="language">
                            @foreach($available_locales as $locale_name => $available_locale)
                            <div class="dropdown-item">
                                <a href="{{ URL::to('/language') }}/{{ $available_locale }}">{{ $locale_name }}</a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                 @endif 
                    

                </div>
            </div>

        </header>
        
                    
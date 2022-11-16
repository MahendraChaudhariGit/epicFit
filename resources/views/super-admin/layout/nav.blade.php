<!-- sidebar -->
<div class="sidebar app-aside" id="sidebar">
    <div class="sidebar-container perfect-scrollbar">
        <nav class="">
            <!-- start: MAIN MENU TOGGLER BUTTON -->
            <div class="navigation-toggler pull-right hidden-sm hidden-xs">
                <a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg"
                    class="btn btn-navbar sidebar-toggle" data-toggle-class="app-slide-off" data-toggle-target="#app"
                    data-toggle-click-outside="#sidebar">
                    <i class="clip-chevron-left"></i>
                    <i class="clip-chevron-right"></i>
                </a>
                <a href="#" class="sidebar-toggler pull-right visible-md visible-lg"
                    data-toggle-class="app-sidebar-closed" data-toggle-target="#app">
                    <i class="clip-chevron-left"></i>
                    <i class="clip-chevron-right"></i>
                </a>
                <a class="pull-right menu-toggler visible-xs-block" id="menu-toggler" data-toggle="collapse"
                    href=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="ti-view-grid"></i>
                </a>
            </div>
            <br>
            <br>
            <!-- end: MAIN MENU TOGGLER BUTTON -->

            <!-- start: MAIN NAVIGATION MENU -->
            <ul class="main-navigation-menu">
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.dashboard') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-qrcode"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Dashboard </span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.businessAccount.index') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-qrcode"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Business Accounts </span>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('users-limit.index') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-qrcode"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Users Limit </span>
                            </div>
                        </div>
                    </a>
                </li> 
                <li
                    class="{{ in_array(Route::getCurrentRoute()->uri(), ['epic-super-admin/category/list', 'epic-super-admin/add/category', 'epic-super-admin/category']) ? 'active open' : '' }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-qrcode"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Gallery </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">

                        <li
                            class="{{ in_array(Route::getCurrentRoute()->uri(), ['epic-super-admin/category/list']) ? 'active' : '' }}">
                            <a href="{{ route('superadmin.gallery.category.list') }}">
                                <span class="title"> Gallery </span>
                            </a>
                        </li>

                        <li
                            class="{{ in_array(Route::getCurrentRoute()->uri(), ['epic-super-admin/category', 'epic-super-admin/add/category']) ? 'active' : '' }}">
                            <a href="{{ route('superadmin.add.gallery.category') }}">
                                <span class="title">Add Category </span>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="{{ in_array(Route::getCurrentRoute()->uri(), 
                    array(
                        'pipeline-process','pipeline-process/dashboard',
                        'pipeline-process/projects','pipeline-process/my-task',
                        'pipeline-process/calendar','pipeline-process/project-store',
    
                    ))?'active open':'' }}">
                        <a href="{{ url('pipeline-process') }}">
                            <div class="item-content">
                                <div class="item-media">
                                 <i class="fa fa-cutlery"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Pipeline Process</span>
                                    <i class="icon-arrow"></i>
                                </div>
                            </div>
                        </a>
                        <ul class="sub-menu sub-menu-new ">
                            <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('pipeline-process/dashboard'))?'active open':''}}">
                                <a href="{{ url('pipeline-process/dashboard') }}">
                                    <span class="title"> Dashboard </span>
                                </a>
                            </li> 
                            <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('pipeline-process/projects','pipeline-process/project-store'))?'active open':''}}">
                                <a href="{{ url('pipeline-process/projects') }}">
                                    <span class="title"> Projects </span>
                                </a>
                            </li> 
                             
                             <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('pipeline-process/my-task'))?'active open':''}}">
                                <a href="{{ url('pipeline-process/tasks') }}">
                                    <span class="title"> My Tasks </span>
                                </a>
                            </li>  
                            <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('pipeline-process/calendar'))?'active open':''}}">
                                <a href="{{ url('pipeline-process/calendar') }}">
                                    <span class="title"> Calendar </span>
                                </a>
                            </li> 
                            <li>
                                <a href="javascript:void(0)">
                                    <div class="item-content">
                                        {{-- <div class="item-inner"> --}}
                                            <span class="title"> Favorites</span>
                                            &nbsp;&nbsp;<i class="icon-arrow"></i>
                                        {{-- </div> --}}
                                    </div>
                                </a>
                            <ul class="sub-menu sub-menu-new ">
                                @php
                                    $favorites = App\Models\PipelineProcess\Project::where('favorite',1)->where('user_id',Auth::user()->id)->get()->toArray();
                                @endphp
                                @if (count($favorites)>0)
                                    @foreach ($favorites as $favorite)
                                    <li>
                                        <a href="{{ url('pipeline-process/projects') }}/{{ $favorite['slug'] }}">
                                            <span class="title"> {{ $favorite['name'] }} </span>
                                        </a>
                                    </li>   
                                    @endforeach 
                                @endif
                                
                            </ul>
                        </li> 
                                               
                        </ul>
                    </li>                      
            </ul>
            <!-- end: MAIN NAVIGATION MENU -->
        </nav>
    </div>
</div>
<!-- / sidebar

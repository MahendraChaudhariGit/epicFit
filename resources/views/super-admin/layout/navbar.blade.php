<!--start: TOP NAVBAR -->
<?php
    $user = session()->get('adminData');
?>
<header class="navbar navbar-default navbar-static-top">
    <!-- start: NAVBAR HEADER -->
    <div class="navbar-header visible-xs">
        <a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg" class="btn btn-navbar sidebar-toggle" data-toggle-class="app-slide-off" data-toggle-target="#app" data-toggle-click-outside="#sidebar">
            <i class="ti-align-justify"></i>
        </a>
        <a href="#" class="sidebar-toggler pull-right visible-md visible-lg" data-toggle-class="app-sidebar-closed" data-toggle-target="#app">
            <i class="ti-align-justify"></i>
        </a>
        <a class="pull-right menu-toggler visible-xs-block" id="menu-toggler" data-toggle="collapse" href=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <i class="ti-view-grid"></i>
        </a>
    </div>
    <!-- end: NAVBAR HEADER -->

    <!-- start: NAVBAR COLLAPSE -->
    <div class="navbar-collapse collapse">
        <h1 class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ url('assets/images/epic-icon.png') }}" alt="Epic Trainer" 
                 style="width: 20px;">
                 <div class="logo-text">EPIC <span >Trainer</span></div>
            </a>
        </h1>
        <ul class="nav navbar-right">
            <!-- start: ACTIVITIES DROPDOWN -->
            <li class="dropdown">
                <a href class="dropdown-toggle" data-toggle="dropdown">
                    <i class="ti-check-box"></i> <span>ACTIVITIES</span>
                </a>
                <ul class="dropdown-menu dropdown-light dropdown-messages dropdown-large">
                    <li>
                        <span class="dropdown-header p-l-10"> You have new notifications</span>
                    </li>
                    <li>
                        <div class="drop-down-wrapper ps-container panel-scroll height-330 perfect-scrollbar ps-active-y p-l-10">
                            <div class="list-group no-margin">
                              <div id='notification'>
                               No any Notification
                              </div>
                            </div>
                        </div>
                    </li>
                    
                </ul>
            </li> 
            <!-- end: ACTIVITIES DROPDOWN -->
            <!-- start: USER OPTIONS DROPDOWN -->
            <li class="dropdown current-user">
                <a href class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ dpSrc(isset($user->profile_picture)?$user->profile_picture: '', isset($user->gender) ? $user->gender: '' ) }}" alt="{{ session()->get('adminData')->fullName }}" class="staffProfilePicturePreviewPics">  
    
                    <span class="username">
                        <span data-realtime="{{ session()->get('adminData')->account_type }}FirstName">{{ session()->get('adminData')->name }}</span>
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-dark">
                   {{--  <li>
                        <a href="{{ route('auth.show') }}">
                            My Profile
                        </a>
                    </li> --}}
                    <li>
                        <a href="{{ route('superadmin.logout') }}">
                            Log Out
                        </a>
                    </li>
                </ul>
            </li>
            <!-- end: USER OPTIONS DROPDOWN -->
        </ul>
        <!-- start: MENU TOGGLER FOR MOBILE DEVICES -->
        <div class="close-handle visible-xs-block menu-toggler" data-toggle="collapse" href=".navbar-collapse">
            <div class="arrow-left"></div>
            <div class="arrow-right"></div>
        </div>
        <!-- end: MENU TOGGLER FOR MOBILE DEVICES -->
    </div>
</header>
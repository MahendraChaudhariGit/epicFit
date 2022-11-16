<!--start: TOP NAVBAR -->
<?php
    use App\Staff;
    use App\Client;

    if(Auth::check()) { 
        if(Auth::user()->account_id == 0) 
            $user = Auth::user();
        elseif(Auth::user()->account_type == 'Client')
            $user = Client::find(Auth::user()->account_id);
        else
            $user = Staff::find(Auth::user()->account_id);
    }
    
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
               <!--  <img src="{{ url('assets/images/epic-icon.png') }}" alt="Epic Trainer" 
                 style="width: 20px;"> -->
                 <div class="logo-text">EPIC <span >Trainer</span></div>
            </a>
        </h1>
        <ul class="nav navbar-right">
            <!-- start: MESSAGES DROPDOWN -->
            <!--<li class="dropdown">
                <a href class="dropdown-toggle" data-toggle="dropdown">
                    <span class="dot-badge partition-red"></span> <i class="ti-comment"></i> <span>MESSAGES</span>
                </a>
                <ul class="dropdown-menu dropdown-light dropdown-messages dropdown-large">
                    <li>
                        <span class="dropdown-header"> Unread messages</span>
                    </li>
                    <li>
                        <div class="drop-down-wrapper ps-container">
                            <ul>
                                <li class="unread">
                                    <a href="javascript:;" class="unread">
                                        <div class="clearfix">
                                            <div class="thread-image">
                                                <img src="./assets/images/avatar-2.jpg" alt="">
                                            </div>
                                            <div class="thread-content">
                                                <span class="author">Nicole Bell</span>
                                                <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula...</span>
                                                <span class="time"> Just Now</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" class="unread">
                                        <div class="clearfix">
                                            <div class="thread-image">
                                                <img src="./assets/images/avatar-3.jpg" alt="">
                                            </div>
                                            <div class="thread-content">
                                                <span class="author">Steven Thompson</span>
                                                <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula...</span>
                                                <span class="time">8 hrs</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <div class="clearfix">
                                            <div class="thread-image">
                                                <img src="./assets/images/avatar-5.jpg" alt="">
                                            </div>
                                            <div class="thread-content">
                                                <span class="author">Kenneth Ross</span>
                                                <span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula...</span>
                                                <span class="time">14 hrs</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="view-all">
                        <a href="#">
                            See All
                        </a>
                    </li>
                </ul>
            </li>-->
            <!-- end: MESSAGES DROPDOWN -->
            <!-- start: ACTIVITIES DROPDOWN -->
            <li class="dropdown">
                <a href class="dropdown-toggle" data-toggle="dropdown">
                    <i class="ti-check-box"></i> <span>ACTIVITIES</span>
                </a>
                <ul class="dropdown-menu dropdown-light dropdown-messages dropdown-large">
                    <li>
                        <span class="dropdown-header p-l-10"> You have new notifications</span>
                        <a href="{{url('activities')}}">View All</a>
                    </li>
                    <li>
                        <div class="drop-down-wrapper ps-container panel-scroll height-330 perfect-scrollbar ps-active-y p-l-10">
                            <div class="list-group no-margin">
                              <div id='notification'>
                               {{-- No any Notification --}}
                              </div>
                            </div>
                        </div>
                    </li>
                    
                </ul>
            </li> 
            <!-- end: ACTIVITIES DROPDOWN -->
            <!-- start: LANGUAGE SWITCHER -->
            <!--<li class="dropdown">
                <a href class="dropdown-toggle" data-toggle="dropdown">
                    <i class="ti-world"></i> English
                </a>
                <ul role="menu" class="dropdown-menu dropdown-light fadeInUpShort">
                    <li>
                        <a href="#" class="menu-toggler">
                            Deutsch
                        </a>
                    </li>
                    <li>
                        <a href="#" class="menu-toggler">
                            English
                        </a>
                    </li>
                    <li>
                        <a href="#" class="menu-toggler">
                            Italiano
                        </a>
                    </li>
                </ul>
            </li>-->
            <!-- start: LANGUAGE SWITCHER -->
            <!-- start: USER OPTIONS DROPDOWN -->
            <li class="dropdown current-user">
                <a href class="dropdown-toggle" data-toggle="dropdown">
                    @if(isUserType(['Staff', 'Admin', 'Client']))
                    {{--  <img src="{{ dpSrc(Auth::user()->profilePic, Auth::user()->gender) }}" alt="{{ Auth::user()->fullName }}" class="staffProfilePicturePreviewPics"> --}}
                        @if($user && Auth::user()->account_type == 'Client')
                            <img src="{{ dpSrc(isset($user->profilepic)?$user->profilepic: '', isset($user->gender) ? $user->gender: '' ) }}" alt="{{ Auth::user()->fullName }}" >  
                        @elseif($user)
                            <img src="{{ dpSrc(isset($user->profile_picture)?$user->profile_picture: '', isset($user->gender) ? $user->gender: '' ) }}" alt="{{ Auth::user()->fullName }}" class="staffProfilePicturePreviewPics">  
                        @else
                            <img src="{{ dpSrc() }}" alt="{{ Auth::user()->fullName }}" class="staffProfilePicturePreviewPics">  
                         @endif
                    @else
                        <img src="{{ dpSrc() }}" alt="{{ Auth::user()->fullName }}">
                    @endif
                    <span class="username">
                        <span data-realtime="{{ Auth::user()->account_type }}FirstName">{{ Auth::user()->name }}</span>
                        <i class="fa fa-angle-down" aria-hidden="true"></i>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-dark">
                    <li>
                        <a href="{{ route('auth.show') }}">
                            My Profile
                        </a>
                    </li>
                    <!--<li>
                        <a href="pages_calendar.html">
                            My Calendar
                        </a>
                    </li>
                    <li>
                        <a hef="pages_messages.html">
                            My Messages (3)
                        </a>
                    </li>-->
                    <li>
                        <a href="#" id="lockscreen">
                            Lock Screen
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('logout') }}">
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
    <!--<a class="dropdown-off-sidebar sidebar-mobile-toggler hidden-md hidden-lg" data-toggle-class="app-offsidebar-open" data-toggle-target="#app" data-toggle-click-outside="#off-sidebar">
        &nbsp;
    </a>
    <a class="dropdown-off-sidebar hidden-sm hidden-xs" data-toggle-class="app-offsidebar-open" data-toggle-target="#app" data-toggle-click-outside="#off-sidebar">
        &nbsp;
    </a>-->
    <!-- end: NAVBAR COLLAPSE -->
</header>
<!-- end: TOP NAVBAR-->
<script>
    $(document).ready(function(){
        $('#lockscreen').click(function(e){
            e.preventDefault();
            lockScreen();
        })
    })
</script>
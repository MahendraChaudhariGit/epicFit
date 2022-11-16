<!-- start: TOP NAVBAR -->


<style type="text/css">
    
   .navbar .navbar-right > li.current-user .message-icon{
        display: none;
    }
    .nav li .message-icon{
        background: #f0f2f5 !important;
    }
    header .navbar-collapse .navbar-right > li > .message-icon:hover, header .navbar-collapse .navbar-right > li > .message-icon:focus, header .navbar-collapse .navbar-right > li > .message-icon:active {
    background: #f1e1df !important;
}
</style>
<header class="navbar navbar-default navbar-static-top">
    <!-- start: NAVBAR HEADER -->
    <div class="navbar-header visible-xs">
        <a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg" class="btn btn-navbar sidebar-toggle">
            <i class="ti-align-justify"></i>
        </a>
        <a href="#" class="sidebar-toggler pull-right visible-md visible-lg">
            <i class="ti-align-justify"></i>
        </a>
        <ul class="nav navbar-right">
            <li class="dropdown current-user">
                <a href="javascript:void(0);" class="message-icon">
                    <svg viewBox="0 0 28 28" alt="" class="a8c37x1j ms05siws hwsy1cff b7h9ocf4 fzdkajry" height="20" width="20"><path d="M14 2.042c6.76 0 12 4.952 12 11.64S20.76 25.322 14 25.322a13.091 13.091 0 0 1-3.474-.461.956 .956 0 0 0-.641.047L7.5 25.959a.961.961 0 0 1-1.348-.849l-.065-2.134a.957.957 0 0 0-.322-.684A11.389 11.389 0 0 1 2 13.682C2 6.994 7.24 2.042 14 2.042ZM6.794 17.086a.57.57 0 0 0 .827.758l3.786-2.874a.722.722 0 0 1 .868 0l2.8 2.1a1.8 1.8 0 0 0 2.6-.481l3.525-5.592a.57.57 0 0 0-.827-.758l-3.786 2.874a.722.722 0 0 1-.868 0l-2.8-2.1a1.8 1.8 0 0 0-2.6.481Z"></path>
                        {{-- <span class="count">0</span> --}}
                        <span class="count hidden"></span> 
                    </svg>
                </a>
            </li>
                    <li class="dropdown current-user">
                <a href class="dropdown-toggle" data-toggle="dropdown">
                    
                        <img src="{{ dpSrc(Auth::user()->profilePic, Auth::user()->gender) }}" alt="{{ Auth::user()->fullName }}" class="clientPreviewPics ">
                   
                      
                  
                    <span class="username">
                        <span data-realtime="firstName">{{ Auth::user()->name }}<b class="caret" style="float:right;"></b></span>
                        <!--i class="ti-angle-down"></i-->
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-dark">
                    <li>
                        <a href="{{ route('profile.edit') }}">
                            My Account
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
                    </li>
                    <li>
                        <a href="login_lockscreen.html">
                            Lock Screen
                        </a>
                    </li>-->
                    <li>
                        <a href="{{ url('logout') }}">
                            Log Out
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
<!--         <a class="pull-right menu-toggler visible-xs-block" id="menu-toggler" data-toggle="collapse" href=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <i class="ti-view-grid"></i>
        </a> -->
    </div>
    <!-- end: NAVBAR HEADER -->

    <!-- start: NAVBAR COLLAPSE -->
    <div class="navbar-collapse collapse">
        <h1 class="logo">
            <a href="{{ url('/new-dashboard') }}">
               <!--   <img src="{{ url('result/images/epic-icon.png') }}" alt="Epic Result" style="width: 20px;">  -->
               <div class="logo-text">EPIC <span >Result</span></div>
            </a>
             <a href="#" class="sidebar-toggler pull-right visible-md visible-lg visible-sm">
            <i class="ti-align-justify"></i>
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
                <a href class="dropdown-toggle hide" data-toggle="dropdown">
                    <i class="ti-check-box"></i> <span>ACTIVITIES</span>
                </a>
                <ul class="dropdown-menu dropdown-light dropdown-messages dropdown-large">
                    <li>
                        <span class="dropdown-header"> You have new notifications</span>
                    </li>
                    <li>
                        <div class="drop-down-wrapper ps-container">
                            <div class="list-group no-margin">
                                <a class="media list-group-item" href="">
                                    <img class="img-circle" alt="..." src="{{ asset('images/user.png') }}">
                                    <span class="media-body block no-margin"> Use awesome animate.css <small class="block text-grey">10 minutes ago</small> </span>
                                </a>
                                <a class="media list-group-item" href="">
                                    <span class="media-body block no-margin"> 1.0 initial released <small class="block text-grey">1 hour ago</small> </span>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="view-all">
                        <a href="#">
                            See All
                        </a>
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
                <a href="javascript:void(0);" class="message-icon">
                    <svg viewBox="0 0 28 28" alt="" class="a8c37x1j ms05siws hwsy1cff b7h9ocf4 fzdkajry" height="20" width="20"><path d="M14 2.042c6.76 0 12 4.952 12 11.64S20.76 25.322 14 25.322a13.091 13.091 0 0 1-3.474-.461.956 .956 0 0 0-.641.047L7.5 25.959a.961.961 0 0 1-1.348-.849l-.065-2.134a.957.957 0 0 0-.322-.684A11.389 11.389 0 0 1 2 13.682C2 6.994 7.24 2.042 14 2.042ZM6.794 17.086a.57.57 0 0 0 .827.758l3.786-2.874a.722.722 0 0 1 .868 0l2.8 2.1a1.8 1.8 0 0 0 2.6-.481l3.525-5.592a.57.57 0 0 0-.827-.758l-3.786 2.874a.722.722 0 0 1-.868 0l-2.8-2.1a1.8 1.8 0 0 0-2.6.481Z"></path>
                        <span class="count hidden"></span>
                    </svg>
                </a>
            </li>
            <li class="dropdown current-user">
                <a href class="dropdown-toggle" data-toggle="dropdown">
                        @php
                        $client = \App\Clients::where('id',Auth::user()->account_id)->first();
                        @endphp
                        <img src="{{ dpSrc(Auth::user()->profilePic, $client->gender) }}" alt="{{ Auth::user()->fullName }}" class="clientPreviewPics ">
                   
                      
                  
                    <span class="username">
                        <span data-realtime="firstName">{{ Auth::user()->name }}<b class="caret" style="float:right;"></b></span>
                        <!--i class="ti-angle-down"></i-->
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-dark">
                    <li>
                        <a href="{{ route('profile.edit') }}">
                            My Account
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
                    </li>
                    <li>
                        <a href="login_lockscreen.html">
                            Lock Screen
                        </a>
                    </li>-->
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
<div class="messenger">
   <div>
   
    <div class="chat_list_data">
        
    </div>
            
</div>
</div>

<script>
        window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT")}}';
        console.log(window.laravel_echo_port + 'port');
</script>
<!-- <script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script> -->
<script src="https://epic.testingserver.in:6001/socket.io/socket.io.js"></script>
<script src="{{ url('/js/laravel-echo-setup.js') }}" type="text/javascript"></script>

<script type="text/javascript">
$(".sidebar-mobile-toggler").click(function(){
  $("#sidebarNew").toggle('slide','left',400);
  $('.mobile-sidebar-overlay').addClass('open');
});
$('body').on('click', '.mobile-sidebar-overlay', function(){
    $("#sidebarNew").toggle('slide','left',400);
    $('.mobile-sidebar-overlay').removeClass('open');
});

$(".sidebar-toggler").click(function(){
    $(".sidebar-left-secondary").removeClass("open");
     // $("#sidebarNew").toggle('slide','left',400); 
      $("#sidebarNew").toggleClass("open");
    $(".main-content").toggleClass("open");
});
function loadNotification(){
    $(".chat_list_data").html("");
    $.ajax({
        type:'GET',
        url: BASE_URL + '/header-notifications',
        cache:false,
        contentType: false,
        processData: false,
        success: (data) => {
            if(data.count > 0){
                $('.current-user > .message-icon > .count').removeClass('hidden');
                $('.current-user > .message-icon > .count').text(data.count);

                //append chat users
                $(".chat_list_data").append(data.html);
                //append chat users - end
            }else{
                $(".chat_list_data").append("<p>There is no data available</p>");
                // $('.current-user > .message-icon > .count').text('0');
            }
        },
        error: function(data){
            console.log(data);
        }
    });
 }

    //listen for socket event
    var i = 0;
    window.Echo.channel('social-user-chat')
     .listen('.UserEvent', (data) => {
        if ( typeof loadNotification == 'function' ) { 
            loadNotification();
        }
        
        if ( typeof fetchNewMessages == 'function' ) { 
            fetchNewMessages();
        }
    });
    //listen for socket event - end
    

  $(".messenger").hide();
$(document).ready( function () {
 $(".message-icon").click(function(){
    $(".messenger").toggle();
   /* if($('.current-user > .message-icon > .count').text() > 0 ){
        $(".messenger").show();
    }else{
        $(".messenger").toggle();
    }*/
    
});
});

$(document).ready(function (e) {
loadNotification();
});
</script>

<!-- end: TOP NAVBAR -->
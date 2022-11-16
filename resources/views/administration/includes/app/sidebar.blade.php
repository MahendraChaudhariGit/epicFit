<!-- sidebar -->
<div class="sidebar app-aside" id="sidebar">
    <div class="sidebar-container perfect-scrollbar">
        <nav class="">
            <!-- start: MAIN MENU TOGGLER BUTTON -->
            <div class="navigation-toggler pull-right hidden-sm hidden-xs">
                <a href="#" class="sidebar-mobile-toggler pull-left hidden-md hidden-lg" class="btn btn-navbar sidebar-toggle" data-toggle-class="app-slide-off" data-toggle-target="#app" data-toggle-click-outside="#sidebar">
                    <i class="clip-chevron-left"></i>
                    <i class="clip-chevron-right"></i>
                </a>
                <a href="#" class="sidebar-toggler pull-right visible-md visible-lg" data-toggle-class="app-sidebar-closed" data-toggle-target="#app">
                    <i class="clip-chevron-left"></i>
                    <i class="clip-chevron-right"></i>
                </a>
                <a class="pull-right menu-toggler visible-xs-block" id="menu-toggler" data-toggle="collapse" href=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="ti-view-grid"></i>
                </a>
            </div>
            <br>
            <br>
            <!-- end: MAIN MENU TOGGLER BUTTON -->

            <!-- start: MAIN NAVIGATION MENU -->
            <ul class="main-navigation-menu">
                <li class="active open">
                    <a href="index.html">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-home"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Dashboard </span>
                            </div>
                        </div>
                    </a>
                </li>

                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> User Management </span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ url('administration/dashboard') }}">
                                <span class="title"> Dashboard </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('administration/access/users') }}">
                                <span class="title"> Users </span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="clip-cog-2"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> UI Elements </span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="ui_elements.html">
                                <span class="title"> Elements </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_buttons.html">
                                <span class="title"> Buttons </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_links.html">
                                <span class="title"> Links </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_icons.html">
                                <span class="title"> Font Awesome Icons </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_line_icons.html">
                                <span class="title"> Linear Icons </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_modals.html">
                                <span class="title"> Modals </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_toggle.html">
                                <span class="title"> Toggle </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_tabs_accordions.html">
                                <span class="title"> Tabs &amp; Accordions </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_panels.html">
                                <span class="title"> Panels </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_notifications.html">
                                <span class="title"> Notifications </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_treeview.html">
                                <span class="title"> Treeview </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_media.html">
                                <span class="title"> Media Object </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_nestable.html">
                                <span class="title"> Nestable List </span>
                            </a>
                        </li>
                        <li>
                            <a href="ui_typography.html">
                                <span class="title"> Typography </span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="clip-grid-6"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Tables </span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="table_basic.html">
                                <span class="title">Basic Tables</span>
                            </a>
                        </li>
                        <li>
                            <a href="table_responsive.html">
                                <span class="title">Responsive Tables</span>
                            </a>
                        </li>
                        <li>
                            <a href="table_data.html">
                                <span class="title">Advanced Data Tables</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="clip-pencil"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Forms </span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="form_elements.html">
                                <span class="title">Form Elements</span>
                            </a>
                        </li>
                        <li>
                            <a href="form_text_editor.html">
                                <span class="title">Text Editor</span>
                            </a>
                        </li>
                        <li>
                            <a href="form_wizard.html">
                                <span class="title">Form Wizard</span>
                            </a>
                        </li>
                        <li>
                            <a href="form_validation.html">
                                <span class="title">Form Validation</span>
                            </a>
                        </li>
                        <li>
                            <a href="form_image_cropping.html">
                                <span class="title">Image Cropping</span>
                            </a>
                        </li>
                        <li>
                            <a href="form_multiple_upload.html">
                                <span class="title">Multiple File Upload</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Login </span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="login_signin.html">
                                <span class="title"> Login Form </span>
                            </a>
                        </li>
                        <li>
                            <a href="login_registration.html">
                                <span class="title"> Registration Form </span>
                            </a>
                        </li>
                        <li>
                            <a href="login_forgot.html">
                                <span class="title"> Forgot Password Form </span>
                            </a>
                        </li>
                        <li>
                            <a href="login_lockscreen.html">
                                <span class="title">Lock Screen</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="clip-file"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Pages </span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="pages_user_profile.html">
                                <span class="title">User Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="pages_invoice.html">
                                <span class="title">Invoice</span>
                            </a>
                        </li>
                        <li>
                            <a href="pages_timeline.html">
                                <span class="title">Timeline</span>
                            </a>
                        </li>
                        <li>
                            <a href="pages_calendar.html">
                                <span class="title">Calendar</span>
                            </a>
                        </li>
                        <li>
                            <a href="pages_messages.html">
                                <span class="title">Messages</span>
                            </a>
                        </li>
                        <li>
                            <a href="pages_blank_page.html">
                                <span class="title">Blank Page</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="clip-attachment-2"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Utilities </span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="utilities_search_result.html">
                                <span class="title">Search Results</span>
                            </a>
                        </li>
                        <li>
                            <a href="utilities_error_404.html">
                                <span class="title">Error 404</span>
                            </a>
                        </li>
                        <li>
                            <a href="utilities_error_500.html">
                                <span class="title">Error 500</span>
                            </a>
                        </li>
                        <li>
                            <a href="utilities_pricing_table.html">
                                <span class="title">Pricing Table</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-folder"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> 3 Level Menu </span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="javascript:;">
                                <span>Item 1</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="#">
                                        Sample Link 1
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Sample Link 2
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Sample Link 3
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Item 2</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="#">
                                        Sample Link 1
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Sample Link 2
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Sample Link 3
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Item 3</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="#">
                                        Sample Link 1
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Sample Link 2
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Sample Link 3
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-folder"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> 4 Level Menu </span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="javascript:;">
                                <span>Item 1</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        <span>Sample Link 1</span> <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span>Sample Link 2</span> <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span>Sample Link 3</span> <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Item 2</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        <span>Sample Link 1</span> <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span>Sample Link 2</span> <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span>Sample Link 3</span> <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Item 3</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="javascript:;">
                                        <span>Sample Link 1</span> <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span>Sample Link 2</span> <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span>Sample Link 3</span> <i class="icon-arrow"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Sample Link 1
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 2
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Sample Link 3
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="maps.html">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-location-pin"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Maps </span>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="charts.html">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="clip-bars"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Charts </span>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
            <!-- end: MAIN NAVIGATION MENU -->
        </nav>
    </div>
</div>
<!-- / sidebar -->
<!DOCTYPE html>
<!-- Template Name: Clip-One - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.4 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
	<!--<![endif]-->
	<!-- start: HEAD -->
	<head>
		<title>Epic Trainer</title>
		<!-- start: META -->
		<meta charset="utf-8" />
		<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta content="" name="description" />
		<meta content="" name="author" />
		<!-- end: META -->
		<!-- start: MAIN CSS -->
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/bootstrap/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/font-awesome/css/font-awesome.min.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/fonts/style.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/css/main.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/css/main-responsive.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/iCheck/skins/all.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/css/theme_light.css') }}" type="text/css" id="skin_color">
		<link rel="stylesheet" href="{{ asset('co/assets/css/print.css') }}" type="text/css" media="print"/>
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/fullcalendar/fullcalendar/fullcalendar.css') }}">
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/datepicker/css/datepicker.css') }}">

		<link rel="stylesheet" type="text/css" href="{{ asset('co/assets/plugins/select2/select2.css') }}" />
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/DataTables/media/css/DT_bootstrap.css') }}" />


		<!--[if IE 7]>
		<link rel="stylesheet" href="{{ asset('co/assets/plugins/font-awesome/css/font-awesome-ie7.min.css') }}">
		<![endif]-->
		<!-- end: MAIN CSS -->
		<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
		<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
		<link rel="shortcut icon" href="favicon.ico" />
		<style type="text/css">
		.swMain > ul li > a.selected .stepNumber {
    		border-color: #ff4401;/*e85801 */
		}
		.swMain > ul li > a.selected:before, .swMain li > a.done:before {
    		border-color: #ff4401;
		}
		.swMain ul li > a.done .stepNumber {
    		border-color: #ff4401;
    		background-color: #ff4401;
    	}
		ul.main-navigation-menu > li.active > a {
    		background: #ff4401 !important;
		}
		.nav > li.dropdown .dropdown-toggle .badge {
    		background-color: #ff4401;
    		border: none;
		}
		ul.main-navigation-menu > li a > i {
		    color: #ff4401;
		    font-weight: normal;
		}
		.badge-new {
    		background-color: #ff4401;
		}

		.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    		color: #555555;
		}
		.form-control:focus {
    		border-color: #ddd;
    		box-shadow: none;
    	}
    	.pagination>li>a, .pagination>li>span {
		    color: #555555;
		}
    	.pagination>.active>a, .pagination>.active>span, .pagination>.active>a:hover, .pagination>.active>span:hover, .pagination>.active>a:focus, .pagination>.active>span:focus {
		    background-color: #555555;
		    border-color:#555555;

		}
		.pagination>li>a:hover, .pagination>li>span:hover, .pagination>li>a:focus, .pagination>li>span:focus {
    		color: #555555;
		}
    	@media (max-width: 767px){
    		#bodyImg {display: none;}
    		ul.epic-mobile-tab li{
    			width:100%;
    		}
    		.filter-clients {
    			margin-top: 15px;
    			width: 100%;
    			border-radius: 0px;
    		}
    		.navbar-tools {
    			margin-left: -45px;
    		}
    		/*
			.main-container {
		    	margin-top: 150px;
			}*/
		}
		@media (min-width: 768px){
			.form-horizontal #wizard .control-label {
				text-align: left;
			}
		}
		</style>
	</head>
	<!-- end: HEAD -->
	<!-- start: BODY -->
	<body>
		<!-- start: HEADER -->
		<div class="navbar navbar-inverse navbar-fixed-top">
			<!-- start: TOP NAVIGATION CONTAINER -->
			<div class="container">
				<div class="navbar-header">
					<!-- start: RESPONSIVE MENU TOGGLER -->
					<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
						<span class="clip-list-2"></span>
					</button>
					<!-- end: RESPONSIVE MENU TOGGLER -->
					<!-- start: LOGO -->
					<img class="ep-logo" src="{{ asset('co/assets/images/eplogo.png') }}">
					<!--<a class="navbar-brand" href="index.html">
						CLIP<i class="clip-clip"></i>ONE
					</a>-->
					<!-- end: LOGO -->
				</div>
				<div class="navbar-tools">
					<!-- start: TOP NAVIGATION MENU -->
					<ul class="nav navbar-right">
						<!-- start: TO-DO DROPDOWN -->
						<li class="dropdown">
							<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
								<i class="fa fa-plus"></i>
								<span class="username">Add</span>
								<i class="clip-chevron-down"></i>
							</a>
							<ul class="dropdown-menu" role="menu">
			                	<li><a class="btn-group ">
			                            <i class="fa fa-pencil"></i>
			                            Add Client
			                        </a>
			                    </li>
			                    <li>
			                        <a href="#">
			                            <i class="fa fa-trash-o"></i>
			                            Add Booking
			                        </a>
			                    </li>
		                	</ul>
						</li>
						<!--<li>
							<a class="btn-group " style="padding-top: 5px;">
				                <button data-toggle="dropdown" class="btn btn-med-grey dropdown-toggle" style="background:#ff4401;border-color:#ff4401;">
				                    <i class="fa fa-plus"></i>
				                    Add <span class="caret"></span>
				                </button>
			                </a>
			                <ul class="dropdown-menu" role="menu">
			                	<li><a class="btn-group ">
			                            <i class="fa fa-pencil"></i>
			                            Add Client
			                        </a>
			                    </li>
			                    <li>
			                        <a href="#">
			                            <i class="fa fa-trash-o"></i>
			                            Add Booking
			                        </a>
			                    </li>
		                	</ul>
					  	</li>-->
						<li class="dropdown">
							<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
								<i class="clip-list-5"></i>
								<span class="badge"> 12</span>
							</a>
							<ul class="dropdown-menu todo">
								<li>
									<span class="dropdown-menu-title"> You have 12 pending tasks</span>
								</li>
								<li>
									<div class="drop-down-wrapper">
										<ul>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc" style="opacity: 1; text-decoration: none;">Staff Meeting</span>
													<span class="label label-danger" style="opacity: 1;"> today</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc" style="opacity: 1; text-decoration: none;"> New frontend layout</span>
													<span class="label label-danger" style="opacity: 1;"> today</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc"> Hire developers</span>
													<span class="label label-warning"> tommorow</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc">Staff Meeting</span>
													<span class="label label-warning"> tommorow</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc"> New frontend layout</span>
													<span class="label label-success"> this week</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc"> Hire developers</span>
													<span class="label label-success"> this week</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc"> New frontend layout</span>
													<span class="label label-info"> this month</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc"> Hire developers</span>
													<span class="label label-info"> this month</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc" style="opacity: 1; text-decoration: none;">Staff Meeting</span>
													<span class="label label-danger" style="opacity: 1;"> today</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc" style="opacity: 1; text-decoration: none;"> New frontend layout</span>
													<span class="label label-danger" style="opacity: 1;"> today</span>
												</a>
											</li>
											<li>
												<a class="todo-actions" href="javascript:void(0)">
													<i class="fa fa-square-o"></i>
													<span class="desc"> Hire developers</span>
													<span class="label label-warning"> tommorow</span>
												</a>
											</li>
										</ul>
									</div>
								</li>
								<li class="view-all">
									<a href="javascript:void(0)">
										See all tasks <i class="fa fa-arrow-circle-o-right"></i>
									</a>
								</li>
							</ul>
						</li>
						<!-- end: TO-DO DROPDOWN-->
						<!-- start: NOTIFICATION DROPDOWN -->
						<li class="dropdown">
							<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
								<i class="clip-notification-2"></i>
								<span class="badge"> 11</span>
							</a>
							<ul class="dropdown-menu notifications">
								<li>
									<span class="dropdown-menu-title"> You have 11 notifications</span>
								</li>
								<li>
									<div class="drop-down-wrapper">
										<ul>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-primary"><i class="fa fa-user"></i></span>
													<span class="message"> New user registration</span>
													<span class="time"> 1 min</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-success"><i class="fa fa-comment"></i></span>
													<span class="message"> New comment</span>
													<span class="time"> 7 min</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-success"><i class="fa fa-comment"></i></span>
													<span class="message"> New comment</span>
													<span class="time"> 8 min</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-success"><i class="fa fa-comment"></i></span>
													<span class="message"> New comment</span>
													<span class="time"> 16 min</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-primary"><i class="fa fa-user"></i></span>
													<span class="message"> New user registration</span>
													<span class="time"> 36 min</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-warning"><i class="fa fa-shopping-cart"></i></span>
													<span class="message"> 2 items sold</span>
													<span class="time"> 1 hour</span>
												</a>
											</li>
											<li class="warning">
												<a href="javascript:void(0)">
													<span class="label label-danger"><i class="fa fa-user"></i></span>
													<span class="message"> User deleted account</span>
													<span class="time"> 2 hour</span>
												</a>
											</li>
											<li class="warning">
												<a href="javascript:void(0)">
													<span class="label label-danger"><i class="fa fa-shopping-cart"></i></span>
													<span class="message"> Transaction was canceled</span>
													<span class="time"> 6 hour</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-success"><i class="fa fa-comment"></i></span>
													<span class="message"> New comment</span>
													<span class="time"> yesterday</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-primary"><i class="fa fa-user"></i></span>
													<span class="message"> New user registration</span>
													<span class="time"> yesterday</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-primary"><i class="fa fa-user"></i></span>
													<span class="message"> New user registration</span>
													<span class="time"> yesterday</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-success"><i class="fa fa-comment"></i></span>
													<span class="message"> New comment</span>
													<span class="time"> yesterday</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0)">
													<span class="label label-success"><i class="fa fa-comment"></i></span>
													<span class="message"> New comment</span>
													<span class="time"> yesterday</span>
												</a>
											</li>
										</ul>
									</div>
								</li>
								<li class="view-all">
									<a href="javascript:void(0)">
										See all notifications <i class="fa fa-arrow-circle-o-right"></i>
									</a>
								</li>
							</ul>
						</li>
						<!-- end: NOTIFICATION DROPDOWN -->
						<!-- start: MESSAGE DROPDOWN -->
						<li class="dropdown">
							<a class="dropdown-toggle" data-close-others="true" data-hover="dropdown" data-toggle="dropdown" href="#">
								<i class="clip-bubble-3"></i>
								<span class="badge"> 9</span>
							</a>
							<ul class="dropdown-menu posts">
								<li>
									<span class="dropdown-menu-title"> You have 9 messages</span>
								</li>
								<li>
									<div class="drop-down-wrapper">
										<ul>
											<li>
												<a href="javascript:;">
													<div class="clearfix">
														<div class="thread-image">
															<img alt="" src="{{ asset('co/assets/images/avatar-2.jpg') }}">
														</div>
														<div class="thread-content">
															<span class="author">Nicole Bell</span>
															<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
															<span class="time"> Just Now</span>
														</div>
													</div>
												</a>
											</li>
											<li>
												<a href="javascript:;">
													<div class="clearfix">
														<div class="thread-image">
															<img alt="" src="{{ asset('co/assets/images/avatar-1.jpg') }}">
														</div>
														<div class="thread-content">
															<span class="author">Peter Clark</span>
															<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
															<span class="time">2 mins</span>
														</div>
													</div>
												</a>
											</li>
											<li>
												<a href="javascript:;">
													<div class="clearfix">
														<div class="thread-image">
															<img alt="" src="{{ asset('co/assets/images/avatar-3.jpg') }}">
														</div>
														<div class="thread-content">
															<span class="author">Steven Thompson</span>
															<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
															<span class="time">8 hrs</span>
														</div>
													</div>
												</a>
											</li>
											<li>
												<a href="javascript:;">
													<div class="clearfix">
														<div class="thread-image">
															<img alt="" src="{{ asset('co/assets/images/avatar-1.jpg') }}">
														</div>
														<div class="thread-content">
															<span class="author">Peter Clark</span>
															<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
															<span class="time">9 hrs</span>
														</div>
													</div>
												</a>
											</li>
											<li>
												<a href="javascript:;">
													<div class="clearfix">
														<div class="thread-image">
															<img alt="" src="{{ asset('co/assets/images/avatar-5.jpg') }}">
														</div>
														<div class="thread-content">
															<span class="author">Kenneth Ross</span>
															<span class="preview">Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</span>
															<span class="time">14 hrs</span>
														</div>
													</div>
												</a>
											</li>
										</ul>
									</div>
								</li>
								<li class="view-all">
									<a href="pages_messages.html">
										See all messages <i class="fa fa-arrow-circle-o-right"></i>
									</a>
								</li>
							</ul>
						</li>
						<!-- end: MESSAGE DROPDOWN -->
						<!-- start: USER DROPDOWN -->
						<li class="dropdown current-user">
							<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
								<img src="{{ asset('co/assets/images/avatar-1-small.jpg') }}" class="circle-img" alt="">
								<span class="username">Peter Clark</span>
								<i class="clip-chevron-down"></i>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="pages_user_profile.html">
										<i class="clip-user-2"></i>
										&nbsp;My Profile
									</a>
								</li>
								<li>
									<a href="pages_calendar.html">
										<i class="clip-calendar"></i>
										&nbsp;My Calendar
									</a>
								<li>
									<a href="pages_messages.html">
										<i class="clip-bubble-4"></i>
										&nbsp;My Messages (3)
									</a>
								</li>
								<li class="divider"></li>
								<li>
									<a href="utility_lock_screen.html"><i class="clip-locked"></i>
										&nbsp;Lock Screen </a>
								</li>
								<li>
									<a href="login_example1.html">
										<i class="clip-exit"></i>
										&nbsp;Log Out
									</a>
								</li>
							</ul>
						</li>
						<!-- end: USER DROPDOWN -->
						<!-- start: PAGE SIDEBAR TOGGLE -->
						<li>
							<a class="sb-toggle" href="#"><i class="fa fa-outdent"></i></a>
						</li>
						<!-- end: PAGE SIDEBAR TOGGLE -->
					</ul>
					<!-- end: TOP NAVIGATION MENU -->
				</div>
			</div>
			<!-- end: TOP NAVIGATION CONTAINER -->
		</div>
		<!-- end: HEADER -->
		<!-- start: MAIN CONTAINER -->
		<div class="main-container">
			<div class="navbar-content">
				<!-- start: SIDEBAR -->
				<div class="main-navigation navbar-collapse collapse">
					<!-- start: MAIN MENU TOGGLER BUTTON -->
					<div class="navigation-toggler">
						<i class="clip-chevron-left"></i>
						<i class="clip-chevron-right"></i>
					</div>
					<!-- end: MAIN MENU TOGGLER BUTTON -->
					<!-- start: MAIN NAVIGATION MENU -->
					<ul class="main-navigation-menu">
						<li>
							<a href="{{ url('/home') }}"><i class="clip-home-3"></i><!-- javascript:void(0) -->
								<span class="title"> Dashboard </span><span class="selected"></span><!--<i class="icon-arrow"></i>--><span class="selected"></span>
							</a>
							<!--
							<ul class="sub-menu">
								<li>
									<a href="layouts_horizontal_menu1.html">
										<span class="title"> Bookings</span>
										<span class="badge badge-new">new</span>
									</a>
								</li>
								<li>
									<a href="layouts_sidebar_closed.html">
										<span class="title"> To Do List </span>
									</a>
								</li>
								
							</ul>
							-->
						</li>
						<li class="active open">
							<a href="{{ url('/home') }}"><i class="fa fa-calendar"></i><!-- javascript:void(0) -->
								<span class="title"> Calendar/Bookings </span><!--<i class="icon-arrow"></i>-->
								<!--<span class="selected"></span>-->
							</a>
							<!--<ul class="sub-menu">
								<li>
									<a href="layouts_horizontal_menu1.html">
										<span class="title"> Horizontal Menu </span>
										<span class="badge badge-new">new</span>
									</a>
								</li>
								<li>
									<a href="layouts_sidebar_closed.html">
										<span class="title"> Sidebar Closed </span>
									</a>
								</li>
								<li>
									<a href="layouts_boxed_layout.html">
										<span class="title"> Boxed Layout </span>
									</a>
								</li>
								<li>
									<a href="layouts_footer_fixed.html">
										<span class="title"> Footer Fixed </span>
									</a>
								</li>
								<li>
									<a href="../clip-one-rtl/index.html">
										<span class="title"> RTL Version </span>
									</a>
								</li>
							</ul>-->
						</li>
						<!--<li>
							<a href="../../frontend/clip-one/index.html" target="_blank"><i class="clip-cursor"></i>
								<span class="title"> Clients </span><span class="selected"></span>
							</a>
						</li>-->
						<li>
							<a href="javascript:void(0)"><i class="clip-user-2"></i>
								<span class="title"> Clients/Customers </span><i class="icon-arrow"></i>
								<!--<span class="selected"></span>-->
							</a>
							<ul class="sub-menu">
								<li>
									<a href="{{ url('/clients') }}">
									<i class="clip-list-5"></i>
										<span class="title">List</span>
									</a>
								</li>
								<li>
									<a href="{{ url('/clients/create') }}">
									<i class="fa fa-plus"></i>
										<span class="title"> Add </span>
									</a>
								</li>
								<!--
								<li>
									<a href="ui_animations.html">
										<span class="title"> CSS3 Animation </span>
									</a>
								</li>
								<li>
									<a href="ui_modals.html">
										<span class="title"> Extended Modals </span>
									</a>
								</li>
								<li>
									<a href="ui_tabs_accordions.html">
										<span class="title"> Tabs &amp; Accordions </span>
									</a>
								</li>
								<li>
									<a href="ui_sliders.html">
										<span class="title"> Sliders </span>
									</a>
								</li>
								<li>
									<a href="ui_treeview.html">
										<span class="title"> Treeview </span>
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
								</li>-->
							</ul>
						</li>
						<li>
							<a href="javascript:void(0)"><i class="clip-bars"></i>
								<span class="title"> Reports </span><i class="icon-arrow"></i>
								<!--<span class="selected"></span>-->
							</a>
							<ul class="sub-menu">
								<li>
									<a href="#">
										<span class="title">Clients</span>
									</a>
								</li>
								<li>
									<a href="table_responsive.html">
										<span class="title">Finance</span>
									</a>
								</li>
								<li>
									<a href="table_data.html">
										<span class="title">Other</span>
									</a>
								</li>
								<!--<li>
									<a href="table_export.html"> <span class="title">Table Export</span> </a>
								</li>-->
							</ul>
						</li>
						<li>
							<a href="#"><i class="clip-pencil"></i><!-- javascript:void(0) -->
								<span class="title"> Communication </span><!--<i class="icon-arrow"></i>-->
								<!--<span class="selected"></span>-->
							</a>
							<!--<ul class="sub-menu">
								<li>
									<a href="form_elements.html">
										<span class="title">Form Elements</span>
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
									<a href="form_inline.html">
										<span class="title">Inline Editor</span>
									</a>
								</li>
								<li>
									<a href="form_x_editable.html">
										<span class="title">Form X-editable</span>
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
								<li>
									<a href="form_dropzone.html">
										<span class="title">Dropzone File Upload</span>
									</a>
								</li>
							</ul>-->
						</li>
						<li>
							<a href="javascript:void(0)"><i class="clip-user-2"></i>
								<span class="title"> Contacts </span><i class="icon-arrow"></i>
								<!--<span class="selected"></span>-->
							</a>
							<ul class="sub-menu">
								<li>
									<a href="#">
										<span class="title">Details</span>
									</a>
								</li>
								<li>
									<a href="#">
										<span class="title">Contact Person</span>
									</a>
								</li>
								<li>
									<a href="#">
										<span class="title">Contact Details</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="">
							<a href="javascript:void(0)"><i class="fa fa-dollar"></i>
								<span class="title"> Finances </span><i class="icon-arrow"></i>
								
							</a>
							<ul class="sub-menu">
								<li>
									<a href="#">
										<span class="title">Income</span>
									</a>
								</li>
								<li>
									<a href="#">
										<span class="title">Sales</span>
										<!--<span class="badge badge-new">new</span>-->
									</a>
								</li>								
								<li>
									<a href="#">
										<span class="title">Payments</span>
									</a>
								</li>
								<li>
									<a href="javascript:;">
										Expenses <i class="icon-arrow"></i>
									</a>
									<ul class="sub-menu">
										<li>
											<a href="#">
												Details
											</a>
										</li>
										<li>
											<a href="#">
												Stock Purchase
											</a>
										</li>
										
									</ul>
								</li>
								<li>
									<a href="#">
										<span class="title">Invoices</span>
									</a>
								</li>
								<li>
									<a href="#">
										<span class="title">Balances</span>
									</a>
								</li>
								<li class="active">
									<a href="#">
										<span class="title">Statements</span>
									</a>
								</li>
								<li class="active">
									<a href="#">
										<span class="title">Cash flow</span>
									</a>
								</li>
								<li class="active">
									<a href="#">
										<span class="title">Cash Forecast</span>
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="javascript:void(0)"><i class="clip-attachment-2"></i>
								<span class="title"> Business Tools </span><i class="icon-arrow"></i>
								<!--<span class="selected"></span>-->
							</a>
							<ul class="sub-menu">
								<li>
									<a href="javascript:;">
										Forms <i class="icon-arrow"></i>
									</a>
									<ul class="sub-menu">
										<li>
											<a href="#">
												EPIC PARQ
											</a>
										</li>
										<li>
											<a href="#">
												PARQ
											</a>
										</li>
										<li>
											<a href="#">
												Medical Clearance
											</a>
										</li>
										<li>
											<a href="#">
												Waiver & Liability
											</a>
										</li>
										<li>
											<a href="#">
												Benchmarking/Progression Sessions
											</a>
										</li>
										
									</ul>
								</li>
								<li>
									<a href="#">
										<span class="title">Resources </span>
										<!--<span class="badge badge-new">new</span>-->
									</a>
								</li>								
								<li>
									<a href="Stationary">
										<span class="title">Lock Screen</span>
									</a>
								</li>
								<li>
									<a href="#">
										<span class="title">Financial Tools</span>
									</a>
								</li>
								<!---<li>
									<a href="utility_404_example2.html">
										<span class="title">Error 404 Example 2</span>
									</a>
								</li>
								<li>
									<a href="utility_404_example3.html">
										<span class="title">Error 404 Example 3</span>
									</a>
								</li>
								<li>
									<a href="utility_500_example1.html">
										<span class="title">Error 500 Example 1</span>
									</a>
								</li>
								<li>
									<a href="utility_500_example2.html">
										<span class="title">Error 500 Example 2</span>
									</a>
								</li>
								<li>
									<a href="utility_pricing_table.html">
										<span class="title">Pricing Table</span>
									</a>
								</li>
								<li>
									<a href="utility_coming_soon.html">
										<span class="title">Cooming Soon</span>
									</a>
								</li>-->
							</ul>
						</li>
						<li>
							<a href="javascript:;" class="active">
								<i class="clip-settings"></i>
								<span class="title"> My Profile </span>
								<i class="icon-arrow"></i>
							</a>
							<ul class="sub-menu">
								<li>
									<a href="javascript:;">
										Item 1 <i class="icon-arrow"></i>
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
										Item 1 <i class="icon-arrow"></i>
									</a>
									<ul class="sub-menu">
										<li>
											<a href="#">
												Sample Link 1
											</a>
										</li>
										<li>
											<a href="#">
												Sample Link 1
											</a>
										</li>
										<li>
											<a href="#">
												Sample Link 1
											</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="#">
										Item 3
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="javascript:;">
								<i class="clip-folder-open"></i>
								<span class="title"> 4 Level Menu </span><i class="icon-arrow"></i>
								<span class="arrow "></span>
							</a>
							<ul class="sub-menu">
								<li>
									<a href="javascript:;">
										Item 1 <i class="icon-arrow"></i>
									</a>
									<ul class="sub-menu">
										<li>
											<a href="javascript:;">
												Sample Link 1 <i class="icon-arrow"></i>
											</a>
											<ul class="sub-menu">
												<li>
													<a href="#"><i class="fa fa-times"></i>
														Sample Link 1</a>
												</li>
												<li>
													<a href="#"><i class="fa fa-pencil"></i>
														Sample Link 1</a>
												</li>
												<li>
													<a href="#"><i class="fa fa-edit"></i>
														Sample Link 1</a>
												</li>
											</ul>
										</li>
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
										Item 2 <i class="icon-arrow"></i>
									</a>
									<ul class="sub-menu">
										<li>
											<a href="#">
												Sample Link 1
											</a>
										</li>
										<li>
											<a href="#">
												Sample Link 1
											</a>
										</li>
										<li>
											<a href="#">
												Sample Link 1
											</a>
										</li>
									</ul>
								</li>
								<li>
									<a href="#">
										Item 3
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="maps.html"><i class="clip-location"></i>
								<span class="title"> Maps </span>
								<!--<span class="selected"></span>-->
							</a>
						</li>
						<li>
							<a href="charts.html"><i class="clip-bars"></i>
								<span class="title"> Charts </span>
								<!--<span class="selected"></span>-->
							</a>
						</li>
					</ul>
					<!-- end: MAIN NAVIGATION MENU -->
				</div>
				<!-- end: SIDEBAR -->
			</div>
			<!-- start: PAGE -->
			<div class="main-content">
				<!-- start: PANEL CONFIGURATION MODAL FORM -->
				<div class="modal fade" id="panel-config" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
									&times;
								</button>
								<h4 class="modal-title">Panel Configuration</h4>
							</div>
							<div class="modal-body">
								Here will be a configuration form
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">
									Close
								</button>
								<button type="button" class="btn btn-primary">
									Save changes
								</button>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->
				<!-- end: SPANEL CONFIGURATION MODAL FORM -->
				<div class="container">

					<!-- start: PAGE HEADER -->
					<div class="row" style="display:none;">
						<div class="col-sm-12">
							<!-- start: STYLE SELECTOR BOX -->
							<!--<div id="style_selector" class="hidden-xs">
								<div id="style_selector_container">
									<div class="style-main-title">
										Style Selector
									</div>
									<div class="box-title">
										Choose Your Layout Style
									</div>
									<div class="input-box">
										<div class="input">
											<select name="layout">
												<option value="default">Wide</option><option value="boxed">Boxed</option>
											</select>
										</div>
									</div>
									<div class="box-title">
										Choose Your Header Style
									</div>
									<div class="input-box">
										<div class="input">
											<select name="header">
												<option value="fixed">Fixed</option><option value="default">Default</option>
											</select>
										</div>
									</div>
									<div class="box-title">
										Choose Your Footer Style
									</div>
									<div class="input-box">
										<div class="input">
											<select name="footer">
												<option value="default">Default</option><option value="fixed">Fixed</option>
											</select>
										</div>
									</div>
									<div class="box-title">
										Backgrounds for Boxed Version
									</div>
									<div class="images boxed-patterns">
										<a id="bg_style_1" href="#"><img alt="" src="{{ asset('co/assets/images/bg.png"></a>
										<a id="bg_style_2" href="#"><img alt="" src="{{ asset('co/assets/images/bg_2.png"></a>
										<a id="bg_style_3" href="#"><img alt="" src="{{ asset('co/assets/images/bg_3.png"></a>
										<a id="bg_style_4" href="#"><img alt="" src="{{ asset('co/assets/images/bg_4.png"></a>
										<a id="bg_style_5" href="#"><img alt="" src="{{ asset('co/assets/images/bg_5.png"></a>
									</div>
									<div class="box-title">
										5 Predefined Color Schemes
									</div>
									<div class="images icons-color">
										<a id="light" href="#"><img class="active" alt="" src="{{ asset('co/assets/images/lightgrey.png"></a>
										<a id="dark" href="#"><img alt="" src="{{ asset('co/assets/images/darkgrey.png"></a>
										<a id="black_and_white" href="#"><img alt="" src="{{ asset('co/assets/images/blackandwhite.png"></a>
										<a id="navy" href="#"><img alt="" src="{{ asset('co/assets/images/navy.png"></a>
										<a id="green" href="#"><img alt="" src="{{ asset('co/assets/images/green.png"></a>
									</div>
									<div class="box-title">
										Style it with LESS
									</div>
									<div class="images">
										<div class="form-group">
											<label>
												Basic
											</label>
											<input type="text" value="#ffffff" class="color-base">
											<div class="dropdown">
												<a class="add-on dropdown-toggle" data-toggle="dropdown"><i style="background-color: #ffffff"></i></a>
												<ul class="dropdown-menu pull-right">
													<li>
														<div class="colorpalette"></div>
													</li>
												</ul>
											</div>
										</div>
										<div class="form-group">
											<label>
												Text
											</label>
											<input type="text" value="#555555" class="color-text">
											<div class="dropdown">
												<a class="add-on dropdown-toggle" data-toggle="dropdown"><i style="background-color: #555555"></i></a>
												<ul class="dropdown-menu pull-right">
													<li>
														<div class="colorpalette"></div>
													</li>
												</ul>
											</div>
										</div>
										<div class="form-group">
											<label>
												Elements
											</label>
											<input type="text" value="#007AFF" class="color-badge">
											<div class="dropdown">
												<a class="add-on dropdown-toggle" data-toggle="dropdown"><i style="background-color: #007AFF"></i></a>
												<ul class="dropdown-menu pull-right">
													<li>
														<div class="colorpalette"></div>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<div style="height:25px;line-height:25px; text-align: center">
										<a class="clear_style" href="#">
											Clear Styles
										</a>
										<a class="save_style" href="#">
											Save Styles
										</a>
									</div>
								</div>
								<div class="style-toggle close"></div>
							</div>-->
							<!-- end: STYLE SELECTOR BOX -->
							<!-- start: PAGE TITLE & BREADCRUMB -->
							<a class="btn btn-blue" href="#"><i class="fa fa-plus"></i>
											Submit Entry</a>
							<ol class="breadcrumb">
								<li>
									<i class="clip-file"></i>
									<a href="#">
										Pages
									</a>
								</li>
								<li class="active">
									Blank Page
								</li>
								<li class="search-box">
									<form class="sidebar-search">
										<div class="form-group">
											<input type="text" placeholder="Start Searching...">
											<button class="submit">
												<i class="clip-search-3"></i>
											</button>
										</div>
									</form>
								</li>
							</ol>
							<div class="page-header">
								<h1>Blank Page <small>blank page</small></h1>
							</div>
							<!-- end: PAGE TITLE & BREADCRUMB -->
						</div>
					</div>
					<!-- end: PAGE HEADER -->
					<!-- start: PAGE CONTENT -->
					<!--<div class="row" style="padding-top:30px;">
					</div>-->

					@yield('content')
					<!-- end: PAGE CONTENT-->

				</div><!-- container -->
			</div>
			<!-- end: PAGE -->
		</div>
		<!-- end: MAIN CONTAINER -->
		<!-- start: FOOTER -->
		<div class="footer clearfix">
			<div class="footer-inner">
				<?php date('Y'); ?> &copy; Epic Trainer.
			</div>
			<div class="footer-items">
				<span class="go-top"><i class="clip-chevron-up"></i></span>
			</div>
		</div>
		<!-- end: FOOTER -->
		<!-- start: RIGHT SIDEBAR -->
		<div id="page-sidebar">
			<a class="sidebar-toggler sb-toggle" href="#"><i class="fa fa-indent"></i></a>
			<div class="sidebar-wrapper">
				<ul class="nav nav-tabs nav-justified" id="sidebar-tab">
					<li class="active">
						<a href="#users" role="tab" data-toggle="tab"><i class="fa fa-users"></i></a>
					</li>
					<li>
						<a href="#favorites" role="tab" data-toggle="tab"><i class="fa fa-heart"></i></a>
					</li>
					<li>
						<a href="#settings" role="tab" data-toggle="tab"><i class="fa fa-gear"></i></a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="users">
						<div class="users-list">
							<h5 class="sidebar-title">On-line</h5>
							<ul class="media-list">
								<li class="media">
									<a href="#">
										<i class="fa fa-circle status-online"></i>
										<img alt="..." src="{{ asset('co/assets/images/avatar-2.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Nicole Bell</h4>
											<span> Content Designer </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<div class="user-label">
											<span class="label label-success">3</span>
										</div>
										<i class="fa fa-circle status-online"></i>
										<img alt="..." src="{{ asset('co/assets/images/avatar-3.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Steven Thompson</h4>
											<span> Visual Designer </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<i class="fa fa-circle status-online"></i>
										<img alt="..." src="{{ asset('co/assets/images/avatar-4.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Ella Patterson</h4>
											<span> Web Editor </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<i class="fa fa-circle status-online"></i>
										<img alt="..." src="{{ asset('co/assets/images/avatar-5.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Kenneth Ross</h4>
											<span> Senior Designer </span>
										</div>
									</a>
								</li>
							</ul>
							<h5 class="sidebar-title">Off-line</h5>
							<ul class="media-list">
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-6.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Nicole Bell</h4>
											<span> Content Designer </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<div class="user-label">
											<span class="label label-success">3</span>
										</div>
										<img alt="..." src="{{ asset('co/assets/images/avatar-7.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Steven Thompson</h4>
											<span> Visual Designer </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-8.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Ella Patterson</h4>
											<span> Web Editor </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-9.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Kenneth Ross</h4>
											<span> Senior Designer </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-10.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Ella Patterson</h4>
											<span> Web Editor </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-5.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Kenneth Ross</h4>
											<span> Senior Designer </span>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div class="user-chat">
							<div class="sidebar-content">
								<a class="sidebar-back" href="#"><i class="fa fa-chevron-circle-left"></i> Back</a>
							</div>
							<div class="user-chat-form sidebar-content">
								<div class="input-group">
									<input type="text" placeholder="Type a message here..." class="form-control">
									<div class="input-group-btn">
										<button class="btn btn-success" type="button">
											<i class="fa fa-chevron-right"></i>
										</button>
									</div>
								</div>
							</div>
							<ol class="discussion sidebar-content">
								<li class="other">
									<div class="avatar">
										<img src="{{ asset('co/assets/images/avatar-4.jpg') }}" alt="">
									</div>
									<div class="messages">
										<p>
											Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
										</p>
										<span class="time"> 51 min </span>
									</div>
								</li>
								<li class="self">
									<div class="avatar">
										<img src="{{ asset('co/assets/images/avatar-1.jpg') }}" alt="">
									</div>
									<div class="messages">
										<p>
											Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
										</p>
										<span class="time"> 37 mins </span>
									</div>
								</li>
								<li class="other">
									<div class="avatar">
										<img src="{{ asset('co/assets/images/avatar-4.jpg') }}" alt="">
									</div>
									<div class="messages">
										<p>
											Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
										</p>
									</div>
								</li>
							</ol>
						</div>
					</div>
					<div class="tab-pane" id="favorites">
						<div class="users-list">
							<h5 class="sidebar-title">Favorites</h5>
							<ul class="media-list">
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-7.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Nicole Bell</h4>
											<span> Content Designer </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<div class="user-label">
											<span class="label label-success">3</span>
										</div>
										<img alt="..." src="{{ asset('co/assets/images/avatar-6.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Steven Thompson</h4>
											<span> Visual Designer </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-10.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Ella Patterson</h4>
											<span> Web Editor </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-2.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Kenneth Ross</h4>
											<span> Senior Designer </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-4.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Ella Patterson</h4>
											<span> Web Editor </span>
										</div>
									</a>
								</li>
								<li class="media">
									<a href="#">
										<img alt="..." src="{{ asset('co/assets/images/avatar-5.jpg') }}" class="media-object">
										<div class="media-body">
											<h4 class="media-heading">Kenneth Ross</h4>
											<span> Senior Designer </span>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div class="user-chat">
							<div class="sidebar-content">
								<a class="sidebar-back" href="#"><i class="fa fa-chevron-circle-left"></i> Back</a>
							</div>
							<ol class="discussion sidebar-content">
								<li class="other">
									<div class="avatar">
										<img src="{{ asset('co/assets/images/avatar-4.jpg') }}" alt="">
									</div>
									<div class="messages">
										<p>
											Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
										</p>
										<span class="time"> 51 min </span>
									</div>
								</li>
								<li class="self">
									<div class="avatar">
										<img src="{{ asset('co/assets/images/avatar-1.jpg') }}" alt="">
									</div>
									<div class="messages">
										<p>
											Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
										</p>
										<span class="time"> 37 mins </span>
									</div>
								</li>
								<li class="other">
									<div class="avatar">
										<img src="{{ asset('co/assets/images/avatar-4.jpg') }}" alt="">
									</div>
									<div class="messages">
										<p>
											Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.
										</p>
									</div>
								</li>
							</ol>
						</div>
					</div>
					<div class="tab-pane" id="settings">
						<h5 class="sidebar-title">General Settings</h5>
						<ul class="media-list">
							<li class="media">
								<div class="checkbox sidebar-content">
									<label>
										<input type="checkbox" value="" class="green" checked="checked">
										Enable Notifications
									</label>
								</div>
							</li>
							<li class="media">
								<div class="checkbox sidebar-content">
									<label>
										<input type="checkbox" value="" class="green" checked="checked">
										Show your E-mail
									</label>
								</div>
							</li>
							<li class="media">
								<div class="checkbox sidebar-content">
									<label>
										<input type="checkbox" value="" class="green">
										Show Offline Users
									</label>
								</div>
							</li>
							<li class="media">
								<div class="checkbox sidebar-content">
									<label>
										<input type="checkbox" value="" class="green" checked="checked">
										E-mail Alerts
									</label>
								</div>
							</li>
							<li class="media">
								<div class="checkbox sidebar-content">
									<label>
										<input type="checkbox" value="" class="green">
										SMS Alerts
									</label>
								</div>
							</li>
						</ul>
						<div class="sidebar-content">
							<button class="btn btn-success">
								<i class="icon-settings"></i> Save Changes
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end: RIGHT SIDEBAR -->
		<!-- start: MAIN JAVASCRIPTS -->
		<!--[if lt IE 9]>
		<script src="{{ asset('co/assets/plugins/respond.min.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/excanvas.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('co/assets/plugins/jQuery-lib/1.10.2/jquery.min.js') }}"></script>
		<![endif]-->
		<!--[if gte IE 9]><!-->
		<script src="{{ asset('co/assets/plugins/jQuery-lib/2.0.3/jquery.min.js') }}"></script>
		<!--<![endif]-->
		<script src="{{ asset('co/assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/blockUI/jquery.blockUI.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/iCheck/jquery.icheck.min.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/less/less-1.5.0.min.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/jquery-cookie/jquery.cookie.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js') }}"></script>
		<script src="{{ asset('co/assets/js/main.js') }}"></script>
		<!-- end: MAIN JAVASCRIPTS -->
		<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script src="{{ asset('co/assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/jQuery-Smart-Wizard/js/jquery.smartWizard.js') }}"></script>
		<script src="{{ asset('co/assets/js/form-wizard.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/fullcalendar/fullcalendar/fullcalendar.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>


		<script type="text/javascript" src="{{ asset('co/assets/plugins/select2/select2.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('co/assets/plugins/DataTables/media/js/jquery.dataTables.min.js') }}"></script>
		<script type="text/javascript" src="{{ asset('co/assets/plugins/DataTables/media/js/DT_bootstrap.js') }}"></script>


		<!--
		<script src="{{ asset('co/assets/plugins/flot/jquery.flot.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/flot/jquery.flot.pie.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/flot/jquery.flot.resize.min.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/jquery.sparkline/jquery.sparkline.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js') }}"></script>
		<script src="{{ asset('co/assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>-->


		<script src="{{ asset('co/assets/js/index.js') }}"></script>
		<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
		<script>
		function listOfpart(valBody){
				    var i;
				  	//var msg="";
				  	var valBody;
				  	if(valBody!=""){
					  	//alert(valBody);
						//  jQuery("#listBodyPart").val(valBody);
					  	document.getElementById("listBodyPart").value=valBody;
					  	document.getElementById("allbodyPart").style.display="block";
					  	document.getElementById("bodyPart"+valBody).style.display="block";
					  	//document.getElementById("bodyPart"+valBody).style.display="block";
					  	for(i=0;i<11;i++){
					    	if(valBody==i){
					        document.getElementById("bodyPart"+i).style.display="block";
					        }else{
					        document.getElementById("bodyPart"+i).style.display="none";
					        }
					    }
					}
				}
		function listOfarea(valBody){
		    var i;
		  	var msg="";
		  	document.getElementById("listBodyArea").value=valBody;
		  	document.getElementById("allbodyArea").style.display="block";
		  	for(i=0;i<9;i++){
		    	if(valBody==i){
		        	document.getElementById("bodyArea"+i).style.display="block";
		        }else{
		        	document.getElementById("bodyArea"+i).style.display="none";
		        }
			}
		}
			jQuery(document).ready(function() {
				// function to initiate Full Calendar
		    var runFullCalendar = function () {
		        //calendar
		        /* initialize the calendar
				 -----------------------------------------------------------------*/
		        var $modal = $('#event-management');
		        $('#event-categories div.event-category').each(function () {
		            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
		            // it doesn't need to have a start or end
		            var eventObject = {
		                title: $.trim($(this).text()) // use the element's text as the event title
		            };
		            // store the Event Object in the DOM element so we can get to it later
		            $(this).data('eventObject', eventObject);
		            // make the event draggable using jQuery UI
		            $(this).draggable({
		                zIndex: 999,
		                revert: true, // will cause the event to go back to its
		                revertDuration: 50 //  original position after the drag
		            });
		        });
		        /* initialize the calendar
				 -----------------------------------------------------------------*/
		        var date = new Date();
		        var d = date.getDate();
		        var m = date.getMonth();
		        var y = date.getFullYear();
		        var form = '';
		        var calendar = $('.calendar').fullCalendar({
		            buttonText: {
		                prev: '<i class="fa fa-chevron-left"></i>',
		                next: '<i class="fa fa-chevron-right"></i>'
		            },
		            header: {
		                left: 'prev,next today',
		                center: 'title',
		                right: 'month,agendaWeek,agendaDay'
		            },
		            events: [{
		                title: 'Meeting with Boss',
		                start: new Date(y, m, 1),
		                className: 'label-default'
		            }, {
		                title: 'Bootstrap Seminar',
		                start: new Date(y, m, d - 5),
		                end: new Date(y, m, d - 2),
		                className: 'label-teal'
		            }, {
		                title: 'Lunch with Nicole',
		                start: new Date(y, m, d - 3, 12, 0),
		                className: 'label-green',
		                allDay: false
		            }],
		            editable: true,
		            droppable: true, // this allows things to be dropped onto the calendar !!!
		            drop: function (date, allDay) { // this function is called when something is dropped
		                // retrieve the dropped element's stored Event Object
		                var originalEventObject = $(this).data('eventObject');
		                var $categoryClass = $(this).attr('data-class');
		                // we need to copy it, so that multiple events don't have a reference to the same object
		                var copiedEventObject = $.extend({}, originalEventObject);
		                // assign it the date that was reported
		                copiedEventObject.start = date;
		                copiedEventObject.allDay = allDay;
		                if ($categoryClass)
		                    copiedEventObject['className'] = [$categoryClass];
		                // render the event on the calendar
		                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
		                $('.calendar').fullCalendar('renderEvent', copiedEventObject, true);
		                // is the "remove after drop" checkbox checked?
		                if ($('#drop-remove').is(':checked')) {
		                    // if so, remove the element from the "Draggable Events" list
		                    $(this).remove();
		                }
		            },
		            selectable: true,
		            selectHelper: true,
		            select: function (start, end, allDay) {
		                $modal.modal({
		                    backdrop: 'static'
		                });
		                form = $("<form></form>");
		                form.append("<div class='row'></div>");
		                form.find(".row").append("<div class='col-md-6'><div class='form-group'><label class='control-label'>New Event Name</label><input class='form-control' placeholder='Insert Event Name' type=text name='title'/></div></div>").append("<div class='col-md-6'><div class='form-group'><label class='control-label'>Category</label><select class='form-control' name='category'></select></div></div>").find("select[name='category']").append("<option value='label-default'>Work</option>").append("<option value='label-green'>Home</option>").append("<option value='label-purple'>Holidays</option>").append("<option value='label-orange'>Party</option>").append("<option value='label-yellow'>Birthday</option>").append("<option value='label-teal'>Generic</option>").append("<option value='label-beige'>To Do</option>");
		                $modal.find('.remove-event').hide().end().find('.save-event').show().end().find('.modal-body').empty().prepend(form).end().find('.save-event').unbind('click').click(function () {
		                    form.submit();
		                });
		                $modal.find('form').on('submit', function () {
		                    title = form.find("input[name='title']").val();
		                    $categoryClass = form.find("select[name='category'] option:checked").val();
		                    if (title !== null) {
		                        calendar.fullCalendar('renderEvent', {
		                                title: title,
		                                start: start,
		                                end: end,
		                                allDay: allDay,
		                                className: $categoryClass
		                            }, true // make the event "stick"
		                        );
		                    }
		                    $modal.modal('hide');
		                    return false;
		                });
		                calendar.fullCalendar('unselect');
		            },
		            eventClick: function (calEvent, jsEvent, view) {
		                var form = $("<form></form>");
		                form.append("<label>Change event name</label>");
		                form.append("<div class='input-group'><input class='form-control' type=text value='" + calEvent.title + "' /><span class='input-group-btn'><button type='submit' class='btn btn-success'><i class='fa fa-check'></i> Save</button></span></div>");
		                $modal.modal({
		                    backdrop: 'static'
		                });
		                $modal.find('.remove-event').show().end().find('.save-event').hide().end().find('.modal-body').empty().prepend(form).end().find('.remove-event').unbind('click').click(function () {
		                    calendar.fullCalendar('removeEvents', function (ev) {
		                        return (ev._id == calEvent._id);
		                    });
		                    $modal.modal('hide');
		                });
		                $modal.find('form').on('submit', function () {
		                    calEvent.title = form.find("input[type=text]").val();
		                    calendar.fullCalendar('updateEvent', calEvent);
		                    $modal.modal('hide');
		                    return false;
		                });
		            }
		        });
		    };
				Main.init();
				$('#client-datatable').DataTable();

				
				//TableData.init();
				FormWizard.init();
				runFullCalendar();
				$('.client-delete').on('click', function(e) {
					var client_id = $(this).attr('data-client-delete');
					if (client_id.length > 0) {

					};
				});
				$('#calendarDatepicker').datepicker();
				$('#calendarDatepicker').on("changeDate", function() {
				    $('#myCalendarDatepicker').val(
				        $('#datepicker').datepicker('getFormattedDate')
				    );
				});
				//Index.init();
				jQuery("#mcfTosmc").click(function () {
				    jQuery("#smc").toggle();
				    jQuery("#mcf").toggle();
				     
				});
				

			});
		</script>
	</body>
	<!-- end: BODY -->
</html>
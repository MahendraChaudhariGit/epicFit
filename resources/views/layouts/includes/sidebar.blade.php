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
                <li class="{{ Request::is('dashboard')?'active':'' }}">
                    <a href="{{ route('dashboard.show') }}">
                        <div class="item-content">
                            <div class="item-media">
                              <i class="fa fa-qrcode"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Dashboard </span>
                            </div>
                        </div>
                    </a>
                </li>

                <!-- <li class="{{ Request::is('todo/calendar')?'active':'' }}">
                    <a href="{{ route('todocalendar.show') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-calendar"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> To-do List </span>
                            </div>
                        </div>
                    </a>
                </li> -->
                @if(Session::has('businessId'))
                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/gallery/category/list','settings/gallery/add/category','settings/gallery/category'))?'active open':'' }}">
                        <a href="javascript:void(0)">
                            <div class="item-content">
                                <div class="item-media">
                                    <i class="fa fa-image"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Gallery </span>
                                    <i class="icon-arrow"></i>
                                </div>
                            </div>
                        </a>
                        <ul class="sub-menu">  
                            
                            <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/gallery/category/list'))?'active':'' }}">
                                <a href="{{ route('gallery.category.list') }}">
                                    <span class="title"> Gallery </span>
                                </a>
                            </li>
                            
                            <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/gallery/category','settings/gallery/add/category'))?'active':'' }}">
                                <a href="{{ route('category') }}">
                                    <span class="title"> Category List </span>
                                </a>
                            </li>
                            
                        </ul>
                    </li>
                @endif  
                @if((isUserType(['Admin']) || isUserType(['Staff'])) && Auth::user()->hasPermission(Auth::user(), 'list-staff-attendence') && Session::has('ifBussHasStaffs'))
                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/staffs/report', 'settings/business/staffs/new-roster'))?'active open':'' }}">
                        <a href="javascript:void(0)">
                            <div class="item-content">
                                <div class="item-media">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Attendence </span>
                                    <i class="icon-arrow"></i>
                                </div>
                            </div>
                        </a>
                        <ul class="sub-menu">     
                            <!-- <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/staffs/attendences'))?'active':'' }}">
                                <a href="{{ route('staffs.listattendences') }}">
                                    <span class="title"> Roster </span>
                                </a>
                            </li> -->
                            
                            <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/staffs/new-roster'))?'active':'' }}">
                                <a href="{{ route('staffs.newroster') }}">
                                    <span class="title"> Roster </span>
                                </a>
                            </li>
                            
                            <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/staffs/report'))?'active':'' }}">
                                <a href="{{ route('staffs.monthlyattendences') }}">
                                    <span class="title"> Report </span>
                                </a>
                            </li>
                             
                        </ul>
                    </li>
                @endif
                {{-- {{dd(isSuperUser())}} --}}
                @if(Session::has('businessId') && Auth::user()->hasPermission(Auth::user(), 'show-sales-tool'))
                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('sales-tools/discounts', 'sales-tools/discounts/create', 'sales-tools/discounts/{id}', 'sales-tools/discounts/{id}/edit', 'sales-tools/invoice/edit'))?'active open':'' }}">
                        <a href="javascript:void(0)">
                            <div class="item-content">
                                <div class="item-media">
                                    <i class="fa fa-barcode"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Sales Tools </span>
                                    <i class="icon-arrow"></i>
                                </div>
                            </div>
                        </a>
                        <ul class="sub-menu">     
                        
                             @if(Session::has('ifBussHasSalesToolsDiscounts') && isUserEligible(['Admin'], 'list-discount'))
                                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('sales-tools/discounts', 'sales-tools/discounts/create', 'sales-tools/discounts/{id}', 'sales-tools/discounts/{id}/edit'))?'active':'' }}">
                                    <a href="{{ route('salestools.discount.list') }}">
                                        <span class="title"> Discounts List</span>
                                    </a>
                                </li>
                            @elseif(!Session::has('ifBussHasSalesToolsDiscounts') && isUserEligible(['Admin'], 'add-discount'))
                                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('sales-tools/discount/create'))?'active':'' }}">
                                    <a href="{{ route('salestools.discount.create') }}">
                                        <span class="title"> Add Discount </span>
                                    </a>
                                </li>
                            @endif
                            
                            @if(isUserEligible(['Admin'], 'edit-invoiceandtax'))
                            <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('sales-tools/invoice/edit'))?'active':'' }}">
                                <a href="{{ route('salestools.invoice.edit') }}">
                                    <span class="title"> Invoices & taxes </span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif
                
                @if(Auth::user()->hasPermission(Auth::user(), 'view-backend'))  
                <!-- <li class="{{ Request::is('dashboard/calendar')?'active':'' }}">
                    <a href="{{ url('dashboard/calendar') }}">
                        <div class="item-content">
                            <div class="item-media">
                               <i class="fa fa-calendar"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Calendar </span>
                            </div>
                        </div>
                    </a>
                </li> -->
                @endif

                @if(isUserType(['Admin']))
                    @if(Session::has('businessId'))      
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/businesses/edit', 'settings/business/locations', 'settings/business/locations/create', 'settings/business/locations/{id}', 'settings/business/locations/{id}/edit', 'settings/business/locations/{locationid}/areas', 'settings/business/areas/create/{id}', 'settings/business/areas/{id}', 'settings/business/areas/{id}/edit', 'settings/business/staffs', 'settings/business/staffs/create', 'settings/business/staffs/{id}', 'settings/business/staffs/{id}/edit', 'settings/business/services', 'settings/business/services/create', 'settings/business/services/{id}', 'settings/business/services/{id}/edit', 'settings/business/classes', 'settings/business/classes/create', 'settings/business/classes/{id}', 'settings/business/classes/{id}/edit', 'settings/business/products', 'settings/business/products/create', 'settings/business/products/{id}', 'settings/business/products/{id}/edit', 'settings/business/contacts', 'settings/business/contacts/create', 'settings/business/contacts/{id}', 'settings/business/contacts/{id}/edit', 'settings/business/memberships/create', 'clients/{filter?}', 'clients/create', 'client/{id}', 'clients/{id}/edit','goal-buddy/edit/{id}','goal-buddy/edithabit/{id}','goal-buddy/editgoal/{id}','goal-buddy/edittask/{id}','settings/business/memberships','settings/business/memberships/create','settings/business/memberships/{id}/edit','settings/business/memberships/{id}','settings/business/closeddate','settings/business/closeddate/create','settings/business/closeddate/{id}/edit', 'settings/business/resources', 'settings/business/resources/create', 'settings/business/resources/{id}/edit','settings/business/calendar/edit','settings/business/admin', 'settings/business/admin/create', 'settings/business/admin/{id}/edit'))?'active open':'' }}">
                            <a href="javascript:void(0)">
                                <div class="item-content">
                                    <div class="item-media">
                                        <i class="fa fa-suitcase"></i>
                                    </div>
                                    <div class="item-inner">
                                        <span class="title"> Business Setup  </span>
                                        <i class="icon-arrow"></i>
                                    </div>
                                </div>
                            </a>
                            <ul class="sub-menu">             
                                @if(isUserType(['Admin']) || isUserType(['Staff']))         
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/ldc'))?'active':'' }}">
                                        <a href="{{ route('ldc.session') }}">
                                            <span class="title"> LDC </span>
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->hasPermission(Auth::user(), 'edit-business-basic'))          
                                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/businesses/edit'))?'active':'' }}">
                                    <a href="{{ route('business.editt') }}">
                                        <span class="title"> Basic Information </span>
                                    </a>
                                </li>
                            @endif


                                @if(Auth::user()->hasPermission(Auth::user(), 'list-location') && Session::has('ifBussHasLocations') && Session::get('ifBussHasLocations') == true)
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/locations', 'settings/business/locations/{id}', 'settings/business/locations/{id}/edit', 'settings/business/locations/{locationid}/areas', 'settings/business/areas/{id}', 'settings/business/areas/{id}/edit','settings/business/areas/create/{id}'))?'active':'' }}">
                                        <a href="{{ route('locations') }}">
                                            <span class="title"> Locations List </span>
                                        </a>
                                    </li>
                                @elseif(Auth::user()->hasPermission(Auth::user(), 'create-location'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/locations/create'))?'active':'' }}">
                                        <a href="{{ route('locations.create') }}">
                                            <span class="title"> Add Location </span>
                                        </a>
                                    </li>
                                @endif
                                
                                @if(Auth::user()->hasPermission(Auth::user(), 'list-staff') && Session::has('ifBussHasStaffs') && Session::get('ifBussHasStaffs') == true)
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/staffs', 'settings/business/staffs/{id}', 'settings/business/staffs/{id}/edit'))?'active':'' }}">
                                        <a href="{{ route('staffs') }}">
                                            <span class="title"> Staffs List </span>
                                        </a>
                                    </li>
                                @elseif(Auth::user()->hasPermission(Auth::user(), 'create-staff'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/staffs/create'))?'active':'' }}">
                                        <a href="{{ route('staffs.create') }}">
                                            <span class="title"> Add Staff </span>
                                        </a>
                                    </li>
                                @endif

                                @if(Auth::user()->hasPermission(Auth::user(), 'list-service') && Session::has('ifBussHasServices') && Session::get('ifBussHasServices') == true)
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/services', 'settings/business/services/{id}', 'settings/business/services/{id}/edit'))?'active':'' }}">
                                        <a href="{{ route('services') }}">
                                            <span class="title"> Services List </span>
                                        </a>
                                    </li>
                                @elseif(Auth::user()->hasPermission(Auth::user(), 'create-service'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/services/create'))?'active':'' }}">
                                        <a href="{{ route('services.create') }}">
                                            <span class="title"> Add Service </span>
                                        </a>
                                    </li>
                                @endif

                                @if(Auth::user()->hasPermission(Auth::user(), 'list-class') && Session::has('ifBussHasClasses') && Session::get('ifBussHasClasses') == true)
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/classes', 'settings/business/classes/{id}', 'settings/business/classes/{id}/edit'))?'active':'' }}">
                                        <a href="{{ route('classes') }}">
                                            <span class="title"> Classes List </span>
                                        </a>
                                    </li>
                                @elseif(Auth::user()->hasPermission(Auth::user(), 'create-class'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/classes/create'))?'active':'' }}">
                                        <a href="{{ route('classes.create') }}">
                                            <span class="title"> Add Class </span>
                                        </a>
                                    </li>
                                @endif

                                @if(Auth::user()->hasPermission(Auth::user(), 'list-product') && Session::has('ifBussHasProducts') && Session::get('ifBussHasProducts') == true)
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/products', 'settings/business/products/{id}', 'settings/business/products/{id}/edit'))?'active':'' }}">
                                        <a href="{{ route('products') }}">
                                            <span class="title"> Products List </span>
                                        </a>
                                    </li>
                                @elseif(Auth::user()->hasPermission(Auth::user(), 'create-product'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/products/create'))?'active':'' }}">
                                        <a href="{{ route('products.create') }}">
                                            <span class="title"> Add Product </span>
                                        </a>
                                    </li>
                                @endif

                                @if(Auth::user()->hasPermission(Auth::user(), 'list-client') && Session::has('ifBussHasClients') && Session::get('ifBussHasClients') == true)
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('clients/{filter?}', 'client/{id}', 'clients/{id}/edit','goal-buddy/edit/{id}','goal-buddy/edithabit/{id}','goal-buddy/editgoal/{id}','goal-buddy/edittask/{id}'))?'active':'' }}">
                                        <a href="{{ url('clients') }}">
                                            <span class="title"> Clients List </span>
                                        </a>
                                    </li>
                                @elseif(Auth::user()->hasPermission(Auth::user(), 'create-client'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('clients/create'))?'active':'' }}">
                                        <a href="{{ route('clients.create') }}">
                                            <span class="title"> Add Client </span>
                                        </a>
                                    </li>
                                @endif

                                @if(Auth::user()->hasPermission(Auth::user(), 'list-contact') && Session::has('ifBussHasContacts') && Session::get('ifBussHasContacts') == true)
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/contacts', 'settings/business/contacts/{id}', 'settings/business/contacts/{id}/edit'))?'active':'' }}">
                                        <a href="{{ route('contacts') }}">
                                            <span class="title"> Contacts List </span>
                                        </a>
                                    </li>
                                @elseif(Auth::user()->hasPermission(Auth::user(), 'create-contact'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/contacts/create'))?'active':'' }}">
                                        <a href="{{ route('contacts.create') }}">
                                            <span class="title"> Add Contact </span>
                                        </a>
                                    </li>
                                @endif

                                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/memberships','settings/business/memberships/create','settings/business/memberships/{id}/edit','settings/business/memberships/{id}'))?'active':'' }}">
                                    <a href="{{ route('memberships') }}">
                                        <span class="title">  Membership Options </span>
                                    </a>
                                </li>

                                @if(Session::has('ifBussHasResources') && isUserEligible(['Admin'], 'list-resource'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/resources', 'settings/business/resources/create', 'settings/business/resources/{id}/edit'))?'active':'' }}">
                                        <a href="{{ route('resources.list') }}">
                                            <span class="title"> Resources List </span>
                                        </a>
                                    </li>
                                @elseif(!Session::has('ifBussHasResources') && isUserEligible(['Admin'], 'add-resource'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/resources/create'))?'active':'' }}">
                                        <a href="{{ route('resources.create') }}">
                                            <span class="title"> Add Resource </span>
                                        </a>
                                    </li>
                                @endif
                                

                                @if(Session::has('ifBussHasClosedDates') && isUserEligible(['Admin'], 'list-closed-dates'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/closeddate', 'settings/business/closeddate/create', 'settings/business/closeddate/{id}/edit'))?'active':'' }}">
                                        <a href="{{ route('closeddate.list') }}">
                                            <span class="title"> Closed Dates List </span>
                                        </a>
                                    </li>
                                @elseif(!Session::has('ifBussHasClosedDates') && isUserEligible(['Admin'], 'create-closed-dates'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/closeddate/create'))?'active':'' }}">
                                        <a href="{{ route('closeddate.create') }}">
                                            <span class="title"> Add Closed Date </span>
                                        </a>
                                    </li>
                                @endif

                                @if(isUserEligible(['Admin'], 'edit-calendar-setting'))
                                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/calendar/edit'))?'active':'' }}">
                                         <a href="{{ route('calendar.edit') }}">
                                            <span class="title"> Calendar Settings </span>
                                        </a>
                                    </li>
                                @endif

                                
                                @if(Session::has('ifBussHasAdministrators') && isUserEligible(['Admin'], 'list-administrator'))
                                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/admin', 'settings/business/admin/create', 'settings/business/admin/{id}/edit'))?'active':'' }}">
                                    <a href="{{ route('admin') }}">
                                        <span class="title"> Admins List </span>
                                    </a>
                                </li>
                                @elseif(!Session::has('ifBussHasAdministrators') && isUserEligible(['Admin'], 'create-administrator'))
                                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('settings/business/admin/create'))?'active':'' }}">
                                    <a href="{{ route('admin.create') }}">
                                        <span class="title"> Add Admin </span>
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </li>
                    @else
                        <li class="{{ Request::is('settings/business/create')?'active':'' }}">
                            <a href="{{ url('settings/business/create') }}">
                                <div class="item-content">
                                    <div class="item-media">
                                        <i class="fa fa-suitcase"></i>
                                    </div>
                                    <div class="item-inner">
                                        <span class="title"> Business Setup </span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endif
                @endif
                @if(Session::has('businessId'))
                <li class="{{ Request::is('dashboard/calendar-new')?'active':'' }}">
                    <a href="{{ route('calendar-new') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Calendar </span>
                            </div>
                        </div>
                    </a>
                </li>
                @endif
                @if(isSuperUser() || Auth::user()->hasPermission(Auth::user(), 'show-business-plan'))
                    @if(Session::has('businessId'))   
                        <li class="{{ Request::is('business-plan')?'active':'' }}">
                            <a href="{{ route('businessplan') }}">
                                <div class="item-content">
                                    <div class="item-media">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <div class="item-inner">
                                        <span class="title"> Business Plan </span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endif
                @endif

                {{-- @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-permission-group'))   --}}
                @if(Session::has('businessId') &&  Auth::user()->hasPermission(Auth::user(), 'show-manage-permission')) 
                <li class="{{ Request::is('permission')?'active':'' }}">
                    <a href="{{ url('permission') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-key"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Manage Permissions </span>
                            </div>
                        </div>
                    </a>
                </li>
                @endif

                @if(Auth::user()->hasPermission(Auth::user(), 'list-invoice') || Auth::user()->hasPermission(Auth::user(), 'create-invoices')) 
                    <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('invoices', 'create-invoices','invoices/{id}'))?'active':'' }}">
                        <a href="{{ route('invoices.view') }}">
                            <div class="item-content">
                                <div class="item-media">
                                    <i class="fa fa-bank"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Manage Invoices </span>
                                </div>
                            </div>
                        </a>
                    </li> 
                @endif

                <!-- <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('finance-tool'))?'active open':''}}">
                    <a href="{{ url('finance-tool') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-bank"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Finance Tool </span>
                            </div>
                        </div>
                    </a>
                </li> -->
                @if(Session::has('businessId') && Auth::user()->hasPermission(Auth::user(), 'show-financial-tool'))
                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('financial-tool','financial-tool/settings-and-preferences','new-financial-tool'))?'active open':'' }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-money"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Financial Tool </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">     
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('financial-tool/settings-and-preferences'))?'active':'' }}">
                            <a href="{{ url('financial-tool/settings-and-preferences') }}">
                                <span class="title"> Settings & Preferences </span>
                            </a>
                        </li>
                        
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('new-financial-tool'))?'active':'' }}">
                            <a href="{{ url('new-financial-tool') }}">
                                <span class="title"> New Setup </span>
                            </a>
                        </li>
                         
                    </ul>
                </li>
                @endif

                {{-- @if(isUserType(['Admin']) && Auth::user()->hasPermission(Auth::user(), 'list-exercise') || Auth::user()->hasPermission(Auth::user(), 'list-library-program')) --}}
                @if(Session::has('businessId') && Auth::user()->hasPermission(Auth::user(), 'show-activity-builder'))
                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('activity-builder/exercise','activity-builder/exercise/create','activity-builder/exercise/{id}/edit','activity-builder/library-program','activity-builder/library-program/create','activity-builder/library-program/{id}/edit','activity-builder/generate-program','activity-builder/generate-program/create','activity-builder/generate-program/{id}/edit','activity-builder/exercise/{id}'))?'active open':'' }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-child"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Activity Builder </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        @if(Auth::user()->hasPermission(Auth::user(), 'list-exercise'))    
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('activity-builder/exercise','activity-builder/exercise/create','activity-builder/exercise/{id}/edit','activity-builder/exercise/{id}'))?'active':'' }}">
                            <a href="{{ route('exercise.list') }}">
                                <span class="title"> Exercise List</span>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->hasPermission(Auth::user(), 'list-library-program'))
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('activity-builder/exercise','activity-builder/exercise/create','activity-builder/exercise/{id}/edit','activity-builder/library-program','activity-builder/library-program/create','activity-builder/library-program/{id}/edit','activity-builder/generate-program','activity-builder/generate-program/create','activity-builder/generate-program/{id}/edit','activity-builder/exercise/{id}'))?'active open':'' }}">
                            <a href="javascript:void(0)">
                                <div class="item-content">
                                    <div class="item-inner">
                                        <span class="title"> Library Program </span>
                                        <i class="icon-arrow"></i>
                                    </div>
                                </div>
                            </a>
                            <ul class="sub-menu">
                                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('activity-builder/library-program','activity-builder/library-program/create','activity-builder/library-program/{id}/edit'))?'active':'' }}">
                                    <a href="{{ route('libraryprogram.list') }}">
                                        <span class="title">Single Phase</span>
                                    </a>
                                </li>
                                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('activity-builder/library-program','activity-builder/library-program/create','activity-builder/library-program/{id}/edit'))?'active':'' }}">
                                    <a href="{{ route('libraryprogram.listMultiPhase') }}">
                                        <span class="title">Multi Phase</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(Auth::user()->hasPermission(Auth::user(), 'edit-generate-program'))
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('activity-builder/generate-program'))?'active':'' }}">
                            <a href="{{ route('generateprogram.edit') }}">
                                <span class="title"> Generate Program </span>
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->hasPermission(Auth::user(), 'list-exercise'))    
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('activity-builder/videos','activity-builder/videos/create','activity-builder/videos/{id}/edit','activity-builder/videos/{id}'))?'active':'' }}">
                            <a href="{{ route('videos.list') }}">
                                <span class="title"> Video List</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if(isUserType(['Admin']) && Auth::user()->id == 11) 
                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('business','business/active/{id}','business/inactive/{id}'))?'active open':'' }}">
                    <a href="{{ url('business') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> User list </span>
                            </div>
                        </div>
                    </a>
                </li>
                @endif

                @if(Session::has('businessId') &&  Auth::user()->hasPermission(Auth::user(), 'show-meal-planner'))
                <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('meal-planner','meal-planner/meals','meal-planner/meals/create','meal-planner/meals/edit/{id}','meal-planner/food','meal-planner/food/create','meal-planner/food/edit/{id}','meal-planner/meal-categories','meal-planner/meal-categories/create','meal-planner/meal-categories/edit/{id}','meal-planner/serving-size','meal-planner/serving-size/create','meal-planner/serving-size/edit/{id}','meal-planner/shopping-category','meal-planner/shopping-category/create','meal-planner/shopping-category/edit/{id}','meal-planner/shopping-items','meal-planner/shopping-items/create','meal-planner/shopping-items/edit/{id}','meal-planner/shopping-list','meal-planner/shopping-list/create','meal-planner/shopping-list/edit/{id}','meal-planner/meallogs','meal-planner/meallogs/create','meal-planner/meallogs/edit/{id}'))?'active open':'' }}">
                    <a href="{{ url('meal-planner') }}" id="bb">
                        <div class="item-content">
                            <div class="item-media">
                             <i class="fa fa-cutlery"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Meal Planner</span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu sub-menu-new ">
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('meal-planner/food','meal-planner/food/create','meal-planner/food/edit/{id}'))?'active open':''}}">
                            <a href="{{ url('meal-planner/food') }}">
                                <span class="title"> Ingredient List </span>
                            </a>
                        </li> 
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('meal-planner/meals','meal-planner/meals/create','meal-planner/meals/edit/{id}'))?'active open':''}}">
                            <a href="{{ url('meal-planner/meals') }}">
                                <span class="title"> Recipe List </span>
                            </a>
                        </li> 
                        {{-- <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('meal-planner/meals','meal-planner/meals/create','meal-planner/meals/edit/{id}'))?'active open':''}}"> --}}
                        <li class="">      
                            <a href="{{ url('meal-planner/main-category') }}">
                                <span class="title">Main Category</span>
                            </a>
                        </li> 
                        <li class="">
                            <a href="{{ url('meal-planner/sub-category') }}">
                                <span class="title">Sub Category</span>
                            </a>
                        </li> 
                         
                        <!-- <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('meal-planner/shopping-category','meal-planner/shopping-category/create','meal-planner/shopping-category/edit/{id}'))?'active open':''}}">
                            <a href="{{ url('meal-planner/shopping-category') }}">
                                <span class="title"> Shopping Category </span>
                            </a>
                        </li>  
                        <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('meal-planner/shopping-list','meal-planner/shopping-list/create','meal-planner/shopping-list/edit/{id}'))?'active open':''}}">
                            <a href="{{ url('meal-planner/shopping-list') }}">
                                <span class="title"> Shopping lists </span>
                            </a>
                        </li>  -->
                        <!-- <li class="{{ in_array(Route::getCurrentRoute()->uri(), array('meal-planner/meallogs','meal-planner/meallogs/create','meal-planner/meallogs/edit/{id}'))?'active open':''}}">
                            <a href="{{ url('meal-planner/meallogs') }}">
                                <span class="title"> Meal Logs </span>
                            </a>
                        </li> -->
                                           
                    </ul>
                </li>
                @endif


                @if(Session::has('is_admin_logged_in') == true ||(Session::has('businessId') && Auth::user()->hasPermission(Auth::user(), 'show-pipeline')))
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
                @endif
            </ul>
            <!-- end: MAIN NAVIGATION MENU -->
        </nav>
    </div>
</div>
<!-- / sidebar
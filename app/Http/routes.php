<?php

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

  if(Request::getHost() == 'epic.testingserver.in')
//  if(Request::getPort() == '8000')
    $currHost = 'crm';
else
    $currHost = 'result';



// create log
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('sendNotification','GoalNotification@emailNotification');
Route::get('/test-function', 'UpdateClientsMembershipController@manageRecureWithInvoiceClients');
Route::get('update-staff-event-classes','Helper@allStaffEventsUpdateByCron');
Route::post('intervention-resizeImage',['as'=>'intervention.postresizeimage','uses'=>'FileController@postResizeImage']);
Route::post('remove/image','FileController@removeImage');

Route::get('challenge_invitation', 'Result\ChallengeController@challenge_invitation'); // email button url for challenge
# Remove video
Route::post('remove/video','FileController@removeVideo');
Route::post('video/save','Helper@uploadFile');
Route::post('upload-pdf-file','Setings\Business\LdcController@uploadPdf');
Route::post('save-ldc-data','Setings\Business\LdcController@saveLdcData');
Route::get('ldc-data','Setings\Business\LdcController@showLdcList');

Route::post('client/photo/save','ClientsController@uploadFile');
Route::post('business/photo/save','Setings\Business\\BusinessController@uploadFile');
Route::post('staff/photo/save','Setings\Staff\\StaffController@uploadFile');
Route::post('service/photo/save','Setings\Service\\ServiceController@uploadFile');
Route::post('class/photo/save','Setings\Service\\ClassController@uploadFile');
Route::post('product/photo/save','Setings\Product\\ProductControllerNew@uploadFile');
Route::post('location/photo/save','Setings\Location\\LocationControllerNew@uploadFile');
Route::post('location-area/photo/save','Setings\Location\\LocationAreasController@uploadFile');
Route::post('exercise/photo/save','ActivityBuilder\\ExerciseContoller@uploadFile');
Route::post('save/record-video','MovementController@saveRecordVideo');


Route::post('photo/save','Helper@uploadFile');
Route::post('photo/capture-save','Helper@uploadCaptureFile');
Route::post('photos/delete','Helper@destroyFile');
Route::get('noimage-src','Helper@noimageSrc');
Route::get('send-summary-email','Setings\Calendar\\CalendarSettingController@sendAppointmentSummary');
Route::get('client-address/{id}','Helper@getClientAddress');
Route::get('update-emi','UpdateClientsMembershipController@updateClientNextEmi');
Route::get('update-unit-price','UpdateClientsMembershipController@updateMembPrice');
Route::get('makeList','UpdateClientsMembershipController@makeList');
Route::get('updateRenewAmount','UpdateClientsMembershipController@updateRenewAmount');
Route::get('updateClientMembAmount','UpdateClientsMembershipController@updateClientMembAmount');
Route::get('updateOriginalMembPrice','UpdateClientsMembershipController@updateOriginalMembPrice');
Route::get('updateNextEmiForDiscForever','UpdateClientsMembershipController@updateNextEmiForDiscForever');
Route::get('fetchClassDetail','UpdateClientsMembershipController@fetchClassDetail');


Route::get('privacypolicy','Helper@privacyPolicy');
Route::get('privacypolicy','Helper@privacyPolicyResult');

/**
 * Remove photo
 */
Route::post('remove/photos','Helper@removePic');

if($currHost == 'crm'){
    Route::group(['middleware' => ['web']], function() {

        /**
         * Switch between the included languages
         */
        Route::group(['namespace' => 'Language'], function () {
            require (__DIR__ . '/Routes/Language/Language.php');
        });

        /**
         * Frontend Routes
         * Namespaces indicate folder structure
         */
        Route::group(['namespace' => 'Frontend'], function () {
            require (__DIR__ . '/Routes/Frontend/Frontend.php');
            require (__DIR__ . '/Routes/Frontend/Access.php');
        });
    });
}
elseif($currHost == 'result'){

    Route::group(['namespace'=>'Result','middleware' => ['web']], function () {

        Route::get('/',function(){
            if (!Auth::check()) {
                return redirect()->guest('login');
            }else{
                return redirect()->intended('new-dashboard');
            }


        });
        Route::group(['namespace' => 'Auth'], function () {

        Route::get('password/reset/{token?}', 'PasswordController@showResetForm')
            ->name('auth.password.reset');
        Route::post('password/forgot', 'PasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'PasswordController@reset')->name('password.reset');
});
        Route::get('login/{businessUrl?}', 'UserController@index')->name('login');
        Route::post('login', 'UserController@login');
        Route::post('check-client','UserController@checkClient');
        //Route::get('signup/{businessId?}', 'UserController@register')->name('register');
    });
}

/**
 * Backend Routes
 * Namespaces indicate folder structure
 * Admin middleware groups web, auth, and routeNeedsPermission
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => 'admin'], function () {
    /**
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
    require (__DIR__ . '/Routes/Backend/Dashboard.php');
    require (__DIR__ . '/Routes/Backend/Access.php');
    require (__DIR__ . '/Routes/Backend/LogViewer.php');
});

/**
 * Administration Routes
 * Namespaces indicate folder structure
 * Admin middleware groups web, auth, and routeNeedsPermission
 */
Route::group(['namespace' => 'Administration', 'prefix' => 'administration', 'middleware' => 'admin'], function () {
    /**
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
    require (__DIR__ . '/Routes/Administration/Dashboard.php');
    require (__DIR__ . '/Routes/Administration/Access.php');
    require (__DIR__ . '/Routes/Administration/LogViewer.php');
});

/**
 * Super Admin Routess
 */
Route::group(['prefix' => 'epic-super-admin','namespace' => 'SuperAdmin','middleware' => ['web']], function(){
    Route::get('login','LoginController@showLoginForm')->name('superadmin.login');
    Route::post('login','LoginController@authenticate')->name('superadmin.authenticate');
    Route::get('logout','LoginController@logout')->name('superadmin.logout');
    Route::group(['middleware' => ['superAdminAuth']], function(){
        Route::get('dashboard','SuperAdminController@dashboard')->name('superadmin.dashboard');
        Route::get('business-accounts','BusinessAccountController@index')->name('superadmin.businessAccount.index');
        Route::get('business-accounts/edit/{id}','BusinessAccountController@edit')->name('superadmin.businessAccount.edit');
        Route::post('business-accounts/edit/{id}','BusinessAccountController@update')->name('superadmin.businessAccount.update');
        Route::get('business-accounts/delete/{id}','BusinessAccountController@delete')->name('superadmin.businessAccount.delete');
        Route::get('business-accounts/view/{id}','BusinessAccountController@view')->name('superadmin.businessAccount.view');
        Route::get('business-accounts/send-confirmation-mail/{id}','BusinessAccountController@sendConfirmationEmail')->name('superadmin.businessAccount.sendConfirmationEmail');
        Route::resource('users-limit','UsersLimitController');
        Route::get('users-limit/delete/{id}','UsersLimitController@destroy')->name('users-limit.delete');

        // Gallery Section
        Route::get('category/list', 'GalleryController@index')->name('superadmin.gallery.category.list');
        Route::get('add/category', 'GalleryController@addCategory')->name('superadmin.add.gallery.category');
        Route::post('save/category', 'GalleryController@saveCategory')->name('superadmin.save.gallery.category');
        Route::get('delete/category/{id}', 'GalleryController@deleteCategory')->name('superadmin.category.destroy');
        Route::post('save/subcategory', 'GalleryController@saveSubCategory')->name('superadmin.save.subcategory');
        Route::get('go/back/{id}', 'GalleryController@goBack');
        Route::get('images/{id}', 'GalleryController@allImages')->name('superadmin.images');
        Route::get('add/images/{id}', 'GalleryController@addImages')->name('superadmin.images.list');
        Route::post('save/images', 'GalleryController@saveImages')->name('superadmin.save.images');
        Route::get('edit/image/{id}', 'GalleryController@editImages')->name('superadmin.edit.image');
        Route::post('update/image', 'GalleryController@updateImage')->name('superadmin.image.update');
        Route::get('delete/image/{id}', 'GalleryController@deleteImage');
        Route::post('delete/images', 'GalleryController@deleteImages');
//End Gallery Section
    });
});


    # Getting client event invoice
    Route::get('clients-event-invoice', 'ClientsController@getClientEventInvoice');
    
    # Get event client booking details
    Route::get('client/booking-details', 'ClientsController@getClientEventBookingDetails');

if($currHost == 'crm'){
    /**
     * All business setting goes here.
     * Routes for only crm
     */
    Route::group(['middleware' => ['web', 'auth', 'member','attendence']], function(){

        Route::get('upload', function(){
            return view('Settings.upload');
        });

        /**
         * Privacy Policy Route
         */

         /* Get client membership id */
        Route::get('/client/membership/{membershipId}', 'ClientsController@getMembershipId');
        Route::post('epic/store-nutritional','ClientsController@storeNutritionalJournal');
        Route::post('epic/store-sleep-questionnaire','ClientsController@storeSleepQuestionnaire');
        Route::post('epic/chronotype-survey','ClientsController@storeChronotypeSurvey');
        /* Get client Membership details*/
        Route::get('/membership/{membershipId}', 'ClientsController@getMembershipDetails');

        /*Get Client future recure classes*/
        Route::get('/client/future-recure-classes/{clientId}', 'ClientsController@getFutureRecureClasses');

        /* Start : only for testing purpose */
            /*Route::get('test/{id}','TestingController@index');
            Route::get('staff-payment-hourly/{id}','TestingController@staffPaymentHourly');
            Route::get('staff-payment-event','TestingController@staffPaymentEvent');*/
            Route::get('set-client-birthday','TestingController@setClientBirthDay');
            /*Route::get('update-birthday-catogry','TestingController@updateTaskCat');
            Route::get('check-membership/{id}', 'TestingController@checkMembership');
            Route::get('set-product-slug','TestingController@setProductSlug');
            Route::get('set-category-slug','TestingController@setCategorySlug');
            Route::get('copy-notes-makeup','TestingController@copyExtraDataFromClientNotesToMakeup');
            Route::get('set-clientid-goalbuddy','TestingController@setClientIdInGoalBuddy');
            Route::get('member-ship-test','TestingController@membershipTest');*/
            Route::get('update-membership-limit','TestingController@updateClientMemberLimit'); 
            /*Route::get('get-existing-invoice','TestingController@existingInvoice');
            Route::get('update-event-area','TestingController@updateEventArea'); */
            Route::get('update-event-recurr-client', 'TestingController@updateEventRecurrClient');
            Route::get('update-event-client', 'TestingController@updateEventClient');
            Route::get('event-client-invoice', 'TestingController@updateEventClientInvoice');
            Route::get('update-client-with-invoice', 'TestingController@updateClientWithInvoice');
            Route::get('update-client-memebership-class', 'TestingController@updateMemberShipClass');
            //Route::get('reset-event-client', 'TestingController@resetEventClient');
            //Route::get('force-delete-event', 'TestingController@destroyEvent');

            Route::get('test-memb-test', 'TestingController@resetMembershipLimit');
            Route::get('clint-status-member', 'TestingController@changeMembershipAccToStatus');
            
        /* End: onnly for testing purpose */

        /* Loack Screen */
        Route::get('lock/user','LockScreenController@lockuser');
        Route::post('unlock/user','LockScreenController@unlockuser');

        /*Log viewer*/
        Route::get('view-logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
        

        Route::group(['prefix'=>'settings/ldc', 'namespace' => 'Setings\Business'], function(){
            Route::get('session', 'LdcController@index')->name('ldc.session');
            Route::get('create', 'LdcController@create')->name('ldc.create');
            Route::get('edit/{id}', 'LdcController@edit')->name('ldc.edit');
            Route::get('delete/{id}', 'LdcController@delete')->name('ldc.delete');
           
        });

        Route::group(['prefix'=>'settings/businesses', 'namespace' => 'Setings\Business'], function(){
            Route::get('edit', 'BusinessController@editt')->name('business.editt');
            Route::patch('{id}', 'BusinessController@updatee')->name('business.updatee');
            Route::post('class-step/change-status','BusinessController@changeStepStatus');
        });
        Route::group(['prefix' => 'business-types', 'namespace' => 'Setings\Business'], function(){
            Route::get('','BusinessTypeController@index')->name('business.getType');
            Route::post('','BusinessTypeController@save');
            Route::delete('{id}','BusinessTypeController@destroy');
        });

        /* Start::Business plan routes */
            Route::group(['prefix' => 'business-plan'], function(){
                Route::get('', 'BusinessPlanController@index')->name('businessplan');
                Route::post('', 'BusinessPlanController@store');
            });
        /* End::Business plan routes */

        /* Start: Edit field from view of entity */
        Route::group(['namespace' => 'Setings'], function(){
            Route::post('product/edit-view-field', 'Product\ProductControllerNew@updateField');
        });
        /* End: Edit field from view of entity */

        Route::group(['prefix' => 'settings/business'], function(){        
            Route::group(['prefix' => 'locations', 'namespace' => 'Setings\Location'], function(){
                Route::get('', 'LocationControllerNew@index')->name('locations');
                Route::get('create', 'LocationControllerNew@create')->name('locations.create');
                Route::post('', 'LocationControllerNew@store')->name('locations.store');
                Route::get('{id}', 'LocationControllerNew@show')->name('locations.show');
                Route::get('{id}/edit', 'LocationControllerNew@edit')->name('locations.edit');
                Route::patch('{id}', 'LocationControllerNew@update')->name('locations.update');
                Route::get('{locationid}/areas', 'LocationAreasController@index')->name('locations.areas'); 
                Route::delete('{id}', 'LocationControllerNew@destroy')->name('locations.destroy');
            });

            Route::group(['prefix' => 'areas', 'namespace' => 'Setings\Location'], function(){
                Route::get('create/{id}', 'LocationAreasController@create')->name('areas.create');
                Route::post('', 'LocationControllerNew@store')->name('areas.store');
                Route::get('common-staffs','LocationAreasController@commonStaffs');
                Route::get('{id}', 'LocationAreasController@show')->name('areas.show');
                Route::get('{id}/edit', 'LocationAreasController@edit')->name('areas.edit');
                Route::patch('{id}', 'LocationAreasController@update')->name('areas.update');
                Route::delete('{id}', 'LocationAreasController@destroy')->name('areas.destroy');
            });

            Route::group(['prefix' => 'staffs', 'namespace' => 'Setings\Staff'], function(){ 
                Route::get('', 'StaffController@index')->name('staffs');
                Route::get('create', 'StaffController@create')->name('staffs.create');
                Route::post('', 'StaffController@store')->name('staffs.store');
                Route::get('attendences','StaffController@listattendences')->name('staffs.listattendences');
                Route::get('new-roster','StaffController@newroster')->name('staffs.newroster');
                Route::get('report','StaffController@monthlyattendences')->name('staffs.monthlyattendences');
                Route::get('report/{id}','StaffController@getMonthlyAttendenceData');
                Route::get('{id}', 'StaffController@show')->name('staffs.show');
                Route::get('{id}/edit', 'StaffController@edit')->name('staffs.edit');
                Route::patch('{id}', 'StaffController@update')->name('staffs.update');
               
                Route::post('{id}/update-field','StaffController@updateField');
                Route::delete('{id}', 'StaffController@destroy')->name('staffs.destroy');  
            });

            Route::group(['prefix' => 'services', 'namespace' => 'Setings\Service'], function(){
                Route::get('', 'ServiceController@index')->name('services');
                Route::get('create', 'ServiceController@create')->name('services.create');
                Route::get('expired-tasks','ServiceController@getExpiredTask');
                Route::post('', 'ServiceController@store')->name('services.store');
                Route::get('all', 'ServiceController@all');
                Route::get('all-by-condition', 'ServiceController@allServiceByCondition');
                Route::get('sales-process/{step}', 'ServiceController@getOfSalesProcessStep');
                Route::get('client/{clientId}', 'ServiceController@getOfClient');
                Route::get('single-service/{bookingId}', 'ServiceController@getOfBooking');
                Route::get('free-staffs','ServiceController@freeStaffs');
                Route::get('free-areas','ServiceController@freeAreas');
                Route::get('{id}', 'ServiceController@show')->name('services.show');
                Route::get('{id}/edit', 'ServiceController@edit')->name('services.edit');
                Route::patch('{id}', 'ServiceController@update')->name('services.update');
                Route::delete('{id}', 'ServiceController@destroy')->name('services.destroy');
                Route::get('{id}/check-association', 'ServiceController@checkAssociation')->name('services.checkAssociation');
                Route::get('{id}/free-staffs','ServiceController@freeStaffs2');
                Route::get('{id}/free-areas','ServiceController@freeAreas2');
                Route::get('{id}/resources','ServiceController@freeResources');
                Route::post('{id}/update-field','ServiceController@updateField');
            });

            /* Start: Busy time routes */
                Route::get('busy-time/free-areas', 'BusyTimeController@freeAreas');
                Route::get('busy-time/free-staffs', 'BusyTimeController@freeStaffs');
                Route::post('busy-time/get-available-staff', 'BusyTimeController@getStaffsArea');

            /* End: Busy time routes */

            Route::group(['prefix' => 'service-categories', 'namespace' => 'Setings\Service'], function(){
                Route::get('','ServiceCatController@index')->name('services.getCat');
                Route::post('','ServiceCatController@save');
                Route::delete('{id}','ServiceCatController@destroy');
            });

            Route::group(['prefix' => 'service-types', 'namespace' => 'Setings\Service'], function(){
                Route::get('','ServiceCatController@typeIndex')->name('services.getType');
                Route::post('','ServiceCatController@typeSave');
                Route::delete('{id}','ServiceCatController@typeDestroy');
            });

            Route::group(['prefix' => 'classes', 'namespace' => 'Setings\Service'], function(){
                Route::get('', 'ClassController@index')->name('classes');
                Route::get('create', 'ClassController@create')->name('classes.create');
                Route::post('', 'ClassController@store')->name('classes.store');
                Route::get('all', 'ClassController@all');
                Route::get('all-by-condition', 'ClassController@allClassesByCondition');
                Route::get('all-class-type', 'ClassController@allClassType');
                Route::get('{id}', 'ClassController@show')->name('classes.show');
                Route::get('{id}/edit', 'ClassController@edit')->name('classes.edit');
                Route::patch('{id}', 'ClassController@update')->name('classes.update');
                Route::delete('{id}', 'ClassController@destroy')->name('classes.destroy');
                Route::get('{id}/check-association', 'ClassController@checkAssociation')->name('classes.checkAssociation');
                Route::get('{id}/free-staffs','ClassController@freeStaffs');
                Route::get('{id}/free-areas','ClassController@freeAreas');
                Route::get('{id}/resources','ClassController@freeResources');
                Route::post('{id}/update-field','ClassController@updateField');
            });

            Route::group(['prefix' => 'class-categories', 'namespace' => 'Setings\Service'], function(){
                Route::get('','ClassCatController@index')->name('class.getCat');
                Route::post('','ClassCatController@save');
                Route::delete('{id}','ClassCatController@destroy');
            });

            Route::group(['prefix' => 'products', 'namespace' => 'Setings\Product'], function(){
                Route::get('', 'ProductControllerNew@index')->name('products');
                Route::get('create', 'ProductControllerNew@create')->name('products.create');
                Route::post('', 'ProductControllerNew@store')->name('products.store');
                Route::get('{id}', 'ProductControllerNew@show')->name('products.show');
                Route::get('{id}/edit', 'ProductControllerNew@edit')->name('products.edit');
                Route::get('{id}/clone', 'ProductControllerNew@productClone')->name('products.clone');
                Route::patch('{id}', 'ProductControllerNew@update')->name('products.update');
                Route::delete('{id}', 'ProductControllerNew@destroy')->name('products.destroy');
            });

            Route::group(['prefix' => 'product-subcategories', 'namespace' => 'Setings\Product'], function(){
                Route::get('','ProductCatController@index2')->name('product.getSubCat');
                Route::post('','ProductCatController@save2');
                Route::delete('{id}','ProductCatController@destroy2');
            });

            Route::group(['prefix' => 'product-size', 'namespace' => 'Setings\Product'], function(){
                Route::get('','ProductSizeController@index')->name('product.getSize');
                Route::post('','ProductSizeController@save');
                Route::delete('{id}','ProductSizeController@destroy');
            });

            Route::group(['prefix' => 'contacts', 'namespace' => 'Setings\Contact'], function(){
                Route::get('', 'ContactController@index')->name('contacts');
                Route::get('create', 'ContactController@create')->name('contacts.create');
                Route::post('', 'ContactController@store')->name('contacts.store');
                Route::get('{id}', 'ContactController@show')->name('contacts.show');
                Route::get('{id}/edit', 'ContactController@edit')->name('contacts.edit');
                Route::patch('{id}', 'ContactController@update')->name('contacts.update');
                Route::delete('{id}', 'ContactController@destroy')->name('contacts.destroy');
            });

            Route::group(['prefix' => 'contact-types', 'namespace' => 'Setings\Contact'], function(){
                Route::get('','ContactTypeController@index')->name('contact.getType');
                Route::post('','ContactTypeController@save');
                Route::delete('{id}','ContactTypeController@destroy');
            });

            Route::group(['prefix' => 'resources', 'namespace' => 'Setings\Resource'], function(){
                Route::get('', 'ResourceController@index')->name('resources.list');
                Route::get('create', 'ResourceController@create')->name('resources.create');
                Route::get('{id}/edit', 'ResourceController@edit')->name('resources.edit');
                Route::get('{locId}','ResourceController@getLocResources');
                Route::post('', 'ResourceController@store')->name('resources.store');
                Route::patch('{id}', 'ResourceController@update')->name('resources.update');
                Route::delete('{id}', 'ResourceController@destroy')->name('resources.destroy');
            });

            Route::group(['prefix' => 'calendar', 'namespace' => 'Setings\Calendar'], function(){
                Route::get('edit', 'CalendarSettingController@edit')->name('calendar.edit');
                Route::patch('{id}', 'CalendarSettingController@update');
            });

            Route::group(['prefix' => 'closeddate', 'namespace' => 'Setings\Closeddate'], function(){
                Route::get('', 'ClosedDateController@index')->name('closeddate.list');
                Route::get('create', 'ClosedDateController@create')->name('closeddate.create');
                Route::get('{id}/edit','ClosedDateController@edit')->name('closeddate.edit');
                Route::post('', 'ClosedDateController@store')->name('closeddate.store');
                Route::patch('{id}', 'ClosedDateController@update')->name('closeddate.update');
                Route::delete('{id}','ClosedDateController@destroy')->name('closeddate.destroy');
            });

            Route::group(['prefix' => 'admin', 'namespace' => 'Setings\Admin'], function(){
                Route::get('', 'AdminController@index')->name('admin');
                Route::get('create', 'AdminController@create')->name('admin.create');
                Route::get('{id}/edit','AdminController@edit')->name('admin.edit');  
                Route::post('', 'AdminController@store')->name('admin.store');  
                Route::patch('{id}', 'AdminController@update')->name('admin.update');
                Route::delete('{id}','AdminController@destroy')->name('admin.destroy');
            });

            Route::group(['prefix' => 'memberships', 'namespace' => 'Setings\Membership'], function(){
                Route::get('', 'MembershipController@index')->name('memberships');
                Route::get('create', 'MembershipController@create')->name('memberships.create');
                Route::post('', 'MembershipController@store')->name('membership.store');
                Route::post('addmembershiptax', 'MembershipController@storemembertax')->name('memberships.addtaxlabel');
                Route::get('{id}', 'MembershipController@show')->name('membership.show');
                Route::get('{id}/edit', 'MembershipController@edit')->name('membership.edit');
                Route::patch('{id}', 'MembershipController@update')->name('membership.update');
                Route::post('membershipcategory','MembershipController@createCategory');
                Route::post('membershipgroup','MembershipController@createMemberGroup');
                Route::post('incomecategory','MembershipController@createIncomeCategory');
                Route::delete('{id}', 'MembershipController@destroy')->name('membership.destroy');

            });

            Route::group(['prefix' => 'memberships-categories', 'namespace' => 'Setings\Membership'], function(){
                Route::get('','MembershipCatController@index')->name('membership.getCat');
                Route::post('','MembershipCatController@save');
                Route::delete('{id}','MembershipCatController@destroy');
            });

            Route::group(['prefix' => 'income-categories', 'namespace' => 'Setings\Membership'], function(){
                Route::get('','IncomeCatController@index')->name('income.getCat');
                Route::post('','IncomeCatController@save');
                Route::delete('{id}','IncomeCatController@destroy');
            });

            Route::post('per-session/roles','Setings\Staff\StaffController@perSessionRoles');
            Route::post('commission/roles','Setings\Staff\StaffController@commissionRoles');
            Route::post('commission/category','Setings\Staff\StaffController@commissionCategory');
            Route::post('commission/source','Setings\Staff\StaffController@commissionSource');
        });
        Route::group(['prefix' => 'settings/gallery'], function(){        
                Route::group(['namespace' => 'Setings\Gallery'], function(){
                Route::get('add/category', 'GalleryController@addCategory')->name('add.gallery.category');
                Route::get('category', 'GalleryController@categoryList')->name('category');
                Route::post('save/category', 'GalleryController@saveCategory')->name('save.gallery.category');
                Route::get('edit/category/{id}', 'GalleryController@addCategory')->name('edit.gallery.category');
                Route::get('category/list', 'GalleryController@index')->name('gallery.category.list');
                Route::post('update/category', 'GalleryController@updateCategory')->name('category.update');
                Route::get('delete/category/{id}', 'GalleryController@deleteCategory')->name('category.destroy');
                Route::get('go/back/{id}', 'GalleryController@goBack');
                Route::post('save/subcategory', 'GalleryController@saveSubCategory')->name('save.subcategory');

                Route::get('images/{id}', 'GalleryController@allImages')->name('images');
                Route::get('add/images/{id}', 'GalleryController@addImages')->name('images.list');
                Route::post('save/images', 'GalleryController@saveImages')->name('save.images');
                Route::get('edit/image/{id}', 'GalleryController@editImages')->name('edit.image');
                Route::post('update/image', 'GalleryController@updateImage')->name('image.update');
                Route::get('delete/image/{id}', 'GalleryController@deleteImage');
                Route::post('delete/images', 'GalleryController@deleteImages');
            });
        });
        Route::resource('settings/business', 'Setings\Business\\BusinessController');
        Route::post('settings/business/type','Setings\Business\\BusinessController@typeSave');
        Route::post('settings/business/serviceType','Setings\Service\\ServiceController@typeSave');
        Route::post('settings/business/serviceCat','Setings\Service\\ServiceController@catSave');
        Route::get('location/{id}/hours','Setings\Location\\LocationControllerNew@getHours');
        Route::get('location/{id}/areas','Setings\Location\\LocationControllerNew@getAreas');
        Route::get('staffs/all','Setings\Staff\\StaffController@allStaffs');
        Route::get('contacts/all','Setings\Contact\\ContactController@allContacts');
        Route::post('settings/business/contactType','Setings\Contact\\ContactController@typeSave');
        
        Route::get('staff-attendence/edit/{id}', 'AttendenceRoasterController@edit');
        Route::post('staff-attendence/mark-attendence/{id}', 'AttendenceRoasterController@store');
        Route::post('staff-attendence/add-attendence', 'AttendenceRoasterController@save');
        Route::delete('staff-attendence/delete-attendence/{id}', 'AttendenceRoasterController@distroy');
        
        Route::resource('settings/location', 'Setings\Location\\LocationControllerNew');
        Route::resource('settings/staff', 'Setings\Staff\\StaffController');
        Route::resource('settings/service', 'Setings\Service\\ServiceController');
        Route::resource('settings/product', 'Setings\Product\\ProductControllerNew');
        Route::resource('settings/client', 'Setings\Client\\ClientController');
        Route::resource('settings/contact', 'Setings\Contact\\ContactController');
        
        Route::get('/dashboard/calendar', 'CalendarController@index');
        Route::post('/dashboard/calendar', 'CalendarController@store');
        Route::get('/dashboard/calendar-new', 'CalendarController@indexNew')->name('calendar-new');
        Route::get('/dashboard/total/hours', 'CalendarController@getTotalHours');
        
        Route::get('permission', 'PermissionController@index');
        Route::post('manage-permission/create', 'PermissionController@store');
        Route::post('manage-permission/delete', 'PermissionController@destroy');
        Route::post('add-group-type', 'PermissionController@addGroup');
        Route::post('delete-group-type', 'PermissionController@deleteGroup');
        
        Route::get('staffs/hours','Setings\Staff\\StaffController@getHours');
        Route::post('staff-hours/{id}','Setings\Staff\\StaffController@setHours');
        Route::get('staffs/editHours','Setings\Staff\\StaffController@getEditHours');
        Route::post('staffs/editHours/{id}','Setings\Staff\\StaffController@setEditHours');
        Route::post('staffs/editHours/reset/{id}','Setings\Staff\\StaffController@resetEditHours');
        
        Route::get('staffevents/appointments','Setings\Staff\\StaffEventController@index');
        Route::get('staffevents/appointment/{eventId}','Setings\Staff\\StaffEventController@show');
        Route::get('staffs/services','Setings\Staff\\StaffController@getServicesByArea');
        Route::post('staffevents/appointments/create','Setings\Staff\\StaffEventController@store');
        Route::post('staffevents/appointments/edit','Setings\Staff\\StaffEventController@update');
        Route::post('staffevents/appointments/delete','Setings\Staff\\StaffEventController@destroyAppointment');
        Route::post('staffevents/appointments/reschedule','Setings\Staff\\StaffEventController@reschedule');
        Route::post('staffevents/appointments/change-status','Setings\Staff\\StaffEventController@setStatus');
        Route::get('areas/staffs','Setings\Location\\LocationAreasController@getStaffs');
        Route::get('areas/has-rostered-staffs','Setings\Location\\LocationAreasController@hasRosteredStaffs');

        Route::group(['prefix' => 'commission-roles', 'namespace' => 'Setings\Staff'], function(){
            Route::get('','StaffRoleController@commissionIndex')->name('commission.getRole');
            Route::post('','StaffRoleController@commissionSave');
            Route::delete('{id}','StaffRoleController@commissionDestroy');
        });

        Route::group(['prefix' => 'commission-sources', 'namespace' => 'Setings\Staff'], function(){
            Route::get('','StaffRoleController@sourceIndex')->name('commission.getSource');
            Route::post('','StaffRoleController@sourceSave');
            Route::delete('{id}','StaffRoleController@sourceDestroy');
        });

         Route::group(['prefix' => 'session-roles', 'namespace' => 'Setings\Staff'], function(){
            Route::get('','StaffRoleController@sessionIndex')->name('session.getRole');
            Route::post('','StaffRoleController@sessionSave');
            Route::delete('{id}','StaffRoleController@sessionDestroy');
        });

        Route::group(['prefix' => 'clients'], function(){
            Route::get('csv-export', 'ClientsController@csvExport')->name('status.export');
            Route::get('all', 'ClientsController@allClients');
            Route::get('create', 'ClientsController@create')->name('clients.create');
            Route::get('print-appointments', 'ClientsController@printAppointments')->name('clients.print');
            Route::get('{filter?}','ClientsController@index')->name('clients');
            Route::get('{id}/co', 'ClientsController@coClients');
            Route::get('{id}/edit', 'ClientsController@edit')->name('clients.edit');
            Route::patch('{id}', 'ClientsController@update')->name('clients.update');
            Route::get('operate-as-client/{id}','ClientsController@operateAsClient')->name('clients.operateAsClient');

            Route::delete('{id}', 'ClientsController@destroy')->name('clients.destroy');
            
            Route::post('', 'ClientsController@save')->name('clients.store');
            Route::post('raise-make-up', 'ClientsController@raiseMakeUp');
            Route::get('makeup-netamount/{id}', 'ClientsController@makeupNetamount');
            Route::patch('raise-make-up/{id}', 'MakeupController@update');
            Route::delete('makeup/{id}', 'MakeupController@destroy')->name('makeup.destroy');
            Route::get('getnotes/{id}', 'MakeupController@getNotes');
            Route::post('sales-process/price-emailed', 'ClientsController@priceEmailed');
            Route::post('sales-process/update', 'ClientsController@updateSalesProcess');
            Route::post('membership/update', 'ClientsController@updateMembership');
            Route::post('{id}/membership/delete', 'ClientsController@deleteMembership');
            Route::post('membership/makeup', 'ClientsController@setMembershipEpic');
            Route::get('membership/services','ClientsController@getMembService');

            Route::patch('{id}/sales-process-settings', 'ClientsController@salesProcSettings')->name('clients.salesProcSett');

            Route::post('movement/save', 'MovementController@store');
            Route::get('movement/edit/{id}', 'MovementController@edit');
            Route::post('movement/update/{id}', 'MovementController@update');
            Route::post('movement/steps', 'MovementController@updateMovementSteps');
            Route::delete('movement/{id}', 'MovementController@destroy')->name('movement.destroy');
            Route::post('{clientId}/menues/', 'ClientsController@saveMenues')->name('menu.save');

            Route::post('save/measurement', 'MeasurementFileController@saveFile');
            Route::get('edit/measurement/{id}', 'MeasurementFileController@editFile');
            Route::get('download/measurement/{id}', 'MeasurementFileController@downloadFile');
            Route::get('delete/measurement/{id}', 'MeasurementFileController@deleteFile');
        });


        # Checking client satisfied membership or not
        Route::get('client-membership', 'ClientsController@isClientMembershipSatisfy');

        // Check for client LDC Satisfied or not
        Route::get('client-ldc','ClientsController@isClientLdcSatisfy');

        Route::group(['prefix' => 'activity-builder/exercise','namespace' => 'ActivityBuilder'], function() {
            Route::get('', 'ExerciseContoller@index')->name('exercise.list');
            Route::get('validate-exercise-name', 'ExerciseContoller@validateName');
            Route::get('create', 'ExerciseContoller@create')->name('exercise.create');
            Route::post('save', 'ExerciseContoller@store')->name('exercise.store');
            Route::get('{id}/edit', 'ExerciseContoller@edit')->name('exercise.edit');
            Route::get('{id}/clone', 'ExerciseContoller@clone')->name('exercise.clone');

            Route::patch('update/{id}', 'ExerciseContoller@update')->name('exercise.update');
            Route::get('{id}', 'ExerciseContoller@show')->name('exercise.show');
            Route::delete('{id}', 'ExerciseContoller@destroy')->name('exercise.destroy');
        });

        /**
         * Routes for Activity Videos
         */
        Route::group(['prefix' => 'activity-builder/videos','namespace' => 'ActivityBuilder'], function() {
            Route::get('', 'VideoController@index')->name('videos.list');
            Route::get('create', 'VideoController@create')->name('videos.create');
            Route::post('save', 'VideoController@store')->name('videos.store');
            Route::get('view/{id}', 'VideoController@show')->name('videos.show');
            Route::get('{id}/edit', 'VideoController@edit')->name('videos.edit');
            Route::post('update/{id}', 'VideoController@update')->name('videos.update');
            Route::delete('{id}', 'VideoController@destroy')->name('videos.destroy');
        });

        Route::group(['prefix' => 'activity-builder/library-program/single-phase','namespace' => 'ActivityBuilder'], function() {
            Route::get('', 'LibraryProgramContoller@index')->name('libraryprogram.list');
            Route::get('create', 'LibraryProgramContoller@create')->name('libraryprogram.create');
            Route::post('save', 'LibraryProgramContoller@store')->name('libraryprogram.store');
            Route::get('{id}/edit', 'LibraryProgramContoller@edit')->name('libraryprogram.edit');
            Route::patch('update/{id}', 'LibraryProgramContoller@update')->name('libraryprogram.update');
            Route::delete('{id}', 'LibraryProgramContoller@destroy')->name('libraryprogram.destroy');
        });

        Route::group(['prefix' => 'activity-builder/library-program/multi-phase','namespace' => 'ActivityBuilder'], function() {
            Route::get('', 'LibraryProgramContoller@indexMultiPhase')->name('libraryprogram.listMultiPhase');
            Route::get('create', 'LibraryProgramContoller@createMultiPhase')->name('libraryprogram.createMultiPhase');
            Route::post('save', 'LibraryProgramContoller@storeMultiPhase')->name('libraryprogram.storeMultiPhase');
            Route::get('{id}/edit', 'LibraryProgramContoller@editMultiPhase')->name('libraryprogram.editMultiPhase');
            Route::patch('update/{id}', 'LibraryProgramContoller@multiPhaseUpdate')->name('libraryprogram.multiPhaseUpdate');
            Route::delete('{id}', 'LibraryProgramContoller@destroy')->name('libraryprogram.destroy');
            Route::get('getProgramDetails', 'LibraryProgramContoller@getProgramDetails');
            Route::get('create-program', 'LibraryProgramContoller@createProgram');
            Route::get('update-program', 'LibraryProgramContoller@UpdateProgram');
            Route::post('plan-preview', 'LibraryProgramContoller@planPreview');
            Route::get('update-plan','LibraryProgramContoller@updatePlan');
            Route::get('get-phase-data','LibraryProgramContoller@getPhaseData');
        });

        Route::group(['prefix' => 'activity-builder/generate-program','namespace' => 'ActivityBuilder'], function() {
            Route::get('', 'GenerateProgramContoller@edit')->name('generateprogram.edit');
            Route::get('show', 'GenerateProgramContoller@show');
        });

        Route::group(['prefix' => 'manage-equipments', 'namespace' => 'ActivityBuilder'], function(){
            Route::get('','EquipmentsController@index')->name('exercise.getEquip');
            Route::post('','EquipmentsController@save');
            Route::delete('{id}','EquipmentsController@destroy');
        });

        Route::group(['prefix' => 'manage-abilities', 'namespace' => 'ActivityBuilder'], function(){
            Route::get('','AbilityController@index')->name('exercise.getAblity');
            Route::post('','AbilityController@save');
            Route::delete('{id}','AbilityController@destroy');
        });

        Route::group(['prefix' => 'manage-body-parts', 'namespace' => 'ActivityBuilder'], function(){
            Route::get('','BodyPartController@index')->name('exercise.getBodypart');
            Route::post('','BodyPartController@save');
            Route::delete('{id}','BodyPartController@destroy');
        });

        Route::group(['prefix' => 'manage-exercise-type', 'namespace' => 'ActivityBuilder'], function(){
            Route::get('','ExerciseTypeController@index')->name('exercise.getExeType');
            Route::post('','ExerciseTypeController@save');
            Route::delete('{id}','ExerciseTypeController@destroy');
        });

        Route::group(['prefix' => 'manage-movement-pattern', 'namespace' => 'ActivityBuilder'], function(){
            Route::get('','MovementController@index')->name('exercise.getMovement');
            Route::post('','MovementController@save');
            Route::delete('{id}','MovementController@destroy');
        });

        Route::get('client/download-gallery-image/{id}/{name}','ClientsController@downloadGalleryImage')->name('image.download');
        Route::post('client/save','ClientsController@save');
        Route::post('client/{id}/update-field','ClientsController@updateField');
        Route::post('client/change-status','ClientsController@changeStatus');
        Route::get('client/{id}','ClientsController@show')->name('clients.show');
        Route::post('client-credits','Setings\Client\\ClientCreditController@store');
        Route::post('saveparq','ParqController@parqSave');
        Route::post('waiver/save','ParqController@waiverSave');
        Route::post('getClientInfo','ParqController@getClient');
        Route::get('client/add-progress/{client_id}','ClientsController@addProgressForm');
        Route::post('client/save-progress','ClientsController@saveTempProgress');
        Route::post('client/save-final-progress','ClientsController@saveProgress');
        Route::post('client/check-progress-photo-exist','ClientsController@checkProgressPhotoExist');
        Route::post('client/delete-gallery-image','ClientsController@deleteGalleryImage');
        Route::get('client/gallery/{gallery_id}','ClientsController@showGallery');
        Route::post('client/ajax-show-gallery/{gallery_id}','ClientsController@showGallery');
        Route::post('client/check-gallery-photo-exist','ClientsController@checkGalleryPhotoExist');
        Route::post('client/edit-gallery','ClientsController@editGallery');
        // Route::post('client/add-gallery-image','ClientsController@addGalleryImage');
        // Route::post('client/add-before-after','ClientsController@addBeforeAfter');
        // Route::post('client/delete-before-after','ClientsController@deleteBeforeAfter');
       
        Route::post('save/heightWeight','PostureController@saveHeightWeight');
        Route::post('save/unit','ClientsController@saveUnit');

        Route::post('posture/image','PostureController@uploadFile');
        Route::post('captcha/image','PostureController@uploadCaptureFile');
        Route::post('save/posture/image','PostureController@savePostureImage');
        Route::post('save/captcha/image','PostureController@saveCaptchaImage');

        Route::post('posture/analysis', 'PostureController@postureAnalysis');
        Route::post('store/coordinates', 'PostureController@storeCoordinates');
        Route::post('store/angle', 'PostureController@storeAngles');
        Route::post('reset/analysis', 'PostureController@resetAnalysis');
        Route::post('undo/analysis', 'PostureController@undoAnalysis');
        Route::post('edit/analysis', 'PostureController@editAnalysis');
        Route::post('preview/analysis', 'PostureController@previewAnalysis');
        Route::get('generate/pdf/{id}', 'PostureController@generatePdf');
        Route::post('preview/mailpdf', 'PostureController@mailpdf');
        Route::post('preview/deleteReport', 'PostureController@deleteReport');
        Route::post('remove/image', 'PostureController@deleteImage');
        Route::post('save/note', 'PostureController@saveNote');


        /**
         * Client Activity Planner New Routes
         */
        Route::get('client/{id}/actvity-plan','ClientsController@createActvityPlan')->name('clients.createActvityPlan');
        Route::group(['prefix' => 'activity', 'namespace' => 'Result\Activity'], function() {
            Route::post('delete', 'CalendarController@removeExercise');
            Route::get('date/plan', 'CalendarController@getPlanDateWise');
            Route::get('date/planDetail', 'CalendarController@getPlanDetailDateWise');
            Route::post('clientPlan/edit', 'CalendarController@clientPlanEdit');
            Route::get('clientPlan/delete', 'CalendarController@clientPlanDelete');
            Route::post('exercise/add', 'CalendarController@addExercise');
            Route::post('daysInWeek', 'CalendarController@daysInWeek');
        });
        //Route::resource('benchmarks', 'BenchmarksController');
        

        Route::group(['prefix' => 'staffevents', 'namespace' => 'Setings\Staff'], function(){
            Route::get('classes','StaffEventClassController@index');
            Route::get('classes/{eventId}','StaffEventClassController@show');
            Route::post('classes/create','StaffEventClassController@store');
            Route::post('classes/edit','StaffEventClassController@update');
            Route::post('classes/delete','StaffEventClassController@destroy');
            Route::post('classes/reschedule-client','StaffEventClassController@rescheduleClient');

             # check client present in future classes
            Route::get('recure-classes/client-present/{eventId}', 'StaffEventClassController@checkClientsPresentInFutureRecureClasses');
            
            Route::post('classes/makeup-client','StaffEventClassController@makeUpClient');
            Route::post('classes/past-update','StaffEventClassController@updatePastClass');
            Route::post('classes/book-team','StaffEventClassController@bookTeam');
            Route::get('classes/{eventId}/change-date','StaffEventClassController@changeDate');
            Route::post('classes/marge','StaffEventClassController@classesMarge');

            Route::post('single-service/create','StaffEventSingleServiceController@store');
            Route::post('single-service/edit','StaffEventSingleServiceController@update');
            Route::get('single-service','StaffEventSingleServiceController@index');
            Route::post('single-service/delete','StaffEventSingleServiceController@destroy');
            Route::post('single-service/change-attendance','StaffEventSingleServiceController@changeAttendance');
            Route::post('single-service/reschedule','StaffEventSingleServiceController@reschedule');
            Route::get('single-service/{eventId}','StaffEventSingleServiceController@show');
            Route::get('single-service/{eventId}/change-date','StaffEventSingleServiceController@changeDate');
        });

        Route::group(['prefix' => 'staffevents/busy-time', 'namespace' => 'Setings\Staff'], function(){
            Route::post('create', 'StaffEventBusyController@store');
            Route::get('', 'StaffEventBusyController@index');
            Route::post('edit', 'StaffEventBusyController@update');
            Route::post('delete', 'StaffEventBusyController@destroy');
            Route::get('{eventId}','StaffEventBusyController@show');
            Route::get('{eventId}/change-date','StaffEventBusyController@changeDate');
            Route::post('reschedule','StaffEventBusyController@reschedule');
            Route::post('status', 'StaffEventBusyController@statusupdate');
            Route::post('update-data-field', 'StaffEventBusyController@updateDataField');


        });

        Route::get('staff-events','Helper@allStaffEvents');
        Route::post('settings/class/create','Setings\Service\\ClassController@store');
        Route::post('settings/business/class/cat/create','Setings\Service\\ClassCatController@store');
        Route::get('/finance-tool', 'FinanceToolController@index')->name('financetool.index');
         
        Route::group(['prefix' => 'my-profile', 'namespace' => 'Frontend\Auth'], function(){
            Route::get('', 'AuthController@show')->name('auth.show');
            Route::patch('', 'AuthController@update')->name('auth.update');
            Route::post('update-field','AuthController@updateField');
        });
     
        Route::post('sales/contact-note/save', 'ContactNoteController2@addContactNote');
        Route::post('sales/create-client-note/save', 'ContactNoteController2@addClientNote');
        Route::post('sales/edit-client-note/{id}', 'ContactNoteController2@editClientNote');
        Route::post('notes-category', 'ContactNoteController2@setNotesCategory');
        Route::delete('notes-delete/{id}', 'ContactNoteController2@destroy')->name('clients-notes.destroy');
        Route::post('dashboard/edit-task-note/{id}','ContactNoteController2@setTaskNote');


        Route::group(['prefix' => 'note-categories'], function(){
            Route::get('','NoteCatController@index')->name('note.getCat');
            Route::post('','NoteCatController@save');
            Route::delete('{id}','NoteCatController@destroy');
        });

        # Update membership of all clients
        Route::get('update-clients-memberships', 'UpdateClientsMembershipController@updateMembership');
        Route::get('get-clients-classes','UpdateClientsMembershipController@getClientsClassesData');
        # Update client membership limit
        Route::get('update-client-membership-limit', 'UpdateClientsMembershipController@updateClientMembershipLimitDemo');

         # Get client classes
        Route::get('client-classes', 'UpdateClientsMembershipController@getClientClasses');


        # Test memb update
        Route::get('test-memb-update', 'UpdateClientsMembershipController@testClientMembUpdate');

         # Update membership of all clients
        Route::get('cron-memb-test', 'UpdateClientsMembershipController@cronMembUpdate');
        Route::get('activities','NewDashboardController@activities');
        Route::get('dashboard','NewDashboardController@show')->name('dashboard.show');
        Route::get('todo/calendar','NewDashboardController@dashboardcalendar')->name('todocalendar.show');
        Route::get('dashboardcalendar/{id}','NewDashboardController@traitfunction');
        Route::post('dashboard/task','NewDashboardController@storetask');
        Route::post('dashboard/category','NewDashboardController@storecat');
        Route::post('dashboard/categoryId','NewDashboardController@storecatId');
        Route::post('dashboard/checkbox','NewDashboardController@storecheckbox');
        Route::post('dashboard/edittask','NewDashboardController@edittask');
        Route::delete('dashboard/{id}', 'NewDashboardController@destroy')->name('dashboardtask.destroy');
        Route::delete('dashboardd/{id}', 'NewDashboardController@destroycategory')->name('dashboardcategory.destroy');
        Route::get('dashboard/notification','NewDashboardController@getNotification');
        Route::post('dashboard/chart-setting','NewDashboardController@editChart');
        Route::post('dashboard/tasks','NewDashboardController@getTasks');
        Route::get('upcoming-tasks','NewDashboardController@callUpcomingTasksTimestamp');
        

        Route::group(['prefix' => 'sales-tools', 'namespace' => 'SalesTools'], function(){
            Route::group(['prefix' => 'invoice', 'namespace' => 'Invoice'], function(){
                Route::get('edit','SalesToolsInvoiceController@edit')->name('salestools.invoice.edit');
                Route::patch('{id}', 'SalesToolsInvoiceController@update')->name('salestools.invoice.update');
            });

            Route::group(['prefix' => 'discounts', 'namespace' => 'Discount'], function(){
                Route::get('', 'DiscountController@index')->name('salestools.discount.list');
                Route::get('create', 'DiscountController@create')->name('salestools.discount.create');
                Route::get('{id}/edit','DiscountController@edit')->name('salestools.discount.edit');
                Route::patch('{id}', 'DiscountController@update')->name('salestools.discount.update');
                Route::post('', 'DiscountController@store');
                Route::delete('{id}','DiscountController@destroy')->name('salestools.discount.destroy');
            });
        });

        /* Start: invoice routes */
        Route::group(['prefix'=>'invoices', 'namespace'=>'Invoices'], function(){
            Route::get('', 'InvoiceController@index')->name('invoices.view');
            Route::get('show/{id}', 'InvoiceController@show')->name('invoices.show');
            Route::delete('delete/{id}', 'InvoiceController@destroy')->name('invoices.destroy');

            Route::post('save-invoice', 'InvoiceController@store');
            Route::post('edit-invoice','InvoiceController@edit');
            Route::post('update-invoice','InvoiceController@update');
            Route::post('delete-invoice','InvoiceController@delete');

            Route::get('all-area', 'InvoiceController@getAreas');
            Route::get('getstaff', 'InvoiceController@getStaff');
            Route::get('getproduct', 'InvoiceController@getProduct');
            
            Route::post('getappointment', 'InvoiceController@getAppointment');

            Route::post('send-invoice-mail', 'InvoiceController@sendInvoiceMail');
            Route::post('raise-invoice', 'InvoiceController@raise');

            Route::get('payment/detail','InvoiceController@getPaymentDetail');
            Route::get('client/epic','InvoiceController@getEpicCash');

            Route::get('download-invoice/{id}','InvoiceController@downloadInvoice')->name('invoice.downloadInvoice');
        });  
        /* End: invoice routes */

        /* Start: Payment route */
        Route::post('save-payment','PaymentController@store');
        Route::post('delete-payment','PaymentController@delete');
        /* End : Payment route */

        /* Start: Business plan routes */
        Route::get('financial-tool', 'FinanceController@showFinancialTools');
        Route::post('financial-tool/save-expenses', 'FinanceController@saveExpenses');
        Route::post('financial-tool/save-income/{id}', 'FinanceController@saveIncome');
        Route::post('financial-tool/save-cashflow/{id}', 'FinanceController@saveCashflow');
        Route::post('financial-tool/save-lead-generation/{id}', 'FinanceController@saveLeadGeneration');

        /* Settings & Preferences */
        Route::get('financial-tool/settings-and-preferences', 'FinanceController@showSettingsAndPreferences');
        Route::post('financial-tool/settings-and-preferences/save', 'FinanceController@saveSettingsAndPreferences');
        Route::get('financial-tool/settings-and-preferences/edit/{id}', 'FinanceController@editSettingsAndPreferences');
        Route::post('financial-tool/settings-and-preferences/update/{id}', 'FinanceController@updateSettingsAndPreferences');
        Route::get('financial-tool/settings-and-preferences/delete/{id}', 'FinanceController@deleteSettingsAndPreferences');
        Route::post('financial-tool/settings-and-preferences/clone/{id}', 'FinanceController@cloneTax');
        Route::get('financial-tool/settings-and-preferences/slabs', 'FinanceController@getSlab')->name('get.slab');
        Route::get('financial-tool/ajaxUpdate/financialTimeFrame', 'FinanceController@ajaxupdateFinancialTimeFrame')->name('ajax.updateFinancialTimeFrame');

        /* New financial tools */
        Route::group(['prefix' => 'new-financial-tool'] , function(){
            Route::get('/', ['uses' => 'FinanceController@showFinancialTools1' ,'as' => 'show.setup']);
            Route::post('/save-business-structure', ['uses' => 'FinanceController@saveBusinessStructure' ,'as' => 'save.business-structure']);
            Route::post('/save-setup-expenses/{id}', 'FinanceController@saveSetupExp');
            Route::post('/save-operation-expenses/{id}', 'FinanceController@saveOperationExp');
            Route::post('/save-sale-projection/{id}', 'FinanceController@saveSaleProjection');
            Route::get('/ajax/deleteData', 'FinanceController@ajaxDeleteData')->name('ajaxDeleteData');
        });
        /* End: Business plan routes */
  
    });

    // after singup
    Route::get('/', 'Frontend\FrontendController@index')->name('frontend.index');

    Route::group(['middleware' => ['web', 'guest']], function () {
        Route::get('test', 'TestsController@index')->name('test');
        Route::post('test','TestsController@store');
        Route::get('verify/{confirmationCode}', 'TestsController@verify')->name('test.verify');
        Route::get('resend-confirmation/{confirmationCode}', 'TestsController@resendConfirmationEmail')->name('test.resendConfirmEmail');
    });

    Route::group(['middleware' => ['web', 'auth']],function(){
        Route::get('business', 'TestsController@businessIndex')->name('newbusiness');
        Route::post('business/active/{id}', 'TestsController@businessActive')->name('business.active');
        Route::post('business/inactive/{id}', 'TestsController@businessInactive')->name('business.inactive');
        Route::delete('{id}','TestsController@destroy')->name('business.destroy');
    });

    Route::get('api/client', ['uses' => 'ClientsController@storeByApi', 'middleware'=>'createClientApi']);
    Route::post('api/photo-upload', ['uses' => 'Helper@uploadFile']); //, 'middleware'=>'createClientApi'

    Route::get('preview', 'Helper@previewAjax');

    /**
     * These are used for API
     */
    Route::get('api/get_access_pass', ['uses' => 'EpicfitStudiosAPIController@get_business_access_pass']);
    Route::get('api/login_api_user', ['uses' => 'EpicfitStudiosAPIController@login_api_user', 'middleware'=>'createClientApi']);
    // Route::get('api/client', ['uses' => 'ClientsControllerResult@storeByApi', 'middleware'=>'createClientApi']);
    Route::get('api/classes', ['uses' => 'EpicfitStudiosAPIController@list_classes', 'middleware'=>'createClientApi']);
    Route::get('api/class_detail', ['uses' => 'EpicfitStudiosAPIController@class_detail', 'middleware'=>'createClientApi']);
    Route::get('api/class_booked', ['uses' => 'EpicfitStudiosAPIController@class_booked', 'middleware'=> 'createClientApi']);
    Route::get('api/loc_area_staff', ['uses' => 'EpicfitStudiosAPIController@load_loc_area_staff', 'middleware'=>'createClientApi']);
    Route::get('api/client_login', ['uses' => 'EpicfitStudiosAPIController@client_login', 'middleware'=>'createClientApi']);
    
    Route::get('api/products', ['uses' => 'EpicfitStudiosAPIController@list_products', 'middleware'=>'createClientApi']);
    Route::get('api/shop_products', ['uses' => 'EpicfitStudiosAPIController@list_products_for_shop', 'middleware'=>'createClientApi']);
    Route::get('api/product_category', ['uses' => 'EpicfitStudiosAPIController@list_product_category', 'middleware'=>'createClientApi']);
    Route::get('api/product_detail', ['uses' => 'EpicfitStudiosAPIController@product_detail', 'middleware'=>'createClientApi']);
    Route::get('api/product_review', ['uses' => 'EpicfitStudiosAPIController@list_product_review', 'middleware'=>'createClientApi']);
    Route::get('api/subcategory_by_category',['uses'=>'EpicfitStudiosAPIController@list_subcategory_by_category','middleware'=>'createClientApi']);
    Route::get('api/product_by_subcategory', ['uses' => 'EpicfitStudiosAPIController@product_by_subcategory', 'middleware'=>'createClientApi']);
    Route::get('api/product_invoice_genrate', ['uses' => 'EpicfitStudiosAPIController@product_invoice_genrate', 'middleware'=>'createClientApi']);

}
elseif($currHost == 'result'){
    /** --------------------------------------------------------------------------- **/
    /** Result route defined --------------------------------------------------------------------------- **/
    Route::group(['middleware' => ['web', 'auth']], function () {
        Route::get('access-restricted', 'ClientsController@accessRestricted');

        /**
         * Privacy Policy Route
         */

        Route::post('preview/analysis', 'PostureController@previewAnalysis');
        Route::get('generate/pdf/{id}', 'PostureController@generatePdf');
        Route::post('remove/image', 'PostureController@deleteImage');

        Route::post('clients/movement/save', 'MovementController@store');
        // Route::get('clients/movement/edit/{id}', 'MovementController@edit');
        // Route::post('clients/movement/update/{id}', 'MovementController@update');
        Route::post('clients/movement/steps', 'MovementController@updateMovementSteps');
        // Route::delete('clients/movement/{id}', 'MovementController@destroy')->name('movement.destroy');
        Route::post('{clientId}/menues/', 'ClientsController@saveMenues')->name('menu.save');


        Route::group(['namespace'=>'Result'], function(){
            Route::get('logout', 'UserController@logout');
            Route::get('header-notifications', 'HeaderNotificationController@index');
        
            Route::group(['prefix' => 'new-dashboard'], function() {
                Route::get('', 'DashboardController@show')->name('dashboard');
                Route::get('app-section/data', 'DashboardController@getAppSectionData');
                Route::get('app-section/week-data', 'DashboardController@getAppSectionWeekData');
            });
            Route::group(['prefix' => 'profile'], function() {
                Route::get('user', 'ProfileController@user')->name('profile.user');
                Route::get('edit', 'ProfileController@edit')->name('profile.edit');
                Route::patch('{id}', 'ProfileController@update')->name('profile.update');
                Route::get('details', 'ProfileController@clientsDetails');
            });

            Route::group(['prefix' => 'social'], function() {
               
                Route::get('home', 'SocialNetworkController@index')->name('social.index');
                Route::get('direct/message', 'SocialNetworkController@direct_message');
                Route::get('add/friend/{id}', 'SocialNetworkController@add_friend');
                Route::get('cancel/friend/{id}', 'SocialNetworkController@cancel_friend');
                Route::get('reject/friend/{id}', 'SocialNetworkController@reject_friend');
                Route::get('confirm/friend/{id}', 'SocialNetworkController@confirm_friend');
                Route::get('search/friend', 'SocialNetworkController@index')->name('search.all_list');
                Route::get('my/friend/{id}', 'SocialNetworkController@index')->name('social.my_friend');
                Route::post('update-profile', 'SocialNetworkController@update_profile');
                Route::post('filter/my/friend', 'SocialNetworkController@filter_my_friend');
                Route::post('filter/requested/friend', 'SocialNetworkController@filter_requested_friend');
                Route::post('filter/sended/friend', 'SocialNetworkController@filter_sended_friend');
                Route::post('cover/image', 'SocialNetworkController@coverImage');
                Route::post('profile/image', 'SocialNetworkController@profileImage');
                Route::post('privacy', 'SocialNetworkController@privacy');
               
                Route::get('messages', 'SocialNetwork\MessageController@all_message')->name('social.all_message');
                
                Route::get('post/preview/{id}', 'SocialNetworkController@postPreview');

                Route::group(['prefix' => 'post', 'namespace'=>'SocialNetwork'], function() {
                    Route::post('store', 'PostController@store')->name('post.store');
                    Route::post('like', 'PostController@like')->name('post.like');
                    Route::get('delete/{post_id}', 'PostController@delete')->name('post.delete');
                    Route::post('comment', 'PostController@comment')->name('post.comment');
                    Route::get('single_comment', 'PostController@single_comment')->name('post.single_comment');
                    Route::get('detail/{post_id}', 'PostController@single_post')->name('post.single_post');
                    Route::post('comment/delete', 'PostController@delete_comment')->name('post.delete_comment');
                    Route::post('update/comment', 'PostController@update_comment')->name('post.update_comment');
                    Route::post('update/{post_id}', 'PostController@update_post')->name('post.update_post'); 
                    Route::post('user-likes', 'PostController@likes')->name('post.user_likes');
                    // Route::get('image/delete/{post_id}', 'PostController@delete_image')->name('post.delete_image');
                    Route::post('image/delete', 'PostController@delete_image')->name('post.delete_image');
                    Route::get('video/delete/{post_id}', 'PostController@delete_video')->name('post.delete_video');
                    Route::post('search_friend', 'PostController@search_friend')->name('post.friends');
                      
                    Route::get('show_all_comment/{id}', 'PostController@show_all_comment')->name('post.show_all_comment');
                    // Route::get('edit/{post_id}', 'PostController@edit_post')->name('post.edit_post'); 
                 });
 
                Route::group(['prefix' => 'direct-message', 'namespace'=>'SocialNetwork'], function() {
                     Route::post('search/friend', 'MessageController@search')->name('message.search');
                     Route::post('people-list', 'MessageController@people_list')->name('message.people-list');     
                     Route::post('chat', 'MessageController@chat')->name('message.chat');
                     Route::post('send', 'MessageController@send')->name('message.send');
                     Route::post('sendFile', 'MessageController@sendFile')->name('message.sendFile');
                     Route::post('delete', 'MessageController@delete_message')->name('message.delete');
                     Route::post('new-messages', 'MessageController@new_messages')->name('message.new');
                     Route::post('contact', 'MessageController@filter_contact');

                     Route::post('chat-list', 'MessageController@chat_list'); // chat page
                     Route::post('single_chat/{id}', 'MessageController@single_chat');
                     
                     
                });


            });

            Route::group(['prefix' => 'business-plan'], function(){
                Route::get('', 'BusinessPlanController@index')->name('businessplan');
                Route::post('', 'BusinessPlanController@store');
            });
            
            // Route::get('tools/fitness-mapper', 'MapController@show');
            Route::post('edit/fitness-map', 'MapController@edit');
            Route::get('editRoute/{id}', 'MapController@editRoute');
            Route::get('copyRoute/{id}', 'MapController@copyRoute');
            Route::get('details/routes/{id}','MapController@detail');
            Route::get('search/routes','MapController@search');
            Route::post('search/route', 'MapController@route')->name('search.route');

            Route::get('delete/map/{id}', 'MapController@delete')->name('delete.fitness-map');
            Route::get('Route/SaveRoute', 'MapController@save');
            Route::get('epic/train-gain/fitness-mapper/{data?}', 'MapController@show');
            /* 25-05-2021 */
           
          
             /* end 25-05-2021 */
            Route::get('goals/calendar', ['as' => 'goals.calendar', 'uses' => 'GoalBuddyCalendarController@index']);
            Route::get('parq/{id}','ProfileController@clientsDetails');
            Route::get('epicprogress/{parameter1}/{parameter2}','ProfileController@clientsDetails');
            Route::get('epic/{parameter1}/{parameter2}','ProfileController@clientsDetailsNew');
            Route::get('epic/measurements','ProfileController@showMeasurement');
            Route::post('epic/store-nutritional','ProfileController@storeNutritionalJournal');
            Route::get('epic/edit-nutritional','ProfileController@editNutritionalJournal');

            Route::get('sleep','SleepQuestionnaireController@sleepQuestionnaire');
            Route::post('/sleep-questionnaire', 'SleepQuestionnaireController@saveSleepQuestionnaire');
            Route::get('/chronotype-survey','SleepQuestionnaireController@chronotypeSurvey');
            Route::post('/store-chronotype-survey', 'SleepQuestionnaireController@saveChronotypeSurvey');

            Route::get('/posture/lists','PostureController@postureLists');
            Route::post('posture/image','PostureController@uploadFile');
            Route::post('captcha/image','PostureController@uploadCaptureFile');
            Route::post('save/posture/image','PostureController@savePostureImage');
            
             
            Route::get('dashboard/calendar', 'CalendarController@show_calendar');
            Route::get('dashboard/calendar/{id}/{date}', 'CalendarController@show_calendar');
            Route::get('dashboard/calendar/edit_service/{id}', 'CalendarController@show_calendar');
            Route::get('client-events', 'Helper@allClientEvents');
            Route::get('get-events-timing', 'Helper@allEventsTiming');
            Route::get('get-epic-balance', 'Helper@fetchClientEpicBalance');

            Route::group(['prefix' => 'measurements'], function() {
            Route::get('download-gallery-image/{id}','MeasurementController@downloadGalleryImage');
            Route::get('add-progress/{client_id}','MeasurementController@addProgressForm');
            Route::post('save-progress','MeasurementController@saveTempProgress');
            Route::post('save-final-progress','MeasurementController@saveProgress');
            Route::post('check-progress-photo-exist','MeasurementController@checkProgressPhotoExist');
            Route::post('delete-gallery-image','MeasurementController@deleteGalleryImage');
            // Route::post('add-gallery-image', 'MeasurementController@addGalleryImage');
            // Route::post('add-before-after', 'MeasurementController@addBeforeAfter');
            // Route::post('delete-before-after', 'MeasurementController@DeleteBeforeAfter');

            });

            Route::group(['prefix' => 'clients'], function() {
                Route::get('all', 'ClientsControllerResult@allClients');
                Route::get('create', 'ClientsControllerResult@create')->name('clients.create');
                Route::get('print-appointments', 'ClientsControllerResult@printAppointments')->name('clients.print');
                Route::get('{filter?}', 'ClientsControllerResult@index')->name('clients');
                Route::get('{id}/co', 'ClientsControllerResult@coClients');
                Route::get('{id}/edit', 'ClientsControllerResult@edit')->name('clients.edit');
                Route::patch('{id}', 'ClientsControllerResult@update')->name('clients.update');

                Route::delete('{id}', 'ClientsControllerResult@destroy')->name('clients.destroy');
                

                Route::delete('makeup/{id}', 'MakeupController@destroy')->name('makeup.destroy');

                Route::post('', 'ClientsControllerResult@save')->name('clients.store');
                Route::post('raise-make-up', 'ClientsControllerResult@raiseMakeUp');
                Route::post('sales-process/update', 'ClientsControllerResult@updateSalesProcess');
            });

            Route::group(['prefix' => 'signup-class', 'namespace' => 'SignupClass'], function() {
                Route::get('all', 'ClassForSignupController@all');
            });

            Route::get('clients/all', 'ProfileController@allClients');
            Route::get('staffs/all', 'ProfileController@allStaffs');
            Route::get('contacts/all', 'ProfileController@allContacts');
            Route::post('client/{id}/update-field', 'ProfileController@updateField');
           
            
            Route::post('photo/save', 'ProfileController@uploadFile');
            Route::post('photos/delete', 'ProfileController@destroyFile');
            Route::post('client/photo/save', 'ProfileController@saveFile');

            Route::post('crm/photo/save', 'ProfileController@uploadFileCrm');
            Route::post('crm/photos/delete', 'ProfileController@destroyFile');
            Route::post('crm/client/photo/save', 'ProfileController@saveFile');

            Route::get('noimage-src', 'ProfileController@noimageSrc');
            Route::post('saveparq', 'ParqController@parqSave');

            Route::get('staffs/services', 'StaffController@getServicesByArea');
            Route::get('staffevents/appointments', 'ClientEventController@index');
            Route::get('staffevents/appointment/{eventId}', 'ClientEventController@show');
            Route::post('staffevents/appointments/create', 'ClientEventController@store');
            Route::post('staffevents/appointments/edit', 'ClientEventController@update');
            Route::post('staffevents/appointments/delete', 'ClientEventController@destroyAppointment');
            Route::post('staffevents/appointments/reschedule', 'ClientEventController@reschedule');
            Route::post('staffevents/appointments/change-status', 'ClientEventController@setStatus');
            Route::get('areas/staffs', 'LocationAreaController@getStaffs');
            Route::post('waiver/save', 'ParqController@waiverSave');

            Route::get('area-staff/classes', 'ClassController@classesByAreaStaff');
            Route::get('fitnesscore', 'MapController@show');

            Route::get('sendy', 'ProfileController@create_email');
            Route::post('save-email', 'ProfileController@save_email');
            Route::post('unsubscribe-email', 'ProfileController@unsubscribe_email');
            Route::post('sending-email', 'ProfileController@sending_email');
            
            Route::get('user-chat', 'ConversationController@index');
            Route::get('message/{id}', 'ConversationController@chatHistory')->name('message.read');
            Route::group(['prefix'=>'ajax', 'as'=>'ajax::'], function() {
               Route::post('message/send', 'ConversationController@ajaxSendMessage')->name('message.new');
               Route::delete('message/delete/{id}', 'ConversationController@ajaxDeleteMessage')->name('message.delete');
            });

            Route::group(['prefix' => 'calculators', 'namespace' => 'Calculator'], function () {
                Route::get('', 'CalculatorController@index');
                Route::get('body-mass-index', 'CalculatorController@bodyMassIndexCalculator');
                Route::get('body-mass-index/{type}', 'CalculatorController@bodyMassIndexCalculator');
                Route::post('body-mass-index',
                ['as' => 'body-mass-index', 'uses' => 'CalculatorController@storeBodyMassIndexCalculation']);           
                Route::post('body-mass-index-update',
                ['as' => 'body-mass-index', 'uses' => 'CalculatorController@updateBodyMassIndexCalculation']); 

                Route::get('basal-metabolism-rate', 'CalculatorController@basalMetabolismRateCalculator');
                Route::get('basal-metabolism-rate/{type}/{gender}/{equation}', 'CalculatorController@basalMetabolismRateCalculator');
                Route::post('basal-metabolism-rate',
                ['as' => 'basal-metabolism-rate', 'uses' => 'CalculatorController@storeBasalMetabolismRateCalculation']);
                Route::post('basal-metabolism-rate-update',
                ['as' => 'basal-metabolism-rate', 'uses' => 'CalculatorController@updateBasalMetabolismRateCalculation']);

                Route::get('target-heart-rate', 'CalculatorController@targetHeartRateCalculator');
                Route::get('target-heart-rate/{goal}', 'CalculatorController@targetHeartRateCalculator');
                Route::post('target-heart-rate',
                ['as' => 'target-heart-rate', 'uses' => 'CalculatorController@storeTargetHeartRateCalculation']);
                Route::post('target-heart-rate-update',
                ['as' => 'target-heart-rate', 'uses' => 'CalculatorController@updateTargetHeartRateCalculation']);

                Route::get('ideal-weight', 'CalculatorController@idealWeightCalculator');
                Route::get('ideal-weight/{type}/{gender}', 'CalculatorController@idealWeightCalculator');
                Route::post('ideal-weight', ['as' => 'ideal-weight', 'uses' => 'CalculatorController@storeIdealWeightCalculation']);
                Route::post('ideal-weight-update', ['as' => 'ideal-weight', 'uses' => 'CalculatorController@updateIdealWeightCalculation']);

                Route::get('calorie-breakdown', 'CalculatorController@calorieBreakdownCalculator');
                Route::get('calorie-breakdown/{gender}', 'CalculatorController@calorieBreakdownCalculator');
                 Route::post('calorie-breakdown',
                ['as' => 'calorie-breakdown', 'uses' => 'CalculatorController@storeCalorieBreakdownCalculation']);
                 Route::post('calorie-breakdown-update',
                ['as' => 'calorie-breakdown', 'uses' => 'CalculatorController@updateCalorieBreakdownCalculation']);

                Route::get('resting-metabolism', 'CalculatorController@restingMetabolismCalculator');
                Route::get('resting-metabolism/{type}/{unittype}', 'CalculatorController@restingMetabolismCalculator');
                Route::post('resting-metabolism',
                ['as' => 'resting-metabolism', 'uses' => 'CalculatorController@storeRestingMetabolismCalculation']);
                Route::post('resting-metabolism-update',
                ['as' => 'resting-metabolism', 'uses' => 'CalculatorController@updateRestingMetabolismCalculation']);

                Route::get('advanced-resting-metabolism', 'CalculatorController@advancedRestingMetabolismCalculator');
                Route::get('advanced-resting-metabolism/{type}/{gender}', 'CalculatorController@advancedRestingMetabolismCalculator');
                Route::post('advanced-resting-metabolism', ['as'   => 'advanced-resting-metabolism', 'uses' => 'CalculatorController@storeAdvancedRestingMetabolismCalculation']);
                Route::post('advanced-resting-metabolism-update', ['as'   => 'advanced-resting-metabolism', 'uses' => 'CalculatorController@updateAdvancedRestingMetabolismCalculation']);

                Route::get('daily-metabolism', 'CalculatorController@dailyMetabolismCalculator');
                Route::get('daily-metabolism/{type}/{gender}/{activity}', 'CalculatorController@dailyMetabolismCalculator');
                Route::post('daily-metabolism',
                ['as' => 'daily-metabolism', 'uses' => 'CalculatorController@storeDailyMetabolismCalculation']);
                Route::post('daily-metabolism-update',
                ['as' => 'daily-metabolism', 'uses' => 'CalculatorController@updateDailyMetabolismCalculation']);

                Route::get('body-fat-navy', 'CalculatorController@bodyFatNavyCalculator');
                Route::get('body-fat-navy/{type}/{gender}', 'CalculatorController@bodyFatNavyCalculator');
                Route::post('body-fat-navy',
                ['as' => 'body-fat-navy', 'uses' => 'CalculatorController@storeBodyFatNavyCalculation']);
                Route::post('body-fat-navy-update',
                ['as' => 'body-fat-navy', 'uses' => 'CalculatorController@updateBodyFatNavyCalculation']);

                Route::get('body-fat-ymca', 'CalculatorController@bodyFatYmcaCalculator');
                Route::get('body-fat-ymca/{type}/{gender}', 'CalculatorController@bodyFatYmcaCalculator');
                Route::post('body-fat-ymca',
                ['as' => 'body-fat-ymca', 'uses' => 'CalculatorController@storeBodyFatYmcaCalculation']);
                Route::post('body-fat-ymca-update',
                ['as' => 'body-fat-ymca', 'uses' => 'CalculatorController@updateBodyFatYmcaCalculation']);

                Route::get('lean-body-mass', 'CalculatorController@leanBodyMassCalculator');
                Route::get('lean-body-mass/{type}/{gender}', 'CalculatorController@leanBodyMassCalculator');
                Route::post('lean-body-mass',
                ['as' => 'lean-body-mass', 'uses' => 'CalculatorController@storeLeanBodyMassCalculation']);
                Route::post('lean-body-mass-update',
                ['as' => 'lean-body-mass', 'uses' => 'CalculatorController@updateLeanBodyMassCalculation']);

                Route::get('waist-hip-ratio', 'CalculatorController@waistHipRatioCalculator');
                Route::get('waist-hip-ratio/{type}/{gender}', 'CalculatorController@waistHipRatioCalculator');
                Route::post('waist-hip-ratio',
                ['as' => 'waist-hip-ratio', 'uses' => 'CalculatorController@storeWaistHipRatioCalculation']);
                Route::post('waist-hip-ratio-update',
                ['as' => 'waist-hip-ratio', 'uses' => 'CalculatorController@updateWaistHipRatioCalculation']);

                Route::get('full-body-analysis', 'CalculatorController@fullBodyAnalysisCalculator');
                Route::get('full-body-analysis/{type}/{gender}/{activity}/{goal}', 'CalculatorController@fullBodyAnalysisCalculator');
                Route::post('full-body-analysis',
                ['as' => 'full-body-analysis', 'uses' => 'CalculatorController@storeFullBodyAnalysisCalculation']);
                Route::post('full-body-analysis-update',
                ['as' => 'full-body-analysis', 'uses' => 'CalculatorController@updateFullBodyAnalysisCalculation']);
            });

            /* Activities planner */
            Route::group(['prefix' => 'activity', 'namespace' => 'Activity'], function() {
                Route::post('delete', 'CalendarController@removeExercise');

                Route::get('calender', 'CalendarController@show');
                Route::get('date/plan', 'CalendarController@getPlanDateWise');
                Route::get('date/planDetail', 'CalendarController@getPlanDetailDateWise');
                Route::post('clientPlan/edit', 'CalendarController@clientPlanEdit');
                Route::get('clientPlan/delete', 'CalendarController@clientPlanDelete');
                Route::post('exercise/add', 'CalendarController@addExercise');
                Route::post('daysInWeek', 'CalendarController@daysInWeek');
            }); 

            /**
             * Clients Personal Statistics Routes
             */
            Route::post('store-sleep-data','PersonalStatisticController@storeSleepData');
            Route::post('store-statistics-data','PersonalStatisticController@storeData');
            Route::post('store-weight-data','PersonalStatisticController@storeWeight');
            Route::get('get-statistics-data','PersonalStatisticController@getData');
            Route::post('store-nutritional-data','PersonalStatisticController@saveNutritionalData');
            Route::post('store-hydration-data','PersonalStatisticController@saveHydrationData');
            Route::post('update-hydration-data','PersonalStatisticController@updateHydrationData');
            
            // Body Measurement
            Route::get('measurement/{bodypart}','PersonalStatisticController@bodyMeasurement');
            Route::get('filter-body-measurement/{bodypart}/{duration}','PersonalStatisticController@filterBodyMeasurement');
            Route::post('filter-body-measurement','PersonalStatisticController@filterBodyMeasurementGraph');

            Route::get('personal-stastic/{stastic}','PersonalStatisticController@personalStastic');
            Route::get('filter-personal-stastic/{bodypart}/{duration}','PersonalStatisticController@filterPersonalStastic');
            Route::post('filter-personal-stastic','PersonalStatisticController@filterPersonalStasticGraph');

            // Challenge Section
            Route::get('fitness-mapper/{id}/create/challenge', 'ChallengeController@create');
            Route::get('fitness-mapper/{id}/update/challenge/{c_id}', 'ChallengeController@create');
            Route::get('delete/challenge/{id}', 'ChallengeController@deleteChallenge');
            Route::post('fitness-mapper/save/challenge', 'ChallengeController@save');
            Route::post('challenge/completed', 'ChallengeController@challengeCompleted');
        });

        /* Start: Service/class data routes */
        Route::group(['prefix' => 'settings/business'], function() {
            Route::group(['prefix' => 'classes', 'namespace' => 'Setings\Service'], function() {
                Route::get('all', 'ClassController@all');
                Route::get('{id}/free-staffs', 'ClassController@freeStaffs');
                Route::get('{id}/free-areas', 'ClassController@freeAreas');
                Route::get('{id}/resources', 'ClassController@freeResources');
            });

            Route::group(['prefix' => 'services', 'namespace' => 'Setings\Service'], function() {
                Route::get('all', 'ServiceController@all');
                Route::get('{id}/free-areas', 'ServiceController@freeAreas2');

                Route::get('{id}/free-staffs', 'ServiceController@freeStaffs2');
                Route::get('sales-process/{step}', 'ServiceController@getOfSalesProcessStep');
            });
        });
        /* End: Service/class data routes */

        /* Start: Client event routes */
        Route::group(['namespace' => 'Setings\Staff'], function(){
            Route::get('clientevents/single-service/{eventId}', 'StaffEventSingleServiceController@show');
            Route::post('clientevents/single-service/checkservicecondition', 'StaffEventSingleServiceController@alertForServiceBooking');
            Route::post('clientevents/single-service/create', 'StaffEventSingleServiceController@clientServiceStore');
            Route::post('clientevents/single-service/delete', 'StaffEventSingleServiceController@appointmentDestroy');
            Route::post('clientevents/single-service-mobile/delete', 'StaffEventSingleServiceController@appointmentDestroyMobile');
            Route::post('clientevents/single-service/edit', 'StaffEventSingleServiceController@appointmentUpdate');
            Route::post('clientevents/single-service-mobile/edit', 'StaffEventSingleServiceController@appointmentUpdateMobile');

            Route::get('clientevents/classes/{eventId}', 'StaffEventClassController@show');
            Route::post('clientevents/classes/checkconditions', 'StaffEventClassController@alertForClassBooking');
            Route::post('clientevents/classes/create', 'StaffEventClassController@signupInClass');
            Route::post('staffevents/classes/delete', 'StaffEventClassController@removeClintFromClass');
            Route::post('staffevents/classes/reschedule-client', 'StaffEventClassController@rescheduleClient');


            Route::post('clientevents/classes/edit', 'ClientEventClassController@update');
            Route::get('staffevents/classes', 'ClientEventClassController@index');
            Route::get('staffevents/classes/{eventId}', 'ClientEventClassController@show');

            Route::post('staffevents/classes/past-update', 'ClientEventClassController@updatePastClass');

            Route::get('clientevents/busy-time/{eventId}', 'ClientEventBusyController@index');
            
        });
        /* End: Client event routes */

        /* Start: Calendar setting routes */
        Route::group(['prefix' => 'calendar', 'namespace' => 'Setings\Calendar'], function(){
            Route::get('settings', 'CalendarSettingController@edit')->name('calendar.edit');
            Route::patch('settings/{id}', 'CalendarSettingController@resultUpdate');
        });
        /* End: Calendar setting routes */

        /* Start: Invoice routes */
        Route::get('invoices', 'Invoices\\InvoiceController@resultIndex');
        /* End: Invoice routes */
    });

    // /**
    //  * These are used for API
    //  */
    // Route::get('api/login_api_user', ['uses' => 'ApiController@login_api_user', 'middleware'=>'createClientApi']);

    // Route::post('api/client', ['uses' => 'ClientsControllerResult@storeByApi', 'middleware'=>'createClientApi']);
    // Route::get('api/classes', ['uses' => 'ApiController@list_classes', 'middleware'=>'createClientApi']);
    // Route::get('api/class_detail', ['uses' => 'ApiController@class_detail', 'middleware'=>'createClientApi']);
    // Route::get('api/class_booked', ['uses' => 'ApiController@class_booked', 'middleware'=>'createClientApi']);
    // Route::get('api/loc_area_staff', ['uses' => 'ApiController@load_loc_area_staff', 'middleware'=>'createClientApi']);

    // Route::get('api/products', ['uses' => 'ApiController@list_products', 'middleware'=>'createClientApi']);
    // Route::get('api/shop_products', ['uses' => 'ApiController@list_products_for_shop', 'middleware'=>'createClientApi']);
    // Route::get('api/product_category', ['uses' => 'ApiController@list_product_category', 'middleware'=>'createClientApi']);
    // Route::get('api/product_detail', ['uses' => 'ApiController@product_detail', 'middleware'=>'createClientApi']);
    // Route::get('api/product_review', ['uses' => 'ApiController@list_product_review', 'middleware'=>'createClientApi']);
    // Route::get('api/subcategory_by_category',['uses'=>'ApiController@list_subcategory_by_category','middleware'=>'createClientApi']);
    // Route::get('api/product_by_subcategory', ['uses' => 'ApiController@product_by_subcategory', 'middleware'=>'createClientApi']);
    // Route::get('api/product_invoice_genrate', ['uses' => 'ApiController@product_invoice_genrate', 'middleware'=>'createClientApi']);
}


/** 
 * Common route is here
 */
if($currHost == 'crm')
    $middlewares = ['web', 'auth', 'member','attendence'];
elseif($currHost == 'result')
    $middlewares = ['web', 'auth'];

Route::group(['middleware' => $middlewares], function () {
    /* Start: Benchmark routes */
    Route::get('benchmark/{id}', 'BenchmarksController@getDetails');
    Route::post('benchmarked','BenchmarksController@store');
    Route::post('benchmark','BenchmarksController@store');
    Route::get('lastbenchmark','BenchmarksController@lastStore');
    Route::post('getClientInfo','BenchmarksController@getClient');
    Route::get('benchmark', 'BenchmarksController@benchmarkDetails');
    Route::post('client/getBenchmarkInfo','BenchmarksController@getBenchmark');
    Route::get('showbenchmark/{id}','ClientsController@showBenchmarks');
    Route::delete('clients/benchmark/{id}', 'BenchmarksController@destroy')->name('benchmark.destroy');
    /* End: Benchmark routes */

    /* Start: Goal buddy routes */
    Route::group(['namespace'=>'GoalBuddy'], function(){
        Route::get('goal-buddy/goal-listing','GoalBuddyController@goalListing')->name('goal-buddy.goallisting');
        Route::get('goals', 'GoalBuddyController@index')->name('goals.goallisting');

        Route::get('goal-buddy','GoalBuddyController@index')->name('goal-buddy');
        Route::get('goal-buddy/create-old','GoalBuddyController@create_old')->name('goal-buddy.create-old');
        Route::get('goal-buddy/loadfirststep','GoalBuddyController@loadFirstStep');

        Route::post('goal-buddy/editgoaldetails','GoalBuddyController@editGoaldetails');

        Route::get('goal-buddy/load-friend-list','GoalBuddyController@friendDataForAutoComplete');
        Route::get('goal-buddy/load-habit-data','GoalBuddyController@getHabitDataGoal');
        Route::get('goal-buddy/load-custom-habit-step','GoalBuddyController@loadCustomHabitStep');
        Route::get('goal-buddy/load-custom-task-list','GoalBuddyController@loadCustomTaskList');
        Route::get('goal-buddy/load-custom-milestone-list','GoalBuddyController@loadCustomMilestoneList');
        Route::post('goal-buddy/load-form-data','GoalBuddyController@getDataFromSession');
        Route::get('goal-buddy/load-custom-task-step','GoalBuddyController@loadCustomTaskStep');
        Route::get('goal-buddy/load-final-step','GoalBuddyController@loadFinalStep');

        Route::get('goal-buddy/create','GoalBuddyController@create')->name('goal-buddy.create');
        Route::get('goal-buddy/edit-old/{id}', 'GoalBuddyController@fetchdataforsteponeedit')->name('goal-buddy.edit');
        Route::get('goal-buddy/fetch/{id}', 'GoalBuddyController@fetchdataforsteponeedit'); // 06--07-2021
        Route::get('goal-buddy/edithabit/{id}', 'GoalBuddyController@edithabit')->name('goal-buddy.edithabit');
        Route::get('goal-buddy/editgoal/{id}', 'GoalBuddyController@editgoal')->name('goal-buddy.editgoal');
        Route::get('goal-buddy/edittask/{id}', 'GoalBuddyController@edittask')->name('goal-buddy.edittask');
        Route::get('goal-buddy/editmilestone/{id}', 'GoalBuddyController@editmilestone')->name('goals.editmilestone');

        # Get goal template details
        Route::get('goal-buddy/template/{id}', 'GoalBuddyController@getGoalTemplate');
        
        Route::get('goal-buddy/goal-print','GoalBuddyController@goalPrint')->name('goal-buddy.print');
        Route::get('goal-buddy/{viewName}','GoalBuddyController@openView');
        Route::post('goal-buddy/checkgoalform','GoalBuddyController@checkGoalForm');
        Route::post('goal-buddy/savegoal','GoalBuddyController@store');
        Route::post('goal-buddy/savegoal-new','GoalBuddyController@storeNew')->name('goal-save-new');

        Route::post('goal-buddy/updategoal','GoalBuddyController@update');
        Route::post('goal-buddy/insert-metadata','GoalBuddyController@storeMetaData');
        Route::post('goal-buddy/deletegoal','GoalBuddyController@delete');
        Route::post('goal-buddy/deletemilestones','GoalBuddyController@deletemilestones');
        Route::post('goal-buddy/updatemilestones','GoalBuddyController@updatemilestones');
        Route::post('goal-buddy/deletehabit','GoalBuddyController@deletehabit');
        Route::post('goal-buddy/showhabit','GoalBuddyController@showhabit');
        Route::post('goal-buddy/deletetask','GoalBuddyController@deletetask');
        Route::post('goal-buddy/showtask','GoalBuddyController@showtask');
        Route::post('goal-buddy/showmilestone','GoalBuddyController@showmilestone');
        Route::post('goal-buddy/deletemilestone','GoalBuddyController@deletemilestone');
        Route::get('showcalendar', 'GoalBuddyCalendarController@index')->name('goal-buddy.showcalendar');
        Route::get('showgoalactivity', 'GoalBuddyCalendarController@show');
        Route::post('manage-status', 'GoalBuddyCalendarController@statusChange');
        Route::post('goal-buddy/get-listing-task','GoalBuddyController@getTaskUpdate');
        Route::post('goal-buddy/get-listing-goal','GoalBuddyController@getGoalUpdate');
        Route::post('search-goal','GoalBuddyController@searching')->name('searchingclientgoal');

        Route::post('goal-buddy/get-habit','GoalBuddyController@getHabit');
        Route::post('goal-buddy/get-task','GoalBuddyController@getTask');
        Route::post('goal-buddy/fetchdataforsteponeedit','GoalBuddyController@fetchdataforsteponeedit');
        Route::post('goal-buddy/get-listing-habit','GoalBuddyController@getHabitUpdate');
        Route::post('goal-buddy/get-listing-milestone','GoalBuddyController@getMilestoneUpdate');
        Route::post('goal-buddy/updategoalstatus','GoalBuddyController@updateGoalStatus');
        Route::post('goal-buddy/getAllHabit','GoalBuddyController@getAllHabit');


        

        


    });
    /* End: Goal buddy routes */

    /* Start: Activity builder routes */
    Route::get('fitness/tools', 'PlannerController@show')->name('tools.show');
    Route::group(['prefix'=>'Planner'], function(){
        Route::get('GetFilterPlan', 'PlannerController@GetFilterPlan');
        Route::get('GetGeneratedPlans', 'PlannerController@GetGeneratedPlans');
        Route::get('GetGeneratedPlanDetail', 'PlannerController@GetGeneratedPlanDetail');
        Route::get('SavePlan', 'PlannerController@SavePlan');
    });
    Route::get('activity-builder/videos/view/{id}', 'ActivityBuilder\VideoController@show');
    Route::group(['prefix' => 'CustomPlan'], function() {
        Route::get('CreateProgram', 'PlannerController@CreateProgram');
        Route::get('CreateProgramCopy', 'PlannerController@CreateProgramCopy');
        Route::get('GetUsersPlans', 'PlannerController@GetUsersPlans');
        Route::get('GetUsersPlanDetail', 'PlannerController@GetUsersPlanDetail');
        Route::get('UpdateProgram', 'PlannerController@UpdateProgram');
        Route::get('UpdateExercise', 'PlannerController@UpdateExercise');
        Route::get('RemoveExerciseFromProgram', 'PlannerController@RemoveExerciseFromProgram');
        Route::get('RemoveWorkoutWithExercise', 'PlannerController@RemoveWorkoutWithExercise');
        Route::get('AddExerciseToProgram', 'PlannerController@AddExerciseToProgram');
        Route::get('RemoveFavExercise', 'PlannerController@RemoveFavExercise');
        Route::get('AddFavExercise', 'PlannerController@AddFavExercise');
        Route::get('SearchExercises', 'PlannerController@SearchExercises');
        Route::get('SearchExercisesByKeywords', 'PlannerController@SearchExercisesByKeywords');
        Route::get('SearchExercisesById/{id}', 'PlannerController@GetExercisesById');
        Route::get('EditTrainingSegment', 'PlannerController@EditTrainingSegment');
        Route::get('DeleteTrainingSegment','PlannerController@DeleteTrainingSegment');
        Route::get('RemoveProgram', 'PlannerController@RemoveProgram');
        Route::get('PlanPreview', 'PlannerController@PlanPreview'); 
        Route::get('AddFilterToGenPlan', 'PlannerController@AddFilterToGenPlan'); 
        Route::get('activityViedos', 'PlannerController@activityViedos');
        Route::get('exercise-type','PlannerController@exerciseType');
        Route::get('updateRest','PlannerController@updateRest');
        Route::get('DeleteMultipleExercise','PlannerController@DeleteMultipleExercise');
        Route::get('activity/date/plan','Result\Activity\CalendarController@getPlanDateWise');
        Route::get('client-multiphase-program','PlannerController@clientMultiphaseProgram');
        Route::get('replicate-program','PlannerController@replicateProgram');
    });
    /* End: Activity builder routes */

    /* Start: Meal-planner routes */
    Route::group(['prefix' => 'meal-planner', 'namespace' => 'MealPlanner'], function() {
        Route::get('main-category','MealPlannerController@mainCategory')->name('main-category.index');
        Route::get('main-category/create','MealPlannerController@mainCategoryCreate')->name('main-category.create');
        Route::post('main-category/store','MealPlannerController@mainCategoryStore')->name('main-category.store');
        Route::get('main-category/fetch/{id}','MealPlannerController@mainCategoryFetch')->name('main-category.fetch');
        Route::post('main-category/update/{id}','MealPlannerController@mainCategoryUpdate')->name('main-category.update');
        Route::get('main-category/delete/{id}','MealPlannerController@mainCategoryDelete')->name('main-category.delete');

        Route::get('sub-category','MealPlannerController@subCategory')->name('sub-category.index');
        Route::get('sub-category/create','MealPlannerController@subCategoryCreate')->name('sub-category.create');
        Route::post('sub-category/store','MealPlannerController@subCategoryStore')->name('sub-category.store');
        Route::get('sub-category/fetch/{id}','MealPlannerController@subCategoryFetch')->name('sub-category.fetch');
        Route::post('sub-category/update/{id}','MealPlannerController@subCategoryUpdate')->name('sub-category.update');
        Route::get('sub-category/delete/{id}','MealPlannerController@subCategoryDelete')->name('sub-category.delete');

        Route::post('remove/image','MealPlannerController@removeImage');
        Route::post('ingredients','MealPlannerController@analyzeIngredients');
        Route::post('single-ingredients','MealPlannerController@singleIngredients'); // api edamam

        Route::get('nutrition-data','MealPlannerController@nutritionData');

        Route::get('food', 'FoodPlannerController@index');
        Route::get('food/create', 'FoodPlannerController@create')->name('food.create');
        Route::post('food/store', 'FoodPlannerController@store')->name('food.store');
        Route::get('food/edit/{id}', 'FoodPlannerController@edit')->name('food.edit');
        Route::post('food/update/{id}', 'FoodPlannerController@update')->name('food.update');
        Route::delete('food/delete/{id}', 'FoodPlannerController@delete')->name('food.destroy');
        Route::get('get-food-data','FoodPlannerController@getFoodData');

        Route::get('meals', 'MealPlannerController@index');
        Route::get('validate-meal-name', 'MealPlannerController@validateName');
        Route::get('meals/create', 'MealPlannerController@create')->name('meals.create');
        Route::post('meals/store', 'MealPlannerController@store')->name('meals.store');
        Route::get('meal/{id}', 'MealPlannerController@show')->name('meals.show');
        Route::get('meals/edit/{id}', 'MealPlannerController@edit')->name('meals.edit');
        Route::post('meals/update/{id}', 'MealPlannerController@update')->name('meals.update');
        Route::delete('meals/delete/{id}', 'MealPlannerController@delete')->name('meals.destroy');
        Route::get('meals/download/{id}', 'MealPlannerController@download')->name('meals.download');
        Route::post('meals/photo/save', 'MealPlannerController@saveFile');
        Route::get('getFoodList', 'MealPlannerController@foodNameListings');

        /* Meal tool routes */
        Route::get('tools', 'MealToolsController@show');
        Route::post('tools/nutrition', 'MealToolsController@nutritionInfo');
        Route::get('tools/foods', 'MealToolsController@getFood');

        /* Sopping list routes */
        Route::get('shopping-list', 'ShoppingListController@index');
        Route::post('update-shopping-list','ShoppingListController@update');
        Route::post('delete-shopping-list','ShoppingListController@deleteShopping');

        /**
         * Recipe Route
         */
        Route::get('/recipes', 'MealPlannerController@allRecipe')->name('recipes.list');
        // Route::post('/recipes', 'MealPlannerController@allRecipe')->name('recipes.post');
        // Route::get('/recipe-details', 'MealPlannerController@recipeDetail');
        Route::get('/recipe-details/{id}', 'MealPlannerController@recipeDetail')->name('recipes.details');
        Route::get('/recipes/{filtersuggestion}', 'MealPlannerController@searchFilterSuggestion')->name('recipes.filtersuggestion');
        Route::get('/calendar-filter', 'MealPlannerController@searchFilterSuggestionCalendar')->name('recipes.calendarfilter');
        
        /* Meal caledar routes */
        Route::post('/shopping-list-ingredients', 'MealPlannerController@saveShoppingList');
        Route::post('/detail-shopping-list', 'MealPlannerController@shoppingList');
        Route::post('/email-ingredient', 'MealPlannerController@emailIngredient');

         /* review */
        Route::post('/post-review', 'MealReviewController@postReview');
        Route::post('/post-reply', 'MealReviewController@postReply');
        Route::post('/upvote', 'MealReviewController@upvote');
        Route::post('/post-rating', 'MealReviewController@starRating');
        Route::post('/review-filter', 'MealReviewController@reviewFilter');
   
        Route::group(['prefix'=>'calendar'], function(){
            Route::get('', 'MealCalendarController@show');
            Route::post('store', 'MealCalendarController@store');
            Route::post('update', 'MealCalendarController@update');
            Route::get('event/{id}', 'MealCalendarController@edit');
        
            Route::get('getEvent', 'MealCalendarController@getEvents'); 
            Route::get('meallist', 'MealCalendarController@getMealList');
            Route::get('foodlist', 'MealCalendarController@getFoodList');
            Route::get('meal/{id}', 'MealCalendarController@getMeal');
            Route::get('food/{id}', 'MealCalendarController@getFood');
            Route::post('delete-event','MealCalendarController@deleteEvent');
        });
    });
    /* End: Meal-planner routes */

     /* Start: Pipeline Process routes */
     Route::group(['prefix' => 'pipeline-process', 'namespace' => 'PipelineProcess'], function() {

        Route::get('dashboard', 'DashboardController@index');

        // Project route
        Route::post('salesProcessStep', 'ProjectController@salesProcessStep');
        Route::get('projects', 'ProjectController@index');
        Route::get('project', 'ProjectController@filterProject');
        Route::post('project-store','ProjectController@store');
        Route::get('projects/{slug}', 'ProjectController@projectDetail');
        Route::post('project-edit','ProjectController@update');
        Route::get('projects/add-favorite/{id}', 'ProjectController@projectAddFavorite');
        Route::get('projects/remove-favorite/{id}', 'ProjectController@projectRemoveFavorite');

        Route::get('projects/complete/{id}', 'ProjectController@projectComplete');
        Route::get('projects/incomplete/{id}', 'ProjectController@projectIncomplete');

        Route::post('projects/add-archive', 'ProjectController@projectAddArchive');
        Route::post('projects/restore', 'ProjectController@projectRestore');

        Route::post('projects/delete', 'ProjectController@projectDelete');

        Route::post('projects/update-project-columns-state','ProjectController@updateProjectColumnsState');
        Route::post('projects/update-column-tasks-state', 'ProjectController@updateColumnTasksState');
        Route::post('projects/update-leave-column-tasks-state', 'ProjectController@updateLeaveColumnTasksState');
        Route::post('projects/get-assigned-project-member', 'ProjectController@getAssignedProjectMember');
        Route::post('projects/delete-selected-project', 'ProjectController@deleteSelectedProject');

        // Column route 
        Route::post('column/store','ColumnController@store');
        Route::post('column/update','ColumnController@update');
        Route::post('column/delete', 'ColumnController@destroy');
        Route::post('column/getTask', 'ColumnController@getTask');


        // ProjectMyTask url
        // Route::get('my-task', 'ProjectMyTaskController@index');
        Route::post('task/store','ProjectMyTaskController@store');
        Route::post('delete/task', 'ProjectMyTaskController@destroy');
        Route::post('task/update','ProjectMyTaskController@update');
        Route::post('task/priority','ProjectMyTaskController@update');
        Route::post('task/dueDate','ProjectMyTaskController@update');
        Route::post('task/status','ProjectMyTaskController@update');
        Route::post('task/updateSubTask','ProjectMyTaskController@update');
        Route::post('task/comment','ProjectMyTaskController@taskComment');
        Route::post('task/subTask','ProjectMyTaskController@subTask');
        Route::get('task/download/{filename}','ProjectMyTaskController@download');
        Route::post('task/assign-user', 'ProjectMyTaskController@assignUser');
        Route::post('task/assign-user-sub-task', 'ProjectMyTaskController@assignUserSubTask');
        Route::post('task/change-task-column', 'ProjectMyTaskController@changeTaskColumn');
        Route::post('task/sales-preference-add', 'ProjectMyTaskController@sales_preference_add');
        Route::post('task/sales-preference-remove', 'ProjectMyTaskController@sales_preference_remove');
        Route::post('projects/update-tasks-proccess', 'ProjectMyTaskController@updateTasksProccess');
        Route::post('task/complete-all-epic-task', 'ProjectMyTaskController@completeAllTasksProccess');
        Route::post('task/incomplete-all-epic-task', 'ProjectMyTaskController@completeAllTasksProccess');
        Route::post('task/popup','MyTaskController@taskPopup');

        Route::post('task/epic-process-add', 'ProjectMyTaskController@epic_process_add');
        Route::post('task/epic-process-remove', 'ProjectMyTaskController@epic_process_remove');
        Route::post('projects/get-epic-process-data', 'ProjectMyTaskController@get_epic_process_data');

        // My Task url
        Route::get('tasks/', 'MyTaskController@index');
        Route::get('tasks/{filter}', 'MyTaskController@filterTask');
        Route::get('project/tasks/{project}', 'MyTaskController@projectTask');

        // Calendar route
        Route::get('calendar', 'CalendarController@index');
        Route::post('/calendar/create','CalendarController@create');
        Route::post('/calendar/update','CalendarController@update');
        Route::post('/calendar/delete','CalendarController@destroy');

       
    });
    /* End: Pipeline Process routes */

    /* Start: Meal-planner serving size routes */
    Route::group(['prefix' => 'serving-size', 'namespace' => 'MealPlanner'], function(){
        Route::get('','ServingSizeController@index')->name('serving.getSize');
        Route::post('','ServingSizeController@save');
        Route::delete('{id}','ServingSizeController@destroy');
    });
    /* Start: Meal-planner serving size routes */

    /* Start: Meal-planner serving size routes */
    Route::group(['prefix' => 'meal-categories', 'namespace' => 'MealPlanner'], function(){
        Route::get('','MealCategoryController@index')->name('meal.getCat');
        Route::post('','MealCategoryController@save');
        Route::delete('{id}','MealCategoryController@destroy');
    });
    /* Start: Meal-planner serving size routes */

    /**
     * Helper routes
     */
    Route::group(['middleware' => ['web']], function () {
        Route::get('countries', 'Helper@getCountries');
        Route::get('countries/{country_code}', 'Helper@getStates');
    });

    /* Excel file hendaler */
    Route::post('excel-to-db', 'FileController@importExcelIntoDB')->name('excel.import');

    Route::get('get-membership-status', 'ClientsController@getMemberShipStatus')->name('clients.getMemberShipStatus');

});


Route::get('/due_message', 'GoalBuddy\GoalBuddyController@due_message');
Route::get('/email_message', 'GoalNotification@emailNotification');

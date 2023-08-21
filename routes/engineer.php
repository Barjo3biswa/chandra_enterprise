<?php

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::group(['prefix' => 'all-assigned-zone'], function () {
	Route::get('/', [
        'as' => 'view-all-assigned-zone',
        'middleware' => ['permission:view engineer-all-assigned-zone'],
        'uses' => 'Engineer\EnggAssignedZoneController@index'
    ]);

    Route::get('/show/{zone_id}', [
        'as' => 'show-assigned-zone',
        'uses' => 'Engineer\EnggAssignedZoneController@show'
    ]);

});

Route::group(['prefix' => 'all-assigned-clients-amc'], function () {

    Route::get('/', [
        'as' => 'view-all-assigned-clients-amc',
        'middleware' => ['permission:view all-assigned-clients-amc'],
        'uses' => 'Engineer\ClientAmcController@index'
    ]);

    Route::get('/get-client-amc-details/{id}', [
        'as' => 'view-clients-amc-details',
        'uses' => 'Engineer\ClientAmcController@show'
    ]);

    Route::get('/bill-payment/{id}', [
        'as' => 'amc-create-bill-payment',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ClientAmcController@raiseBillEditPayment'
    ]);

    Route::get('/bill-payment-edit/{id}', [
        'as' => 'raise-amc-edit-bill',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ClientAmcController@raiseBillEdit'
    ]);

    Route::patch('/bill-payment-update/{id}', [
        'as' => 'amc-bill-payment-update',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ClientAmcController@raiseBillEditPaymentUpdate'
    ]);

   Route::get('/bill-payment-details/{id}', [
        'as' => 'amc-bill-payment-details',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ClientAmcController@raiseBillPaymentDetails'
    ]);

    Route::get('/bill-payment-delete/{id}', [
        'as' => 'amc-payment-delete-bill',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ClientAmcController@raiseBillDelete'
    ]);

    Route::get('/export', [
        'as' => 'client-amc-details.excel',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ClientAmcController@export'
    ]);

});    

Route::group(['prefix' => 'daily-service-report'], function () {
	Route::get('/', [
        'as' => 'view-all-daily-service-report',
        'middleware' => ['permission:view all-daily-service-report'],
        'uses' => 'Engineer\DailyServiceReportController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-daily-service-report',
        'middleware' => ['permission:add all-daily-service-report'],
        'uses' => 'Engineer\DailyServiceReportController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-daily-service-report.store',
        'uses' => 'Engineer\DailyServiceReportController@store'
    ]);

    Route::get('/get-report-details', [
        'as' => 'getcomplaintoramc-daily-service-report.ajax',
        'uses' => 'Engineer\DailyServiceReportController@getComplaintOrAmcDetails'
    ]);

    Route::get('/get-client-branch-name', [
        'as' => 'get-client-branch-name-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'Engineer\DailyServiceReportController@getBranchName'
    ]);

    Route::get('/get-product-name', [
        'as' => 'get-dsr-product-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'Engineer\DailyServiceReportController@getDsrProductDetails'
    ]);

    Route::get('/get-spare-part-detail', [
        'as' => 'get-spare-part-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'Engineer\DailyServiceReportController@getSparePartDetails'
    ]);

    Route::get('/get-client-contact-person-details', [
        'as' => 'get-client-contact-person-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'Engineer\DailyServiceReportController@getContactPersonDetails'
    ]);

    Route::get('/get-complaint-details', [
        'as' => 'get-maintenance-complaint-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'Engineer\DailyServiceReportController@getMaintenanceComplaintDetails'
    ]);

    Route::get('/get-dsr-details/{id}', [
        'as' => 'get-dsr-details',
        'middleware' => ['auth'],
        'uses' => 'Engineer\DailyServiceReportController@show'
    ]);

    Route::get('/edit-dsr-details/{id}', [
        'as' => 'edit-dsr-details',
        'middleware' => ['permission:edit all-daily-service-report'],
        'uses' => 'Engineer\DailyServiceReportController@edit'
    ]);

    Route::get('/deactivate/{id}', [
        'as' => 'destroy-dsr',
        'middleware' => ['permission:delete all-daily-service-report'],
        'uses' => 'Engineer\DailyServiceReportController@destroy'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-dsr',
        'middleware' => ['auth'],
        'uses' => 'Engineer\DailyServiceReportController@update'
    ]);

    Route::get('/print-view/{id}', [
        'as' => 'print-view-dsr',
        'middleware' => ['auth'],
        'uses' => 'Engineer\DailyServiceReportController@printView'
    ]);

    Route::get('/export', [
        'as' => 'engg-dsr-details.excel',
        'middleware' => ['auth'],
        'uses' => 'Engineer\DailyServiceReportController@export'
    ]);

});	

Route::group(['prefix' => 'complaint-details'], function () {

    Route::get('/', [
        'as' => 'engg-view-all-complaints',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ComplaintController@allComplaint'
    ]);

    Route::get('/details/{id}', [
        'as' => 'details-complaint-register',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ComplaintController@show'
    ]);

    Route::get('/all-closed-complaint', [
        'as' => 'view-closed-complaints',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ComplaintController@closedComplaint'
    ]);

    Route::get('/all-assigned-complaint', [
        'as' => 'engg-view-all-assigned-complaints',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ComplaintController@index'
    ]);

    // Route::get('/show-closed-complaint/{id}', [
    //     'as' => 'show-closed-complaints',
    //     'middleware' => ['auth'],
    //     'uses' => 'Engineer\ComplaintController@showclosedComplaint'
    // ]);

    Route::get('/export', [
        'as' => 'engg-complaint-details.excel',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ComplaintController@export'
    ]);

});

Route::group(['prefix' => 'issued-spare-part-details'], function () {
    Route::get('/', [
        'as' => 'issued-spare-parts-details',
        'middleware' => ['permission:view engineer-issued-spare-parts-details'],
        'uses' => 'Engineer\SparePartController@index'
    ]);

    Route::get('/export', [
        'as' => 'issued-spare-parts-details.excel',
        'middleware' => ['auth'],
        'uses' => 'Engineer\SparePartController@export'
    ]);
}); 

Route::group(['prefix' => 'assigned-toolkit-details'], function () {
    Route::get('/', [
        'as' => 'assigned-toolkits-details',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ToolKitController@index'
    ]);

    Route::get('/export', [
        'as' => 'assigned-toolkits-details.excel',
        'middleware' => ['auth'],
        'uses' => 'Engineer\ToolKitController@export'
    ]);
});

Route::group(['prefix' => 'outstanding-details'], function () {
    Route::get('/', [
        'as' => 'all-bill-outstanding-details',
        'middleware' => ['permission:view all-bill-outstanding-details'],
        'uses' => 'Engineer\OutstandingController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-bill-outstanding-details',
        'middleware' => ['auth'],
        'uses' => 'Engineer\OutstandingController@create'
    ]);

    Route::get('/get-all-client-branch', [
        'as' => 'get-branch.ajax',
        'middleware' => ['auth'],
        'uses' => 'Engineer\OutstandingController@getBranchName'
    ]);

    Route::post('/create', [
        'as' => 'add-new-bill-outstanding-details.store',
        'middleware' => ['auth'],
        'uses' => 'Engineer\OutstandingController@store'
    ]);

    Route::get('/details/{id}', [
        'as' => 'show-bill-outstanding-details',
        'middleware' => ['auth'],
        'uses' => 'Engineer\OutstandingController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-bill-outstanding-details',
        'middleware' => ['auth'],
        'uses' => 'Engineer\OutstandingController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-bill-outstanding-details',
        'middleware' => ['auth'],
        'uses' => 'Engineer\OutstandingController@update'
    ]);

    Route::get('/delete/{id}', [
        'as' => 'delete-bill-outstanding-details',
        'middleware' => ['auth'],
        'uses' => 'Engineer\OutstandingController@delete'
    ]);

    Route::get('/export', [
        'as' => 'bill-outstanding-details.excel',
        'middleware' => ['auth'],
        'uses' => 'Engineer\OutstandingController@export'
    ]);

});    
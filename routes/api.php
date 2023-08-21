<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'REST\AuthController@login')->name('api.login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('version', function () {
    \Log::critical([
        "mobile_agent" => request()->header('user-agent'),
        "ip" => request()->ip(),
        "fullurl" => request()->fullUrl(),
    ]);
    return response()->json([
        "version" => (Int)env("APP_VERSION_API")
    ]);
});
// Route::get('getPasswordRequest', 'REST\AuthController@getPasswordChangeOtp');

Route::middleware('jwt.auth')->group(function(){
    
    Route::post('logout', 'REST\AuthController@logout');
    Route::post('all-assigned-complaint', 'REST\ComplaintController@index');

    Route::post('/all-closed-complaint', [
        'as' => 'all-closed-complaints',
        'uses' => 'REST\ComplaintController@closedComplaint'
    ]);

    Route::post('/all-complaint', [
        'as' => 'all-complaints',
        'uses' => 'REST\ComplaintController@getAllComplaint'
    ]);

 	Route::post('all-count', 'REST\DashoardController@index');

 	Route::post('all-dsr', 'REST\DsrController@index');

 	Route::post('all-amc', 'REST\AmcController@index');

    Route::post('all-monthly-amc', 'REST\AmcController@allMonthlyAmc');
    Route::post('store-monthly-amc', 'REST\AmcController@store');
    Route::post('update-monthly-amc/{id}', 'REST\AmcController@update');

    Route::post('add-new-dsr', 'REST\DsrController@create');

    Route::post('add-new-breakdown', 'REST\ComplaintController@addNewBreakDown');
    Route::post('store-breakdown', 'REST\ComplaintController@store');
    Route::post('update-breakdown/{id}', 'REST\ComplaintController@update');

    Route::post('all-outstanding', 'REST\OutstandingController@index');
    Route::post('add-new-outstanding', 'REST\OutstandingController@create');
    Route::post('store-outstanding', 'REST\OutstandingController@store');

    Route::post('store-user-track-location', 'REST\LocationController@store');

    Route::post('all-assigned-toolkit/{user_id}', 'REST\ToolKitController@index');

    Route::post('all-assigned-spare-parts/{user_id}', 'REST\SparePartController@index');

    Route::post("upcoming-amc", [
        "uses" => "REST\AmcController@upcomingAMC"
    ]);
    Route::group(['prefix' => 'toolkit-request'], function () {
        Route::post('new', ["uses" => "REST\ToolkitRequestController@new"]);
    });
    Route::post('user-client-data', ["uses" => "REST\DashoardController@userClientData"]);

    Route::post("client-name-search", [
        "uses"  => "REST\DashoardController@ajaxClientNameSearch"
    ]);
    
    Route::post("branch-name-search", [
        "uses"  => "REST\DashoardController@ajaxBranchNameSearch"
    ]);

});
Route::post("validate-scr-no", ["uses" => "REST\DsrController@validateScrNo"]);
Route::post("post-bulk-locations", ["uses" => "REST\LocationController@bulkLocations"]);

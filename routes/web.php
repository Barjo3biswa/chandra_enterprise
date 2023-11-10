<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {

    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
});
Route::get('/home', function () {
    return redirect('/dashboard');
});

Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');

Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.password.request');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('user.password.email');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/automatic-assign', [
    'as' => 'automatic-assign',
    'uses' => 'ClientAmcController@AutomaticAssign'
]);



Route::get('/dashboard', [
	'as' => 'dashboard',
    'middleware' => ['auth'],
	'uses' => 'DashboardController@index'
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'change-password'], function () {
    Route::get('/', [
          'as' => 'change-password',
          'middleware' => ['auth'],
          'uses' => 'ChangePasswordController@changePassword'
    ]);

    Route::post('/credentials', [
          'as' => 'user.change-password.post',
          'middleware' => ['auth'],
          'uses' => 'ChangePasswordController@changePasswordStore'
    ]);
});


Route::group(['prefix' => 'role'], function () {
    Route::get('/', [
        'as' => 'view-all-roles',
        'middleware' => ['permission:view role'],
        'uses' => 'RoleController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-role',
        'middleware' => ['permission:add role'],
        'uses' => 'RoleController@create'
    ]);

    Route::post('/create', [
        'as' => 'user.permission.store',
        'middleware' => ['auth'],
        'uses' => 'RoleController@store'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-role',
        'middleware' => ['permission:show role'],
        'uses' => 'RoleController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-role',
        'middleware' => ['permission:edit role'],
        'uses' => 'RoleController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-role',
        'middleware' => ['auth'],
        'uses' => 'RoleController@update'
    ]);

    Route::get('/export', [
        'as' => 'role-details.excel',
        'middleware' => ['auth'],
        'uses' => 'RoleController@export'
    ]);

    Route::get('/delete/{id}', [
        'as' => 'delete-role',
        'middleware' => ['permission:delete role'],
        'uses' => 'RoleController@destroy'
    ]);

    // Route::post('user_permission', 'PermissionController@user_permission')->name('user.permission.store');
});

// Route::group(['prefix' => 'menu'], function () {

// 	Route::get('/', [
//         'as' => 'view-all-menus',
//         'middleware' => ['auth'],
//         'uses' => 'MenuController@index'
//     ]);

//     Route::get('/create', [
//         'as' => 'add-new-menu',
//         'middleware' => ['auth'],
//         'uses' => 'MenuController@create'
//     ]);

//     Route::post('/create', [
//         'as' => 'add-new-menu.post',
//         'middleware' => ['auth'],
//         'uses' => 'MenuController@store'
//     ]);

//     Route::get('/edit/{id}', [
//         'as' => 'edit-menu',
//         'middleware' => ['auth'],
//         'uses' => 'MenuController@edit'
//     ]);

//     Route::patch('/update/{id}', [
//         'as' => 'update-menu',
//         'middleware' => ['auth'],
//         'uses' => 'MenuController@update'
//     ]);

//     Route::get('/export', [
//         'as' => 'menu-details.excel',
//         'middleware' => ['auth'],
//         'uses' => 'MenuController@export'
//     ]);

// });

// Route::group(['prefix' => 'sub-menu'], function () {

//     Route::get('/', [
//         'as' => 'view-all-sub-menues',
//         'middleware' => ['auth'],
//         'uses' => 'SubMenuController@index'
//     ]);

//     Route::get('/create', [
//         'as' => 'add-new-sub-menues',
//         'middleware' => ['auth'],
//         'uses' => 'SubMenuController@create'
//     ]);

//     Route::post('/create', [
//         'as' => 'add-new-sub-menues.post',
//         'middleware' => ['auth'],
//         'uses' => 'SubMenuController@store'
//     ]);

//     Route::get('/edit/{id}', [
//         'as' => 'edit-sub-menues',
//         'middleware' => ['auth'],
//         'uses' => 'SubMenuController@edit'
//     ]);

//     Route::patch('/update/{id}', [
//         'as' => 'update-sub-menues',
//         'middleware' => ['auth'],
//         'uses' => 'SubMenuController@update'
//     ]);

//     Route::get('/export', [
//         'as' => 'sub-menues-details.excel',
//         'middleware' => ['auth'],
//         'uses' => 'SubMenuController@export'
//     ]);

// });

Route::group(['prefix' => 'group'], function () {

    Route::get('/', [
        'as' => 'view-all-groups',
        'middleware' => ['permission:view group'],
        'uses' => 'GroupController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-groups',
        'middleware' => ['permission:add group'],
        'uses' => 'GroupController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-groups.post',
        'middleware' => ['auth'],
        'uses' => 'GroupController@store'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-groups',
        'middleware' => ['auth','permission:edit group'],
        'uses' => 'GroupController@edit'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-groups',
        'middleware' => ['auth'],
        'uses' => 'GroupController@show'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-groups',
        'middleware' => ['auth'],
        'uses' => 'GroupController@update'
    ]);

    Route::get('/export', [
        'as' => 'group-details.excel',
        'middleware' => ['auth'],
        'uses' => 'GroupController@export'
    ]);

    Route::get('/deactivate/{id}', [
        'as' => 'destroy-groups',
        'middleware' => ['auth', 'permission:delete group'],
        'uses' => 'GroupController@destroy'
    ]);

}); 

Route::group(['prefix' => 'sub-group'], function () {

    Route::get('/', [
        'as' => 'view-all-sub-groups',
        'middleware' => ['permission:view sub_group'],
        'uses' => 'SubGroupController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-sub-groups',
        'middleware' => ['permission:add sub_group'],
        'uses' => 'SubGroupController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-sub-groups.post',
        'middleware' => ['auth'],
        'uses' => 'SubGroupController@store'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-sub-groups',
        'middleware' => ['auth'],
        'uses' => 'SubGroupController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-sub-groups',
        'middleware' => ['permission:edit sub_group'],
        'uses' => 'SubGroupController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-sub-groups',
        'middleware' => ['auth'],
        'uses' => 'SubGroupController@update'
    ]);

    Route::get('/export', [
        'as' => 'sub-group-details.excel',
        'middleware' => ['auth'],
        'uses' => 'SubGroupController@export'
    ]);

    Route::get('/deactivate/{id}', [
        'as' => 'destroy-sub-groups',
        'middleware' => ['permission:delete sub_group'],
        'uses' => 'SubGroupController@destroy'
    ]);

}); 


Route::group(['prefix' => 'zones'], function () {

    Route::get('/', [
        'as' => 'view-all-zones',
        'middleware' => ['permission:view zone'],
        'uses' => 'ZoneController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-zone',
        'middleware' => ['permission:add zone'],
        'uses' => 'ZoneController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-zone.post',
        'middleware' => ['auth'],
        'uses' => 'ZoneController@store'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-zone',
        'middleware' => ['auth'],
        'uses' => 'ZoneController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-zone',
        'middleware' => ['permission:edit zone'],
        'uses' => 'ZoneController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-zone',
        'middleware' => ['auth'],
        'uses' => 'ZoneController@update'
    ]);

    Route::get('/deactivate/{id}', [
        'as' => 'destroy-zone',
        'middleware' => ['permission:delete zone'],
        'uses' => 'ZoneController@destroy'
    ]);

    Route::get('/export', [
        'as' => 'zone-details.excel',
        'middleware' => ['auth'],
        'uses' => 'ZoneController@export'
    ]);

});


Route::group(['prefix' => 'regions'], function () {

    Route::get('/', [
        'as' => 'view-all-regions',
        'middleware' => ['permission:view region'],
        'uses' => 'RegionController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-region',
        'middleware' => ['permission:add region'],
        'uses' => 'RegionController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-region.post',
        'middleware' => ['auth'],
        'uses' => 'RegionController@store'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-region',
        'middleware' => ['auth'],
        'uses' => 'RegionController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-region',
        'middleware' => ['permission:edit region'],
        'uses' => 'RegionController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-region',
        'middleware' => ['auth'],
        'uses' => 'RegionController@update'
    ]);

    Route::get('/deactivate/{id}', [
        'as' => 'destroy-region',
        'middleware' => ['permission:delete region'],
        'uses' => 'RegionController@destroy'
    ]);

    Route::get('/export', [
        'as' => 'region-details.excel',
        'middleware' => ['auth'],
        'uses' => 'RegionController@export'
    ]);

}); 

Route::group(['prefix' => 'user'], function () {

    Route::get('/', [
        'as' => 'view-all-users',
        'middleware' => ['permission:view user'],
        'uses' => 'UserController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-users',
        'middleware' => ['permission:add user'],
        'uses' => 'UserController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-users.post',
        'middleware' => ['auth'],
        'uses' => 'UserController@store'
    ]);

    Route::get('/get-district-list', [
        'as' => 'getdistlist.ajax.post',
        'middleware' => ['auth'],
        'uses' => 'UserController@getDistList'
    ]);

    Route::get('/details/{id}', [
        'as' => 'details-users',
        'middleware' => ['auth'],
        'uses' => 'UserController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-users',
        'middleware' => ['permission:edit user'],
        'uses' => 'UserController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-users',
        'middleware' => ['auth'],
        'uses' => 'UserController@update'
    ]);

    Route::get('/reset-password/{id}', [
        'as' => 'reset-password_user',
        'middleware' => ['permission:reset user password'],
        'uses' => 'UserController@changePassword'
    ]);

    Route::get('/export', [
        'as' => 'user-details.excel',
        'middleware' => ['auth'],
        'uses' => 'UserController@export'
    ]);

    Route::get('/deactivate/{id}', [
        'as' => 'deactivate-user',
        'middleware' => ['permission:delete user'],
        'uses' => 'UserController@deactivateUser'
    ]);

    Route::get('/activate/{id}', [
        'as' => 'activate-user',
        'middleware' => ['permission:delete user'],
        'uses' => 'UserController@activateUser'
    ]);

    Route::get('/delete/{id}', [
        'as' => 'destroy-user',
        'middleware' => ['permission:delete user'],
        'uses' => 'UserController@destroy'
    ]);

    Route::get('/assign-tools/{id}', [
        'as' => 'user-assign-tools',
        'middleware' => ['permission:add assign tools'],
        'uses' => 'UserController@assignTool'
    ]);

    Route::post('/assign-tools-store/{id}', [
        'as' => 'user-assign-tools.post',
        'middleware' => ['auth'],
        'uses' => 'UserController@assignToolStore'
    ]);

    Route::get('/show-assign-tools/{user_id}', [
        'as' => 'show-user-assign-tools',
        'middleware' => ['permission:view assign tools'],
        'uses' => 'UserController@showAssignTool'
    ]);

    Route::get('/edit-assign-tools/{user_id}', [
        'as' => 'edit-user-assign-tools',
        'middleware' => ['permission:edit assign tools'],
        'uses' => 'UserController@editAssignTool'
    ]);

    Route::patch('/update-assign-tools/{user_id}', [
        'as' => 'update-user-assign-tools',
        'middleware' => ['auth'],
        'uses' => 'UserController@updateAssignTool'
    ]);

    Route::get('/delete-assign-tools/{user_id}', [
        'as' => 'delete-user-assign-tools',
        'middleware' => ['permission:delete assign tools'],
        'uses' => 'UserController@deleteAssignTool'
    ]);

}); 	

Route::group(['prefix' => 'products'], function () {

    Route::get('/', [
        'as' => 'view-all-product',
        'middleware' => ['permission:view product'],
        'uses' => 'ProductController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-product',
        'middleware' => ['permission:add product'],
        'uses' => 'ProductController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-product.post',
        'middleware' => ['auth'],
        'uses' => 'ProductController@store'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-product',
        'middleware' => ['auth'],
        'uses' => 'ProductController@show'
    ]);

    Route::get('/get-detail/{id}', [
        'as' => 'get-company-product-detail',
        'middleware' => ['auth'],
        'uses' => 'ProductController@getCompanyProductDetail'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-product',
        'middleware' => ['permission:edit product'],
        'uses' => 'ProductController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-product',
        'middleware' => ['auth'],
        'uses' => 'ProductController@update'
    ]);

    Route::get('/export', [
        'as' => 'product-details.excel',
        'middleware' => ['auth'],
        'uses' => 'ProductController@export'
    ]);

    Route::get('/get-sub-group-list', [
        'as' => 'getsubgroup.ajax.post',
        'middleware' => ['auth'],
        'uses' => 'ProductController@getSubGroupList'
    ]);
 
    Route::get('/deactivate/{id}', [
        'as' => 'deactivate-product',
        'middleware' => ['permission:delete product'],
        'uses' => 'ProductController@deactivateProduct'
    ]);

    Route::get('/activate/{id}', [
        'as' => 'activate-product',
        'middleware' => ['permission:delete product'],
        'uses' => 'ProductController@activateProduct'
    ]);

    Route::get('/delete/{id}', [
        'as' => 'destroy-product',
        'middleware' => ['permission:delete product'],
        'uses' => 'ProductController@destroy'
    ]);

}); 

Route::group(['prefix' => 'companies'], function () {

    Route::get('/', [
        'as' => 'view-all-company',
        'middleware' => ['permission:view company'],
        'uses' => 'CompanyController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-company',
        'middleware' => ['permission:add company'],
        'uses' => 'CompanyController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-company.post',
        'middleware' => ['auth'],
        'uses' => 'CompanyController@store'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-company',
        'middleware' => ['auth'],
        'uses' => 'CompanyController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-company',
        'middleware' => ['permission:edit company'],
        'uses' => 'CompanyController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-company',
        'middleware' => ['auth'],
        'uses' => 'CompanyController@update'
    ]);

    Route::get('/export', [
        'as' => 'company-details.excel',
        'middleware' => ['auth'],
        'uses' => 'CompanyController@export'
    ]);

    Route::get('/deactivate/{id}', [
        'as' => 'destroy-company',
        'middleware' => ['permission:delete company'],
        'uses' => 'CompanyController@destroy'
    ]);

});  

Route::group(['prefix' => 'clients'], function () {

    Route::get('/', [
        'as' => 'view-all-client',
        'middleware' => ['permission:view client'],
        'uses' => 'ClientController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-client',
        'middleware' => ['permission:add client'],
        'uses' => 'ClientController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-client.post',
        'middleware' => ['auth'],
        'uses' => 'ClientController@store'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-client',
        'middleware' => ['permission:edit client'],
        'uses' => 'ClientController@edit'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-client',
        'middleware' => ['auth'],
        'uses' => 'ClientController@show'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-client',
        'middleware' => ['auth'],
        'uses' => 'ClientController@update'
    ]);

    Route::get('/export', [
        'as' => 'client-details.excel',
        'middleware' => ['auth'],
        'uses' => 'ClientController@export'
    ]);

    Route::get('/deactivated/{id}', [
        'as' => 'destroy-client',
        'middleware' => ['permission:delete client'],
        'uses' => 'ClientController@destroy'
    ]);

    Route::get('/get-client-branch-list', [
        'as' => 'getclientbranch.ajax.post',
        'middleware' => ['auth'],
        'uses' => 'ClientController@getBranchName'
    ]);

    // Route::post('/filter', [
    //     'as' => 'filter-client.post',
    //     'middleware' => ['auth'],
    //     'uses' => 'ClientController@filterClientDetails'
    // ]);

    Route::post("/convert-client", [
        'as' => 'convert-client.post',
        'middleware' => ['auth'],
        'uses' => 'ClientController@convertClient'
    ]);

}); 

Route::group(['prefix' => 'tool-kits'], function () {
     Route::get('/', [
        'as' => 'view-all-tool-kit',
        'middleware' => ['permission:view tool-kit'],
        'uses' => 'ToolKitController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-tool-kit',
        'middleware' => ['permission:add tool-kit'],
        'uses' => 'ToolKitController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-tool-kit.post',
        'middleware' => ['auth'],
        'uses' => 'ToolKitController@store'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-tool-kit',
        'middleware' => ['permission:edit tool-kit'],
        'uses' => 'ToolKitController@edit'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-tool-kit',
        'middleware' => ['auth'],
        'uses' => 'ToolKitController@show'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-tool-kit',
        'middleware' => ['auth'],
        'uses' => 'ToolKitController@update'
    ]);

    Route::get('/export', [
        'as' => 'tool-kit-details.excel',
        'middleware' => ['auth'],
        'uses' => 'ToolKitController@export'
    ]);

    Route::get('/deactivated/{id}', [
        'as' => 'destroy-tool-kit',
        'middleware' => ['permission:delete tool-kit'],
        'uses' => 'ToolKitController@destroy'
    ]);
});


Route::group(['prefix' => 'assign-product-to-client'], function () {

    Route::get('/', [
        'as' => 'view-all-assign-client',
        'middleware' => ['permission:view assign-product-to-client'],
        'uses' => 'AssignClientController@index'
    ]);

    Route::get('/get-sub-group', [
        'as' => 'get-sub-group.ajax',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@getSubGroup'
    ]);

    Route::get('/get-group', [
        'as' => 'get-group.ajax',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@getGroupDetail'
    ]);

    Route::get('/get-detail', [
        'as' => 'get-details-assign-new-product-to-client.ajax',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@getDetailOfProduct'
    ]);

    Route::get('/create', [
        'as' => 'assign-new-product-to-client',
        'middleware' => ['permission:add assign-product-to-client'],
        'uses' => 'AssignClientController@create'
    ]);

    Route::post('/create', [
        'as' => 'assign-new-product-to-client.post',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@store'
    ]);

    Route::get('/edit/{client_id}', [
        'as' => 'edit-assign-new-product-to-client',
        'middleware' => ['permission:edit assign-product-to-client'],
        'uses' => 'AssignClientController@edit'
    ]);

    Route::get('/edit-product/{product_id}', [
        'as' => 'product-edit-assign-to-client',
        'middleware' => ['permission:edit product assign-product-to-client'],
        'uses' => 'AssignClientController@productEdit'
    ]);

    Route::patch('/update-product/{product_id}', [
        'as' => 'product-update-assign-to-client',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@productUpdate'
    ]);

    Route::get('/delete-product/{product_id}', [
        'as' => 'delete-product-assign-to-client',
        'middleware' => ['permission:delete product assign-product-to-client'],
        'uses' => 'AssignClientController@productDestroy'
    ]);

    Route::get('/show/{client_id}', [
        'as' => 'show-assign-new-product-to-client',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@show'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-assign-new-product-to-client',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@update'
    ]);

    Route::get('/export', [
        'as' => 'assign-new-product-to-client-details.excel',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@export'
    ]);

    Route::get('/deactivated/{id}', [
        'as' => 'destroy-assign-new-product-to-client',
        'middleware' => ['permission:delete assign-product-to-client'],
        'uses' => 'AssignClientController@destroy'
    ]);

    Route::get('/transfer-product/{id}', [
        'as' => 'transfer-assign-product-to-another-client',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@transferProduct'
    ]);

    Route::post('/transfer-product/{id}', [
        'as' => 'transfer-assign-product-to-another-client.post',
        'middleware' => ['auth'],
        'uses' => 'AssignClientController@transferProductPost'
    ]);

});

Route::group(['prefix' => 'assign-engineer'], function () {

    Route::get('/', [
        'as' => 'view-all-assign-engineer',
        'middleware' => ['permission:view assign-engineer'],
        'uses' => 'AssignEngineerController@index'
    ]);

    Route::get('/create', [
        'as' => 'assign-new-client-to-engineer',
        'middleware' => ['permission:add assign-engineer'],
        'uses' => 'AssignEngineerController@create'
    ]);

    Route::get('/get-rolewise-user', [
        'as' => 'get-rolewise-user-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@getRolewiseUser'
    ]);

    Route::get('/get-client-branch', [
        'as' => 'get-client-branch-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@getBranchName'
    ]);

    Route::get('/get-company', [
        'as' => 'get-company-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@getCompanyDetail'
    ]);

    Route::get('/get-group', [
        'as' => 'get-group-engineer.ajax',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@getGroupDetail'
    ]);

    Route::get('/get-client', [
        'as' => 'get-client-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@getClientDetail'
    ]);


    Route::get('/get-detail', [
        'as' => 'get-details-assign-new-client-to-engineer.ajax',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@getDetailOfProduct'
    ]);

    Route::post('/create', [
        'as' => 'assign-new-client-to-engineer.post',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@store'
    ]);

    Route::get('/edit/{engineer_id}', [
        'as' => 'edit-assign-new-client-to-engineer',
        'middleware' => ['permission:edit assign-engineer'],
        'uses' => 'AssignEngineerController@edit'
    ]);

    Route::get('/client-edit/{engineer_id}', [
        'as' => 'assign-client-engineer.edit',
        'middleware' => ['permission:edit client assign-engineer'],
        'uses' => 'AssignEngineerController@clientEdit'
    ]);

    Route::patch('/client-update/{engineer_id}', [
        'as' => 'assign-client-engineer.update',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@clientUpdate'
    ]);

    Route::get('/delete-assigned-client/{engineer_id}', [
        'as' => 'assign-client-to-engineer.delete',
        'middleware' => ['permission:delete client assign-engineer'],
        'uses' => 'AssignEngineerController@clientDelete'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-assign-new-client-to-engineer',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@show'
    ]);
 
    Route::get('/export', [
        'as' => 'assign-new-client-to-engineer-details.excel',
        'middleware' => ['auth'],
        'uses' => 'AssignEngineerController@export'
    ]);

    Route::get('/deactivated/{engineer_id}', [
        'as' => 'destroy-assign-new-client-to-engineer',
        'middleware' => ['permission:delete assign-engineer'],
        'uses' => 'AssignEngineerController@destroy'
    ]);

});


Route::group(['prefix' => 'complaint-register'], function () {

    Route::get('/', [
        'as' => 'view-all-complaints',
        'middleware' => ['permission:view complaint'],
        'uses' => 'ComplaintController@index'
    ]);

    Route::get('/closed-complaint', [
        'as' => 'view-closed-complaints',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@closedComplaint'
    ]);

    Route::get('/create', [
        'as' => 'add-new-complaint',
        'middleware' => ['permission:add complaint'],
        'uses' => 'ComplaintController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-complaint.post',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@store'
    ]);

    Route::get('/get-all-branch', [
        'as' => 'get-all-branch-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@getBranchName'
    ]);

    Route::get('/get-all-branch-new', [
        'as' => 'get-all-branch-details-new.ajax',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@getBranchNameNew'
    ]);

    Route::get('/get-contact-person-details', [
        'as' => 'get-contact-person-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@getContactPersonDetails'
    ]);

    Route::get('/get-all-complaint-master', [
        'as' => 'get-all-complaint-master-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@getComplaintMaster'
    ]);

    Route::get('/assign-to/{id}', [
        'as' => 'assigned-complaint-to-engineer',
        'middleware' => ['permission:assign complaint to engineer'],
        'uses' => 'ComplaintController@assignToEngineer'
    ]);

    Route::patch('/assign-to-update/{id}', [
        'as' => 'update-assigned-complaint-to-engineer',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@assignToEngineerUpdate'
    ]);

    Route::patch('/update-complaint-status/{id}', [
        'as' => 'update-complaint-status',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@updateComplaintStatus'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-complaint-register-details',
        'middleware' => ['permission:edit complaint'],
        'uses' => 'ComplaintController@edit'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-complaint-register-details',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@show'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-complaint-register-details',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@update'
    ]);

    Route::get('/delete/{id}', [
        'as' => 'delete-complaint-register-details',
        'middleware' => ['permission:delete complaint'],
        'uses' => 'ComplaintController@destroy'
    ]);

    Route::get('/get-all-engineers', [
        'as' => 'get-all-engineers-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@getEngineerDetails'
    ]);

    Route::get('/export', [
        'as' => 'complaint-details.excel',
        'middleware' => ['auth'],
        'uses' => 'ComplaintController@export'
    ]);

});


Route::group(['prefix' => 'reports', "middleware" => 'auth'], function () {

    Route::get('/', [
        'as' => 'view-all-complaint-reports',
        'middleware' => ['permission:view all-complaint-reports'],
        'uses' => 'ReportController@index'
    ]);

    Route::get('/search', [
        'as' => 'view-complaint-reports.store',
        'middleware' => ['auth'],
        'uses' => 'ReportController@store'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-complaint-reports.store',
        'middleware' => ['auth'],
        'uses' => 'ReportController@show'
    ]);

    Route::get('/engineers-machine', [
        'as' => 'admin.reports.engineers-machine',
        'middleware' => ['auth'],
        'uses' => 'ReportController@engineersMachine'
    ]);
    
    Route::get('requested-toolkits', [
        'as' => 'toolkit-requested',
        'middleware' => ['permission:view assign tools'],
        'uses' => 'ToolKitController@allRequestedToolkit'
    ]);

    Route::post('requested-toolkits-update/{id}', [
        'as' => 'toolkit-requested-update',
        'middleware' => ['permission:view assign tools'],
        'uses' => 'ToolKitController@updateToolkitIssued'
    ]);
}); 

Route::group(['prefix' => 'user-stockin-reports'], function () {
    Route::get('/', [
        'as' => 'view-user-stockin-reports',
        'middleware' => ['permission:view spare-part-stockin-reports'],
        'uses' => 'StockinReportController@userStockIn'
    ]);

    Route::get('/create', [
        'as' => 'view-user-stockin-reports.store',
        'middleware' => ['auth'],
        'uses' => 'StockinReportController@userStockInStore'
    ]);
});

Route::group(['prefix' => 'user-assigned-toolkit-reports'], function () {
    Route::get('/', [
        'as' => 'view-user-assigned-toolkit-reports',
        'middleware' => ['permission:view assigned-toolkit-reports'],
        'uses' => 'StockinToolkitReportController@userAssignedToolkit'
    ]);
    Route::get('/engineer-wise', [
        'as' => 'view-user-assigned-toolkit-reports.engineer-wise',
        'middleware' => ['permission:view assigned-toolkit-reports'],
        'uses' => 'StockinToolkitReportController@userAssignedToolkitEngineerWise'
    ]);

    Route::get('/create', [
        'as' => 'view-user-assigned-toolkit-reports.store',
        'middleware' => ['auth'],
        'uses' => 'StockinToolkitReportController@userAssignedToolkitStore'
    ]);

    Route::get('/total-toolkit', [
        'as' => 'total-assigned-toolkit-reports',
        'middleware' => ['permission:view total-assigned-toolkit-reports'],
        'uses' => 'StockinToolkitReportController@userAssignedToolkitTotal'
    ]);

    Route::get('/export-total', [
        'as' => 'total-assigned-toolkit.excel',
        'middleware' => ['auth'],
        'uses' => 'StockinToolkitReportController@exportTotal'
    ]);

    Route::get('/export', [
        'as' => 'user-assigned-toolkit.excel',
        'middleware' => ['auth'],
        'uses' => 'StockinToolkitReportController@export'
    ]);
});

Route::group(['prefix' => 'service-reports'], function () {
    Route::get('/', [
        'as' => 'get-all-service-report-detail',
        'middleware' => ['permission:view all-service-reports'],
        'uses' => 'ServiceReportController@index'
    ]);

    Route::get('/get-service-report-detail/{id}', [
        'as' => 'get-service-report-detail',
        'middleware' => ['auth'],
        'uses' => 'ServiceReportController@show'
    ]);

    Route::get('/service-report-print-view/{id}', [
        'as' => 'service-report-print-view-dsr',
        'middleware' => ['auth'],
        'uses' => 'ServiceReportController@printView'
    ]);

    Route::get('/export', [
        'as' => 'service-report-details.excel',
        'middleware' => ['auth'],
        'uses' => 'ServiceReportController@export'
    ]);

});

Route::group(['prefix' => 'spare-parts'], function () {

    Route::get('/', [
        'as' => 'view-all-spare-parts',
        'middleware' => ['permission:view spare-parts'],
        'uses' => 'SparePartsController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-spare-parts',
        'middleware' => ['permission:add spare-parts'],
        'uses' => 'SparePartsController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-spare-parts.post',
        'middleware' => ['auth'],
        'uses' => 'SparePartsController@store'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-spare-parts',
        'middleware' => ['permission:edit spare-parts'],
        'uses' => 'SparePartsController@edit'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-spare-parts',
        'middleware' => ['auth'],
        'uses' => 'SparePartsController@show'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-spare-parts',
        'middleware' => ['auth'],
        'uses' => 'SparePartsController@update'
    ]);

    Route::get('/export', [
        'as' => 'spare-parts-details.excel',
        'middleware' => ['auth'],
        'uses' => 'SparePartsController@export'
    ]);

    Route::get('/deactivated/{id}', [
        'as' => 'destroy-spare-parts',
        'middleware' => ['permission:delete spare-parts'],
        'uses' => 'SparePartsController@destroy'
    ]);

    
}); 


Route::group(['prefix' => 'stock-in'], function () { 
    Route::get('/', [
        'as' => 'view-all-stock-in',
        'middleware' => ['permission:view spare-part-stock-in'],
        'uses' => 'StockInController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-stock-in',
        'middleware' => ['permission:add spare-part-stock-in'],
        'uses' => 'StockInController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-stock-in.post',
        'middleware' => ['auth'],
        'uses' => 'StockInController@store'
    ]);

    // Route::post('/stock-in', [
    //     'as' => 'add-stock-in-spare-parts',
    //     'middleware' => ['auth'],
    //     'uses' => 'StockInController@addNewStockInPost'
    // ]);

    Route::get('/show/{id}', [
        'as' => 'show-stock-in',
        'middleware' => ['auth'],
        'uses' => 'StockInController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-stock-in',
        'middleware' => ['permission:edit spare-part-stock-in'],
        'uses' => 'StockInController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-stock-in',
        'middleware' => ['auth'],
        'uses' => 'StockInController@update'
    ]);

    Route::get('/deactivate/{id}', [
        'as' => 'deactivate-stock-in',
        'middleware' => ['permission:delete spare-part-stock-in'],
        'uses' => 'StockInController@destroy'
    ]);

    Route::get('/export', [
        'as' => 'stockin-details.excel',
        'middleware' => ['auth'],
        'uses' => 'StockInController@export'
    ]);

}); 

Route::group(['prefix' => 'engineer-issue-stockin'], function () {

    Route::get('/', [
        'as' => 'view-all-engineer-issue-stockin',
        'middleware' => ['permission:view issue-stockin'],
        'uses' => 'EngineerIssueStockInController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-engineer-issue-stockin',
        'middleware' => ['permission:add issue-stockin'],
        'uses' => 'EngineerIssueStockInController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-engineer-issue-stockin.post',
        'middleware' => ['auth'],
        'uses' => 'EngineerIssueStockInController@store'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-engineer-issue-stockin',
        'middleware' => ['auth'],
        'uses' => 'EngineerIssueStockInController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-engineer-issue-stockin',
        'middleware' => ['permission:edit issue-stockin'],
        'uses' => 'EngineerIssueStockInController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-engineer-issue-stockin',
        'middleware' => ['auth'],
        'uses' => 'EngineerIssueStockInController@update'
    ]);

    Route::get('/deactivate/{id}', [
        'as' => 'deactivate-engineer-issue-stockin',
        'middleware' => ['permission:delete issue-stockin'],
        'uses' => 'EngineerIssueStockInController@destroy'
    ]);

    Route::get('/export', [
        'as' => 'engineer-issue-stockin.excel',
        'middleware' => ['auth'],
        'uses' => 'EngineerIssueStockInController@export'
    ]);

});


Route::group(['prefix' => 'client-amc', "middleware" => "auth"], function () {

    Route::get('/', [
        'as' => 'view-all-client-amc',
        'middleware' => ['permission:view client-amc'],
        'uses' => 'ClientAmcController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-client-amc',
        'middleware' => ['permission:add client-amc'],
        'uses' => 'ClientAmcController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-client-amc.store',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@store'
    ]);

    Route::get('/show/{id}', [
        'as' => 'show-client-amc',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-client-amc',
        'middleware' => ['permission:edit client-amc'],
        'uses' => 'ClientAmcController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-client-amc',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@update'
    ]);

    Route::get('/export', [
        'as' => 'all-client-amc-details.excel',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@export'
    ]);

    Route::get('/deactivated/{id}', [
        'as' => 'destroy-client-amc',
        'middleware' => ['permission:delete client-amc'],
        'uses' => 'ClientAmcController@destroy'
    ]);

    Route::get('/zone-wise-client-details', [
        'as' => 'zone-wise-client-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@zoneWiseClientDetails'
    ]);

    Route::get('/client-wise-branch-details', [
        'as' => 'client-wise-branch-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@getBranchName'
    ]);

    Route::get('/assigned-product-details', [
        'as' => 'assigned-product-details.ajax',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@getAssignedProductDetails'
    ]);

    // Route::get('/client-amc-details', [
    //     'as' => 'client-amc-details.ajax',
    //     'middleware' => ['auth'],
    //     'uses' => 'ClientAmcController@getClientAmcDetails'
    // ]);

    Route::get('/raise-bill/{id}', [
        'as' => 'raise-bill-client-amc',
        'middleware' => ['permission:add raise-bill'],
        'uses' => 'ClientAmcController@raiseBill'
    ]);

    Route::patch('/raise-bill/{id}', [
        'as' => 'raise-bill-client-amc.update',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@raiseBillUpdate'
    ]);

    Route::get('/raise-bill-edit/{id}', [
        'as' => 'raise-client-amc-edit-bill',
        'middleware' => ['permission:edit raise-bill'],
        'uses' => 'ClientAmcController@raiseBillEdit'
    ]);

    Route::patch('/raise-bill-update/{id}', [
        'as' => 'raise-client-amc-update-bill',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@raiseBillEditUpdate'
    ]);

    Route::get('/raise-bill-delete/{id}', [
        'as' => 'raise-client-amc-delete-bill',
        'middleware' => ['permission:delete raise-bill'],
        'uses' => 'ClientAmcController@raiseBillDelete'
    ]);

    Route::get('/raise-bill-payment/{id}', [
        'as' => 'raise-client-amc-edit-bill-payment',
        'middleware' => ['permission:update bill-payment-details'],
        'uses' => 'ClientAmcController@raiseBillEditPayment'
    ]);

    Route::patch('/raise-bill-payment-update/{id}', [
        'as' => 'raise-client-amc-bill-payment-update',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@raiseBillEditPaymentUpdate'
    ]);

    Route::get('/raise-bill-payment-details/{id}', [
        'as' => 'raise-client-amc-bill-payment-details',
        'middleware' => ['auth'],
        'uses' => 'ClientAmcController@raiseBillPaymentDetails'
    ]);

    Route::get('/assigned-engineer/{id}', [
        'as' => 'amc-assigned-to-engineers',
        'middleware' => ['auth',"permission:assign amc to engineer"],
        'uses' => 'ClientAmcController@assignAmcToEngineer'
    ]);

    Route::patch('/assigned-engineer/{id}', [
        'as' => 'amc-assigned-to-engineers.post',
        'middleware' => ['auth', "permission:assign amc to engineer"],
        'uses' => 'ClientAmcController@assignAmcToEngineerPost'
    ]);

});


Route::group(['prefix' => 'client-outstanding-bill'], function () {

    Route::get('/', [
        'as' => 'view-all-client-outstanding-bill',
        'middleware' => ['permission:view client outstanding bill'],
        'uses' => 'OutstandingController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-client-outstanding-bill',
        'middleware' => ['permission:add client outstanding bill'],
        'uses' => 'OutstandingController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-client-outstanding-bill.store',
        'middleware' => ['auth'],
        'uses' => 'OutstandingController@store'
    ]);

    Route::get('/get-client-all-branch', [
        'as' => 'get-all-client-branch.ajax',
        'middleware' => ['auth'],
        'uses' => 'OutstandingController@getBranchName'
    ]);

    Route::get('/show/{id}', [
        'as' => 'details-client-outstanding-bill',
        'middleware' => ['auth'],
        'uses' => 'OutstandingController@show'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-client-outstanding-bill',
        'middleware' => ['permission:edit client outstanding bill'],
        'uses' => 'OutstandingController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-client-outstanding-bill',
        'middleware' => ['auth'],
        'uses' => 'OutstandingController@update'
    ]);

    Route::get('/followup/{id}', [
        'as' => 'followup-client-outstanding-bill-edit',
        'middleware' => ['auth'],
        'uses' => 'OutstandingController@getFollowupDetail'
    ]);

    Route::patch('/update-followup/{id}', [
        'as' => 'update-followup-client-outstanding-bill',
        'middleware' => ['auth'],
        'uses' => 'OutstandingController@updateFollowupDetail'
    ]);

    Route::get('/delete/{id}', [
        'as' => 'delete-client-outstanding-bill',
        'middleware' => ['permission:delete client outstanding bill'],
        'uses' => 'OutstandingController@destroy'
    ]);

    Route::get('/export', [
        'as' => 'all-client-outstanding-bill.excel',
        'middleware' => ['auth'],
        'uses' => 'OutstandingController@export'
    ]);

});

Route::group(['prefix' => 'engineer-outstanding-bill'], function () {

    Route::get('/', [
        'as' => 'engineer-view-outstanding-bill',
        'middleware' => ['permission:view outstanding-bill-reports'],
        'uses' => 'OutstandingController@engineerIndex'
    ]);

    Route::get('/details/{id}', [
        'as' => 'show-engineer-view-outstanding-bill',
        'middleware' => ['auth'],
        'uses' => 'OutstandingController@engineerShow'
    ]);

});

Route::group(['prefix' => 'engineer-track-location'], function () {

    Route::get('/', [
        'as' => 'view-engineer-track-location',
        'middleware' => ['permission:view engineer-track-location'],
        'uses' => 'LocationController@index'
    ]);

    Route::get('/track-engineer-location', [
        'as' => 'details-engineer-track-location',
        'middleware' => ['auth'],
        'uses' => 'LocationController@trackLocationDetails'
    ]);

    Route::get('/all-engineer-locations', [
        'as' => 'all-engineer-track-locations',
        'middleware' => ['auth'],
        'uses' => 'LocationController@engineerLocations'
    ]);

});

Route::group(['prefix' => 'ajax', "as" => "ajax."], function () {
    Route::get("product-list",[
        "uses"  => "ProductController@ajaxProductList",
        "as"    => "products"
    ]);
});

Route::group(['prefix' => 'districts'], function () {

    Route::get('/', [
        'as' => 'view-all-district',
        'middleware' => ['permission:view district'],
        'uses' => 'DistrictController@index'
    ]);

    Route::get('/create', [
        'as' => 'add-new-district',
        'middleware' => ['permission:add district'],
        'uses' => 'DistrictController@create'
    ]);

    Route::post('/create', [
        'as' => 'add-new-district.post',
        'middleware' => ['auth'],
        'uses' => 'DistrictController@store'
    ]);

    Route::get('/edit/{id}', [
        'as' => 'edit-district',
        'middleware' => ['permission:edit district'],
        'uses' => 'DistrictController@edit'
    ]);

    Route::patch('/update/{id}', [
        'as' => 'update-district',
        'middleware' => ['auth'],
        'uses' => 'DistrictController@update'
    ]);
 

});  

// ########################### Test data ############################
// Route::get('/test-table-data', [
// 	    'as' => 'get-table-data.post',
// 	    'uses' => 'UserController@getTableData'
// 	]);

// Route::get('/details-test-table-data', [
// 	    'as' => 'add-data.ajax.post',
// 	    'uses' => 'UserController@getTableDataDetails'
// 	]);
// ########################## end of test data #############################
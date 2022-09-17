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

use App\Http\Controllers\admin\AjaxController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::any('/dashboard', 'HomeController@index')->name('dashboard');
//Route::any('/admin', 'HomeController@index')->name('dashboard');

///////// Lead Section
Route::get('admin/addlead', 'admin\LeadController@addlead')->name('addlead');
Route::post('admin/savelead', 'admin\LeadController@savelead')->name('savelead');
Route::get('admin/leadmanagement', 'admin\LeadController@leadmanagement')->name('leadmanagement');
Route::post('admin/leadmanagement', 'admin\LeadController@leadmanagement')->name('leadmanagement');
Route::get('admin/sendemail', 'admin\LeadController@sendemail')->name('sendemail');
Route::get('admin/editlead/{id}', 'admin\LeadController@editlead')->name('editlead');
Route::post('admin/updatelead', 'admin\LeadController@updatelead')->name('updatelead');
Route::get('admin/leadtatus/{id}', 'admin\LeadController@leadtatus')->name('leadtatus');

///////// Activity Section
Route::get('admin/addactivity', 'admin\ActivityController@addactivity')->name('addactivity');
Route::post('admin/saveactivity', 'admin\ActivityController@savelead')->name('saveactivity');
Route::get('admin/activitymanagement', 'admin\ActivityController@activitymanagement')->name('activitymanagement');
Route::post('admin/activitymanagement', 'admin\ActivityController@activitymanagement')->name('activitymanagement');
Route::get('admin/editactivity/{id}', 'admin\ActivityController@editactivity')->name('editactivity');
Route::post('admin/updateactivity', 'admin\ActivityController@updateactivity')->name('updateactivity');
Route::get('admin/activitytatus/{id}', 'admin\ActivityController@activitytatus')->name('activitytatus');
Route::any('admin/history/{id}', 'admin\ActivityController@history')->name('activity-history');
Route::post('admin/savemanageactivity', 'admin\ActivityController@savemanageactivity')->name('savemanageactivity');
Route::get('admin/activityleadtatus/{id}', 'admin\ActivityController@activityleadtatus')->name('activityleadtatus');

///////// Master: Department Section
Route::get('admin/department', 'admin\MasterController@Department')->name('Department');
Route::post('admin/savedepartment', 'admin\MasterController@savedepartment')->name('savedepartment');
Route::get('admin/editdepartment/{id}', 'admin\MasterController@editdepartment')->name('editdepartment');
Route::post('admin/updatedepartment', 'admin\MasterController@updatedepartment')->name('updatedepartment');

///////// Master: Designation Section
Route::post('admin/savedesignation', 'admin\MasterController@savedesignation')->name('savedesignation');
Route::get('admin/editdesignation/{id}', 'admin\MasterController@editdesignation')->name('editdesignation');
Route::post('admin/updatedesignation', 'admin\MasterController@updatedesignation')->name('updatedesignation');

///////// Master: Region Section
Route::post('admin/saveregion', 'admin\MasterController@saveregion')->name('saveregion');
Route::get('admin/region', 'admin\MasterController@Region')->name('Region');
Route::get('admin/editregion/{id}', 'admin\MasterController@editregion')->name('editregion');
Route::post('admin/updateregion', 'admin\MasterController@updateregion')->name('updateregion');

///////// Master: Circle Section
Route::post('admin/savecircle', 'admin\MasterController@savecircle')->name('savecircle');
Route::get('admin/editcircle/{id}', 'admin\MasterController@editcircle')->name('editcircle');
Route::post('admin/updatecircle', 'admin\MasterController@updatecircle')->name('updatecircle');

///////// Master: Employees Section
Route::get('admin/empolyees', 'admin\UserController@empolyees')->name('empolyees');
Route::post('admin/userregistration', 'admin\UserController@userregistration')->name('userregistration');
Route::get('admin/edituser/{id}', 'admin\UserController@edituser')->name('edituser');
Route::post('admin/updateprofile', 'admin\UserController@updateprofile')->name('updateprofile');
Route::get('admin/changepassword', 'admin\UserController@changepassword')->name('changepassword');
Route::post('admin/updatepassword', 'admin\UserController@updatepassword')->name('updatepassword');

////////////// Product Section Data
Route::get('admin/product', 'admin\MasterController@Product')->name('Product');
Route::post('admin/saveproduct', 'admin\MasterController@saveproduct')->name('saveproduct');
Route::get('admin/editproduct/{id}', 'admin\MasterController@editproduct')->name('editproduct');
Route::post('admin/updateproduct', 'admin\MasterController@updateproduct')->name('updateproduct');
Route::post('admin/savesubproduct', 'admin\MasterController@savesubproduct')->name('savesubproduct');
Route::get('admin/editsubproduct/{id}', 'admin\MasterController@editsubproduct')->name('editsubproduct');
Route::post('admin/updatesubproduct', 'admin\MasterController@updatesubproduct')->name('updatesubproduct');

///////// Master: Source Type Section
Route::get('admin/source', 'admin\MasterController@Source')->name('Source');
Route::post('admin/savesourcetype', 'admin\MasterController@savesourcetype')->name('savesourcetype');
Route::get('admin/editsourcetype/{id}', 'admin\MasterController@editsourcetype')->name('editsourcetype');
Route::post('admin/updatesourcetype', 'admin\MasterController@updatesourcetype')->name('updatesourcetype');

///////// Master: Source Value Section
Route::post('admin/savesourcevalue', 'admin\MasterController@savesourcevalue')->name('savesourcevalue');
Route::get('admin/editsourcevalue/{id}', 'admin\MasterController@editsourcevalue')->name('editsourcevalue');
Route::post('admin/updatesourcevalue', 'admin\MasterController@updatesourcevalue')->name('updatesourcevalue');

///////// Master: Accounts Section
Route::get('admin/accountind', 'admin\MasterController@Accountind')->name('Accountind');
Route::post('admin/saveaccount', 'admin\MasterController@saveaccount')->name('saveaccount');
Route::get('admin/editaccount/{id}', 'admin\MasterController@editaccount')->name('editaccount');
Route::post('admin/updateaccount', 'admin\MasterController@updateaccount')->name('updateaccount');

///////// Master: Industry Section
Route::post('admin/saveindustry', 'admin\MasterController@saveindustry')->name('saveindustry');
Route::get('admin/editindustry/{id}', 'admin\MasterController@editindustry')->name('editindustry');
Route::post('admin/updateindustry', 'admin\MasterController@updateindustry')->name('updateindustry');

///////// Opportunities Section
Route::post('admin/saveOpportunity', 'admin\OpportunityController@saveOpportunity')->name('saveOpportinity');
Route::any('admin/opportunities', 'admin\OpportunityController@index')->name('opportunities');
Route::post('admin/updateOpportunity', 'admin\OpportunityController@updateOpportunity')->name('updateOpportinity');
Route::post('admin/deleteOpportunity', 'admin\OpportunityController@deleteOpportunity')->name('deleteOpportinity');
Route::any('admin/editOpportunity', 'admin\OpportunityController@editOpportunity')->name('editOpportunity');

///////// Miscllaneous Section
Route::get('check-mobile-number', 'admin\AjaxController@checkMobileNumber')->name('checkMobileNumber');
Route::get('get-source-value', 'admin\AjaxController@getSourceValue')->name('getSourceValue');
Route::get('get-users', 'admin\AjaxController@get_zone_users')->name('getusers');
Route::post('checkAddLeadData', [AjaxController::class, 'checkAddLeadData'])->name('checkAddLeadData');
Route::post('checkEditLeadData', [AjaxController::class, 'checkEditLeadData'])->name('checkEditLeadData');
///////// Reports Section
Route::get('wcr-reports', 'ReportController@wcrReport')->name('wcr-report');
Route::get('wap-reports', 'ReportController@wapReport')->name('wap-report');
Route::get('funnel-reports', 'ReportController@funnelReport')->name('funnel-report');

Route::get('export-reports', 'ReportController@exportReport')->name('export-report');

Route::get('admin/duplicate/{id}', 'admin\LeadController@duplicatelead')->name('duplicate');
Route::any('admin/updateduplicate', 'admin\LeadController@updateduplicate')->name('updateduplicate');

//////////// Define Url Setting
if(isset($_SERVER['HTTP_HOST'])) {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define('SITEURL','http://localhost/leadcrm/');
    }
    else if ($_SERVER['HTTP_HOST'] == 'crm.uat.smartping.in') {
        define('SITEURL','http://crm.uat.smartping.in/');
    }
    else {
        define('SITEURL','http://crm.smartping.in/');
    }
}


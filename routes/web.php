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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::pattern('id', '[0-9]+');
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/', function () {
    return redirect(route('login'));
});

Route::post('users/check_device', 'UserController@check_device')->name('users.check_device');


//route for show mac
Route::get('getmac', function () {
    return 'your mac is:'.substr(exec('getmac'), 0, 17).'</br> and your ip is:'.\Illuminate\Support\Facades\Request::ip();
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
//route for activity
Route::post('activity', 'ActivityController@store')->name('activity.store');
Route::group(['middleware' => ['auth','checkPower:allow_manage_activities']], function () {
    Route::post('activity/search', 'ActivityController@search')->name('activity.search');
    Route::delete('activity/truncate', 'ActivityController@truncate')->name('activity.truncate');
    Route::resource('activity', 'ActivityController')->only(['index', 'destroy']);
});

//route for user
Route::group(['middleware' => ['auth']], function () {
    Route::get('users/report', 'UserController@report')->name('users.report')->middleware('checkPower:allow_access_total_report');
    Route::post('users/uploadImg', 'UserController@uploadImg')->name('users.uploadImg')->middleware('checkPower:allow_change_background');
    Route::post('users/editBg', 'UserController@editBg')->name('users.editBg')->middleware('checkPower:allow_change_background');
    Route::post('users/getData', 'UserController@getData')->name('users.getData');
    Route::post('users/getReport', 'UserController@getReport')->name('users.getReport')->middleware('checkPower:allow_access_total_report');;
    Route::post('users/changeState/{id}', 'UserController@changeState')->name('users.changeState')->middleware('checkPower:1');
    Route::resource('users', 'UserController')->only(['create','index', 'store','destroy','show'])->middleware('checkPower:1');
    Route::resource('users', 'UserController')->only(['edit', 'update'])->middleware('checkPower:allow_edit_my_account');
});

//route for backup
Route::get('restore', function () {
    return view('backups.restore');
});
Route::post('backups/restore', 'BackupController@restore')->name('backups.restore');
Route::group(['middleware' => ['auth']], function () {
    Route::resource('backups', 'BackupController')->only(['index', 'store'])->middleware('checkPower:allow_mange_backup');
    Route::get('backups/createBackup/{type?}/{id?}', 'BackupController@createBackup')->name('backups.createBackup');
    Route::get('backups/downloadBackup', 'BackupController@downloadBackup')->name('backups.downloadBackup')->middleware('checkPower:allow_download_backup');
});

//route for setting
Route::group(['middleware' => ['auth']], function () {
    Route::resource('settings', 'SettingController')->only(['index', 'update'])->middleware('checkPower:allow_mange_setting');
});

//route for barcode
Route::group(['middleware' => ['auth']], function () {
    Route::resource('barcodes', 'BarcodeController')->only(['index', 'update'])->middleware('checkPower:allow_mange_barcode,use_barcode');
//    Route::get('barcodes/print/{{barcod}}', 'BarcodeController@print')/*->middleware('checkPower:allow_mange_barcode,use_barcode')*/;
});

//route for device
Route::group(['middleware' => ['auth']], function () {
    Route::resource('devices', 'DeviceController')->only(['index', 'update'])->middleware('checkPower:allow_mange_device');
    Route::post('devices/{id}', 'DeviceController@changeState')->name('devices.changeState')->middleware('checkPower:allow_mange_device');
    Route::post('devices/changeDefaultBillPrint/{id}', 'DeviceController@changeDefaultBillPrint')->name('devices.changeDefaultBillPrint')->middleware('checkPower:allow_mange_device');
});

//route for stokes
Route::group(['middleware' => ['auth']], function () {
    Route::resource('stokes', 'StokeController')->only(['index', 'store', 'destroy', 'update'])->middleware('checkPower:allow_mange_stoke,mange_stoke');
    Route::post('stokes/getData', 'StokeController@getData')->name('stokes.getDate');
    Route::post('stokes/changeState/{id}', 'StokeController@changeState')->name('stokes.changeState')->middleware('checkPower:allow_mange_stoke');
});

//route for stoke_product_places
Route::group(['middleware' => ['auth']], function () {
    Route::resource('stoke_product_places', 'StokePlaceNameController')->only(['index', 'store', 'destroy', 'update'])->middleware('checkPower:allow_mange_place_in_stoke,place_product');
    Route::post('stoke_product_places/getData', 'StokePlaceNameController@getData')->name('stoke_product_places.getDate');
    Route::post('stoke_product_places/changeState/{id}', 'StokePlaceNameController@changeState')->name('stoke_product_places.changeState')->middleware('checkPower:allow_mange_place_in_stoke');

    //route for product place
    Route::get('stoke_product_places/showProductPlace', 'StokePlaceNameController@showProductPlace')->name('stoke_product_places.showProductPlace')->middleware('checkPower:allow_mange_product_place_in_stoke,place_product');
    Route::post('stoke_product_places/updateProductPlace', 'StokePlaceNameController@updateProductPlace')->name('stoke_product_places.updateProductPlace')->middleware('checkPower:allow_mange_product_place_in_stoke');

});

//route for product_categories
Route::group(['middleware' => ['auth']], function () {
    Route::resource('products_categories', 'ProductCategoryController')->only(['index', 'store', 'destroy', 'update'])->middleware('checkPower:allow_mange_product_category');
    Route::post('products_categories/getData', 'ProductCategoryController@getData')->name('products_categories.getDate');
    Route::post('products_categories/changeState/{id}', 'ProductCategoryController@changeState')->name('products_categories.changeState');
});

//route for product_units
Route::group(['middleware' => ['auth']], function () {
    Route::resource('products_units', 'ProductUnitController')->only(['index', 'store', 'destroy', 'update'])->middleware('checkPower:allow_mange_product_unit');
    Route::post('products_units/getData', 'ProductUnitController@getData')->name('products_units.getDate');
    Route::post('products_units/changeState/{id}', 'ProductUnitController@changeState')->name('products_units.changeState');
});

//route for stores
Route::group(['middleware' => ['auth']], function () {
    Route::resource('stores', 'StoreController')->only(['index'])->middleware('checkPower:allow_access_product_in_stoke');
    Route::resource('stores', 'StoreController')->only(['edit','update'])->middleware('checkPower:allow_move_product_in_stoke');
    Route::post('stores/getData', 'StoreController@getData')->name('stores.getDate');
    Route::post('stores/addDamage', 'StoreController@addDamage')->name('stores.addDamage')->middleware('checkPower:allow_add_damage');
    //for delete damage
    Route::resource('stores', 'StoreController')->only(['destroy'])->middleware('checkPower:allow_delete_damage');
});

//route for accounts
Route::group(['middleware' => ['auth']], function () {
    Route::get('accounts/account_bill_with_details','AccountController@account_bill_with_details')->name('accounts.account_bill_with_details');

    Route::resource('accounts', 'AccountController');//other middleware in controller
    Route::post('accounts/getData', 'AccountController@getData')->name('accounts.getDate');
    Route::get('accounts/adjust_account/{id}','AccountController@get_adjust_account');
    Route::post('accounts/adjust_account/{id}','AccountController@post_adjust_account')->name('accounts.adjust_account');
    Route::get('accounts/add_or_subtract_debt/{id}/{type}','AccountController@get_add_or_subtract_debt');//type 1 for add number to account, 2 for subtract number from account
    Route::post('accounts/add_or_subtract_debt/{id}/{type}','AccountController@post_add_or_subtract_debt')->name('accounts.post_add_or_subtract_debt');
});
//route for account calculation
Route::group(['middleware' => ['auth']], function () {
    Route::resource('account_calculation', 'AccountCalculationController');
    Route::post('account_calculation/getData', 'AccountCalculationController@getData')->name('account_calculation.getDate');
});


//route for products
Route::group(['middleware' => ['auth']], function () {
    Route::resource('products', 'ProductController');
    Route::post('products/getData', 'ProductController@getData')->name('products.getDate');
    Route::post('products/changeState/{id}', 'productController@changeState')->name('products.changeState');
});

//route for making
Route::group(['middleware' => ['auth']], function () {
    Route::resource('makings', 'MakeController')->except(['show','edit','update']);//middleware in controller and not complete
    Route::post('makings/getData', 'MakeController@getData')->name('makings.getDate');
});

//route for bills
Route::group(['middleware' => ['auth']], function () {
    Route::get('bills/print/{id?}', 'BillController@print')->name('bills.print');
    Route::resource('bills', 'BillController')->except(['create','index']);
    Route::get('bills/create/{type}', 'BillController@create')->name('bills.create')->where('type', '^[0-2]$');
    Route::get('bills/index/{type}', 'BillController@index')->name('bills.index')->where('type', '^[0-2]$');
    Route::post('bills/getData', 'BillController@getData')->name('bills.getDate');

    //bill back
    Route::get('bills/back/{id}/{type}','BillController@create_bill_back')->name('bills.create_bill_back');
    Route::post('bills/back/{id}','BillController@store_bill_back')->name('bills.store_bill_back');
});

//route for bill print design
Route::group(['middleware' => ['auth']], function () {
    Route::resource('bill_prints', 'BillPrintController')->only(['index','update'])->middleware('checkPower:allow_mange_print_setting,bill_design');
    Route::post('bill_prints/getData', 'BillPrintController@getData')->name('bill_prints.getDate');
});

//route for bill_messages
Route::group(['middleware' => ['auth']], function () {
    Route::resource('bill_messages', 'BillMessageController')->only(['index', 'store', 'destroy', 'update'])->middleware('checkPower:allow_mange_bill_message');
    Route::post('bill_messages/getData', 'BillMessageController@getData')->name('bill_messages.getDate');
    Route::post('bill_messages/changeState/{id}', 'BillMessageController@changeState')->name('bill_messages.changeState')->middleware('checkPower:allow_mange_bill_message');
    Route::post('bill_messages/setDefault/{id}', 'BillMessageController@setDefault')->name('bill_messages.setDefault')->middleware('checkPower:allow_mange_bill_message');
});


//route for product_moves
Route::group(['middleware' => ['auth']], function () {
    Route::get('product_moves/account_product_move', 'ProductMoveController@account_product_move')->name('product_moves.account_product_move')->middleware('checkPower:allow_access_report_account,account_product_move');
    Route::get('product_moves/show_profit', 'ProductMoveController@show_profit')->name('product_moves.show_profit')->middleware('checkPower:allow_access_product_profit');
    Route::resource('product_moves', 'ProductMoveController')->only(['index'])->middleware('checkPower:allow_access_product_move');
    Route::post('product_moves/getData', 'ProductMoveController@getData')->name('product_moves.getDate')/*->middleware('checkPower:allow_access_product_move')*/;
});

//route for treasury
Route::group(['middleware' => ['auth']], function () {
    Route::resource('treasuries', 'TreasuryController')->only(['index','destroy']);
    Route::get('treasuries/add_or_take_money','TreasuryController@get_add_or_take_money')->name('treasuries.get_add_or_take_money');
    Route::post('treasuries/add_or_take_money/{type}','TreasuryController@post_add_or_take_money')->name('treasuries.post_add_or_take_money');//type 0 for add money ,1 for take money
    Route::post('treasuries/getData', 'TreasuryController@getData')->name('treasuries.getDate');
});

//route for expenses
Route::group(['middleware' => ['auth']], function () {
    Route::resource('expenses_types', 'ExpensesTypeController')->only(['index', 'store', 'destroy', 'update'])->middleware('checkPower:allow_mange_expenses,use_expenses');
    Route::post('expenses_types/getData', 'ExpensesTypeController@getData')->name('expenses_types.getDate');
    Route::post('expenses_types/changeState/{id}', 'ExpensesTypeController@changeState')->name('expenses_types.changeState')->middleware('checkPower:allow_mange_expenses');
    Route::resource('expenses', 'ExpenseController')->only(['index','create', 'store', 'destroy']);//middleware in controller
    Route::post('expenses/getData', 'ExpenseController@getData')->name('expenses.getDate');//used in expenses.index
});

//route for emp jops
Route::group(['middleware' => ['auth']], function () {
    Route::resource('emp_jops', 'EmpJopController')->only(['index', 'store', 'destroy', 'update'])->middleware('checkPower:allow_manage_emp_jops');
    Route::post('emp_jops/getData', 'EmpJopController@getData')->name('emp_jops.getDate');
    Route::post('emp_jops/changeState/{id}', 'EmpJopController@changeState')->name('emp_jops.changeState')->middleware('checkPower:allow_manage_emp_jops');
});

//route for emp
Route::group(['middleware' => ['auth']], function () {
    Route::get('emps/report', 'EmpController@report')->name('emps.report')->middleware('checkPower:allow_manage_emp_move');
    Route::get('emps/report2', 'EmpController@report2')->name('emps.report2')->middleware('checkPower:allow_manage_emp_move');
    Route::resource('emps', 'EmpController')->except(['show']);
    Route::post('emps/getData', 'EmpController@getData')->name('emps.getDate');
    Route::post('emps/changeState/{id}', 'EmpController@changeState')->name('emps.changeState');
    Route::get('emps/create_operation/{emp_id}/{type}', 'EmpController@create_operation')->name('emps.create_operation')->where('type','[0-3]')->middleware('checkPower:allow_manage_emp_operation');
    Route::post('emps/post_operation/{emp_id}', 'EmpController@post_operation')->name('emps.post_operation')->middleware('checkPower:allow_manage_emp_operation');
    Route::post('emps/getData', 'EmpController@getData')->name('emps.getData');

    Route::get('emps/attend/show_emp_attend', 'EmpController@show_emp_attend')->name('emps.show_emp_attend')->middleware('checkPower:allow_manage_emp_attend');
    Route::post('emps/attend/change_emp_attend', 'EmpController@change_emp_attend')->name('emps.change_emp_attend');

});

//route for exist deal
Route::group(['middleware' => ['auth']], function () {
    Route::resource('exist_deals', 'ExistDealController');//middleware in controller
    Route::post('exist_deals/getData', 'ExistDealController@getData')->name('exist_deals.getDate');
});

//route for visit
Route::group(['middleware' => ['auth']], function () {
    Route::resource('visits', 'VisitController');//middleware in controller
    Route::post('visits/getData', 'VisitController@getData')->name('visits.getDate');
});


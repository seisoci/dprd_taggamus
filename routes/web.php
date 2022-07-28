<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend as Backend;
use App\Http\Controllers\Default as DefaultBackend;
use Illuminate\Support\Facades\Route;

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


Route::get('/', function () {
  return redirect()->route('backend.login');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::prefix('backend')->name('backend.')->group(function () {
  Route::get('/', [LoginController::class, 'showLoginForm']);
  Route::post('/', [LoginController::class, 'login'])->name('login');
  Route::post('resetpassword', [DefaultBackend\UserController::class, 'resetpassword'])->name('users.resetpassword');
  Route::post('changepassword', [DefaultBackend\UserController::class, 'changepassword'])->name('changepassword');
});

Route::prefix('backend')->name('backend.')->middleware(['auth:web'])->group(function () {
  Route::get('dashboard', DefaultBackend\DashboardController::class)->name('dashboard.index');
  Route::resource('guestbooks', Backend\GuestBookController::class);
  Route::put('sliders/updateimage', [Backend\SliderController::class, 'updateimage'])->name('sliders.update-image');
  Route::resource('sliders', Backend\SliderController::class);
  Route::post('news/uploadimagecke', [Backend\NewsController::class, 'uploadimagecke'])->name('news.uploadimagecke');
  Route::resource('news', Backend\NewsController::class);
  Route::get('post-categories/select2', [Backend\PostCategoryController::class, 'select2'])->name('post-categories.select2');
  Route::resource('post-categories', Backend\PostCategoryController::class);
  Route::resource('pages', Backend\PageController::class);
  Route::resource('videos', Backend\VideoController::class);
  Route::resource('galleries', Backend\GalleryController::class);
  Route::post('galleries/{gallery}/photos/updateimage', [Backend\PhotoController::class, 'updateimage'])->name('photos.updateimage');
  Route::resource('galleries.photos', Backend\PhotoController::class);



  Route::get('dashboard', DefaultBackend\DashboardController::class)->name('dashboard.index');
  Route::get('roles/select2', [DefaultBackend\RoleController::class, 'select2'])->name('roles.select2');
  Route::resource('users', DefaultBackend\UserController::class);
  Route::resource('roles', DefaultBackend\RoleController::class);
  Route::resource('permissions', DefaultBackend\PermissionController::class)->except('create', 'edit', 'show');
  Route::get('menupermissions/select2', [DefaultBackend\MenuPermissionController::class, 'select2'])->name('menupermissions.select2');
  Route::resource('menupermissions', DefaultBackend\MenuPermissionController::class)->except('create', 'edit', 'show');
  Route::post('menu/changeHierarchy', [DefaultBackend\MenuManagerController::class, 'changeHierarchy'])->name('menu.changeHierarchy');
  Route::resource('menu', DefaultBackend\MenuManagerController::class)->except('create', 'show');
  Route::resource('settings', DefaultBackend\SettingController::class)->only('index', 'store');
  Route::get('banks/select2', [Backend\BankController::class, 'select2'])->name('banks.select2');
  Route::apiResource('banks', Backend\BankController::class);
  Route::apiResource('signature', Backend\SignatureController::class);
  Route::apiResource('uploadfile_jalin_rekapdebit', Backend\UploadRekapJalinController::class);
  Route::apiResource('uploadfile_jalin_clearing', Backend\UploadClearingJalinController::class);
  Route::apiResource('uploadfile_jalin_klaim', Backend\UploadKlaimJalinController::class);
  Route::apiResource('uploadfile_jalin_harian', Backend\UploadHarianJalinController::class);
  Route::apiResource('jalin_klaim', Backend\JalinKlaimController::class);
  Route::apiResource('jalin_clearing', Backend\JalinClearingController::class);
  Route::apiResource('jalin_harian', Backend\JalinHarianController::class);

});




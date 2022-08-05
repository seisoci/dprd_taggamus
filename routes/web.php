<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend as Backend;
use App\Http\Controllers\Frontend as Frontend;
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
  Route::get('dashboard', [DefaultBackend\DashboardController::class, 'index'])->name('dashboard.index');
  Route::get('dashboard/detail', [DefaultBackend\DashboardController::class, 'show'])->name('dashboard.show');
  Route::resource('guestbooks', Backend\GuestBookController::class);
  Route::put('sliders/updateimage', [Backend\SliderController::class, 'updateimage'])->name('sliders.update-image');
  Route::resource('sliders', Backend\SliderController::class);
  Route::post('news/uploadimagecke', [Backend\NewsController::class, 'uploadimagecke'])->name('news.uploadimagecke');
  Route::resource('news', Backend\NewsController::class);
  Route::resource('jdih', Backend\JDIHController::class);
  Route::get('post-categories/select2', [Backend\PostCategoryController::class, 'select2'])->name('post-categories.select2');
  Route::resource('post-categories', Backend\PostCategoryController::class);
  Route::resource('pages', Backend\PageController::class);
  Route::resource('videos', Backend\VideoController::class);
  Route::resource('schedules', Backend\ScheduleController::class);
  Route::resource('galleries', Backend\GalleryController::class);
  Route::resource('partai-member', Backend\PartaiMemberController::class);
  Route::get('election-regions/select2', [Backend\ElectionRegionController::class, 'select2'])->name('election-regions.select2');
  Route::resource('election-regions', Backend\ElectionRegionController::class);
  Route::get('komisi/select2', [Backend\KomisiController::class, 'select2'])->name('komisi.select2');
  Route::resource('komisi', Backend\KomisiController::class);
  Route::post('galleries/{gallery}/photos/updateimage', [Backend\PhotoController::class, 'updateimage'])->name('photos.updateimage');
  Route::resource('galleries.photos', Backend\PhotoController::class);
  Route::resource('partai-member.educations', Backend\EducationController::class);
  Route::resource('partai-member.professions', Backend\ProfessionController::class);
  Route::resource('partai-member.organizations', Backend\OrganizationController::class);
  Route::resource('partai-member.movements', Backend\MovementController::class);
  Route::resource('partai-member.awards', Backend\AwardController::class);
  Route::get('roles/select2', [DefaultBackend\RoleController::class, 'select2'])->name('roles.select2');
  Route::resource('users', DefaultBackend\UserController::class);
  Route::resource('roles', DefaultBackend\RoleController::class);
  Route::resource('permissions', DefaultBackend\PermissionController::class)->except('create', 'edit', 'show');
  Route::get('menupermissions/select2', [DefaultBackend\MenuPermissionController::class, 'select2'])->name('menupermissions.select2');
  Route::resource('menupermissions', DefaultBackend\MenuPermissionController::class)->except('create', 'edit', 'show');
  Route::post('menu/changeHierarchy', [DefaultBackend\MenuManagerController::class, 'changeHierarchy'])->name('menu.changeHierarchy');
  Route::resource('menu', DefaultBackend\MenuManagerController::class)->except('create', 'show');
  Route::get('banks/select2', [Backend\BankController::class, 'select2'])->name('banks.select2');
  Route::apiResource('banks', Backend\BankController::class);
  Route::apiResource('signature', Backend\SignatureController::class);
  Route::resource('pollings', Backend\PollingController::class);
  Route::resource('settings', Backend\SettingController::class);
});

Route::get('berita/{slug}', [Frontend\NewsController::class, 'show'])->name('pages.show');


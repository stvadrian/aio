<?php

use App\Http\Controllers\AJAXController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Models\Departemen;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/error/{error_code}', [UserController::class, 'viewError']);
Route::any('/qr-generator/view/{qr_name}', [UserController::class, 'viewGeneratedQR']);

Route::get('/forms/view/{link_form}', [FormController::class, 'viewForm']);
Route::post('/forms/view/{link_form}/post', [FormController::class, 'postForm']);
Route::get('/forms/view/{link_form}/success', [FormController::class, 'showSuccessPage']);

Route::middleware(['guest'])->group(function () {
    Route::get('/', [AuthController::class, 'viewLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginAttempt'])->middleware('throttle:login');

    Route::get('/reset-password', [AuthController::class, 'viewResetPassword'])->name('password.request');
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::get('/recover-password/{pass_token}', [AuthController::class, 'viewRecoverPassword']);
    Route::post('/recover-password/{pass_token}', [AuthController::class, 'recoverPassword']);
});

Route::any('/profile/change-password', [AuthController::class, 'viewChangePassword'])->middleware(['auth']);

if (Schema::connection('sqlite')->hasTable('menu_master_header') && Schema::connection('sqlite')->hasTable('menu_master_item')) {

    Route::middleware(['auth', 'checkPasswordChange'])->group(function () {

        //////////////////  DYNAMIC LINK SECTION  //////////////////////////////
        $menuItems = MenuItem::where('menu_item_status', '1')
            ->where('master_header', '!=', '1')
            ->where('modul_departemen', '!=', 'umum')
            ->whereHas('menuHeader', function ($query) {
                $query->where('menu_header_status', '1');
            })
            ->get();
        $departemenItems = Departemen::all();
        foreach ($menuItems as $menuItem) {
            $modul = $menuItem->modul_departemen;
            $matchingDepartemen = $departemenItems->first(function ($departemen) use ($modul) {
                return $departemen->modul == $modul;
            });
            $menuItem->departemen = $matchingDepartemen;

            Route::middleware(["user-access:$menuItem->modul_departemen///$menuItem->hak_akses"])->group(function () use ($menuItem) {
                $controller = 'App\Http\Controllers' . DIRECTORY_SEPARATOR . $menuItem->departemen->controller;
                Route::any("$menuItem->menu_item_link", ["$controller", "$menuItem->menu_function"]);
            });
        }

        ////////////////// PUBLIC SECTION ////////////////////
        Route::controller(UserController::class)->group(function () {
            $items = MenuItem::where('menu_item_status', '1')->where('master_header', '1')->orWhere('modul_departemen', 'umum')->get();
            foreach ($items as $item) {
                Route::any("$item->menu_item_link", "$item->menu_function");
            }
            Route::any('/profile', 'viewProfile');
        });

        Route::controller(MessageController::class)->group(function () {
            Route::any('/message', 'viewInboxMessage');
            Route::any('/message/sent', 'viewSentMessage');
            Route::any('/message/starred', 'viewStarredMessage');
            Route::any('/message/{token}', 'viewDetailMessage');
        });

        Route::controller(FormController::class)->group(function () {
            Route::any('/forms', 'viewForms');
            Route::any('/forms/edit/{form_name}', 'editForm');
            Route::any('/forms/edit/{form_name}/{item_name}/options', 'showOptions');
            Route::get('/forms/view-qr/{form_name}', 'viewQRCodeForm');
            Route::any('/forms/list-data/{form_name}', 'viewDataForm');
            Route::any('/forms/list-data/{form_name}/{postid}', 'viewForwardPage');
        });

        Route::controller(ForumController::class)->group(function () {
            Route::any('/forum', 'viewForumCategory');
            Route::any('/forum/{thread_category}', 'viewThreadList');
            Route::any('/forum/{thread_category}/{thread}', 'viewThreadDetail');
        });



        ////////////////// FEATURED SECTION ///////////////////////
        Route::post('/afk', [AuthController::class, 'AFKManager']);

        Route::get('lang/{lang}', [UserController::class, 'switchLang'])->name('lang.switch');

        Route::get('/user/stop-impersonate', [UserController::class, 'stopImpersonating'])->middleware(['impersonate']);

        Route::post('/export/excel', [UserController::class, 'exportToExcel']);

        Route::any('/qr-generator', [UserController::class, 'viewQRGenerator']);



        ////////////////// AJAX SECTION ///////////////////////
        Route::post('/ajax/sendLiveChat', [AJAXController::class, 'sendLiveChat'])->name('ajax.sendLiveChat');
        Route::any('/ajax/liveChatPoll', [AJAXController::class, 'poll']);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
}


Route::get('/toggle-darkmode', [UserController::class, 'toggleDarkmode']);

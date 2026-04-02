<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DomainManagementController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\InstagramController;

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

Auth::routes();

Route::get('/',  [HomeController::class, 'index'])->name('home-login');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home',  [HomeController::class, 'index'])->name('home');
    
    //Google Auth
    /** Step 1 */
    Route::get('/gauth/{id}',  [HomeController::class, 'gauth'])->name('gauth-id');
    /** Step 2 */
    Route::get('/gauth',  [HomeController::class, 'gauth'])->name('gauth');
    
    //Clients
    Route::get('/clients',  [DomainManagementController::class, 'index'])->name('clients');
    Route::get('/add-client',  [DomainManagementController::class, 'create'])->name('add-client');
    Route::post('/add-client',  [DomainManagementController::class, 'store'])->name('store-client');
    Route::get('/view-client/{id}',  [DomainManagementController::class, 'show'])->name('view-client');

    //Tags
    Route::get('/tags',  [TagsController::class, 'index'])->name('tags');
    Route::get('/add-tag',  [TagsController::class, 'create'])->name('add-tag');
    Route::post('/add-tag',  [TagsController::class, 'store'])->name('store-tag');

    //Groups
    Route::get('/groups',  [GroupsController::class, 'index'])->name('groups');
    Route::get('/add-group',  [GroupsController::class, 'create'])->name('add-group');
    Route::post('/add-group',  [GroupsController::class, 'store'])->name('store-group');

    //Client Analysis
    Route::get('/present-client/lms/{id}/{url}',  [AnalysisController::class, 'get_lms_data_step_1'])->name('present-client-lms-step-1');
    Route::post('/present-client/lms/{id}/{url}',  [AnalysisController::class, 'get_lms_data_step_2'])->name('present-client-lms-step-2');
    Route::get('/present-client/website/{id}/{url}',  [AnalysisController::class, 'get_website_data_step_1'])->name('present-client-website-step-3');
    Route::get('/present-client/youtube/{id}',  [AnalysisController::class, 'get_youtube_data_overall'])->name('present-client-youtube-overall-1');
    Route::get('/present-client/assign-group/{id}',  [AnalysisController::class, 'get_youtube_assign_group'])->name('assign-group');
    Route::post('/present-client/youtube/{id}',  [AnalysisController::class, 'get_youtube_data_overall'])->name('present-client-youtube-overall-2');
    Route::get('/youtube-details/{id}/{filter}',  [AnalysisController::class, 'get_youtube_detail_data'])->name('present-client-youtube-details-1');
    Route::post('/youtube-details/{id}/{filter}',  [AnalysisController::class, 'get_youtube_detail_data'])->name('present-client-youtube-details-2');
    Route::get('/video-details/{id}/{videoid}/{filter}',  [AnalysisController::class, 'get_youtube_video_data'])->name('present-client-youtube-video-data-1');
    Route::post('/video-details/{id}/{videoid}/{filter}',  [AnalysisController::class, 'get_youtube_video_data'])->name('present-client-youtube-video-data-2');

    //YT Route
    Route::post('/addtagname',  [CustomController::class, 'addtagnameFunc'])->name('addtagname');
    Route::post('/gettagdetails',  [AnalysisController::class, 'gettagdetailsFunc'])->name('gettagdetails');
    Route::post('/all_yt_channel_ajax',  [AnalysisController::class, 'all_yt_channel_ajaxFunc'])->name('all_yt_channel_ajax');
    Route::post('/all_yt_kpi_playlist_ajax',  [AnalysisController::class, 'all_yt_kpi_playlist_ajaxFunc'])->name('all_yt_kpi_playlist_ajax');
    Route::post('/all_yt_kpi_views_ajax',  [AnalysisController::class, 'all_yt_kpi_views_ajaxFunc'])->name('all_yt_kpi_views_ajax');
    Route::post('/all_yt_last_video_ajax',  [AnalysisController::class, 'all_yt_last_video_ajaxFunc'])->name('all_yt_last_video_ajax');
    Route::post('/all_yt_benchmark_ajax',  [AnalysisController::class, 'all_yt_benchmark_ajaxFunc'])->name('all_yt_benchmark_ajax');

    //Instagram Start Here
    Route::get('/facebook-auth/{id}',  [InstagramController::class, 'handle_facebook_auth'])->name('facebook-auth.handle');
    Route::post('/facebook-auth/{id}',  [InstagramController::class, 'store_facebook_auth'])->name('facebook-auth.store');
    Route::get('/present-client/instagram/{id}',  [InstagramController::class, 'get_instagram_data_overall'])->name('present-client-instagram.overall');
    Route::get('/present-client/instagram-insights/{igaccountid}/{id}',  [InstagramController::class, 'get_instagram_insight'])->name('instagram.insight');
    Route::get('/present-client/instagram-insights/{igaccountid}/{id}/{mediaid}',  [InstagramController::class, 'get_instagramMedia_insight'])->name('instagram-media.insight');

});





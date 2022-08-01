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

Route::get('/', function () {
    if (Auth::guest())
    {
        return redirect('login');
    }
    else
    {
        return redirect('dashboard');
    }
})->name('home');

Auth::routes();

Route::get('/dashboard', 'DashboardController@dashboard')
    ->middleware('is_admin')
    ->name('dashboard');

Route::get('/filters', 'FilterController@filters')
    ->middleware('is_admin')
    ->name('filters');

Route::get('/campaigns', 'CampaignController@campaigns')
    ->middleware('is_admin')
    ->name('campaigns');

Route::get('/campaigns/create', 'CampaignController@create')
    ->middleware('is_admin')
    ->name('campaigns.create');

Route::post('/campaigns/create', 'CampaignController@store')
    ->middleware('is_admin')
    ->name('campaigns.store');

Route::get('/campaigns/{campaign}/edit', 'CampaignController@edit')
    ->middleware('is_admin')
    ->name('campaigns.edit');

Route::put('/campaigns/{campaign}/edit', 'CampaignController@update')
    ->middleware('is_admin')
    ->name('campaigns.update');
    
Route::delete('/campaigns/{campaign}', 'CampaignController@delete')
    ->middleware('is_admin')
    ->name('campaigns.delete');

Route::delete('/campaigns/{campaign}/issues/{issue}', 'CampaignController@deleteIssue')
    ->middleware('is_admin')
    ->name('campaigns.issues.delete');

Route::post('/campaigns/{campaign}/actions/schedule', 'CampaignController@schedule')
    ->middleware('is_admin')
    ->name('campaigns.actions.schedule');

Route::post('/campaigns/{campaign}/actions/detach', 'CampaignController@detachFromMailchimp')
    ->middleware('is_admin')
    ->name('campaigns.actions.detach');

Route::get('/campaigns/{campaign}/preview', 'EmailController@preview')
    ->middleware('is_admin')
    ->name('campaigns.preview');

Route::get('/issues', 'IssueController@issues')
    ->middleware('is_admin')
    ->name('issues');

Route::get('/issues/{issue}/edit', 'IssueController@edit')
    ->middleware('is_admin')
    ->name('issues.edit');

Route::put('/issues/{issue}/edit', 'IssueController@update')
    ->middleware('is_admin')
    ->name('issues.update');

Route::post('/issues/{issue}/actions/vote', 'VoteController@vote')
    ->middleware('is_admin')
    ->name('issues.vote');

Route::post('/issues/{issue}/actions/unvote', 'VoteController@unvote')
    ->middleware('is_admin')
    ->name('issues.unvote');

<?php

/*
 * Define your routes and which views to display
 * depending of the query.
 *
 * Based on WordPress conditional tags from the WordPress Codex
 * http://codex.wordpress.org/Conditional_Tags
 *
 */

Route::get('home', function(){

    if (!is_user_logged_in())
    {
        return View::make('pages.home');
    }

    // Logged in users are automatically redirected to their tasks list.
    wp_redirect(home_url('tasks'));
    exit;

});

// Start tasks routes.
Route::get('postTypeArchive', array('tasks', 'uses' => 'TasksController@index'));

Route::post('postTypeArchive', array('tasks', 'uses' => 'TasksController@register'));

Route::get('singular', array('tasks', 'uses' => 'TasksController@single'));

Route::post('singular', array('tasks', 'uses' => 'TasksController@modify'));
// End tasks routes.

// Listen to 404
Route::any('404', function()
{
    return 'We think you are lost.';
});

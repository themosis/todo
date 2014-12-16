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

    return View::make('welcome');

});

Route::get('postTypeArchive', array('tasks', 'uses' => 'TasksController@index'));

Route::post('postTypeArchive', array('tasks', 'uses' => 'TasksController@register'));

Route::get('singular', array('tasks', 'uses' => 'TasksController@single'));

Route::post('singular', array('tasks', 'uses' => 'TasksController@modify'));

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

Route::get('postTypeArchive', function($post, $query)
{
    $action = $query->get('task_action');

    if ('create' === $action)
    {
        return View::make('tasks.create');
    }

    return View::make('tasks.all');
});

Route::post('postTypeArchive', function($post, $query)
{
    $task = Input::get('task');
    $inserted = false;

    if (!empty($task))
    {
        $inserted = wp_insert_post(array(
            'post_title'        => $task,
            'post_type'         => 'tasks',
            'post_status'       => 'publish'
        ));
    }

    return View::make('tasks.all')->with('taskCreated', $inserted);
});

Route::get('singular', array('tasks', function($post, $query)
{
    $action = $query->get('task_action');

    if ('edit' === $action)
    {
        return View::make('tasks.edit', array('task' => $post));
    }
    elseif ('delete' === $action)
    {
        return View::make('tasks.delete', array('task' => $post));
    }

    return 'Task: '.$post->post_title;
}));

Route::post('singular', array('tasks', function($post, $query)
{
    $action = $query->get('task_action');

    if ('edit' === $action)
    {
        $input = Input::get('task');
        $updated = wp_update_post(array('ID' => $post->ID, 'post_title' => $input));

        if ($updated)
        {
            wp_redirect(home_url('tasks'));
            exit;
        }
    }
    elseif ('delete' === $action)
    {
        $deleted = wp_delete_post($post->ID, true);

        if ($deleted)
        {
            wp_redirect(home_url('tasks'));
            exit;
        }

        return View::make('tasks.delete', array('task' => $post));
    }
}));

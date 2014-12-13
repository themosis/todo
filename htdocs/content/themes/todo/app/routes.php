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
    if (isset($query->query_vars['task_action']) && 'create' === $query->query_vars['task_action'])
    {
        return 'Add a new task';
    }

    return View::make('tasks.all');
});

Route::get('singular', array('tasks', function($post, $query)
{
    $vars = $query->query_vars;

    if (isset($vars['task_action']) && 'edit' === $vars['task_action'])
    {
        return View::make('tasks.edit', array('task' => $post));
    }
    elseif (isset($vars['task_action']) && 'delete' === $vars['task_action'])
    {
        return View::make('tasks.delete', array('task' => $post));
    }

    return 'Task: '.$post->post_title;
}));

Route::post('singular', array('tasks', function($post, $query)
{
    $vars = $query->query_vars;

    if (isset($vars['task_action']) && 'edit' === $vars['task_action'])
    {
        $input = Input::get('task');
        $updated = wp_update_post(array('ID' => $post->ID, 'post_title' => $input));

        if ($updated)
        {
            wp_redirect(home_url('tasks'));
            exit;
        }
    }
    elseif (isset($vars['task_action']) && 'delete' === $vars['task_action'])
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

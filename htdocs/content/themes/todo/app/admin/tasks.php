<?php

// Define my tasks custom post type
$tasks = PostType::make('tasks', 'Tasks', 'Task')->set(array(
    'public'                => false,
    'show_ui'               => true,
    'publicly_queryable'    => true,
    'supports'              => array('title'),
    'has_archive'           => true
));

// Add custom metabox in order to view task details.
// Example: due date,...
$view = View::make('tasks.metabox.details');
Metabox::make('Details', $tasks->getSlug(), array('priority' => 'high'), $view)->set();

// Define a custom rewrite rule and endpoints for the tasks.
add_action('init', function() use ($tasks)
{
    // Create the query var use for task actions.
    add_rewrite_tag('%task_action%', '([^&]+)');

    // Set a rule in order to create a new task
    // http://domain.tld/tasks/create/
    add_rewrite_rule($tasks->getSlug().'/create/?$', 'index.php?post_type='.$tasks->getSlug().'&task_action=create', 'top');

    // Set a rule where we get a specific task by its ID
    // Example: http://domain.tld/tasks/4/ -> Retrieve task with ID 4
    add_rewrite_rule($tasks->getSlug().'/([0-9]+)?$','index.php?post_type='.$tasks->getSlug().'&p=$matches[1]','top');

    // Set a rule in order to edit a specific task
    // http://domain.tld/tasks/4/edit/ -> Edit task with ID 4
    add_rewrite_rule($tasks->getSlug().'/([0-9]+)/edit/?$', 'index.php?post_type='.$tasks->getSlug().'&p=$matches[1]&task_action=edit', 'top');

    // Set a rule in order to delete a specific task
    // http://domain.tld/tasks/4/delete/ -> Delete task with ID 4
    add_rewrite_rule($tasks->getSlug().'/([0-9]+)/delete/?$', 'index.php?post_type='.$tasks->getSlug().'&p=$matches[1]&task_action=delete', 'top');

});

// Change the default query for tasks archive
// Return the first 500 tasks
add_action('pre_get_posts', function($query) use ($tasks)
{
    if ($tasks->getSlug() === $query->get('post_type'))
    {
        if ('create' === $query->get('task_action'))
        {
            $query->set('posts_per_page', 0);
        }
        else
        {
            $query->set('posts_per_page', 500);
        }
    }
});

// Modify admin table columns
// in order to display the author name.
add_filter('manage_edit-'.$tasks->getSlug().'_columns', function($titles)
{
    $titles['author'] = __('Author');
    return $titles;
});
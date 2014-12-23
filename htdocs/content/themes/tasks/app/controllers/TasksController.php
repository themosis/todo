<?php

class TasksController extends BaseController
{
    /**
     * The current user ID.
     *
     * @var \Themosis\User\User
     */
    private $user;

    /**
     * The tasks model instance.
     *
     * @var \TasksModel
     */
    private $model;

    public function __construct()
    {
        // Redirect to home page if users are not logged in.
        // This is triggered for all routes using this controller.
        if (!is_user_logged_in())
        {
            wp_redirect(home_url());
            exit;
        }

        // Will handle the assets.
        $this->loadAssets();

        // Set the current user.
        $this->user = User::current();

        // Set the tasks model
        $this->model = new TasksModel($this->user);
    }

    /**
     * Handle the list of tasks.
     *
     * @param \WP_Post|bool $post False if no posts found.
     * @param \WP_Query $query
     * @return mixed
     */
    public function index($post, $query)
    {
        // Start...
        $action = $query->get('task_action');

        // Look for the create task action.
        if ('create' === $action)
        {
            return $this->create();
        }

        // Default tasks list view.
        // Grab the nonce action.
        $a = Input::get('action');
        $message = '';

        // Check for editing task action.
        if (wp_verify_nonce($a, 'task_updated'))
        {
            $message = 'Task updated successfully.';
        }

        // Check for deleted task action.
        if (wp_verify_nonce($a, 'task_deleted'))
        {
            $message = 'Task deleted successfully.';
        }

        // Check for cleared/removed task action.
        if (wp_verify_nonce($a, 'tasks_list_cleared'))
        {
            $message = 'Tasks list updated successfully.';
        }

        // Default output. Display a list of tasks.
        return View::make('tasks.all')->with(array(
            'query'     => $this->model->getQuery(),
            'message'   => $message
        ));
    }

    /**
     * Handle the create task GET request.
     *
     * @return mixed
     */
    private function create()
    {
        // Start create...
        $action = Input::get('action');

        // Nonce to check in order to access the default create task view.
        $nonce = wp_verify_nonce($action, 'create_task');

        // Nonce to check if there is an error when inserting a new task.
        $errorNonce = wp_verify_nonce($action, 'task_error');

        // Nonce to check if the task already exists when the user creates it.
        $existNonce = wp_verify_nonce($action, 'task_exist');

        // If nonce ok, proceed with the form in order to create a task.
        if ($nonce)
        {
            return View::make('tasks.create');
        }

        // Validation fails, display an error message.
        if ($errorNonce)
        {
            return View::make('tasks.create')->with('error', 'Error when inserting your task. Make sure to use alphanumeric characters only.');
        }

        // Task already exists.
        if ($existNonce)
        {
            return View::make('tasks.create')->with('error', 'This task already exists. Add a new one please.');
        }

        // Avoid "anyone" to get access to the create screen
        // If not using the in-app link to create a task, users
        // are redirected to the tasks list.
        wp_redirect(home_url('tasks'));
        exit;
    }

    /**
     * Handle POST requests on tasks list view in order
     * to register a new task or remove completed tasks.
     *
     * @return mixed
     */
    public function register()
    {
        // Check form nonce for REMOVE completed tasks requests.
        if (wp_verify_nonce(Input::get(APP_NONCE), 'task_remove'))
        {
            return $this->removeTasks();
        }

        // Check form nonce for CREATE requests.
        if (wp_verify_nonce(Input::get(APP_NONCE), 'task_create'))
        {
            return $this->createTask();
        }

        // If nothing, simply return the tasks list.
        // Redirect.
        wp_redirect(home_url('tasks'));
        exit;
    }

    /**
     * Handle the POST request in order to create a task.
     *
     * @return mixed
     */
    private function createTask()
    {
        // Get the submitted task value.
        $task = Validator::single(Input::get('task'), array('textfield', 'min:3'));

        if (!empty($task))
        {
            // Insert task data.
            $inserted = $this->model->insert($task); // Return the task ID

            // Return task list view with update message.
            if ($inserted)
            {
                // Add task due date.
                $date = Validator::single(Input::get('schedule'), array('textfield'));
                $this->model->setDate($inserted, $date);

                return View::make('tasks.all')->with(array(
                    'message'       => 'Task created successfully.',
                    'query'         => $this->model->getQuery()
                ));
            }

            // Tell the user the task already exists.
            wp_redirect(wp_nonce_url(home_url('tasks/create/'), 'task_exist', 'action'));
            exit;
        }

        // Error when processing the task. The validation fails.
        wp_redirect(wp_nonce_url(home_url('tasks/create/'), 'task_error', 'action'));
        exit;
    }

    /**
     * Handle the POST request in order to remove
     * completed tasks.
     *
     * @return mixed
     */
    private function removeTasks()
    {
        // Processed / deleted tasks.
        $processed = array();
        $selectedTasks = (array) Input::get('task_check');

        foreach ($selectedTasks as $task)
        {
            $task = Validator::single($task, array('num'));

            if (is_numeric($task))
            {
                $processed[] = wp_delete_post($task, true);
            }
        }

        $error = array_filter($processed, function($task)
        {
            if (!$task)
            {
                return true;
            }
        });

        // If there is an error during the delete process.
        // Redirect to tasks list and display a message.
        if ($error)
        {
            wp_redirect(wp_nonce_url(home_url('tasks'), 'error_completed_tasks', 'action'));
            exit;
        }

        // Everything is deleted/removed.
        // Simply redirect to tasks list.
        wp_redirect(wp_nonce_url(home_url('tasks'), 'tasks_list_cleared', 'action'));
        exit;
    }

    /**
     * Handle GET requests for a single task.
     * Look for the 'edit' and 'delete' screens.
     *
     * @param \WP_Post $post
     * @param \WP_Query $query
     * @return mixed
     */
    public function single($post, $query)
    {
        $action = $query->get('task_action');

        if ('edit' === $action)
        {
            // Show the single task view.
            return $this->show($post);
        }
        elseif ('delete' === $action)
        {
            // Show the delete task view.
            $delete = Input::get('action');

            // Default delete nonce.
            $nonce = wp_verify_nonce($delete, 'delete_task');

            // If on delete screen.
            // Show the task and the form in order to delete it.
            if ($nonce)
            {
                return View::make('tasks.delete', array('task' => $post));
            }
        }

        // If not on edit or delete screen, redirect to the tasks list.
        wp_redirect(home_url('tasks'));
        exit;
    }

    /**
     * Handle POST requests in order to edit or delete
     * an existing task.
     *
     * @param \WP_Post $post
     * @param \WP_Query $query
     * @return mixed
     */
    public function modify($post, $query)
    {
        $action = $query->get('task_action');

        if ('edit' === $action)
        {
            // Update the task.
            $this->update($post);
        }
        elseif ('delete' === $action)
        {
            // Delete the task.
            // If there is an error, output the delete screen.
            return $this->delete($post);
        }

        // If nothing, redirect to tasks list.
        wp_redirect(home_url('tasks'));
        exit;
    }

    /**
     * Handle the GET request for editing/show single task.
     *
     * @param \WP_Post $post The task post object.
     * @return mixed
     */
    private function show($post)
    {
        $action = Input::get('action');

        // Default edit nonce.
        $nonce = wp_verify_nonce($action, 'edit_task');

        // Error edit nonce.
        $error = wp_verify_nonce($action, 'edit_task_error');

        // Exists edit nonce.
        $exists  = wp_verify_nonce($action, 'edit_task_exist');

        // If nonce verified, render the edit form in order to edit
        // the selected task.
        if ($nonce)
        {
            return View::make('tasks.edit')->with('task', $post);
        }

        // The updated task is similar to an existing one.
        if ($exists)
        {
            return View::make('tasks.edit')->with(array(
                'task'      => $post,
                'error'     => 'A similar task already exists. Please set another task.'
            ));
        }

        // There was an error when submitting the task value.
        // Return the edit view with a message.
        if ($error)
        {
            return View::make('tasks.edit')->with(array(
                'task'      => $post,
                'error'     => 'Error when updating your task. Make sure to use alphanumeric characters only.'
            ));
        }

        // If not allowed to edit, simply redirects to the tasks list.
        wp_redirect(home_url('tasks'));
        exit;
    }

    /**
     * Handle the POST request for single task EDIT.
     *
     * @param \WP_Post $post
     * @return void
     */
    private function update($post)
    {
        // Check form nonce.
        if (wp_verify_nonce($_POST[Session::nonceName], Session::nonceAction))
        {
            // Validate the task input value before updating.
            $taskInput = Validator::single(Input::get('task'), array('textfield', 'min:3'));

            // If value validated
            // update the task.
            if (!empty($taskInput))
            {
                $updated = $this->model->update($post, $taskInput);

                // If task post updated
                // send the user back to its tasks list.
                if ($updated)
                {
                    // Update the due date as well.
                    $date = Validator::single(Input::get('schedule'), array('textfield'));
                    $this->model->setDate($updated, $date);

                    wp_redirect(wp_nonce_url(home_url('tasks'), 'task_updated', 'action'));
                    exit;
                }

                // Task already exists.
                // Redirect to the edit screen with message.
                wp_redirect(wp_nonce_url(home_url('tasks/'.$post->ID.'/edit/'), 'edit_task_exist', 'action'));
                exit;
            }

            // If validation fails, the value is not valid.
            // Redirect to the edit page and display an error message.
            wp_redirect(wp_nonce_url(home_url('tasks/'.$post->ID.'/edit/'), 'edit_task_error', 'action'));
            exit;
        }

        // If post request is not valid.
        // Redirect the user to the tasks list.
        wp_redirect(home_url('tasks'));
        exit;

    }

    /**
     * Handle the delete POST request.
     *
     * @param \WP_Post $post The task post object.
     * @return mixed
     */
    private function delete($post)
    {
        if (wp_verify_nonce($_POST[Session::nonceName], Session::nonceAction))
        {
            $task = Validator::single(Input::get('task'), array('num'));

            // If task is the task ID number.
            // Delete it.
            if (is_numeric($task))
            {
                $deleted = wp_delete_post($post->ID, true);

                // The task is deleted.
                // Send the user back to its tasks list.
                if ($deleted)
                {
                    wp_redirect(wp_nonce_url(home_url('tasks'), 'task_deleted', 'action'));
                    exit;
                }
            }
        }

        // In case of an error, show the delete screen again with a gentle warning message.
        return View::make('tasks.delete', array(
            'task'      => $post,
            'error'     => 'Something went wrong. Try again please.'
        ));
    }

    /**
     * Load tasks assets.
     *
     * @return void
     */
    private function loadAssets()
    {
        // Tasks list assets.
        View::composer('tasks.all', function()
        {
            Asset::add('js-tasks', 'js/tasks.js', array('jquery'), '1.0', true);
        });

        // Create/add/edit task assets
        View::composer(array('tasks.create', 'tasks.edit'), function()
        {
            Asset::add('js-lib-moment', 'js/library/moment.js', array('jquery'), '2.8.4', true);
            Asset::add('js-lib-pikaday', 'js/library/pikaday.js', array('js-lib-moment'), '1.0', true);
            Asset::add('js-lib-pikaday-plugin', 'js/library/pikaday.jquery.js', array('js-lib-pikaday'), '1.0', true);
            Asset::add('js-date', 'js/date.js', array('js-lib-pikaday-plugin'), '1.0', true);
        });
    }
}
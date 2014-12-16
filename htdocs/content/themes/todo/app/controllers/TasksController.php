<?php

class TasksController extends BaseController
{
    /**
     * Default tasks loop query.
     *
     * @var \WP_Query
     */
    private $query = array();

    /**
     * The current user ID.
     *
     * @var \Themosis\User\User
     */
    private $user;

    public function __construct()
    {
        // Redirect to home page if users are not logged in.
        // This is triggered for all routes using this controller.
        if (!is_user_logged_in())
        {
            wp_redirect(home_url());
            exit;
        }

        $this->user = User::current();

        // Main query parameters used to display the tasks list.
        $this->query = array(
            'post_type'         => 'tasks',
            'posts_per_page'    => 500,
            'post_status'       => 'publish',
            'author'            => $this->user->ID
        );
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
        $action = $query->get('task_action');

        if ('create' === $action)
        {
            return $this->create();
        }

        // Check for editing task action.
        $updated = wp_verify_nonce(Input::get('action'), 'task_updated');

        if ($updated)
        {
            return View::make('tasks.all')->with(array(
                'query'     => new WP_Query($this->query),
                'message'   => 'Task updated.'
            ));
        }

        // Default output. Display a list of tasks.
        return View::make('tasks.all')->with('query', new WP_Query($this->query));
    }

    /**
     * Handle the create task GET request.
     *
     * @return mixed
     */
    private function create()
    {
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
     * Handle POST requests in order to register
     * a new task.
     *
     * @return mixed
     */
    public function register()
    {
        // Check form nonce.
        if (wp_verify_nonce($_POST[Session::nonceName], Session::nonceAction))
        {
            // Get the submitted task value.
            $task = Validator::single(Input::get('task'), array('textfield', 'min:3'));

            if (!empty($task))
            {
                // Insert task data.
                $t = new TasksModel($this->user);
                $inserted = $t->insert($task);

                // Return task list view with update message.
                if ($inserted)
                {
                    return View::make('tasks.all')->with(array(
                        'message'       => 'Task created.',
                        'query'         => new WP_Query($this->query)
                    ));
                }

                // Tell the user the task already exists.
                wp_redirect(wp_nonce_url(home_url('tasks/create/'), 'task_exist', 'action'));
                exit;
            }
        }

        // Error when processing the task. The validation fails.
        wp_redirect(wp_nonce_url(home_url('tasks/create/'), 'task_error', 'action'));
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
            return $this->show($post);
        }
        elseif ('delete' === $action)
        {
            return View::make('tasks.delete', array('task' => $post));
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
            $this->update($post);
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

        // If nonce verified, render the edit form in order to edit
        // the selected task.
        if ($nonce)
        {
            return View::make('tasks.edit')->with('task', $post);
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
                $t = new TasksModel($this->user);
                $updated = $t->update($post, $taskInput);

                // If task post updated
                // send the user back to its tasks list.
                if ($updated)
                {
                    wp_redirect(wp_nonce_url(home_url('tasks'), 'task_updated', 'action'));
                    exit;
                }
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
}
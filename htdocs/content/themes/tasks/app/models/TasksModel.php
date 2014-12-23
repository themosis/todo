<?php

class TasksModel {

    /**
     * A list of all registered/published tasks.
     * @var array
     */
    private $tasks;

    /**
     * The custom post type slug name.
     *
     * @var string
     */
    private $slug = 'tasks';

    /**
     * The logged in user.
     *
     * @var \Themosis\User\User
     */
    private $user;

    /**
     * The task due date meta key.
     *
     * @var string
     */
    private $date_meta = 'task_due_date';

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Return a task query instance.
     *
     * @return \WP_Query
     */
    public function getQuery()
    {
        $query = new WP_Query(array(
            'post_type'         => $this->slug,
            'posts_per_page'    => 1000,
            'post_status'       => 'publish',
            'author'            => $this->user->ID
        ));

        return $query;
    }

    /**
     * Insert a new task.
     *
     * @param string $title The task title.
     * @return bool|int The post_id if inserted, false if it exists.
     */
    public function insert($title)
    {
        // Check if the task exists.
        if ($this->taskExists($title))
        {
            return false;
        }

        // If not, insert it.
        return wp_insert_post(array(
            'post_title'        => $title,
            'post_type'         => $this->slug,
            'post_status'       => 'publish',
            'post_author'       => $this->user->ID,
            'ping_status'       => 'closed'
        ));
    }

    /**
     * Update a task post.
     *
     * @param \WP_Post $task The task post object.
     * @param string $title
     * @return bool|int The task post ID or false if it already exists.
     */
    public function update($task, $title)
    {
        return wp_update_post(array(
            'ID' => $task->ID,
            'post_title' => $title
        ));
    }

    /**
     * Register due date for a specific task.
     *
     * @param int $id The task ID
     * @param string $newDate The due date for a task.
     * @return void
     */
    public function setDate($id, $newDate)
    {
        $currentDate = Meta::get($id, $this->date_meta);
        update_post_meta($id, $this->date_meta, $newDate, $currentDate);
    }

    /**
     * Check if the task exists.
     *
     * @param string $title The task title.
     * @return bool
     */
    private function taskExists($title)
    {
        $task = array_filter($this->getQuery()->get_posts(), function($t) use ($title)
        {
            if ($title === $t->post_title)
            {
                return true;
            }

            return false;
        });

        return $task;
    }

}
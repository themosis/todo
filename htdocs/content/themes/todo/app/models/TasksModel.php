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

    public function __construct()
    {
        $query = new WP_Query(array(
            'post_type'         => $this->slug,
            'posts_per_page'    => 1000,
            'post_status'       => 'publish'
        ));

        // Fill the tasks property.
        $this->tasks = $query->get_posts();
    }

    /**
     * Insert a new task.
     *
     * @param string $title The task title.
     * @param \WP_User $user The user who creates the task.
     * @return bool|int The post_id if inserted, false if it exists.
     */
    public function insert($title, $user)
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
            'post_author'       => $user->ID,
            'ping_status'       => 'closed'
        ));
    }

    /**
     * Check if the task exists.
     *
     * @param string $title The task title.
     * @return bool
     */
    private function taskExists($title)
    {
        $task = array_filter($this->tasks, function($t) use ($title)
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
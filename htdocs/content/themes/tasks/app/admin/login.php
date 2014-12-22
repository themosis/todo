<?php

/*****************************************************************************/
// Remove access to default 'wp-login.php' request.
/*****************************************************************************/
add_action('init', function()
{
    $page_viewed = basename($_SERVER['REQUEST_URI']);

    if ($page_viewed === "wp-login.php" && $_SERVER['REQUEST_METHOD'] === 'GET')
    {
        wp_redirect(home_url());
        exit;
    }
});

/*****************************************************************************/
// Redirect if login failed.
/*****************************************************************************/
add_action('wp_login_failed', function()
{
    wp_redirect(home_url().'?login=failed');
    exit;
});

/*****************************************************************************/
// Check if username or password are empty.
/*****************************************************************************/
add_filter('authenticate', function($user, $username, $password)
{
    if (empty($username) || empty($password))
    {
        wp_redirect(home_url().'?login=empty');
        exit;
    }
}, 1, 3);

/*****************************************************************************/
// Redirect on logout to home page.
/*****************************************************************************/
add_action('wp_logout', function()
{
    wp_redirect(home_url().'?login=logout');
    exit;
});

<?php

/*
Plugin Name: wp-context
*/

// using this instead of other methods because any kind of redirect
// is going to cause issues b/c docker
add_action('init', function () {
    if (isset($_GET['lol-login'])) {
        $user = get_user_by('login', $_GET['lol-login']);
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        exit();
    }
});

require_once __DIR__ . '/vendor/autoload.php';

if (file_exists(__DIR__ . '/fixture.php')) {
   require_once __DIR__ . '/fixture.php';
}

<?php

use O\WordPress\Context\Context;

add_action('wp_ajax_nopriv_wp_context', function () {
    $ctx = new Context();
    if ($ctx->isAdmin()) {
        header('wp-context-admin-ajax: true');
        die;
    }
});

<?php

use O\WordPress\Context\Context;

add_action('template_redirect', function () {
    $ctx = new Context();
    if ($ctx->isRobots()) {
        header('wp-context-late-robots: true');
        die;
    }
});

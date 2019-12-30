<?php

use O\WordPress\Context\Context;

add_action('wp_loaded', function () {
    $ctx = new Context();
    if ($ctx->isRest()) {
        header('wp-context-late-rest: true');
        die;
    }
});

<?php

use O\WordPress\Context\Context;

add_action('template_redirect', function () {
    $ctx = new Context();
    if ($ctx->isFeed()) {
        header('wp-context-late-feed: true');
        die;
    }
});

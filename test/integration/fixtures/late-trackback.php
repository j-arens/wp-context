<?php

use O\WordPress\Context\Context;

add_action('template_redirect', function () {
    $ctx = new Context();
    if ($ctx->isTrackback()) {
        header('wp-context-late-trackback: true');
        die;
    }
});

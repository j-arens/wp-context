<?php

use O\WordPress\Context\Context;

add_action('current_screen', function () {
    $ctx = new Context();
    if ($ctx->isBlockEditor()) {
        header('wp-context-block-editor: true');
        die;
    }
});

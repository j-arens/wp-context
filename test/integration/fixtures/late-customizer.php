<?php

use O\WordPress\Context\Context;

add_action('wp_loaded', function () {
    $ctx = new Context();
    if ($ctx->isCustomizer()) {
        header('wp-context-late-customizer: true');
        die;
    }
});

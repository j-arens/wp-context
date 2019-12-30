<?php

use O\WordPress\Context\Context;

add_action('init', function () {
    $ctx = new Context();
    // techincally is_admin is true on xmlrpc requests
    // but i prefer to have them be exclusive of each other
    if ($ctx->isXmlRpc() && !$ctx->isAdmin()) {
        header('wp-context-admin-xmlrpc: true');
        die;
    }
});

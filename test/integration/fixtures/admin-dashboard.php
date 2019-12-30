<?php

use O\WordPress\Context\Context;

$ctx = new Context();
if ($ctx->isAdmin()) {
    header('wp-context-admin-dashboard: true');
    die;
}

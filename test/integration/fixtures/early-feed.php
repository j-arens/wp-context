<?php

use O\WordPress\Context\Context;

$ctx = new Context();
if ($ctx->isFeed()) {
    header('wp-context-early-feed: true');
    die;
}

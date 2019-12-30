<?php

use O\WordPress\Context\Context;

$ctx = new Context();
if ($ctx->isTrackback()) {
    header('wp-context-early-trackback: true');
    die;
}

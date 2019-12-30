<?php

use O\WordPress\Context\Context;

$ctx = new Context();
if ($ctx->isRobots()) {
    header('wp-context-early-robots: true');
    die;
}

<?php

use O\WordPress\Context\Context;

$ctx = new Context;
if ($ctx->isRest()) {
    header('wp-context-early-rest: true');
    die;
}

<?php

use O\WordPress\Context\Context;

$ctx = new Context();
if ($ctx->isFront()) {
    header('wp-context-front: true');
    die;
}

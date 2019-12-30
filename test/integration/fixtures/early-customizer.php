<?php

use O\WordPress\Context\Context;

$ctx = new Context();
if ($ctx->isCustomizer()) {
    header('wp-context-early-customizer: true');
    die;
}

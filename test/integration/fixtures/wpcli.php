<?php

use O\WordPress\Context\Context;

$ctx = new Context();
if ($ctx->isWpCli()) {
    print "wp-context-wp-cli";
    exit;
}

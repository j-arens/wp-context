<?php

use O\WordPress\Context\Context;

$ctx = new Context();
if ($ctx->isGraphQL()) {
    header('wp-context-graphql: true');
    die;
}

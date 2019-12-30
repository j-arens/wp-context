<?php

use O\WordPress\Context\Context;

$ctx = new Context;
if ($ctx->isXmlRpc()) {
    header('wp-context-xmlrpc: true');
    die;
}

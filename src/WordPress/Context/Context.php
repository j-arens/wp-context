<?php

declare(strict_types=1);

namespace O\WordPress\Context;

class Context implements ContextInterface
{
    /**
     * @var string
     */
    public const ALL = 'all';

    /**
     * @var string
     */
    public const ADMIN = 'admin';

    /**
     * @var string
     */
    public const FRONT = 'front';

    /**
     * @var string
     */
    public const REST = 'rest';

    /**
     * @var string
     */
    public const XML_RPC = 'xmlrpc';

    /**
     * @var string
     */
    public const WP_CLI = 'wpcli';

    /**
     * @var string
     */
    public const GRAPH_QL = 'graphql';

    /**
     * @var string
     */
    public const FEED = 'feed';

    /**
     * @var string
     */
    public const ROBOTS = 'robots';

    /**
     * @var string
     */
    public const TRACKBACK = 'trackback';

    /**
     * @var string
     */
    public const BLOCK_EDITOR = 'blockeditor';

    /**
     * @var array<string>
     */
    protected $ctxToFunc = [
        self::ADMIN => 'isAdmin',
        self::REST => 'isRest',
        self::XML_RPC => 'isXmlRpc',
        self::WP_CLI => 'isWpCli',
        self::GRAPH_QL => 'isGraphQL',
        self::FEED => 'isFeed',
        self::ROBOTS => 'isRobots',
        self::TRACKBACK => 'isTrackback',
        self::BLOCK_EDITOR => 'isBlockEditor',
        self::FRONT => 'isFront',
    ];

    /**
     * {@inheritdoc}
     */
    public function is(string $const): bool
    {
        if (!isset($this->ctxToFunc[$const])) {
            throw ContextException::unknownConst($const);
        }
        $fn = $this->ctxToFunc[$const];
        return $this->$fn();
    }

    /**
     * {@inheritdoc}
     */
    public function getContext(): string
    {
        foreach ($this->ctxToFunc as $ctx => $func) {
            if ($this->$func()) {
                return $ctx;
            }
        }
        throw ContextException::unknownContext();
    }

    /**
     * {@inheritdoc}
     */
    public function isAdmin(): bool
    {
        // generally true for pretty much any admin request including admin-ajax
        // and the wp-customizer, technically true on xml rpc requests too but I prefer
        // isAdmin to be exclusive of xml rpc
        return is_admin() && !$this->isXmlRpc();
    }

    /**
     * {@inheritdoc}
     */
    public function isFront(): bool
    {
        // wp-includes/template-loader.php checks is_feed, is_robots, and
        // is_trackback before loading any templates, do the same checks
        // here to determine whether this is really a front request or not
        return !$this->isAdmin()
            && !$this->isFeed()
            && !$this->isRobots()
            && !$this->isTrackback()
            && !$this->isRest()
            && !$this->isXmlRpc()
            && !$this->isGraphQL()
            && !$this->isWpCli();
    }

    /**
     * {@inheritdoc}
     */
    public function isRest(): bool
    {
        if (defined('REST_REQUEST') && constant('REST_REQUEST')) {
            return true;
        }
        // the following two checks are done in the case that this method
        // is called before the wp query has been instantiated
        return isset($_GET['rest_route']) || $this->uriMatches('^\/wp-json');
    }

    /**
     * {@inheritdoc}
     */
    public function isXmlRpc(): bool
    {
        if (defined('XMLRPC_REQUEST') && constant('XMLRPC_REQUEST')) {
            return true;
        }
        return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function isWpCli(): bool
    {
        if (defined('WP_CLI') && constant('WP_CLI')) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isGraphQL(): bool
    {
        if (defined('GRAPHQL_REQUEST') && constant('GRAPHQL_REQUEST')) {
            return true;
        }
        // the graphql plugin defers fully loading until themes and plugins have been setup
        // so in general the above check will probably fail and this one will run
        // graphql requires pretty-permalinks, don't need to worry about other permalink styles
        return $this->uriMatches('^\/graphql');
    }

    /**
     * {@inheritdoc}
     */
    public function isFeed(): bool
    {
        if (did_action('wp')) {
            return is_feed();
        }
        // wp won't allow posts to take the slug "feed" so this check should always be accurate
        // feed requires pretty-permalinks, have to use the rest-api otherwise
        return $this->uriMatches('^\/feed');
    }

    /**
     * {@inheritdoc}
     */
    public function isRobots(): bool
    {
        if (did_action('wp')) {
            return is_robots();
        }
        // this doesn't cover non-pretty-permalinks but wp doesn't respond correctly
        // in those cases anyways...
        return $this->uriMatches('^\/robots\.txt');
    }

    /**
     * {@inheritdoc}
     */
    public function isTrackback(): bool
    {
        if (did_action('wp')) {
            return is_trackback();
        }
        // not sure if trackbacks work for other permalink styles besides pretty?
        // seems to be very legacy at this point, there should always be a slug
        // before the trackback portion of the uri and trackback should be the last path
        return $this->uriMatches('.+\/trackback');
    }

    /**
     * {@inheritdoc}
     */
    public function isBlockEditor(): bool
    {
        if (!$this->isAdmin()) {
            return false;
        }
        if (!function_exists('get_current_screen')) {
            throw ContextException::missingFunc('get_current_screen');
        }
        $screen = get_current_screen();
        if (!is_null($screen) && method_exists($screen, 'is_block_editor')) {
            return $screen->is_block_editor();
        } else {
            throw ContextException::missingFunc('$screen->is_block_editor');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isCustomizer(): bool
    {
        if (did_action('wp')) {
            return is_customize_preview();
        }
        return $this->uriMatches('\/wp-admin\/customize.php');
    }

    /**
     * check if the request uri mathches a pattern
     *
     * @param string $pattern
     * @return bool
     */
    protected function uriMatches(string $pattern): bool
    {
        if ($this->isWpCli()) {
            return false;
        }
        if (!isset($_SERVER['REQUEST_URI'])) {
            wp_fix_server_vars();
        }
        return (bool) preg_match("/$pattern/", $_SERVER['REQUEST_URI']);
    }
}

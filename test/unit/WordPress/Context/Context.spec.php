<?php

use O\WordPress\Context\Context;
use O\WordPress\Context\ContextException;

describe('Context', function () {
    beforeEach(function () {
        $this->ctx = new Context();
    });

    describe('->is', function () {
        it('throws if the given const does not correspond to a method', function () {
            $fn = function () {
                $this->ctx->is('lol');
            };
            expect($fn)->toThrow(ContextException::unknownConst('lol'));
        });

        it('matches the given const to a method and returns the result of invoking it', function () {
            allow($this->ctx)->toReceive('isAdmin')->andReturn(true);
            expect($this->ctx->is(Context::ADMIN))->toBe(true);
        });
    });

    describe('->getContext', function () {
        it('throws if it cannot determine the current context', function () {
            allow($this->ctx)->toReceive('isAdmin')->andReturn(false);
            allow($this->ctx)->toReceive('isRest')->andReturn(false);
            allow($this->ctx)->toReceive('isXmlRpc')->andReturn(false);
            allow($this->ctx)->toReceive('isWpCli')->andReturn(false);
            allow($this->ctx)->toReceive('isGraphQL')->andReturn(false);
            allow($this->ctx)->toReceive('isAtom')->andReturn(false);
            allow($this->ctx)->toReceive('isFeed')->andReturn(false);
            allow($this->ctx)->toReceive('isRobots')->andReturn(false);
            allow($this->ctx)->toReceive('isTrackback')->andReturn(false);
            allow($this->ctx)->toReceive('isBlockEditor')->andReturn(false);
            allow($this->ctx)->toReceive('isFront')->andReturn(false);
            $fn = function () {
                $this->ctx->getContext();
            };
            expect($fn)->toThrow(ContextException::unknownContext());
        });
    });

    describe('->isAdmin', function () {
        it('delegates to is_admin wordpress function', function () {
            allow('is_admin')->toBeCalled()->andReturn(true);
            expect($this->ctx->isAdmin())->toBe(true);
        });
    });

    describe('->isFront', function () {
        it('returns true if all other conditions are false', function () {
            allow($this->ctx)->toReceive('isAdmin')->andReturn(false);
            allow($this->ctx)->toReceive('isFeed')->andReturn(false);
            allow($this->ctx)->toReceive('isRobots')->andReturn(false);
            allow($this->ctx)->toReceive('isTrackback')->andReturn(false);
            allow($this->ctx)->toReceive('isRest')->andReturn(false);
            allow($this->ctx)->toReceive('isXmlRpc')->andReturn(false);
            allow($this->ctx)->toReceive('isGraphQL')->andReturn(false);
            allow($this->ctx)->toReceive('isWpCli')->andReturn(false);
            expect($this->ctx->isFront())->toBe(true);
        });
    });

    describe('->isRest', function () {
        it('returns true if REST_REQUEST is defined and true', function () {
            allow('defined')->toBeCalled()->with('REST_REQUEST')->andReturn(true);
            allow('constant')->toBeCalled()->with('REST_REQUEST')->andReturn(true);
            expect($this->ctx->isRest())->toBe(true);
        });

        it('returns true if the rest_route $_GET param is set', function () {
            allow('defined')->toBeCalled()->with('REST_REQUEST')->andReturn(false);
            $_GET['rest_route'] = '';
            expect($this->ctx->isRest())->toBe(true);
            unset($_GET['rest_route']);
        });

        it('returns true if the request uri includes wp-json', function () {
            allow('defined')->toBeCalled()->with('REST_REQUEST')->andReturn(false);
            $_SERVER['REQUEST_URI'] = '/wp-json/lol-route';
            expect($this->ctx->isRest())->toBe(true);
            unset($_SERVER['REQUEST_URI']);
        });
    });

    describe('->isXmlRpc', function () {
        it('returns true if XMLRPC_REQUEST is defined and true', function () {
            allow('defined')->toBeCalled()->with('XMLRPC_REQUEST')->andReturn(true);
            allow('constant')->toBeCalled()->with('XMLRPC_REQUEST')->andReturn(true);
            expect($this->ctx->isXmlRpc())->toBe(true);
        });
    });

    describe('->isWpCli', function () {
        it('returns true if WP_CLI is defined and true', function () {
            allow('defined')->toBeCalled()->with('WP_CLI')->andReturn(true);
            allow('constant')->toBeCalled()->with('WP_CLI')->andReturn(true);
            expect($this->ctx->isWpCli())->toBe(true);
        });
    });

    describe('->isGraphQL', function () {
        it('returns true if GRAPHQL_REQUEST is defined and true', function () {
            allow('defined')->toBeCalled()->with('GRAPHQL_REQUEST')->andReturn(true);
            allow('constant')->toBeCalled()->with('GRAPHQL_REQUEST')->andReturn(true);
            expect($this->ctx->isGraphQL())->toBe(true);
        });

        it('returns true if the request uri includes graphql', function () {
            allow('defined')->toBeCalled()->with('GRAPHQL_REQUEST')->andReturn(false);
            $_SERVER['REQUEST_URI'] = '/graphql';
            expect($this->ctx->isGraphQL())->toBe(true);
            unset($_SERVER['REQUEST_URI']);
        });
    });

    describe('->isFeed', function () {
        it('checks the request uri if the wp action has not yet fired', function () {
            allow('did_action')->toBeCalled()->with('wp')->andReturn(false);
            $_SERVER['REQUEST_URI'] = '/feed';
            expect($this->ctx->isFeed())->toBe(true);
            unset($_SERVER['REQUEST_URI']);
        });

        it('delegates to the is_feed wordpress function', function () {
            allow('did_action')->toBeCalled()->with('wp')->andReturn(true);
            allow('is_feed')->toBeCalled()->andReturn(true);
            expect($this->ctx->isFeed())->toBe(true);
        });
    });

    describe('->isRobots', function () {
        it('checks the request uri if the wp action has not yet fired', function () {
            allow('did_action')->toBeCalled()->with('wp')->andReturn(false);
            $_SERVER['REQUEST_URI'] = '/robots.txt';
            expect($this->ctx->isRobots())->toBe(true);
            unset($_SERVER['REQUEST_URI']);
        });

        it('delegates to the is_robots wordpress function', function () {
            allow('did_action')->toBeCalled()->with('wp')->andReturn(true);
            allow('is_robots')->toBeCalled()->andReturn(true);
            expect($this->ctx->isRobots())->toBe(true);
        });
    });

    describe('->isTrackback', function () {
        it('checks the request uri if the wp action has not yet fired', function () {
            allow('did_action')->toBeCalled()->with('wp')->andReturn(false);
            $_SERVER['REQUEST_URI'] = '/some-post/trackback';
            expect($this->ctx->isTrackback())->toBe(true);
            unset($_SERVER['REQUEST_URI']);
        });

        it('delegates to the is_trackback wordpress function', function () {
            allow('did_action')->toBeCalled()->with('wp')->andReturn(true);
            allow('is_trackback')->toBeCalled()->andReturn(true);
            expect($this->ctx->isTrackback())->toBe(true);
        });
    });

    describe('->isBlockEditor', function () {
        it('returns false if current context is not admin', function () {
            allow($this->ctx)->toReceive('isAdmin')->andReturn(false);
            expect($this->ctx->isBlockEditor())->toBe(false);
        });

        it('throws if the get_current_screen function is not loaded', function () {
            allow($this->ctx)->toReceive('isAdmin')->andReturn(true);
            allow('function_exists')->toBeCalled()->with('get_current_screen')->andReturn(false);
            $fn = function () {
                $this->ctx->isBlockEditor();
            };
            expect($fn)->toThrow(ContextException::missingFunc('get_current_screen'));
        });

        it('throws if the current screen is null', function () {
            allow($this->ctx)->toReceive('isAdmin')->andReturn(true);
            allow('function_exists')->toBeCalled()->with('get_current_screen')->andReturn(true);
            allow('get_current_screen')->toBeCalled()->andReturn(null);
            $fn = function () {
                $this->ctx->isBlockEditor();
            };
            expect($fn)->toThrow(ContextException::missingFunc('$screen->is_block_editor'));
        });

        it('throws if the current screen does not have the is_block_editor method', function () {
            allow($this->ctx)->toReceive('isAdmin')->andReturn(true);
            allow('function_exists')->toBeCalled()->with('get_current_screen')->andReturn(true);
            allow('get_current_screen')->toBeCalled()->andReturn(new stdClass());
            $fn = function () {
                $this->ctx->isBlockEditor();
            };
            expect($fn)->toThrow(ContextException::missingFunc('$screen->is_block_editor'));
        });

        it('delegates to the current screen\'s is_block_editor method', function () {
            class MockScreen {
                public function is_block_editor()
                {
                    return true;
                }
            }
            allow($this->ctx)->toReceive('isAdmin')->andReturn(true);
            allow('get_current_screen')->toBeCalled()->andReturn(new MockScreen());
            expect($this->ctx->isBlockEditor())->toBe(true);
        });
    });

    describe('->isCustomizer', function () {
        it('checks the request uri if the wp action has not yet fired', function () {
            allow('did_action')->toBeCalled()->with('wp')->andReturn(false);
            $_SERVER['REQUEST_URI'] = '/wp-admin/customize.php';
            expect($this->ctx->isCustomizer())->toBe(true);
            unset($_SERVER['REQUEST_URI']);
        });

        it('delegates to the is_customize_preview wordpress function', function () {
            allow('did_action')->toBeCalled()->andReturn(1);
            allow('is_customize_preview')->toBeCalled()->andReturn(true);
            expect($this->ctx->isCustomizer())->toBe(true);
        });
    });
});

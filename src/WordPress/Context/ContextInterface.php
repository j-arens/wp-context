<?php

declare(strict_types=1);

namespace O\WordPress\Context;

interface ContextInterface
{
    /**
     * matches the given const to its corresponding method and invokes it
     *
     * @param string $const
     * @return bool
     */
    public function is(string $const): bool;

    /**
     * returns the current context
     *
     * @return string
     */
    public function getContext(): string;

    /**
     * check if the current request is an admin request
     *
     * @return bool
     */
    public function isAdmin(): bool;

    /**
     * check if the current request is a front request
     *
     * @return bool
     */
    public function isFront(): bool;

    /**
     * check if the current request is a rest request
     *
     * @return bool
     */
    public function isRest(): bool;

    /**
     * check if the current request is a xml-rpc request
     *
     * @return bool
     */
    public function isXmlRpc(): bool;

    /**
     * check if the current request is a wp-cli request
     *
     * @return bool
     */
    public function isWpCli(): bool;

    /**
     * check if the current request is a graphql request
     *
     * @return bool
     */
    public function isGraphQL(): bool;

    /**
     * check if the current request is a feed request
     *
     * @return bool
     */
    public function isFeed(): bool;

    /**
     * check if the current request is a robots request
     *
     * @return bool
     */
    public function isRobots(): bool;

    /**
     * check if the current request is a trackback request
     *
     * @return bool
     */
    public function isTrackback(): bool;

    /**
     * check if the block editor is going to be rendered
     *
     * @return bool
     */
    public function isBlockEditor(): bool;

    /**
     * check if the wp customizer is going to be rendered
     *
     * @return bool
     */
    public function isCustomizer(): bool;
}

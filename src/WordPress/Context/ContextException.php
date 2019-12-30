<?php

declare(strict_types=1);

namespace O\WordPress\Context;

use Exception;

class ContextException extends Exception
{
    /**
     * @var array<mixed>
     */
    protected $data = [];

    /**
     * ContextException constructor
     *
     * @param string $msg
     * @param array<mixed> $data
     */
    public function __construct(string $msg = '', array $data = [])
    {
        parent::__construct($msg);
        $this->data = $data;
    }

    /**
     * converts the exception into an array
     *
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => get_class($this),
            'message' => $this->message,
            'data' => $this->data,
        ];
    }

    /**
     * converts the exception into a string
     *
     * @return string
     */
    public function __toString(): string
    {
        $type = get_class($this);
        $msg = $this->message;
        $data = empty($this->data) ? '' : print_r($this->data, true);
        return "[$type]: $msg\n$data";
    }

    /**
     * returns a context exception with unknown const message
     *
     * @param string $const
     * @return ContextException
     */
    public static function unknownConst(string $const): ContextException
    {
        return new ContextException("cannot resolve context corresponding to unknown const $const", [
            'const' => $const,
        ]);
    }

    /**
     * returns a context exception with unknown context message
     *
     * @return ContextException
     */
    public static function unknownContext(): ContextException
    {
        return new ContextException('failed to resolve the current context');
    }

    /**
     * returns a context exception with missing func message
     *
     * @param string $func
     * @return ContextException
     */
    public static function missingFunc(string $func): ContextException
    {
        return new ContextException("could not invoke non-existent function $func", [
            'func' => $func,
        ]);
    }

    /**
     * returns a context exception with timing error message
     *
     * @param string $method
     * @param string $timing
     * @return ContextException
     */
    public static function timingError(string $method, string $timing): ContextException
    {
        return new ContextException("method $method invoked $timing", [
            'method' => $method,
            'timing' => $timing,
        ]);
    }
}

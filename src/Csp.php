<?php

namespace Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use ParagonIE\CSPBuilder\CSPBuilder;

class Csp implements MiddlewareInterface
{
    /**
     * @var CSPBuilder
     */
    private $builder;

    /**
     * Set CSPBuilder.
     *
     * @param CSPBuilder|null $builder
     */
    public function __construct(CSPBuilder $builder = null)
    {
        $this->builder = $builder;
    }

    /**
     * Process a request and return a response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $response = $delegate->process($request);
        $builder = $this->builder ?: self::createBuilder();
        $builder->compile();

        return $builder->injectCSPHeader($response);
    }

    /**
     * Create a default csp builder.
     *
     * @return CSPBuilder
     */
    private static function createBuilder()
    {
        return new CSPBuilder([
            'script-src' => ['self' => true],
            'object-src' => ['self' => true],
            'frame-ancestors' => ['self' => true],
        ]);
    }
}

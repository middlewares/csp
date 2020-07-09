<?php
declare(strict_types = 1);

namespace Middlewares;

use ParagonIE\CSPBuilder\CSPBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Csp implements MiddlewareInterface
{
    /**
     * @var CSPBuilder
     */
    private $builder;
    private $legacy = true;

    public static function createFromFile(string $path): self
    {
        return new static(CSPBuilder::fromFile($path));
    }

    public static function createFromData(array $data): self
    {
        return new static(new CSPBuilder($data));
    }

    /**
     * Set CSPBuilder.
     */
    public function __construct(CSPBuilder $builder = null)
    {
        $this->builder = $builder ?: self::createBuilder();
    }

    /**
     * Set if include legacy headers for old browsers
     */
    public function legacy($legacy = true): self
    {
        $this->legacy = $legacy;

        return $this;
    }

    /**
     * Process a request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $this->builder->compile();

        return $this->builder->injectCSPHeader($response, $this->legacy);
    }

    /**
     * Create a default csp builder.
     */
    private static function createBuilder(): CSPBuilder
    {
        return new CSPBuilder([
            'script-src' => ['self' => true],
            'object-src' => ['self' => true],
            'frame-ancestors' => ['self' => true],
        ]);
    }
}

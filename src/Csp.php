<?php

namespace Middlewares;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Middlewares\Utils\Factory;
use ParagonIE\CSPBuilder\CSPBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class Csp implements MiddlewareInterface
{
    /**
     * @var CSPBuilder
     */
    private $builder;

    /**
     * @var string|null
     */
    private $reportPath = null;

    /**
     * @var LoggerInterface|null
     */
    private $reportLogger = null;

    /**
     * Set CSPBuilder.
     *
     * @param CSPBuilder|null $builder
     */
    public function __construct(CSPBuilder $builder = null)
    {
        $this->builder = $builder ?: self::createBuilder();
    }

    /**
     * Configure the report-uri directive
     *
     * @param string          $path   Path of the report-uri
     * @param LoggerInterface $logger The logger interface used to log the errors
     *
     * @return self
     */
    public function report($path, LoggerInterface $logger)
    {
        $this->reportPath = $path;
        $this->builder->addDirective('report-uri', $path);
        $this->reportLogger = $logger;

        return $this;
    }

    /**
     * Process a request and return a response.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        if ($this->logReport($request)) {
            return Factory::createResponse();
        }

        $response = $handler->handle($request);
        $this->builder->compile();
        return $this->builder->injectCSPHeader($response, true);
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

    /**
     * Handle the csp-report
     * Returns true if the request is a report, false otherwise
     *
     * @param ServerRequestInterface $request
     *
     * @return bool
     */
    private function logReport(ServerRequestInterface $request)
    {
        if ($request->getMethod() !== 'POST') {
            return false;
        }

        if (empty($this->reportLogger) || $request->getUri()->getPath() !== $this->reportPath) {
            return false;
        }

        $data = $request->getParsedBody();

        if (!isset($data['csp-report'])) {
            return false;
        }

        $this->reportLogger->error('CSP error', $data);

        return true;
    }
}

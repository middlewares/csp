<?php
declare(strict_types = 1);

namespace Middlewares;

use Middlewares\Utils\Factory;
use ParagonIE\CSPBuilder\CSPBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
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
     */
    public function __construct(CSPBuilder $builder = null)
    {
        $this->builder = $builder ?: self::createBuilder();
    }

    /**
     * Configure the report-uri directive
     */
    public function report(string $path, LoggerInterface $logger): self
    {
        $this->builder->setReportUri($path);
        $this->reportPath = $path;
        $this->reportLogger = $logger;

        return $this;
    }

    /**
     * Process a request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
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
     */
    private static function createBuilder(): CSPBuilder
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
     */
    private function logReport(ServerRequestInterface $request): bool
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

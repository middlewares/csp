<?php

namespace Middlewares\Tests;

use Middlewares\Csp;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use ParagonIE\CSPBuilder\CSPBuilder;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CspTest extends \PHPUnit_Framework_TestCase
{
    public function cspProvider()
    {
        return [
            [
                null,
                "frame-ancestors 'self'; object-src 'self'; script-src 'self'; ",
            ],
            [
                (new CSPBuilder([
                    'default-src' => ['self' => true],
                    'report-uri' => '/csp_violation_reporting_endpoint',
                ]))->enableOldBrowserSupport(),
                "default-src 'self'; report-uri /csp_violation_reporting_endpoint; ",
            ],
        ];
    }

    /**
     * @dataProvider cspProvider
     */
    public function testCsp($cspBuilder, $expected)
    {
        $response = Dispatcher::run([
            new Csp($cspBuilder),
        ]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($expected, $response->getHeaderLine('Content-Security-Policy'));
    }

    public function reportProvider()
    {
        return [
            [
                Factory::createServerRequest([], 'GET', '/'),
                false
            ],
            [
                Factory::createServerRequest([], 'POST', '/csp-report'),
                false
            ],
            [
                Factory::createServerRequest([], 'POST', '/csp-report')
                    ->withParsedBody([
                        'csp-report' => [
                            'blocked-uri' => 'https://other-domain.com/script.js',
                            'document-uri' => 'https://example.com',
                            'effective-directive' => 'script-src',
                        ]
                    ]),
                true
            ]
        ];
    }

    /**
     * @dataProvider reportProvider
     */
    public function testReports(ServerRequestInterface $request, $reported)
    {
        $logs = fopen('php://temp', 'r+');
        $logger = new Logger('test');
        $logger->pushHandler(new StreamHandler($logs));

        $response = Dispatcher::run([
            (new Csp)->report('/csp-report', $logger),
            function () {
                return 'No CSP report';
            }
        ], $request);

        rewind($logs);
        $this->assertInstanceOf(ResponseInterface::class, $response);

        if (!$reported) {
            $this->assertEquals('No CSP report', (string) $response->getBody());
            $this->assertEquals('', stream_get_contents($logs));
        } else {
            $this->assertEquals('', (string) $response->getBody());
            $this->assertRegExp('#.* test.ERROR: CSP error .*#', stream_get_contents($logs));
        }
    }
}

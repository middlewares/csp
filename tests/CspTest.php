<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Csp;
use Middlewares\Utils\Dispatcher;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class CspTest extends TestCase
{
    public function cspProvider()
    {
        return [
            [
                new Csp(),
                "frame-ancestors 'self'; object-src 'self'; script-src 'self'; ",
            ],
            [
                Csp::createFromData([
                    'default-src' => ['self' => true],
                    'report-uri' => '/csp_violation_reporting_endpoint',
                ]),
                "default-src 'self'; report-uri /csp_violation_reporting_endpoint; "
                .'report-to /csp_violation_reporting_endpoint; ',
            ],
            [
                Csp::createFromFile(__DIR__.'/config.json'),
                "default-src 'self'; report-uri /csp_violation_reporting_endpoint; "
                .'report-to /csp_violation_reporting_endpoint; ',
            ],
        ];
    }

    /**
     * @dataProvider cspProvider
     */
    public function testCsp(Csp $csp, string $expected)
    {
        $response = Dispatcher::run([$csp]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($expected, $response->getHeaderLine('Content-Security-Policy'));
        $this->assertEquals($expected, $response->getHeaderLine('X-Content-Security-Policy'));
        $this->assertEquals($expected, $response->getHeaderLine('X-Webkit-CSP'));
    }
}

<?php
declare(strict_types = 1);

namespace Middlewares\Tests;

use Middlewares\Csp;
use Middlewares\Utils\Dispatcher;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class CspTest extends TestCase
{
    /**
     * @return array<array<Csp|string>>
     */
    public function cspProvider(): array
    {
        return [
            [
                new Csp(),
                "frame-ancestors 'self'; object-src 'self'; script-src 'self'",
            ],
            [
                Csp::createFromData([
                    'default-src' => ['self' => true],
                    'report-uri' => '/csp_violation_reporting_endpoint',
                ]),
                "default-src 'self'; report-uri /csp_violation_reporting_endpoint",
            ],
            [
                Csp::createFromFile(__DIR__.'/config.json'),
                "default-src 'self'; report-uri /csp_violation_reporting_endpoint",
            ],
        ];
    }

    /**
     * @dataProvider cspProvider
     */
    public function testCsp(Csp $csp, string $expected): void
    {
        $response = Dispatcher::run([$csp]);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($expected, $response->getHeaderLine('Content-Security-Policy'));
        $this->assertEquals($expected, $response->getHeaderLine('X-Content-Security-Policy'));
        $this->assertEquals($expected, $response->getHeaderLine('X-Webkit-CSP'));
    }

    public function testLegacy(): void
    {
        $response = Dispatcher::run([
            (new Csp())->legacy(false),
        ]);

        $expected = "frame-ancestors 'self'; object-src 'self'; script-src 'self'";

        $this->assertEquals($expected, $response->getHeaderLine('Content-Security-Policy'));
        $this->assertEmpty($response->getHeaderLine('X-Content-Security-Policy'));
        $this->assertEmpty($response->getHeaderLine('X-Webkit-CSP'));
    }
}

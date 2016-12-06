<?php

namespace Middlewares\Tests;

use Middlewares\Csp;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\Factory;
use ParagonIE\CSPBuilder\CSPBuilder;

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

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals($expected, $response->getHeaderLine('Content-Security-Policy'));
    }
}

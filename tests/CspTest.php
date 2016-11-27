<?php

namespace Middlewares\Tests;

use Middlewares\Csp;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\CallableMiddleware;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
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
        $response = (new Dispatcher([
            new Csp($cspBuilder),

            new CallableMiddleware(function () {
                return new Response();
            }),
        ]))->dispatch(new ServerRequest());

        $this->assertInstanceOf('Psr\\Http\\Message\\ResponseInterface', $response);
        $this->assertEquals($expected, $response->getHeaderLine('Content-Security-Policy'));
    }
}

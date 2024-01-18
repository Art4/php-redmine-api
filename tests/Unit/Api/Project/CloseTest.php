<?php

namespace Redmine\Tests\Unit\Api\Project;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Redmine\Api\Project;
use Redmine\Exception\UnexpectedResponseException;
use Redmine\Http\HttpClient;
use Redmine\Http\Response;

/**
 * @covers \Redmine\Api\Project::close
 */
class CloseTest extends TestCase
{
    public function testCloseReturnsResponse()
    {
        $client = $this->createMock(HttpClient::class);
        $client->expects($this->exactly(1))
            ->method('request')
            ->willReturnCallback(function (string $method, string $path, string $body = '') {
                $this->assertSame('PUT', $method);
                $this->assertSame('/projects/5/close.xml', $path);
                $this->assertSame('', $body);

                return $this->createConfiguredMock(
                    Response::class,
                    [
                        'getContentType' => 'application/xml',
                        'getBody' => '',
                    ]
                );
            })
        ;

        $api = new Project($client);

        $this->assertSame('', $api->close(5));
    }

    public function testCloseWithoutIntOrStringThrowsInvalidArgumentException()
    {
        $client = $this->createMock(HttpClient::class);

        $api = new Project($client);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Redmine\Api\Project::close(): Argument #1 ($projectIdentifier) must be of type int or string');

        // Perform the tests
        $api->close(true);
    }
}

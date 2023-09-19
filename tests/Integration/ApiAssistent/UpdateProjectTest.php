<?php

namespace Redmine\Tests\Integration\ApiAssistent;

use PHPUnit\Framework\TestCase;
use Redmine\Api\ApiAssistent;
use Redmine\Tests\Fixtures\MockClient;

class UpdateProjectTest extends TestCase
{
    /**
     * @var MockClient
     */
    private $client;

    public function setup(): void
    {
        $this->client = new MockClient('http://test.local', 'asdf');
    }

    public function testUpdateProjectWithName()
    {
        $res = ApiAssistent::fromClient($this->client)
            ->updateProject(1)
            ->withName('different name')
            ->executeStatement()
        ;

        $response = json_decode($res, true);

        $this->assertEquals('PUT', $response['method']);
        $this->assertEquals('/projects/1.xml', $response['path']);
        $this->assertXmlStringEqualsXmlString(
            <<< XML
            <?xml version="1.0"?>
            <project>
                <id>1</id>
                <name>different name</name>
            </project>
            XML,
            $response['data']
        );
    }
}

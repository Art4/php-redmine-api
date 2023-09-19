<?php

namespace Redmine\Tests\Integration\ApiAssistent;

use PHPUnit\Framework\TestCase;
use Redmine\Api\ApiAssistent;
use Redmine\Tests\Fixtures\MockClient;

class CreateProjectTest extends TestCase
{
    /**
     * @var MockClient
     */
    private $client;

    public function setup(): void
    {
        $this->client = new MockClient('http://test.local', 'asdf');
    }

    public function testCreateProjectWithCustomField()
    {
        $res = ApiAssistent::fromClient($this->client)
            ->createProject('some name', 'the_identifier')
            ->withCustomField(123, 'cf_name', [1, 2, 3], 'string')
            ->executeStatement()
        ;

        $response = json_decode($res, true);

        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/projects.xml', $response['path']);
        $this->assertXmlStringEqualsXmlString(
            <<< XML
            <?xml version="1.0"?>
            <project>
                <name>some name</name>
                <identifier>the_identifier</identifier>
                <custom_fields type="array">
                    <custom_field name="cf_name" field_format="string" id="123" multiple="true">
                        <value type="array">
                            <value>1</value>
                            <value>2</value>
                            <value>3</value>
                        </value>
                    </custom_field>
                </custom_fields>
            </project>
            XML,
            $response['data']
        );
    }
}

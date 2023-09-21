<?php

namespace Redmine\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Redmine\Exception\MissingParameterException;
use Redmine\Tests\Fixtures\MockClient;

class IssueCategoryXmlTest extends TestCase
{
    /**
     * @var MockClient
     */
    private $client;

    public function setup(): void
    {
        $this->client = new MockClient('http://test.local', 'asdf');
    }

    public function testCreateBlank()
    {
        $api = $this->client->getApi('issue_category');
        $this->assertInstanceOf('Redmine\Api\IssueCategory', $api);

        $this->expectException(MissingParameterException::class);
        $this->expectExceptionMessage('Theses parameters are mandatory: `name`');

        $api->create('aProject');
    }

    public function testCreateComplex()
    {
        $api = $this->client->getApi('issue_category');
        $res = $api->create('otherProject', [
            'name' => 'test category',
        ]);
        $response = json_decode($res, true);

        $this->assertEquals('POST', $response['method']);
        $this->assertEquals('/projects/otherProject/issue_categories.xml', $response['path']);
        $this->assertXmlStringEqualsXmlString(
            <<< XML
            <?xml version="1.0"?>
            <issue_category>
                <name>test category</name>
            </issue_category>
            XML,
            $response['data']
        );
    }

    public function testUpdate()
    {
        $api = $this->client->getApi('issue_category');
        $res = $api->update(1, [
            'name' => 'new category name',
        ]);
        $response = json_decode($res, true);

        $this->assertEquals('PUT', $response['method']);
        $this->assertEquals('/issue_categories/1.xml', $response['path']);
        $this->assertXmlStringEqualsXmlString(
            <<< XML
            <?xml version="1.0"?>
            <issue_category>
                <name>new category name</name>
            </issue_category>
            XML,
            $response['data']
        );
    }
}

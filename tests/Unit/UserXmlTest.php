<?php

namespace Redmine\Tests\Unit;

use DOMDocument;
use Exception;
use PHPUnit\Framework\TestCase;
use Redmine\Tests\Fixtures\MockClient as TestClient;
use SimpleXMLElement;

class UserXmlTest extends TestCase
{
    /**
     * @var TestClient
     */
    private $client;

    public function setup(): void
    {
        $this->client = new TestClient('http://test.local', 'asdf');
    }

    public function testCreateBlank()
    {
        $this->expectException(Exception::class);
        $api = $this->client->user;
        $this->assertInstanceOf('Redmine\Api\User', $api);

        $api->create();
    }

    public function testCreateComplex()
    {
        $api = $this->client->user;
        $res = $api->create([
            'login' => 'test',
            'firstname' => 'test',
            'lastname' => 'test',
            'mail' => 'test@example.com',
        ]);

        $xml = '<?xml version="1.0"?>
<user>
    <login>test</login>
    <lastname>test</lastname>
    <firstname>test</firstname>
    <mail>test@example.com</mail>
</user>';
        $this->assertEquals($this->formatXml($xml), $this->formatXml($res['data']));
    }

    public function testUpdate()
    {
        $api = $this->client->user;
        $res = $api->update(1, [
            'firstname' => 'Raul',
        ]);

        $xml = '<?xml version="1.0"?>
<user>
    <id>1</id>
    <firstname>Raul</firstname>
</user>';
        $this->assertEquals($this->formatXml($xml), $this->formatXml($res['data']));
    }

    private function formatXml($xml)
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML((new SimpleXMLElement($xml))->asXML());

        return $dom->saveXML();
    }
}

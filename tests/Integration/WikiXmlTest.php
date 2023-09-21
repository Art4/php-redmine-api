<?php

namespace Redmine\Tests\Integration;

use DOMDocument;
use PHPUnit\Framework\TestCase;
use Redmine\Tests\Fixtures\MockClient;
use SimpleXMLElement;

class WikiXmlTest extends TestCase
{
    /**
     * @var MockClient
     */
    private $client;

    public function setup(): void
    {
        $this->client = new MockClient('http://test.local', 'asdf');
    }

    public function testCreateComplex()
    {
        /** @var \Redmine\Api\Wiki */
        $api = $this->client->getApi('wiki');
        $res = $api->create('testProject', 'about', [
            'text' => 'asdf',
            'comments' => 'asdf',
            'version' => 'asdf',
        ]);
        $res = json_decode($res, true);

        $xml = '<?xml version="1.0"?>
<wiki_page>
    <text>asdf</text>
    <comments>asdf</comments>
    <version>asdf</version>
</wiki_page>';
        $this->assertEquals($this->formatXml($xml), $this->formatXml($res['data']));
    }

    public function testUpdate()
    {
        /** @var \Redmine\Api\Wiki */
        $api = $this->client->getApi('wiki');
        $res = $api->update('testProject', 'about', [
            'text' => 'asdf',
            'comments' => 'asdf',
            'version' => 'asdf',
        ]);
        $res = json_decode($res, true);

        $xml = '<?xml version="1.0"?>
<wiki_page>
    <text>asdf</text>
    <comments>asdf</comments>
    <version>asdf</version>
</wiki_page>';
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

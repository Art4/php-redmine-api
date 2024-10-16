<?php

namespace Redmine\Tests\Unit\Api\Issue;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Redmine\Api\Issue;
use Redmine\Tests\Fixtures\AssertingHttpClient;

#[CoversClass(Issue::class)]
class AttachTest extends TestCase
{
    /**
     * @dataProvider getAttachData
     */
    #[DataProvider('getAttachData')]
    public function testAttachReturnsCorrectResponse($issueId, $parameters, $expectedPath, $expectedBody, $responseCode, $response): void
    {
        $client = AssertingHttpClient::create(
            $this,
            [
                'PUT',
                $expectedPath,
                'application/json',
                $expectedBody,
                $responseCode,
                '',
                $response,
            ],
        );

        // Attach the object under test
        $api = new Issue($client);

        // Perform the tests
        $this->assertSame('', $api->attach($issueId, $parameters));
    }

    public static function getAttachData(): array
    {
        return [
            'test without parameters' => [
                5,
                [],
                '/issues/5.json',
                <<<JSON
                {
                    "issue": {
                        "id": 5,
                        "uploads": [
                            []
                        ]
                    }
                }
                JSON,
                201,
                '',
            ],
            'test with attachment' => [
                5,
                [
                    'token' => 'sample-test-token',
                    'filename' => 'test.txt',
                ],
                '/issues/5.json',
                <<<JSON
                {
                    "issue": {
                        "id": 5,
                        "uploads": [
                            {
                                "filename": "test.txt",
                                "token": "sample-test-token"
                            }
                        ]
                    }
                }
                JSON,
                201,
                '',
            ],
        ];
    }
}

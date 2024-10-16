<?php

declare(strict_types=1);

namespace Redmine\Tests\Unit\Api\Project;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Redmine\Api\Project;
use Redmine\Exception\UnexpectedResponseException;
use Redmine\Http\HttpClient;
use Redmine\Tests\Fixtures\AssertingHttpClient;

#[CoversClass(Project::class)]
class ArchiveTest extends TestCase
{
    public function testArchiveReturnsTrue(): void
    {
        $client = AssertingHttpClient::create(
            $this,
            [
                'PUT',
                '/projects/5/archive.xml',
                'application/xml',
                '',
                204,
            ],
        );

        $api = new Project($client);

        $this->assertTrue($api->archive(5));
    }

    public function testArchiveThrowsUnexpectedResponseException(): void
    {
        $client = AssertingHttpClient::create(
            $this,
            [
                'PUT',
                '/projects/5/archive.xml',
                'application/xml',
                '',
                403,
            ],
        );

        $api = new Project($client);

        $this->expectException(UnexpectedResponseException::class);
        $this->expectExceptionMessage('The Redmine server replied with an unexpected response.');

        $api->archive(5);
    }

    public function testArchiveWithoutIntOrStringThrowsInvalidArgumentException(): void
    {
        $client = $this->createMock(HttpClient::class);

        $api = new Project($client);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Redmine\Api\Project::archive(): Argument #1 ($projectIdentifier) must be of type int or string');

        /** @phpstan-ignore-next-line We are providing an invalid parameter to test the exception */
        $api->archive(true);
    }
}

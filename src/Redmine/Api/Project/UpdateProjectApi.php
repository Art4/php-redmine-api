<?php

namespace Redmine\Api\Project;

use Redmine\Api\Project;
use Redmine\Client\Client;

/**
 * @since Redmine 0.9.1
 *
 * @link https://www.redmine.org/issues/296
 *
 * @internal
 */
final class UpdateProjectApi
{
    public static function createFromId(Client $client, int $id)
    {
        return new self($client, $id);
    }

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $data = [];

    private function __construct(Client $client, string $id)
    {
        $this->client = $client;

        $this->data['id'] = $id;
    }

    public function withName(string $name): self
    {
        $clone = clone($this);

        $clone->data['name'] = $name;

        return $clone;
    }

    public function executeStatement(): mixed
    {
        /** @var Project */
        $api = $this->client->getApi('project');

        return $api->update($this->data['id'], $this->data);
    }
}

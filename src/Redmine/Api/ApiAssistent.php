<?php

namespace Redmine\Api;

use Redmine\Api\Project\CreateProjectApi;
use Redmine\Client\Client;

final class ApiAssistent
{
    public static function fromClient(Client $client): self
    {
        return new self($client);
    }

    /**
     * @var Client
     */
    private $client;

    private function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @since Redmine 0.9.1
     */
    public function createProject(string $name, string $identifier): CreateProjectApi
    {
        return CreateProjectApi::create($this->client, $name, $identifier);
    }
}

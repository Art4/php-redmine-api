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
final class CreateProjectApi
{
    public static function create(Client $client, string $name, string $identifier)
    {
        return new self($client, $name, $identifier);
    }

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $data = [];

    private function __construct(Client $client, string $name, string $identifier)
    {
        $this->client = $client;

        $this->data['name'] = $name;
        $this->data['identifier'] = $identifier;
    }

    public function withCustomField(int $id, string $name, $value, ?string $field_format = null): self
    {
        $clone = clone($this);

        if (! array_key_exists('custom_fields', $clone->data)) {
            $clone->data['custom_fields'] = [];
        }

        $field = [
            'id' => $id,
            'name' => $name,
            'value' => $value,
        ];

        if (is_string($field_format)) {
            $field['field_format'] = $field_format;
        }

        $clone->data['custom_fields'][] = $field;

        return $clone;
    }

    public function withTrackerIds(int ...$ids): self
    {
        $clone = clone($this);

        if (! array_key_exists('tracker_ids', $clone->data)) {
            $clone->data['tracker_ids'] = [];
        }

        $clone->data['tracker_ids'] = array_merge($clone->data['tracker_ids'], $ids);

        return $clone;
    }

    public function executeStatement(): mixed
    {
        /** @var Project */
        $api = $this->client->getApi('project');

        return $api->create($this->data);
    }
}

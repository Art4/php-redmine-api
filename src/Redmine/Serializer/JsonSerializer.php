<?php

namespace Redmine\Serializer;

use JsonException;
use Redmine\Exception\SerializerException;

/**
 * JsonSerializer
 */
final class JsonSerializer
{
    public static function createFromString(string $data): self
    {
        $serializer = new self();
        $serializer->decode($data);

        return $serializer;
    }

    private string $encoded;

    /** @var mixed $normalized */
    private $normalized;

    private function __construct()
    {
        // use factory method instead
    }

    /**
     * @return mixed
     */
    public function getNormalized()
    {
        return $this->normalized;
    }

    private function decode(string $encoded)
    {
        $this->encoded = $encoded;

        try {
            $this->normalized = json_decode(
                $encoded,
                true,
                512,
                \JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new SerializerException(
                'Catched error "' . $e->getMessage() . '" while decoding JSON: ' . $encoded,
                $e->getCode(),
                $e
            );
        }
    }
}

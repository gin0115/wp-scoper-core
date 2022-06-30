<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Patcher\Dictionary;

use JsonSerializable;
use DateTimeImmutable;

class PatcherModel implements JsonSerializable
{
    private string $key;

    private string $name;

    private string $path;

    private string $version;

    private DateTimeImmutable $createdAt;

    /**
     * @param string $key
     * @param string $name
     * @param string $path
     * @param string $version
     * @param DateTimeImmutable|null $createdAt
     * @return PatcherModel
     */
    public function __construct(
        string $key,
        string $name,
        string $path,
        string $version,
        ?DateTimeImmutable $createdAt = null
    ) {
        $this->key = $key;
        $this->name = $name;
        $this->path = $path;
        $this->version = $version;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
    }

    /**
     * Get the patchers unique key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }


    /**
     * Get the patchers name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the patchers Path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get the patchers Version.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Get the date patcher was created.
     *
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
            'path' => $this->path,
            'version' => $this->version,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }
}

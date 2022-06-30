<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Patcher\Dictionary;

use Closure;
use Exception;
use League\Flysystem\Filesystem;
use Gin0115\WpScoper\Helper\Json;
use PinkCrab\FunctionConstructors\Arrays as Arr;
use Gin0115\WpScoper\Patcher\Dictionary\PatcherModel;

class PatcherCacheRepository
{
    private const CACHE_FILENAME = 'patcher-records.json';

    private string $path;

    private Filesystem $filesystem;

    private Json $json;

    /** @var PatcherModel[] */
    private array $internalCache = [];

    public function __construct(string $path, Filesystem $filesystem, Json $json)
    {
        $this->path = $path;
        $this->filesystem = $filesystem;
        $this->json = $json;

        $this->load();
    }

    /**
     * Update the internal cache from filesystem.
     *
     * @return null
     */
    public function load(): void
    {
        $file = $this->filesystem->read($this->getInternalCachePath());
        $this->internalCache = array_map([$this, 'mapJsonToPatcherModel'], $this->json->decode($file, true)) ?? [];
    }

    private function mapJsonToPatcherModel(array $json): PatcherModel
    {
        return new PatcherModel(
            $json['key'],
            $json['name'],
            $json['path'],
            $json['version'],
            new \DateTimeImmutable($json['created_at'])
        );
    }

    /**
     * Get the internal cache file path
     *
     * @return string
     */
    private function getInternalCachePath(): string
    {
        return $this->path . \DIRECTORY_SEPARATOR . self::CACHE_FILENAME;
    }

    /**
     * Adds a patcher to the internal cache.
     *
     * @param PatcherModel $patcher
     * @return self
     */
    public function add(PatcherModel $patcher): self
    {
        $this->internalCache[$patcher->getKey()] = $patcher;
        return $this;
    }

    /**
     * Removes a patcher from the internal cache.
     *
     * @param string $key
     * @return self
     */
    public function remove(string $key): self
    {
        unset($this->internalCache[$key]);
        return $this;
    }

    /**
     * Get a patcher from the internal cache.
     *
     * @param string $key
     * @return PatcherModel|null
     *
     * @throws \Exception
     */
    public function get(string $key): ?PatcherModel
    {
        if (isset($this->internalCache[$key])) {
            return $this->internalCache[$key];
        }

        throw new Exception("Patcher {$key} not found");
    }

    /**
     * Filters the internal cache by a given closure.
     *
     * @param \Closure $callback
     * @return array
     */
    public function filter(Closure $callback): array
    {
        return array_filter($this->internalCache, $callback);
    }

    /**
     * Get all patchers from the internal cache.
     *
     * @return PatcherModel[]
     */
    public function getAll(): array
    {
        return Arr\usort(function (PatcherModel $a, PatcherModel $b) {
            return strcmp($a->getName(), $b->getName()) ?: strcmp($a->getVersion(), $b->getVersion());
        })($this->internalCache);
    }

    /**
     * Save the internal cache to the filesystem.
     * @return self
     */
    public function save(): self
    {
        $this->filesystem->write($this->getInternalCachePath(), $this->json->encode($this->internalCache));
        return $this;
    }
}

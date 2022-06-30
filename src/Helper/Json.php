<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\Helper;

class Json
{
    /**
     * Json Encode Wrapper
     *
     * @param mixed $data
     * @param int $flags
     * @param int $depth
     *
     * @return string|null
     */
    public function encode($data, int $flags = 0, int $depth = 512): ?string
    {
        $value = \json_encode($data, $flags, $depth);

        return $value ?: null;
    }

    /**
     * Json Decode Wrapper
     *
     * @param string $json
     * @param bool $assoc
     * @param int $depth
     * @param int $options
     *
     * @return mixed|null
     */
    public function decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
    {
        $value = \json_decode($json, $assoc, $depth, $options);

        return false !== $value ? $value : null;
    }
}

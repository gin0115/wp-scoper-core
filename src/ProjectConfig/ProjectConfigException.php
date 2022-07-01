<?php

declare(strict_types=1);

namespace Gin0115\WpScoper\ProjectConfig;

class ProjectConfigException extends \Exception
{
    /**
     * Exception for when the config file is not found.
     *
     * @param string $projectPath
     * @return self
     */
    public static function missingConfigFile(string $projectPath): self
    {
        return new self(sprintf('Missing config file in project path: %s', $projectPath));
    }

    /**
     * Exception for when the config file is invalid
     *
     * @param string $message
     * @return self
     */
    public static function invalidConfigFile(string $message): self
    {
        return new self(sprintf('Invalid config: %s', $message));
    }
}

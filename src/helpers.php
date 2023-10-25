<?php

/**
 * Get an env variable.
 */
function env(string $key, $default = null): ?string
{
    return allEnv()[$key] ?? $default;
}

/**
 * Get all env variables.
 * @return array<string, string>
 */
function allEnv(): array
{
    static $env = null;

    $env ??= [...getenv(), ...$_ENV];

    return $env;
}

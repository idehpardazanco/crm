<?php

namespace Modules\Settings\app\Services;

use Modules\Settings\Repositories\SettingRepository;

/**
 * Central settings service
 */
class SettingService
{
    public function __construct(
        private SettingRepository $repo
    ) {}

    public function get(string $key, $default = null)
    {
        return $this->repo->get($key, $default);
    }

    public function set(string $key, $value, string $type = 'string'): void
    {
        $this->repo->set($key, $value, $type);
    }

    public function all(): array
    {
        return $this->repo->all();
    }
}
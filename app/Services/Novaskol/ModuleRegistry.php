<?php

namespace App\Services\Novaskol;

class ModuleRegistry
{
    public function all(): array
    {
        return config('novaskol.modules', []);
    }

    public function navigable(): array
    {
        return array_filter($this->all(), fn (array $module) => empty($module['section']));
    }
}

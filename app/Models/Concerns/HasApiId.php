<?php

namespace App\Models\Concerns;

trait HasApiId
{
    public function getIdAttribute(): int|string|null
    {
        return $this->getKey();
    }
}

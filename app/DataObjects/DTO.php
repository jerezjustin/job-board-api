<?php

namespace App\DataObjects;

interface DTO
{
    public static function fromArray(array $attributes): self;

    public function toArray(): array;
}

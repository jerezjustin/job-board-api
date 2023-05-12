<?php

namespace App\DataObjects;

readonly class User implements DTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        return new self(
            name: data_get($attributes, 'name'),
            email: data_get($attributes, 'email'),
            password: data_get($attributes, 'password')
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}

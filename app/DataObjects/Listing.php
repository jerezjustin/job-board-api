<?php

namespace App\DataObjects;

readonly class Listing implements DTO
{
    public function __construct(
        public string  $title,
        public string  $company,
        public string  $location,
        public string  $content,
        public string  $applyLink,
        public ?string $logo = null,
        public ?bool   $highlighted = false,
        public ?bool   $active = true,
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        return new self(
            title: data_get($attributes, 'title'),
            company: data_get($attributes, 'company'),
            location: data_get($attributes, 'location'),
            content: data_get($attributes, 'content'),
            applyLink: data_get($attributes, 'apply_link'),
            logo: data_get($attributes, 'logo'),
            highlighted: data_get($attributes, 'is_highlighted', false),
            active: data_get($attributes, 'is_active', true),
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'company' => $this->company,
            'location' => $this->location,
            'content' => $this->content,
            'apply_link' => $this->applyLink,
            'logo' => $this->logo,
            'is_highlighted' => $this->highlighted,
            'is_active' => $this->active
        ];
    }
}

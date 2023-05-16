<?php

namespace App\Actions;

use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CreateTags
{
    public function handle(array|string $tags): \Illuminate\Support\Collection
    {
        if (is_string($tags)) {
            return $this->create(explode(',', $tags));
        }

        return $this->create($tags);
    }

    protected function create(array $tags): \Illuminate\Support\Collection
    {
        $tags = new Collection();

        foreach ($tags as $tag) {
            $tags->push(
                Tag::firstOrCreate([
                    'slug' => Str::slug(trim($tag))
                ], [
                    'name' => ucwords(trim($tag))
                ])
            );
        }

        return $tags;
    }
}

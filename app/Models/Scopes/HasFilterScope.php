<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait HasFilterScope
{
    public function scopeFilter(Builder $query)
    {
        $query->when(request()->get('tags'), function (Builder $query, $tags) {
            if (is_string($tags)) {
                $tags = explode(',', $tags);
            }

            $query->where(function (Builder $query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->orWhereHas('tags', function (Builder $query) use ($tag) {
                        $query->Where('name', 'like', '%' . trim($tag) . '%');
                    });
                }
            });
        });

        $query->when(request()->get('title'), function (Builder $query, $title) {
            $query->where('title', 'like', '%' . trim($title) . '%');
        });

        $query->when(request()->get('min_salary'), function (Builder $query, $minSalary) {
            $query->where('min_salary', '>=', $minSalary);
        });

        $query->when(request()->get('max_salary'), function (Builder $query, $maxSalary) {
            $query->where('max_salary', '>=', $maxSalary);
        });

        $query->when(request()->get('salary'), function (Builder $query, $salary) {
            $query->where('salary', '>=', $salary);
        });

        $query->when(request()->get('company'), function (Builder $query, $company) {
            $query->where('company', 'like', '%' . trim($company) . '%');
        });

        $query->when(request('location'), function (Builder $query, $location) {
            $query->where('location', 'like', '%' . trim($location) . '%');
        });
    }
}

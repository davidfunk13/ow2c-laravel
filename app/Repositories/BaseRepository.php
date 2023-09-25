<?php
declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class BaseRepository
{
    protected function handleSort(Builder $qb, array $options): void
    {
        if (isset($options['sortBy']) && !empty($options['sortBy'])) {
            if (str_contains($options['sortBy'], '.')) {
                $fields = explode('.', $options['sortBy']);
                $sortDir = $options['sortDirection'] ?? 'asc';
                $subjectTableName = $qb->getModel()->getTable();
                $relatedTableName = $qb->getRelation($fields[0])->getModel()->getTable();
                $relatedTableColumn = Str::singular($relatedTableName);

                $qb->leftJoin(
                    $relatedTableName,
                    "$subjectTableName.{$relatedTableColumn}_id",
                    '=',
                    "$relatedTableName.id"
                )->orderBy("$relatedTableName.$fields[1]", $sortDir);

                return;
            }

            $qb->orderBy($options['sortBy'], $options['sortDirection'] ?? 'asc');
        }
    }

    protected function handleRelations(Builder $qb, array $relations, array $options): void
    {
        if (isset($options['with']) && is_array($options['with']) && count($options['with']) > 0) {
            $relations = array_merge($relations, $options['with']);
        }

        $qb->with($relations);
    }
}

<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * @property int $user_id
 * @property int $id
 * @property boolean $system
 * @property string $auto_update
 * @property boolean public
 * @method static ListModel findOrFail($id, $columns = ['*'])
 */
class ListModel extends Model
{
    protected $table = 'lists';
    protected $guarded = ['id'];
    protected $hidden = ['pivot'];
    protected $casts = [
        'id' => 'integer',
        'system' => 'boolean',
        'public' => 'boolean',
        'user_id' => 'integer'
    ];

    /**
     * @param array $options
     * @return Collection
     */
    public function getItems($options = [])
    {
        $items = collect();

        $pivot = app(Listable::class)
            ->where('list_id', $this->id)
            ->limit(Arr::get($options, 'limit', 500))
            ->orderBy('order', 'asc')
            ->get();

        if ($pivot->isNotEmpty()) {
            $items = $pivot
                ->groupBy('listable_type')
                ->map(function(Collection $records, $model) {
                    $select = ['id', 'name', 'poster'];
                    if ($model === Title::class) {
                        $select = array_merge($select, [
                            'is_series',
                            'year',
                            'tmdb_vote_average',
                            'backdrop',
                            'description',
                            'runtime',
                            'release_date',
                            'popularity'
                        ]);
                    }

                    $items = app($model)->whereIn('id', $records->pluck('listable_id'))->get($select);

                    if ($model === Title::class) {
                        $items->load(['genres', 'videos' => function(HasMany $query) use($items) {
                            $query->where('type', '!=', 'external')
                                ->groupBy('title_id')
                                ->limit($items->count());
                        }]);
                    }

                    return $items->map(function($item) use($records) {
                        $pivot = $records->first(function($record) use($item) {
                            return $record->listable_id === $item->id && $record->listable_type === get_class($item);
                        })->toArray();

                        $item['pivot'] = [
                            'id' => $pivot['id'],
                            'order' => $pivot['order'],
                            'created_at' => $pivot['created_at'],
                        ];
                        return $item;
                    });
                })->flatten();

            $items = $items->sortBy('pivot.order')->values();
        }

        if (Arr::get($options, 'minimal')) {
            $items = $items->map(function($item) {
                return ['id' => $item->id, 'type' => $item->type];
            });
        }

        return $items;
    }

    /**
     * @param array $items
     */
    public function attachItems($items)
    {
        if (empty($items)) return;

        $listables = collect($items)->map(function($item, $key) {
            return [
                'list_id' => $this->id,
                'listable_id' => $item['id'],
                'listable_type' => $this->getListableType($item['type']),
                'created_at' => Carbon::now(),
                'order' => $key
            ];
        });

        app(Listable::class)->insert($listables->toArray());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->morphedByMany(Title::class, 'listable', null, 'list_id');
    }

    /**
     * @param string $type
     * @return string
     */
    public function getListableType($type)
    {
        switch ($type) {
            case Title::MOVIE_TYPE:
            case Title::SERIES_TYPE:
            case Title::TITLE_TYPE:
                return Title::class;
            case Person::PERSON_TYPE;
                return Person::class;
            case Episode::EPISODE_TYPE:
                return Episode::class;
        }
    }
}

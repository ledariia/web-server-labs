<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    public function collections()
    {
        $result = [];

        /**
         * @var \Illuminate\Database\Eloquent\Collection $eloquentCollection
         */
        $eloquentCollection = BlogPost::withTrashed()->get();

        // dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray()); // Крок 1 (Закоментовано)

        /**
         * @var \Illuminate\Support\Collection $collection
         */
        $collection = collect($eloquentCollection->toArray());

        // dd(get_class($eloquentCollection), get_class($collection), $collection); // Крок 2 (Закоментовано)

        $result['first'] = $collection->first();
        $result['last'] = $collection->last();

        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->values()
            ->keyBy('id');

        $result['where']['count'] = $result['where']['data']->count();
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        if ($result['where']['data']->isNotEmpty()) {
            //
        }

        $result['where_first'] = $collection
            ->firstWhere('created_at', '>' , '2020-02-24 03:46:16');

        $result['map']['all'] = $collection->map(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);

            return $newItem;
        });

        $result['map']['not_exists'] = $result['map']['all']->where('exists', '=', false)->values()->keyBy('item_id');

        // КРОК 3: Зараз працює цей (виводить масив $result)
        dd($result);

        $collection->transform(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });

        // dd($collection); // Крок 4

        $newItem = new \stdClass;
        $newItem->id = 9999;

        $newItem2 = new \stdClass;
        $newItem2->id = 8888;

        // dd($newItem, $newItem2); // Крок 5

        $newItemFirst = $collection->prepend($newItem)->first();
        $newItemLast = $collection->push($newItem2)->last();
        $pulledItem = $collection->pull(1);

        // dd(compact('collection', 'newItemFirst' , 'newItemLast', 'pulledItem')); // Крок 6

        $filtered = $collection->filter(function ($item) {
            if (!isset($item->created_at)) {
                return false;
            }
            $byDay = $item->created_at->isFriday();
            $byDate = $item->created_at->day == 11;

            return $byDay && $byDate;
        });

        // dd(compact('filtered')); // Крок 7

        $sortedSimpleCollection = collect([5, 3, 1, 2, 4])->sort()->values();
        $sortedAscCollection = $collection->sortBy('created_at');
        $sortedDescCollection = $collection->sortByDesc('item_id');

        // dd(compact('sortedSimpleCollection', 'sortedAscCollection', 'sortedDescCollection')); // Крок 8
    }
}


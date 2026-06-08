<?php

namespace App\Http\Controllers\Icons;

use App\Data\IconData;
use App\Http\Controllers\Controller;
use App\Models\Icon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SearchIconControllerJson extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $queryBuilder = Icon::query()->when($request->input('search'), function (Builder $query) use ($request) {
            $query->whereAny([
                'name',
                'set',
                'path',
                'disk',
            ], 'like', '%'.$request->input('search').'%');
        });

        $selected = $this->getSelectedItem($request);

        // PERFORMANCE FIX: Apply the limit(25) directly to the SQL query, not after fetching all records
        $items = $queryBuilder->limit(25)->get();

        if ($selected) {
            $items->contains('name', $selected->name) ?: $items->add($selected);
        }

        // Return JSON transformed through Spatie Laravel Data
        return response()->json(IconData::collect($items)->sortBy('name')->values());
    }

    private function getSelectedItem(Request $request): ?Icon
    {
        if ($request->input('selected_key') && $request->input('selected_value') && in_array(strtolower($request->get('selected_key')), Schema::getColumnListing((new Icon)->getTable()), true)) {
            if (! $request->input('search') || $request->input('search') === '') {
                $selectedKey = $request->input('selected_key');
                $selectedValue = $request->input('selected_value');
                return Icon::where($selectedKey, $selectedValue)->first();
            }
        }

        return null;
    }
}

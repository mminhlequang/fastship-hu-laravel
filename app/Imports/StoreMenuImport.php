<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StoreMenuImport implements ToCollection, WithHeadingRow
{
    protected $storeId;

    public function __construct($storeId)
    {
        $this->storeId = $storeId;
    }

    public function collection(Collection $rows)
    {
        $categoryIds = [];


        foreach ($rows as $row) {
            if (isset($row['selected']) && $row['selected'] != NULL) {
                $categoryId = $row['id'] ?? null;

                if ($categoryId && Category::where('id', $categoryId)->exists()) {
                    $categoryIds[] = $categoryId;
                }
            }
        }


        $store = Store::find($this->storeId);
        if ($store) {
            $store->categories()->syncWithoutDetaching($categoryIds);
        }

        if (count($categoryIds) === 0) {
            throw new \Exception('No categories are selected for import.');
        }
    }

}


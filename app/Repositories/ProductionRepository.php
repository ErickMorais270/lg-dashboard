<?php

namespace App\Repositories;

use App\Production;
use Illuminate\Support\Collection;

class ProductionRepository
{
    /**
     * Get production efficiency data for January 2026.
     * Aggregates by product line (sum of produced, sum of defects).
     *
     * @param string|null $productLine Filter by product line or null for all
     * @return Collection
     */
    public function getEfficiencyByMonth(?string $productLine = null): Collection
    {
        $query = Production::query()
            ->selectRaw('
                product_line,
                SUM(produced_quantity) as produced_quantity,
                SUM(defect_quantity) as defect_quantity
            ')
            ->whereYear('production_date', 2026)
            ->whereMonth('production_date', 1)
            ->groupBy('product_line');

        if ($productLine) {
            $query->where('product_line', $productLine);
        }

        return $query->get();
    }

    /**
     * Check if any production data exists for January 2026.
     */
    public function hasJanuaryData(): bool
    {
        return Production::query()
            ->whereYear('production_date', 2026)
            ->whereMonth('production_date', 1)
            ->exists();
    }
}

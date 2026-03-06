<?php

namespace App\Services;

use App\Production;
use App\Repositories\ProductionRepository;
use Illuminate\Support\Collection;

class DashboardService
{
    /** @var ProductionRepository */
    protected $repository;

    public function __construct(ProductionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Calculate efficiency percentage.
     * Efficiency = (produced - defects) / produced * 100
     */
    public function calculateEfficiency(int $produced, int $defects): float
    {
        if ($produced <= 0) {
            return 0.0;
        }

        return round((($produced - $defects) / $produced) * 100, 2);
    }

    /**
     * Get dashboard data with efficiency for January 2026.
     *
     * @param string|null $productLine Filter by product line
     * @return Collection
     */
    public function getDashboardData(?string $productLine = null): Collection
    {
        $items = $this->repository->getEfficiencyByMonth($productLine);

        return $items->map(function ($item) {
            return [
                'product_line'      => $item->product_line,
                'product_line_label'=> Production::getLabel($item->product_line),
                'produced_quantity' => (int) $item->produced_quantity,
                'defect_quantity'   => (int) $item->defect_quantity,
                'efficiency'        => $this->calculateEfficiency(
                    (int) $item->produced_quantity,
                    (int) $item->defect_quantity
                ),
            ];
        });
    }

    /**
     * Get available product lines for filter.
     */
    public function getProductLines(): array
    {
        return Production::LINES;
    }

    /**
     * Validate if product line is valid.
     */
    public function isValidProductLine(?string $line): bool
    {
        if ($line === null) {
            return true;
        }

        return in_array($line, Production::LINES, true);
    }
}

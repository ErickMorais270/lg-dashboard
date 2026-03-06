<?php

use App\Production;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Seed productions for January 2026.
     *
     * @return void
     */
    public function run()
    {
        $lines = Production::LINES;
        $startDate = \Carbon\Carbon::create(2026, 1, 1);
        $endDate = \Carbon\Carbon::create(2026, 1, 31);

        foreach ($lines as $line) {
            $this->seedLine($line, $startDate, $endDate);
        }
    }

    /**
     * Seed production data for a single product line.
     */
    private function seedLine(string $productLine, $startDate, $endDate): void
    {
        $baseProduced = $this->getBaseProduced($productLine);

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Skip weekends (optional - simulates real factory)
            if ($date->isWeekend()) {
                continue;
            }

            $variation = rand(-50, 80);
            $produced = max(50, $baseProduced + $variation);
            $defectRate = rand(1, 5) / 100; // 1% to 5% defect rate
            $defects = (int) round($produced * $defectRate);

            Production::create([
                'product_line'      => $productLine,
                'produced_quantity' => $produced,
                'defect_quantity'   => $defects,
                'production_date'   => $date->format('Y-m-d'),
            ]);
        }
    }

    /**
     * Base daily production per product line (varies by complexity).
     */
    private function getBaseProduced(string $productLine): int
    {
        $bases = [
            Production::REFRIGERATOR     => 35,
            Production::WASHING_MACHINE  => 40,
            Production::TV               => 45,
            Production::AIR_CONDITIONER  => 30,
        ];

        return $bases[$productLine] ?? 35;
    }
}

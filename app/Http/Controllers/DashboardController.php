<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /** @var DashboardService */
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the production efficiency dashboard.
     */
    public function index(Request $request): View
    {
        $productLine = $request->query('line');

        if (!$this->dashboardService->isValidProductLine($productLine)) {
            $productLine = null;
        }

        $data = $this->dashboardService->getDashboardData($productLine);
        $productLines = $this->dashboardService->getProductLines();

        return view('dashboard.index', [
            'data'         => $data,
            'productLines' => $productLines,
            'activeLine'   => $productLine,
        ]);
    }

    /**
     * Export dashboard data as CSV (respects line filter).
     */
    public function export(Request $request): StreamedResponse
    {
        $productLine = $request->query('line');

        if (!$this->dashboardService->isValidProductLine($productLine)) {
            $productLine = null;
        }

        $data = $this->dashboardService->getDashboardData($productLine);

        $filename = 'eficiencia-planta-a-jan2026'
            . ($productLine ? '-' . str_replace('_', '-', $productLine) : '')
            . '.csv';

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM for Excel
            fputcsv($handle, ['Linha do Produto', 'Quantidade Produzida', 'Quantidade de Defeitos', 'Eficiência (%)'], ';');

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row['product_line_label'],
                    $row['produced_quantity'],
                    $row['defect_quantity'],
                    number_format($row['efficiency'], 1, ',', '') . '%',
                ], ';');
            }
            
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    /**
     * Product lines from Plant A.
     */
    const REFRIGERATOR = 'refrigerator';
    const WASHING_MACHINE = 'washing_machine';
    const TV = 'tv';
    const AIR_CONDITIONER = 'air_conditioner';

    /**
     * All product lines.
     */
    const LINES = [
        self::REFRIGERATOR,
        self::WASHING_MACHINE,
        self::TV,
        self::AIR_CONDITIONER,
    ];

    /**
     * Display labels for product lines (Portuguese).
     */
    const LABELS = [
        self::REFRIGERATOR      => 'Geladeira',
        self::WASHING_MACHINE   => 'Máquina de Lavar',
        self::TV                => 'TV',
        self::AIR_CONDITIONER   => 'Ar-Condicionado',
    ];

    protected $fillable = [
        'product_line',
        'produced_quantity',
        'defect_quantity',
        'production_date',
    ];

    protected $casts = [
        'production_date' => 'date',
        'produced_quantity' => 'integer',
        'defect_quantity' => 'integer',
    ];

    /**
     * Get display label for a product line.
     */
    public static function getLabel(string $productLine): string
    {
        return self::LABELS[$productLine] ?? $productLine;
    }
}

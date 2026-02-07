<?php

namespace Modules\Product\Services;

use Illuminate\Support\Facades\Storage;
use Modules\Product\Models\Product;

class ProductImportService
{
    public function importFromCsv(string $path, int $supplierId): array
    {
        $content = Storage::get($path);
        $lines   = array_map('str_getcsv', explode("\n", $content));

        $header = array_shift($lines);

        $created = 0;
        $errors  = 0;

        foreach ($lines as $line) {
            if (count($line) < 4) {
                $errors++;
                continue;
            }

            [$reference, $name, $color, $price] = $line;

            try {
                Product::updateOrCreate(
                    [
                        'supplier_id' => $supplierId,
                        'reference'   => $reference ?: null,
                    ],
                    [
                        'name'  => $name,
                        'color' => $color ?: null,
                        'price' => (float) $price,
                    ]
                );

                $created++;
            } catch (\Throwable $e) {
                $errors++;
            }
        }
        $this->clearSupplierCache($supplierId);
        return [
            'created' => $created,
            'errors'  => $errors,
        ];
    }
}

<?php

namespace Modules\Product\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Services\ProductImportService;
use Modules\Product\Mail\ProductImportFinishedMail;
use Modules\User\Models\User;

class ImportProductsFromCsvJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $path,
        protected int $supplierId,
        protected User $user
    ) {}

    public function handle(ProductImportService $service): void
    {
        $result = $service->importFromCsv(
            $this->path,
            $this->supplierId
        );

        Mail::to($this->user->email)
            ->send(new ProductImportFinishedMail($result));

        Storage::delete($this->path);
    }
}

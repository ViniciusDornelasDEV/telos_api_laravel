<?php

namespace Modules\Product\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;

class ProductImportFinishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $result
    ) {}

    public function build()
    {
        return $this
            ->subject('📦 Importação de produtos finalizada')
            ->view('product::emails.import-finished');
    }
}

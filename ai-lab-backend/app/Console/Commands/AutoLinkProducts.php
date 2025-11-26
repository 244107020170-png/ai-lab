<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ResearchProduct;
use Illuminate\Support\Facades\Storage;

class AutoLinkProducts extends Command
{
    protected $signature = 'products:autolink';

    protected $description = 'Automatically link images in storage/products to research_products table';

    public function handle()
    {
        $files = Storage::disk('public')->files('products');

        foreach ($files as $file) {
            $filename = basename($file);
            $nameNoExt = pathinfo($filename, PATHINFO_FILENAME);

            // cari produk yang nama titlenya mirip filename
            $product = ResearchProduct::where('title', 'ILIKE', "%$nameNoExt%")->first();

            if ($product) {
                $product->image = "/storage/" . $file;
                $product->save();

                $this->info("Linked: $filename → Product ID $product->id");
            } else {
                $this->warn("NO MATCH for: $filename");
            }
        }

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\WoocommerceProduct;
use App\Models\WoocommerceProductVariation;
use Automattic\WooCommerce\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Saloon\XmlWrangler\XmlReader;
use Throwable;

class SyncProducts extends Command
{
    protected $signature = 'products:sync {--prune : Delete local records that no longer exist in the source APIs (only after a successful sync).}';

    protected $description = 'Sync products from the Innvoice API and WooCommerce store. Idempotent — safe to run on a schedule.';

    /** @var array<int,string> */
    private array $innvoiceSkus = [];

    /** @var array<int,int> */
    private array $woocommerceProductIds = [];

    /** @var array<int,int> */
    private array $woocommerceVariationIds = [];

    public function handle(): int
    {
        $innvoice = config('services.innvoice');
        $woocommerce = config('services.woocommerce');

        if (empty($innvoice['user']) || empty($innvoice['password'])) {
            $this->error('Innvoice credentials missing. Set INNVOICE_USER and INNVOICE_PASSWORD in .env.');

            return self::FAILURE;
        }

        if (empty($woocommerce['url']) || empty($woocommerce['key']) || empty($woocommerce['secret'])) {
            $this->error('WooCommerce credentials missing. Set WOOCOMMERCE_URL, WOOCOMMERCE_KEY, WOOCOMMERCE_SECRET in .env.');

            return self::FAILURE;
        }

        try {
            $this->syncInnvoice($innvoice);
            $this->syncWoocommerceProducts($woocommerce);
            $this->syncWoocommerceVariations($woocommerce);

            if ($this->option('prune')) {
                $this->prune();
            }
        } catch (Throwable $e) {
            $this->error('Sync failed: '.$e->getMessage());
            Log::error('products:sync — exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return self::FAILURE;
        }

        $this->info('Sync complete.');

        return self::SUCCESS;
    }

    private function syncInnvoice(array $innvoice): void
    {
        $url = rtrim($innvoice['url'], '/').'/'.$innvoice['user'].'/product';

        $this->info('Fetching products from Innvoice...');
        $response = Http::withBasicAuth($innvoice['user'], $innvoice['password'])
            ->timeout(120)
            ->get($url);

        if ($response->failed()) {
            Log::error('products:sync — Innvoice request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Innvoice request failed with status '.$response->status());
        }

        $reader = XmlReader::fromString($response->body());
        $products = $reader->values()['response']['product'] ?? [];

        $this->info(sprintf('Got %d Innvoice products.', count($products)));

        foreach ($products as $product) {
            $sku = $this->clearString($product['CikkSzam'] ?? '');
            if ($sku === '') {
                continue;
            }

            $stocks = $product['Keszletek']['Keszlet'] ?? [];
            $storage = (int) ($stocks[0]['Raktar_Keszlet'] ?? 0);
            $storage += (int) ($stocks[1]['Raktar_Keszlet'] ?? 0);

            $prices = $product['Arak'] ?? [];

            Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'nev' => $this->clearString($product['Nev'] ?? ''),
                    'ean' => $this->clearString($product['EAN'] ?? ''),
                    'price' => $this->clearString($prices[0]['Arcsoport_Ar'] ?? ''),
                    'price_kivitelezok' => $this->clearString($prices[6]['Arcsoport_Ar'] ?? ''),
                    'price_kp_elore_harminc' => $this->clearString($prices[4]['Arcsoport_Ar'] ?? ''),
                    'price_kp_elore_huszonot' => $this->clearString($prices[3]['Arcsoport_Ar'] ?? ''),
                    'storage' => $storage,
                ],
            );

            $this->innvoiceSkus[] = $sku;
        }
    }

    private function syncWoocommerceProducts(array $woocommerce): void
    {
        $client = $this->woocommerceClient($woocommerce);

        $totalProducts = (int) $client->get('products/count')->count;
        $pages = max(1, (int) ceil($totalProducts / 100));

        $this->info(sprintf('WooCommerce reports %d products across %d pages.', $totalProducts, $pages));

        for ($page = 1; $page <= $pages; $page++) {
            $remoteProducts = $client->get('products', ['per_page' => 100, 'page' => $page]);
            foreach ($remoteProducts as $product) {
                WoocommerceProduct::updateOrCreate(
                    ['wordpress_id' => $product->id],
                    [
                        'name' => $product->name,
                        'sku' => $product->sku,
                    ],
                );
                $this->woocommerceProductIds[] = (int) $product->id;
            }
        }
    }

    private function syncWoocommerceVariations(array $woocommerce): void
    {
        $client = $this->woocommerceClient($woocommerce);

        $this->info('Syncing WooCommerce variations...');

        WoocommerceProduct::query()->chunkById(200, function ($parents) use ($client) {
            foreach ($parents as $parent) {
                $variations = $client->get(
                    'products/'.$parent->wordpress_id.'/variations',
                    ['per_page' => 50],
                );

                foreach ($variations as $variation) {
                    $internal = Product::whereSku($variation->sku)->first();
                    $parent->woocommerceProductVariation()->updateOrCreate(
                        ['wordpress_id' => $variation->id],
                        [
                            'product_id' => $internal?->id,
                            'name' => $variation->name,
                            'sku' => $variation->sku,
                        ],
                    );
                    $this->woocommerceVariationIds[] = (int) $variation->id;
                }
            }
        });
    }

    private function prune(): void
    {
        $this->info('Pruning records not present in source APIs...');

        $deletedProducts = Product::query()
            ->whereNotIn('sku', $this->innvoiceSkus)
            ->delete();

        $deletedWoo = WoocommerceProduct::query()
            ->whereNotIn('wordpress_id', $this->woocommerceProductIds)
            ->delete();

        $deletedVariations = WoocommerceProductVariation::query()
            ->whereNotIn('wordpress_id', $this->woocommerceVariationIds)
            ->delete();

        $this->info(sprintf(
            'Pruned: %d products, %d woocommerce products, %d variations.',
            $deletedProducts,
            $deletedWoo,
            $deletedVariations,
        ));
    }

    private function woocommerceClient(array $config): Client
    {
        return new Client(
            $config['url'],
            $config['key'],
            $config['secret'],
            ['version' => 'wc/v3'],
        );
    }

    private function clearString(mixed $value): string
    {
        $string = is_scalar($value) ? (string) $value : '';

        return trim((string) preg_replace('/\s+/', ' ', $string));
    }
}

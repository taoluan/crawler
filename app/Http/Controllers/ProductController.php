<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Services\LazadaService;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as STATUS_CODE;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(string $store)
    {
        return Inertia::render('Home');
    }

    public function crawlDataLazada(string $store)
    {
        DB::beginTransaction();
        try {
            $lazada = new LazadaService();
            $lazada->setStore($store);
            $data = $lazada->crawlProducts();
            if (!$data) {
                return response()->json([
                    "status"  => false,
                    'message' => "crawl data fail",
                ]);
            }

            $listProducts = $data->mods->listItems ?? [];
            if (empty($listProducts)) {
                return response()->json([
                    "status"  => false,
                    'message' => "Product is empty",
                ]);
            }

            $mainInfo       = $data?->mainInfo;
            $totalResults   = $mainInfo?->totalResults;
            $pageSize       = $mainInfo?->pageSize;
            $totalPage      = ceil($totalResults / $pageSize);
            $filterProducts = [];
            $newStore       = Store::firstOrCreate([
                "uuid"     => $listProducts[0]->brandId,
                "name"     => $listProducts[0]->brandName,
                "nameRoot" => $store
            ]);
            if (!$newStore->wasRecentlyCreated) {
                return response()->json([
                    "status"  => true,
                    'message' => "Saved",
                ]);
            }
            for ($page = 1; $page <= $totalPage; $page++) {
                if ($page !== 1) {
                    $data         = $lazada->crawlProducts($page);
                    $listProducts = $data->mods->listItems ?? [];
                }
                foreach ($listProducts as $product) {
                    $filterProducts[] = [
                        "storeId"         => $newStore->id,
                        "name"            => $product->name,
                        "price"           => $product->price,
                        "nid"             => $product->nid,
                        "image"           => $product->image,
                        "ratingScore"     => $product->ratingScore,
                        "originalPrice"   => $product->originalPrice ?? 0,
                        "itemSoldCntShow" => $product->itemSoldCntShow ?? "",
                        "location"        => $product->location,
                        "discount"        => $product->discount ?? ""
                    ];
                }
            }
            Product::insert($filterProducts);
            DB::commit();
            return response()->json([
                "status" => true,
                "data"   => $filterProducts,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "status"  => false,
                'message' => "crawl data fail",
            ], STATUS_CODE::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getProducts(string $store)
    {
        try {
            $store = Store::where("nameRoot", $store)->first();
            if (empty($store)) {
                return response()->json([
                    "status"  => false,
                    "message" => "store not found",
                ]);
            }
            $products = Product::where("storeId", $store->id)->paginate(40);
            return response()->json([
                "data"   => $products,
                "status" => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status"  => false,
                'message' => "get products fail",
            ], STATUS_CODE::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

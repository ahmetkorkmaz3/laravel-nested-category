<?php

namespace App\Http\Controllers;

use App\Http\Resources\Auditing\AuditingResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductAuditingController extends Controller
{
    /**
     * @param Product $product
     * @return AnonymousResourceCollection
     */
    public function __invoke(Product $product): AnonymousResourceCollection
    {
        return AuditingResource::collection($product->audits);
    }
}

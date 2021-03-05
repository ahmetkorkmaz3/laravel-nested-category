<?php

namespace App\Http\Controllers;

use App\Http\Resources\Auditing\AuditingResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryAuditingController extends Controller
{
    /**
     * @param Category $category
     * @return AnonymousResourceCollection
     */
    public function __invoke(Category $category): AnonymousResourceCollection
    {
        return AuditingResource::collection($category->audits);
    }
}

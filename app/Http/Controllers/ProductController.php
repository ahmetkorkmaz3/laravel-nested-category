<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * @param Category $category
     * @return AnonymousResourceCollection
     */
    public function index(Category $category): AnonymousResourceCollection
    {
        $categories = $category->descendants()->add($category)->pluck('id');
        $products = Product::with('category')
            ->whereIn('category_id', $categories)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return ProductResource::collection($products);
    }

    /**
     * @param StoreProductRequest $request
     * @param Category $category
     * @return ProductResource
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function store(StoreProductRequest $request, Category $category): ProductResource
    {
        DB::beginTransaction();
        try {
            $product = $category->products()->create($request->validated());
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
        DB::commit();

        return ProductResource::make($product);
    }

    /**
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        $product->load('category');
        return ProductResource::make($product);
    }

    /**
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return ProductResource
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        DB::beginTransaction();
        try {
            $product->update($request->validated());
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
        DB::commit();

        $product->load('category');
        return ProductResource::make($product);
    }

    /**
     * @param Product $product
     * @return Response
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function destroy(Product $product): Response
    {
        DB::beginTransaction();
        try {
            $product->delete();
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
        DB::commit();

        return response()->noContent();
    }
}

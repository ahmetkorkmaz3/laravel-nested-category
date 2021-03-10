<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryTreeResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::whereNull('category_id')
            ->with('childrenCategories')
            ->get();

        return CategoryTreeResource::collection($categories);
    }

    /**
     * @param StoreCategoryRequest $request
     * @return CategoryTreeResource
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function store(StoreCategoryRequest $request): CategoryTreeResource
    {
        DB::beginTransaction();
        try {
            $category = Category::create($request->validated());
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
        DB::commit();

        return CategoryTreeResource::make($category);
    }

    /**
     * @param Category $category
     * @return CategoryTreeResource
     */
    public function show(Category $category): CategoryTreeResource
    {
        return CategoryTreeResource::make($category->with('childrenCategories')->first());
    }

    /**
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return CategoryResource
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function update(UpdateCategoryRequest $request, Category $category): CategoryResource
    {
        DB::beginTransaction();
        try {
            $category->update($request->validated());
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
        DB::commit();

        return CategoryResource::make($category);
    }

    /**
     * @param Category $category
     * @return Response
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function destroy(Category $category): Response
    {
        DB::beginTransaction();
        try {
            $category->delete();
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500, $exception);
        }
        DB::commit();

        return response()->noContent();
    }
}

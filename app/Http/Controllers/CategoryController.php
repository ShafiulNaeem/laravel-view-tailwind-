<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CategoryProduct;
use App\Models\Feature;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return sendResponse(
            'Category List', 
            Category::all(),
            Response::HTTP_OK
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->all());
        return sendResponse(
            'Category Created',
            [],
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return sendResponse(
            'Category Detail',
            Category::find($id),
            Response::HTTP_OK
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request,$id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return sendResponse(
            'Category Updated',
            [],
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $product_ids = CategoryProduct::where('category_id', $id)->pluck('product_id');
            if(count($product_ids) > 0)
            {
                Feature::whereIn('product_id', $product_ids)->delete();
                CategoryProduct::whereIn('product_id', $product_ids)->delete();
                Product::whereIn('id', $product_ids)->delete();
            }
            Category::where('id', $id)->delete();
            return sendResponse(
                'Category deleted',
                [],
                Response::HTTP_OK
            );
        } catch (\Exception $exception) {
            return sendError(
                'something went wrong',
                [
                    'error' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'file' => $exception->getFile(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }
}

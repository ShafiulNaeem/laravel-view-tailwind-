<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CategoryProduct;
use App\Models\Feature;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return sendResponse(
            'Product List', 
            Product::with('categories')->latest()->get(),
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
    public function store(ProductRequest $request)
    {
        try {
            // insert 
            $data = Product::create($this->processProduct($request));
            $this->processCategories($request, $data->id);
            $this->processFeatures($request, $data->id);
            return sendResponse(
                'Product Created',
                [],
                Response::HTTP_CREATED
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

    private function processProduct($request)
    {
        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }
        return [
            'name' => $request->name,
            'image' => $imageName
        ];
    }
    private function processCategories($request ,$product_id): void
    {
        $data = $request->categories;
        $categories = [];
        foreach ($data as $key => $value) {
            $categories[] = [
                'product_id' => $product_id,
                'category_id' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        CategoryProduct::where('product_id', $product_id)->delete();
        CategoryProduct::insert($categories);
    }
    private function processFeatures($request, $product_id): void
    {
        $data = $request->features;
        $features = [];
        foreach ($data as $key => $value) {
            $features[] = [
                'product_id' => $product_id,
                'description' => $value,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        Feature::where('product_id', $product_id)->delete();
        if (count($features) > 0)
        {
            Feature::insert($features);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return sendResponse(
            'Product data',
            Product::with(['categories', 'feature'])->findOrFail($id),
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
    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            $data = Product::findOrFail($id);
            $data->update($this->processProduct($request));
            return sendResponse(
                'Product updated',
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $data = Product::findOrFail($id);
            CategoryProduct::where('product_id', $id)->delete();
            Feature::where('product_id', $id)->delete();
            $data->delete();
            return sendResponse(
                'Product deleted',
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

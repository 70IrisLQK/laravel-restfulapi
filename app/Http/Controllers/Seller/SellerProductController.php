<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $listProduct = $seller->products;

        return $this->showAll($listProduct, 200, "OK", 'Get products by seller');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => ['required', 'max:255'],
            'description' => ['required'],
            'quantity' => ['required', 'integer', 'min:1'],
            'image' => ['required', 'image', 'mimes:png,jpg', 'max:1000']
        ];

        $request->validate($rules);

        $data = $request->all();

        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;
        $data['slug'] = Str::slug($request->name);

        $newProduct = Product::create($data);

        return $this->showOne($newProduct, 201, 'OK', 'Create Product successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => ['integer', 'min:1'],
            'status' => ['in: ' . Product::UNAVAILABLE_PRODUCT . ',' . Product::AVAILABLE_PRODUCT],
            'image' => 'image',
        ];

        $request->validate($rules);
        $this->checkSeller($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity'
        ]));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorResponse(409, 'error', 'An active product must have at least category');
            }
        }
        if ($request->has('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }

        if ($product->isClean()) {
            return $this->errorResponse(422, 'error', 'The specific have at least 1 field update');
        }

        $product->save();

        return $this->showOne($product, 200, 'OK', 'Update Product successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);

        $product->delete();
        Storage::delete($product->image);

        return $this->showOne($product, 200, 'OK', 'Delete Product successfully');
    }

    protected function checkSeller($seller, $product)
    {
        return new JsonResponse($seller);
        if (!$seller->id != $product->seller_id) {
            throw new HttpException(422, 'The specified seller is not the actual seller & product');
        }
    }
}
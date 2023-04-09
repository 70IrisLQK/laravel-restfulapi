<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $listProduct = $buyer->transactions()->with('product')
            ->get()
            ->pluck('product');

        return $this->showAll($listProduct, 200, 'OK', 'Get product by buyers successfully.');
    }
}
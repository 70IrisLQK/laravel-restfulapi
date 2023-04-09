<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $listSeller = $buyer->transactions()
            ->with('product.seller')
            ->get()
            ->pluck('product.seller');

        return $this->showAll($listSeller, 200, 'OK', 'Get seller by buyers successfully.');
    }
}
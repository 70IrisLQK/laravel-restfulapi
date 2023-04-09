<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;

class SellerBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $listBuyer = $seller->products()
            ->whereHas('transactions')->with('transactions.buyer')
            ->get()->pluck('transactions')->collapse()->pluck('buyer')->unique('id')->values();
        return $this->showAll($listBuyer, 200, 'OK', 'Get buyer sellers successfully.');
    }
}
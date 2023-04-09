<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $listCategory = $seller->products()->whereHas('categories')
            ->with('categories')->get()->pluck('categories')->collapse();
        return $this->showAll($listCategory, 200, 'OK', 'Get category sellers successfully.');
    }
}
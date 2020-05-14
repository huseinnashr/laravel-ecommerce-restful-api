<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class BuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Buyer $buyer
     * @return JsonResponse
     */
    public function index(Buyer $buyer)
    {
        $transactions = $buyer->transactions;

        return $this->showAll($transactions);
    }
}

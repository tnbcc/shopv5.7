<?php

namespace App\Http\Controllers;


use App\Http\Requests\AddCartRequest;
use App\Models\ProductSku;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{

    protected $carService;

    public function __construct(CartService $cartService)
    {
        $this->carService = $cartService;
    }

    public function index(Request $request)
    {
        $cartItems = $this->carService->get();
        $addresses = $request->user()->addresses()->orderBy('last_used_at', 'desc')->get();

        return view('cart.index', compact('cartItems', 'addresses'));
    }



    public function add(AddCartRequest $request)
    {

        $this->carService->add($request->input('sku_id'), $request->input('amount'));

        return [];
    }

    public function remove(ProductSku $sku, Request $request)
    {
        $this->carService->remove($sku->id);

        return [];
    }
}

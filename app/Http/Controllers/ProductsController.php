<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $builder = Product::query()->where('on_sale', true);

        if ($search = $request->input('search', '')) {
            $like = '%'.$search.'%';
            // 模糊搜索商品标题、商品详情、SKU 标题、SKU描述
            $builder->where(function ($query) use ($like) {
                $query->where('title', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('skus', function ($query) use ($like) {
                        $query->where('title', 'like', $like)
                            ->orWhere('description', 'like', $like);
                    });
            });
        }

        if ($order = $request->input('order', '')) {

            //是否以_asc 或者 desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

        $products = $builder->paginate(16);

        $filters = [
          'search' => $search,
          'order'  => $order,
        ];

        return view('products.index', compact('products', 'filters'));
    }

    public function show(Request $request, Product $product)
    {
        if (!$product->on_sale) {
            throw new InvalidRequestException('该商品未上架');
        }

        $favored = false;

        if ($user = $request->user()) {
            $favored = boolval($user->favoriteProducts()->find($product->id));
        }

        $reviews = OrderItem::query()
                        ->with(['order.user', 'productSku'])
                        ->where('product_id', $product->id)
                        ->whereNotNull('reviewed_at')
                        ->orderBy('reviewed_at', 'desc')
                        ->limit(10)
                        ->get();

        return view('products.show', compact('product', 'favored', 'reviews'));
    }

    //收藏
    public function favor(Product $product, Request $request)
    {
        $user = $request->user();

        if ($user->favoriteProducts()->find($product->id)) {
            return [];
        }

        $user->favoriteProducts()->attach($product);

        return [];
    }

    //取消收藏
    public function disfavor(Product $product, Request $request)
    {
        $user = $request->user();

        $user->favoriteProducts()->detach($product);

        return [];
    }

    //收藏列表
    public function favorites(Request $request)
    {
        $products = $request->user()->favoriteProducts()->paginate(16);

        return view('products.favorites', compact('products'));
    }


}

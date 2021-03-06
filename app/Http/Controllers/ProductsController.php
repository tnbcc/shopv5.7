<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\SearchBuilders\ProductSearchBuilder;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $page    = $request->input('page', 1);
        $perPage = 16;
        // 新建查询构造器对象，设置只搜索上架商品，设置分页
        $builder = (new ProductSearchBuilder())->onSale()->paginate($perPage, $page);

        if ($request->input('category_id') && $category = Category::find($request->input('category_id'))) {
            // 调用查询构造器的类目筛选
            $builder->category($category);
        }

        if ($search = $request->input('search', '')) {
            $keywords = array_filter(explode(' ', $search));
            // 调用查询构造器的关键词筛选
            $builder->keywords($keywords);
        }

        if ($search || isset($category)) {
            // 调用查询构造器的分面搜索
            $builder->aggregateProperties();
        }

        $propertyFilters = [];
        if ($filterString = $request->input('filters')) {
            $filterArray = explode('|', $filterString);
            foreach ($filterArray as $filter) {
                list($name, $value) = explode(':', $filter);
                $propertyFilters[$name] = $value;
                // 调用查询构造器的属性筛选
                $builder->propertyFilter($name, $value);
            }
        }

        if ($order = $request->input('order', '')) {
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    // 调用查询构造器的排序
                    $builder->orderBy($m[1], $m[2]);
                }
            }
        }

        $result = app('es')->search($builder->getParams());

        // 通过 collect 函数将返回结果转为集合，并通过集合的 pluck 方法取到返回的商品 ID 数组
        $productIds = collect($result['hits']['hits'])->pluck('_id')->all();
        // 通过 whereIn 方法从数据库中读取商品数据
        $products = Product::query()->byIds($productIds)->get();
        // 返回一个 LengthAwarePaginator 对象
        $pager = new LengthAwarePaginator($products, $result['hits']['total'], $perPage, $page, [
            'path' => route('products.index', false), // 手动构建分页的 url
        ]);

        $properties = [];
        // 如果返回结果里有 aggregations 字段，说明做了分面搜索
        if (isset($result['aggregations'])) {
            // 使用 collect 函数将返回值转为集合
            $properties = collect($result['aggregations']['properties']['properties']['buckets'])
                ->map(function ($bucket) {
                    // 通过 map 方法取出我们需要的字段
                    return [
                        'key'    => $bucket['key'],
                        'values' => collect($bucket['value']['buckets'])->pluck('key')->all(),
                    ];
                })
                ->filter(function ($property) use ($propertyFilters) {
                    // 过滤掉只剩下一个值 或者 已经在筛选条件里的属性
                    return count($property['values']) > 1 && !isset($propertyFilters[$property['key']]) ;
                });
        }


        return view('products.index', [
            'products' => $pager,
            'filters'  => [
                'search' => $search,
                'order'  => $order,
            ],
            'category' => $category ?? null,
            'properties' => $properties,
            'propertyFilters' => $propertyFilters,
        ]);
    }

    public function show(Request $request, Product $product, ProductService $service)
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


        $similarProductIds = $service->getSimilarProductIds($product, 4);
        // 根据 Elasticsearch 搜索出来的商品 ID 从数据库中读取商品数据
        $similar   = Product::query()->byIds($similarProductIds)->get();

        return view('products.show', compact('product', 'favored', 'reviews', 'similar'));
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

        return $product->id;
    }

    //收藏列表
    public function favorites(Request $request)
    {
        $products = $request->user()->favoriteProducts()->paginate(16);

        return view('products.favorites', compact('products'));
    }


}

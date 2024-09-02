<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function product()
    {
        return view('pages.dashboard.product-page');
    }

    public function productList(Request $request)
    {
        $products = Product::where('user_id', $request->header('id'))->get();

        return response($products, 200);
    }

    public function productCreate(ProductRequest $request)
    {
        $validatedData = $request->validated();
        $user_id = $request->header('id');

        $img = $request->file('img');
        $fileName = time() . $user_id . rand(100, 1000) . '.' . $img->getClientOriginalExtension();
        $img_url = $fileName;
        $img->move(public_path('uploads/products'), $fileName);

        try {
            Product::create([
                'user_id' => $user_id,
                'category_id' => $validatedData['category_id'],
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'unit' => $validatedData['unit'],
                'img_url' => $img_url,
            ]);
            return response()->json(['message' => 'Request Successfull'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Request Failed!'], 404);
        }
    }
    public function productInfo(Request $request)
    {
        $product = Product::where('user_id', $request->header('id'))
            ->find($request->id);
        if ($product) {
            return response()->json($product, 200);
        } else {
            return response()->json(['message' => 'Request Failed!'], 404);
        }
    }
    public function productUpdate(ProductRequest $request)
    {

        $validatedData = $request->validated();

        $user_id = $request->header('id');
        $product = Product::where('user_id', $user_id)->find($request->input('product_id'));

        $img_url = $product->img_url;
        $oldImg_url = $product->img_url;

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $fileName = time() . $request->header('id') . rand(100, 1000) . '.' . $img->getClientOriginalExtension();
            $img_url = $fileName;
            $img->move(public_path('uploads/products'), $fileName);
        }
        try {
            $product->update([
                'user_id' => $user_id,
                'category_id' => $validatedData['category_id'],
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'unit' => $validatedData['unit'],
                'img_url' => $img_url,
            ]);

            $oldFile = public_path('uploads/products/' . $oldImg_url);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }

            return response()->json(['message' => 'Request Successfull'], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Request Failed!'], 404);
        }

    }
    public function productDelete(Request $request)
    {
        $product = Product::where('user_id', $request->header('id'))
            ->find($request->id);
        if ($product) {
            $img = $product->img_url;
            $oldFile = public_path('uploads/products/' . $img);
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
            $product->delete();
            return response()->json(['message' => 'Request Successfull'], 202);
        } else {
            return response()->json(['message' => 'Request Failed!'], 404);
        }

    }
}

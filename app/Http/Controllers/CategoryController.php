<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function category()
    {
        return view('pages.dashboard.category-page');
    }

    public function categoryList(Request $request)
    {
        $categories = Category::where('user_id', $request->header('id'))->get();

        return response($categories, 200);
    }

    public function categoryInfo(Request $request)
    {
        try {
            $category = Category::where('user_id', $request->header('id'))
                ->find($request->id);

            return response()->json(['name' => $category->name], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 404);
        }

    }

    public function categoryCreate(Request $request)
    {
        try {
            Category::create(['name' => $request->name, 'user_id' => $request->header('id')]);
            return response()->json(['name' => $request->name], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 422);
        }
    }

    public function categoryUpdate(Request $request)
    {
        try {
            $category = Category::where('user_id', $request->header('id'))
                ->find($request->id);

            $category->update(['name' => $request->name]);
            return response()->json(['status' => 'sucess', 'message' => 'Request Successfull'], 202);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 422);
        }
    }
    public function categoryDelete(Request $request)
    {
        try {
            $category = Category::where('user_id', $request->header('id'))
                ->find($request->id);
            $category->delete();
            return response()->json(['status' => 'sucess', 'message' => 'Request Successfull'], 202);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 404);
        }
    }
}

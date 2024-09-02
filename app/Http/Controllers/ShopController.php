<?php

namespace App\Http\Controllers;

use App\Models\ShopDetail;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function shopInfo(Request $request)
    {
        $shop = ShopDetail::where('user_id', $request->header('id'))->first();
        if ($shop) {

            return response()->json(['status' => 'success', 'message' => 'Request Successfull', 'data' => $shop], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 400);
        }

    }
    public function shopUpdate(Request $request)
    {
        $user_id = $request->header('id');
        $shop = ShopDetail::where('user_id', $user_id)->first();
        if ($shop) {
            $shop->title = $request->input('title');
            $logo = $request->file('logo');
            if ($logo) {
                $fileName = 'logo' . $user_id . '.png';
                $img_url = $fileName;
                $logo->move(public_path('uploads/logo/'), $fileName);
                $shop->logo = $img_url;
            }
            $shop->save();
            return response()->json(['status' => 'success', 'message' => 'Shop updated successfully!'], 200);

        } else {
            return response()->json(['status' => 'failed', 'message' => 'Request Failed!'], 400);
        }

    }
}

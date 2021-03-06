<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FavouriteResource;
use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FavouriteController extends Controller
{
    public function myfavourite(Request $request)
    {
        try {
            $userid = $request->user()->id;
            $myfavourite = Favourite::where('user_id', $userid)->orderBy('created_at', 'desc')->get();
            return response(['success' => true, 'data' => FavouriteResource::collection($myfavourite)]);
        } catch (\Throwable $e) {
            return response(['success' => false, 'errors' => $e->getMessage()], 500);
        }
    }

    public function favouriteProducts()
    {
        try {
            $userid = Auth::user()->id;
            $favouriteProduct = Favourite::with('product.images')->where('user_id', $userid)->orderBy('created_at', 'desc')->get();
            return response(['success' => true, 'data' => $favouriteProduct]);
        } catch (\Throwable $e) {
            return response(['success' => false, 'errors' => $e->getMessage()], 500);
        }
    }

    public function addfavourite(Request $request)
    {
        // return response(['req' => $request->all(), 'userid'=>$userid = $request->user()->id]);
        if (Auth::check()) {
            $userid = $request->user()->id;
            // Product::findOrFail($request->product_id);
            $isExist = Favourite::where('user_id', $userid)->where('product_id', $request->product_id)->first();
            if ($isExist) {
                $favId = $isExist->id;
                $isExist->delete();
                return response(['success' => true, 'message' => 'remove from favourite', 'data' => $favId], 200);
            } else {
                try {
                    $create = Favourite::create([
                        'user_id' => $userid,
                        'product_id' => $request->product_id
                    ]);
                    return response(['success' => true, 'message' => 'added to favourite', 'data' => $create], 201);
                } catch (\Throwable $e) {
                    return response(['success' => false, 'errors' => $e->getMessage()], 500);
                }
            }
        } else {
            return response(['success' => false, 'message' => 'Unauthorized'], 401);
        }
    }

    public function removeFavourite(Favourite $favourite)
    {
        try {
            $favourite->delete();
            return response(['success' => true, 'message' => 'removed from favourite']);
        } catch (\Throwable $e) {
            return response(['success' => false, 'errors' => $e->getMessage()], 500);
        }
    }
}

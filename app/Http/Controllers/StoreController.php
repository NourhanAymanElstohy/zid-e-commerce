<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function getMerchantStores()
    {
        $user = Auth::user();
        $stores = $user->stores()->paginate();
        if ($stores->count() > 0)
            return response()->json(['data' => $stores], 200);
        else
            return response()->json(['message' => 'No Stores Found'], 404);
    }

    public function getAllStores()
    {
        $stores = Store::paginate();
        if ($stores->count() > 0)
            return response()->json(['data' => $stores], 200);
        else
            return response()->json(['message' => 'No Stores Found'], 404);
    }

    public function getStoreProducts(Request $request)
    {
        $store = Store::find($request->id);
        if ($store)
            return response()->json(['data' => $store->products()->paginate()], 200);
        else
            return response()->json(['message' => 'No Stores Found'], 404);
    }

    public function addStore(StoreRequest $request)
    {
        $store = Store::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);
        return response()->json(['message' => 'Store has been created successfully', ["data" => $store]], 200);
    }

    public function setStoreName(StoreRequest $request)
    {
        $user = Auth::user();
        $store = Store::find($request->id);
        if ($store) {
            $user_stores = $user->stores;
            if ($user_stores->contains($store->id)) {
                $store->update(['name' => $request->name]);
                return response()->json(['message' => 'Your store name has been updated successfully', ["data" => $store]], 200);
            } else
                return response()->json(['message' => 'Unauthorized to access this store'], 401);
        } else
            return response()->json(['message' => 'No stores Found'], 404);
    }

    public function deleteStore(Request $request)
    {
        $user = Auth::user();
        $store = Store::find($request->id);
        if ($store) {
            $user_stores = $user->stores;
            if ($user_stores->contains($store->id)) {
                $store->delete();
                return response()->json(['message' => 'Store has been deleted successfully'], 200);
            } else
                return response()->json(['message' => 'Unauthorized'], 401);
        } else
            return response()->json(['message' => 'No stores Found'], 404);
    }
}

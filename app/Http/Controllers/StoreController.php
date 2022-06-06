<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function getMerchantStores()
    {
        $stores = $this->storeService->getMerchantStores(Auth::user());
        if ($stores->count() > 0)
            return response()->json(['data' => $stores], 200);
        else
            return response()->json(['message' => 'No Stores Found'], 404);
    }

    public function getAllStores()
    {
        $stores = $this->storeService->getAllStores();
        if ($stores->count() > 0)
            return response()->json(['data' => $stores], 200);
        else
            return response()->json(['message' => 'No Stores Found'], 404);
    }

    public function getStoreProducts(Request $request)
    {
        $products = $this->storeService->getStoreProducts($request->id);
        if ($products)
            return response()->json(['data' => $products], 200);
        else
            return response()->json(['message' => 'No Stores Found'], 404);
    }

    public function addStore(StoreRequest $request)
    {
        $store = $this->storeService->create($request);
        return response()->json(['message' => 'Store has been created successfully', ["data" => $store]], 200);
    }

    public function setStoreName(StoreRequest $request)
    {
        $store = Store::find($request->id);
        $result = $this->storeService->update($store, $request);
        if ($result == 1)
            return response()->json(['message' => 'Your store name has been updated successfully', ["data" => $store]], 200);
        elseif ($result == 2)
            return response()->json(['message' => 'Unauthorized to access this store'], 401);
        elseif ($result == 0)
            return response()->json(['message' => 'No stores Found'], 404);
    }

    public function deleteStore(Request $request)
    {
        $result = $this->storeService->delete($request->id);
        if ($result == 1)
            return response()->json(['message' => 'Store has been deleted successfully'], 200);
        elseif ($result == 2)
            return response()->json(['message' => 'Unauthorized'], 401);
        elseif ($result == 0)
            return response()->json(['message' => 'No stores Found'], 404);
    }
}

<?php

namespace App\Services;

use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StoreService
{

    public function getMerchantStores(User $user)
    {
        return $user->stores()->paginate();
    }

    public function getAllStores()
    {
        return Store::paginate();
    }

    public function getStoreProducts($storeId)
    {
        $store = Store::find($storeId);
        return $store ? $store->products()->paginate() : null;
    }

    public function create($request): Store
    {
        return Store::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
        ]);
    }

    public function update(Store $store, $request): bool
    {
        $user = Auth::user();
        if ($store) {
            $user_stores = $user->stores;
            if ($user_stores->contains($store->id)) {
                $store->update(['name' => $request->name]);
                return 1;
            } else
                return 2;
        } else
            return 0;
    }

    public function delete($storeId)
    {
        $user = Auth::user();
        $store = Store::find($storeId);
        if ($store) {
            $user_stores = $user->stores;
            if ($user_stores->contains($store->id)) {
                $store->delete();
                return 1;
            } else
                return 2;
        } else
            return 0;
    }
}

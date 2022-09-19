<?php
namespace App\Http\Resources\Shop;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Inventory\Product as ProductResource;

class ShopAskforQuote extends Resource{

    public function toArray($request){
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this ->phone,
            'email' => $this ->email,
            'ask_for_quote_company_id' => $this->ask_for_quote_company_id,
            'company_product_id' => $this->product_id,
            'company_id'=> $this->company_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price'=> $this->price,
            'product' => new ProductResource($this->product),
            'prduct_name' => $this->product_name,
            'product_description' => $this->product_description,
            'product_image' => $this->product_image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'is_complete'=> $this->is_complete,
            'is_complete_at'=> $this->complete_at
        ];
    }
}
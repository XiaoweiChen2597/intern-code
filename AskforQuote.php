<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AskforQuote extends Model
{
    use SoftDeletes;
    protected $table = 'ask_for_quote';

    protected $fillable = ['first_name','last_name','phone','email','ask_for_quote_company_id','product_id','company_id','company_product_id',
    'quantity','price','product_name','product_description','product_image','created_at','updated_at','deleted_at','is_complete','is_complete_at'];

    public function product(){
        return $this->belongsTo('App\Models\Inventory\Product','company_product_id');
    }

}
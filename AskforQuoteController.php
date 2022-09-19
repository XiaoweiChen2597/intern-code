<?php
namespace App\Http\Controllers\Shop;

use OfficeBuilding;
use ApiRespond;
use Validator;
use Auth;
Use DB;
Use Carbon\carbon;
use App\Models\Company;
use App\Models\Shop\AskforQuote;
use App\Models\Inventory\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ShopAskforQuote as ShopAskforQuoteResource;

Class AskforQuoteController extends controller{
    public function __construct(){
        $this->middleware('jwt.auth')->except('add');
    }

    // add ask for quote information into table AskforQuote
    // personal info & product info
    public function add(Request $request, Company $company){
        //$user = Auth::user();
        function getdata($request){
            $info = new AskforQuote;
            $info->fill($request->only(['first_name','last_name','phone','email','prodyct_id','company_id',
            'company_product_id','quantity','product_name','product_description','product_image','created_at',
            'updated_at','deleted_at','is_complete','complete_at']));
            $info->company_id = $request->company->id;
            $info->is_complete=FALSE;
            return $info;
        }

        return OfficeBuilding::visit($company->id, function() use($request) {
            $info=getdata($request);
            $product_only=Product::where('id',$info->company_product_id);
            $info->product_name= $product_only->value('name');
            $info->product_description= $product_only->value('description');
            $info->product_image= $product_only->value('image');
            $info->save();
            $info->ask_for_quote_company_id=AskforQuote::where('first_name',$info->first_name)
                ->where('last_name',$info->last_name)
                ->where('phone',$info->phone)
                ->latest('id')
                ->value('id');
            OfficeBuilding::visit($company=171, function() use ($request,$info){
            $info_shop = getdata($request);
            $product_shop=db::table('products')
                ->where('company_id',$info_shop->company_id)
                ->where('company_product_id',$info_shop->company_product_id);
            $info_shop->product_id=$product_shop->value('id');
            $info_shop->ask_for_quote_company_id=$info->ask_for_quote_company_id;
            $info_shop->save();
        });
            $info->save();
  
            return ApiRespond::success(new ShopAskforQuoteResource($info));
        });
        
    }

    public function read(Request $request, Company $company, $askforquote_id){
        return OfficeBuilding::visit($company->id, function() use($askforquote_id) {
            $quote = AskforQuote::findOrFail($askforquote_id);
            // dd($quote );
            return ApiRespond::success(new ShopAskforQuoteResource($quote));
        });
    }

    public function browse(Request $request, Company $company){
        return OfficeBuilding::visit($company->id, function() use($request) {
        $quote = AskforQuote::where('is_complete',$request->is_complete)
                ->orderby('created_at','desc')->paginate(15)->appends(request()->query());

            return ApiRespond::success([
                "data"=>ShopAskforQuoteResource::collection($quote),
                'paging' => [
                    'next' => $quote->nextPageUrl(),
                    'previous' => $quote->previousPageUrl(),
                    'total' => $quote->total()
                ]
            ]);
        });
    }

    public function delete(Request $request, Company $company, $askforquote_id){
        //dd('delete');
        officeBuilding::visit($company->id, function() use($askforquote_id){
            $quote = AskforQuote::where('ask_for_quote_company_id',$askforquote_id);
            $quote -> delete();
        });

        OfficeBuilding::visit($company=171, function() use($askforquote_id){
            $quote_shop = AskforQuote::where('ask_for_quote_company_id',$askforquote_id);
            $quote_shop -> delete();
        });

        return ApiRespond::noContent();

    }

    public function admin_delete(Request $request, Company $company){
        return OfficeBuilding::visit($company->id, function() use($request){
            $quote = AskforQuote::whereNotNull('deleted_at')
                                ->withTrashed()
                                ->paginate(15)
                                ->appends(request()->query());

            return ApiRespond::success([
                "data"=>ShopAskforQuoteResource::collection($quote),
                'paging' => [
                    'next' => $quote->nextPageUrl(),
                    'previous' => $quote->previousPageUrl(),
                    'total' => $quote->total()
                ]
                ]);
            });
    }

    public function update(Request $request, Company $company, $askforquote_id){

        function update_data($request,$askforquote_id){
            $info=AskforQuote::where('ask_for_quote_company_id',$askforquote_id)->first();
            $info->update(['first_name'=>$request->first_name,'last_name'=>$request->last_name,
            'phone'=>$request->phone, 'email'=>$request->email,'quantity'=>$request->quantity,
            'price'=>$request->price,'is_complete' => $request->is_complete,
            'is_complete_at'=>carbon::now()->format('Y-m-d H:i:s')]);
            return($info);
        }

        return OfficeBuilding::visit($company->id, function() use($request,$askforquote_id){
            $info=update_data($request,$askforquote_id);
            
            OfficeBuilding::visit($company=171, function() use($request,$askforquote_id){
                $info_shop=update_data($request,$askforquote_id);
            });

            return ApiRespond::updated(new ShopAskforQuoteResource($info));
        });

    }

/*     public function is_complete(Request $request, Company $company, $askforquote_id){
        
        function complete_quote($request,$askforquote_id){
            $info=DB::table('ask_for_quote')->where('ask_for_quote_company_id',$askforquote_id);
            $info->update(['is_complete' => TRUE,'is_complete_at'=>carbon::now()->format('Y-m-d H:i:s')]);
            return($info);
        };

        return OfficeBuilding::visit($company->id, function() use($request,$askforquote_id){
            $info=complete_quote($request,$askforquote_id);
            //dd($info);
            
            OfficeBuilding::visit($company=171, function() use($request,$askforquote_id){
                $info_shop=complete_quote($request,$askforquote_id);
                //$info_shop->save();
                //dd($info_shop);
            });
            //$info->save();

        });
        return ApiRespond::updated(new ShopAskforQuoteResource($info));
        
    } */
}
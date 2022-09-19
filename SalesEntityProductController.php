<?php

namespace App\Http\Controllers\Business;

use DB;
use Auth;
use Carbon\Carbon;
use OfficeBuilding;
use ApiRespond;
use App\Models\Business\BusinessInvoiceProduct;
use App\Models\Business\BusinessSalesEntity;
use App\Http\Resources\Business\BusinessInvoiceProduct as BusinessInvoiceProductResource;
use App\Http\Resources\Business\BusinessSalesEntityList as BusinessSalesEntityListResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;

class SalesEntityProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    
    // get salesEntity data type 1,2,3 with timeranges, the formal api is the one below
/*     public function getSalesEntityData(Request $request, Company $company)
    {
        return OfficeBuilding::visit($company->id, function() use($request){
            $user=Auth::user();
            $starttime=Carbon::parse($request->starttime)->format('Y-m-d');
            $endtime=Carbon::parse($request->endtime)->format('Y-m-d');
            
            $total_quotes=SalesEntity::where('created_by',$user->id)
                ->where('type',1)
                ->whereNull('deleted_at')
                ->count();
            //dd($total_quotes);
            $new_quotes=SalesEntity::where('created_by',$user->id)   
                ->where('type',1)
                ->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $starttime)
                ->whereDate('created_at', '<=', $endtime)
                ->count();

            $sales_order=SalesEntity::where('created_by',$user->id)
                ->where('type',2)
                ->whereNull('deleted_at')
                ->count();

            $new_sales_order=SalesEntity::where('created_by',$user->id)
                ->where('type',2)
                ->whereDate('created_at', '>=', $starttime)
                ->whereDate('created_at', '<=', $endtime)
                ->whereNull('deleted_at')
                ->count();

            $invoice=SalesEntity::where('created_by',$user->id)
                ->where('type',3)
                ->whereNull('deleted_at')
                ->count();

            $new_invoice=SalesEntity::where('created_by',$user->id)
                ->where('type',3)
                ->whereDate('created_at', '>=', $starttime)
                ->whereDate('created_at', '<=', $endtime)
                ->whereNull('deleted_at')
                ->count();

            //dd($sales_order);
            
            return ApiRespond::success([
                'total_quotes' => $total_quotes,
                'new_quotes'=>$new_quotes,
                'sales_order' => $sales_order,
                'new_sales_order' => $new_sales_order,
                'invoice'=>$invoice,
                'new_invoice'=>$new_invoice
            ]);

        });
 
    }   */

    // get salesEntity data type 1,2,3 with amount and count with time range
    public function getNewAmountData(Request $request, Company $company){
        return OfficeBuilding::visit($company->id, function() use($request){
            $user=Auth::user();
            $starttime=Carbon::parse($request->starttime)->format('Y-m-d');
            $endtime=Carbon::parse($request->endtime)->format('Y-m-d');
            //dd($starttime);

            $sales_entity_tmp=BusinessSalesEntity::where('created_by',$user->id)
                ->where('type',$request->type)
                ->whereNull('deleted_at')
                ->whereDate('created_at', '>=', $starttime)
                ->whereDate('created_at', '<=', $endtime);
            //dd($sales_entity_tmp);

            $sales_entity=$sales_entity_tmp
                ->paginate()->appends(request()->query());

            //dd(BusinessSalesEntityListResource::collection($sales_entity));
            $quote_total_amount=$sales_entity_tmp
                ->sum('total');
            

            $quote_total=$sales_entity_tmp   
                ->count();

            return ApiRespond::success([
                'data'=> BusinessSalesEntityListResource::collection($sales_entity),
                'paging' =>[
                    'next' => $sales_entity->nextPageUrl(),
                    'previous'=> $sales_entity->previousPageUrl(),
                    'quote_total_amount'=>round($quote_total_amount,2),
                    'quotes_total'=>$quote_total,
                ]
            ]);

        });
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Company $company, $sales_entity_id, $product_id)
    {
        return OfficeBuilding::visit($company->id, function() use($request, $sales_entity_id, $product_id){

            // dd($sales_entity_id, $product_id, $request->max_qty, $request->unit, $request->type);

            $product = BusinessInvoiceProduct::where('sales_entity_id', $sales_entity_id)
                ->where('product_id', $product_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->where('max_qty', $request->max_qty)
                ->where('unit', $request->unit)
                ->where('type', $request->type)
                ->first();
            return ApiRespond::success(new BusinessInvoiceProductResource($product));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company, $sales_entity_id, $product_id)
    {
        return OfficeBuilding::visit($company->id, function() use($request, $sales_entity_id, $product_id){

            // $productWhere = BusinessInvoiceProduct::withTrashed()
            //     ->where('sales_entity_id', $sales_entity_id)
            $productWhere = BusinessInvoiceProduct::where('sales_entity_id', $sales_entity_id)
            ->where('product_id', $product_id)
            ->where('max_qty', $request->max_qty)
            ->where('unit', $request->unit)
            ->where('type', $request->type);

            $assignments = collect($request->assignments);
            $ids = $assignments->pluck('warehouse_id')->filter(function ($id) use ($request) {
                return $id != $request->warehouse_id;
            });
            $editProducts = with(clone $productWhere)
            ->whereIn('warehouse_id', $ids)
            ->get()
            ->map(function ($item) use ($assignments, $productWhere) {
                $quantity = $assignments->where('warehouse_id', $item->warehouse_id)->first()['quantity'];
                $quantity += $item->quantity;

                $deletedProductWhere = with(clone $productWhere)
                ->onlyTrashed()
                ->where('warehouse_id', $item->warehouse_id)
                ->where('quantity', $quantity);
                if($deletedProductWhere->count()) {
                    $deletedProductWhere->restore();
                    with(clone $productWhere)->where('warehouse_id', $item->warehouse_id)->where('quantity', $item->quantity)->delete();
                }
                else with(clone $productWhere)->where('warehouse_id', $item->warehouse_id)->update(['quantity' => $quantity]);
                
                $item->quantity = $quantity;
                return $item;
            });

            $product = with(clone $productWhere)->where('warehouse_id', $request->warehouse_id)->first();
            $newProducts = $assignments
            ->whereNotIn('warehouse_id', $editProducts->pluck('warehouse_id'))
            ->map(function ($item) use ($product, $productWhere) {
                $clone = $product->replicate();
                $clone->warehouse_id = $item['warehouse_id'];
                $clone->quantity = $item['quantity'];
                
                $deletedProductWhere = with(clone $productWhere)
                ->onlyTrashed()
                ->where('warehouse_id', $item['warehouse_id'])
                ->where('quantity', $item['quantity']);
                if($deletedProductWhere->count()) $deletedProductWhere->restore();
                else $clone->save();
                
                return $clone;
            });

            with(clone $productWhere)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('quantity', $request->quantity)
            ->delete();
            // with(clone $productWhere)->where('warehouse_id', $request->warehouse_id)->restore();
            $products = $editProducts->merge($newProducts);
            return ApiRespond::success(BusinessInvoiceProductResource::collection($products));
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}

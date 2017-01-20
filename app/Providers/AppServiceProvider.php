<?php

namespace App\Providers;

use App\Models\DeliveryOrder;
use App\Models\Product;
use App\Models\PurchaseDemand;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('check_stock', function($attribute, $value, $parameters, $validator){
            $valid = true;
            $productId = unserialize($parameters[0])[explode('.', $attribute)[1]];
            $qty = $value;
            $product = Product::find($productId);
            $productQty = $product->quantity;
            if($productQty - $qty < 0)
                $valid = false;
            return $valid;
        });

        Validator::replacer('check_stock', function ($message, $attribute, $rule, $parameters) {
            $productId = unserialize($parameters[0])[explode('.', $attribute)[1]];
            $product = Product::find($productId);
            $productQty = $product->quantity;
            $message = "Quantity ".$product->name." must be less or same than ".($productQty).
                " in accordance with product quantity";
            return $message;
        });

        Validator::extend('check_price', function ($attribute, $value, $parameters, $validator) {
            $valid = true;
            $model = new $parameters[0]();
            $products = explode('-', $parameters[1]);
            $column = $parameters[2];
            $condition = $parameters[3];
            foreach ($products as $v){
                $tablePrice = $model->find($v)->{$column};
                if($condition === 'min')
                    $valid = intval($value) >= $tablePrice;
                else if($condition === 'max')
                    $valid = intval($value) <= $tablePrice;
                else
                    $valid = false;
            }
            return $valid;
        });

        Validator::replacer('add_po_product', function ($message, $attribute, $rule, $parameters) {
            $poId = $parameters[0];
            $productId = unserialize($parameters[1])[explode('.', $attribute)[1]];
            $isRetur = isset($parameters[2]) ? true : false;
            $product = PurchaseOrder::find($parameters[0])
                ->products()
                ->where('product_id', unserialize($parameters[1])[explode('.', $attribute)[1]])
                ->first();
            $maxQty = 0;
            $maxQty += DB::table('product_receipts')
                ->join('product_product_receipt', 'product_receipts.id', '=', 'product_product_receipt.product_receipt_id')
                ->where('purchase_order_id', $poId)
                ->where('product_id', $productId)
                ->sum('quantity');
            if($isRetur)
                $maxQty += DB::table('purchase_returns')
                    ->join('product_purchase_return', 'purchase_returns.id', '=', 'product_purchase_return.purchase_return_id')
                    ->where('purchase_order_id', $poId)
                    ->where('product_id', $productId)
                    ->where('status', '1')
                    ->sum('quantity');
            $message = "Quantity ".$product->name." must be less or same than ".($product->pivot->quantity - $maxQty).
                " in accordance with the purchase order";
            return $message;
        });

        Validator::extend('add_po_product', function ($attribute, $value, $parameters, $validator) {
            $valid = true;
            $poId = $parameters[0];
            $productId = unserialize($parameters[1])[explode('.', $attribute)[1]];
            $newQty = $value;
            $isRetur = isset($parameters[2]) ? true : false;
            $newQty += DB::table('product_receipts')
                ->join('product_product_receipt', 'product_receipts.id', '=', 'product_product_receipt.product_receipt_id')
                ->where('purchase_order_id', $poId)
                ->where('product_id', $productId)
                ->sum('quantity');
            if($isRetur)
                $newQty += DB::table('purchase_returns')
                    ->join('product_purchase_return', 'purchase_returns.id', '=', 'product_purchase_return.purchase_return_id')
                    ->where('purchase_order_id', $poId)
                    ->where('product_id', $productId)
                    ->where('status', '1')
                    ->sum('quantity');
            $maxQuantity = PurchaseOrder::find($poId)
                ->products()
                ->where('product_id', $productId)
                ->first()
                ->pivot->quantity;
            if($newQty > $maxQuantity)
                $valid = false;
            return $valid;
        });

        Validator::extend('add_so_product', function ($attribute, $value, $parameters, $validator) {
            $valid = true;
            $soId = $parameters[0];
            $productId = unserialize($parameters[1])[explode('.', $attribute)[1]];
            $newQty = $value;
            $isRetur = isset($parameters[2]) ? true : false;
            $newQty += DB::table('delivery_orders')
                ->join('delivery_order_product', 'delivery_orders.id', '=', 'delivery_order_product.delivery_order_id')
                ->where('sales_order_id', $soId)
                ->where('product_id', $productId)
                ->sum('quantity');
            if($isRetur)
                $newQty += DB::table('sales_returns')
                    ->join('product_sales_return', 'sales_returns.id', '=', 'product_sales_return.sales_return_id')
                    ->where('sales_order_id', $soId)
                    ->where('product_id', $productId)
                    ->where('status', '1')
                    ->sum('quantity');
            $maxQuantity = SalesOrder::find($soId)
                ->products()
                ->where('product_id', $productId)
                ->first()
                ->pivot->quantity;
            if($newQty > $maxQuantity)
                $valid = false;
            return $valid;
        });

        Validator::replacer('add_so_product', function ($message, $attribute, $rule, $parameters) {
            $soId = $parameters[0];
            $productId = unserialize($parameters[1])[explode('.', $attribute)[1]];
            $isRetur = isset($parameters[2]) ? true : false;
            $product = SalesOrder::find($parameters[0])
                ->products()
                ->where('product_id', unserialize($parameters[1])[explode('.', $attribute)[1]])
                ->first();
            $maxQty = 0;
            $maxQty += DB::table('delivery_orders')
                ->join('delivery_order_product', 'delivery_orders.id', '=', 'delivery_order_product.delivery_order_id')
                ->where('sales_order_id', $soId)
                ->where('product_id', $productId)
                ->sum('quantity');
            if($isRetur)
                $maxQty += DB::table('sales_returns')
                    ->join('product_sales_return', 'sales_returns.id', '=', 'product_sales_return.sales_return_id')
                    ->where('sales_order_id', $soId)
                    ->where('product_id', $productId)
                    ->where('status', '1')
                    ->sum('quantity');
            $message = "Quantity ".$product->name." must be less or same than ".($product->pivot->quantity - $maxQty).
                " in accordance with the sales order";
            return $message;
        });

        Validator::extend('add_sales_retur_product', function ($attribute, $value, $parameters, $validator) {
            $valid = true;
            $doId = $parameters[0];
            $productId = unserialize($parameters[1])[explode('.', $attribute)[1]];
            $newQty = $value;
            $maxQuantity = DeliveryOrder::find($doId)
                ->products()
                ->where('product_id', $productId)
                ->first()
                ->pivot->quantity;
            if($newQty > $maxQuantity)
                $valid = false;
            return $valid;
        });

        Validator::replacer('add_sales_retur_product', function ($message, $attribute, $rule, $parameters) {
            $doId = $parameters[0];
            $productId = unserialize($parameters[1])[explode('.', $attribute)[1]];
            $maxQuantity = DeliveryOrder::find($doId)
                ->products()
                ->where('product_id', $productId)
                ->first()
                ->pivot->quantity;
            $message = "Quantity ".Product::find($productId)->name." must be less or same than ".($maxQuantity).
                " in accordance with the deliver order";
            return $message;
        });

        Validator::extend('add_demand_product', function ($attribute, $value, $parameters, $validator) {
            $valid = true;
            $demandId = $parameters[0];
            $productId = unserialize($parameters[1])[explode('.', $attribute)[1]];
            $newQty = $value;
                        $maxQuantity = PurchaseDemand::find($demandId)
                ->products()
                ->where('product_id', $productId)
                ->first()
                ->pivot->quantity;
            if($newQty > $maxQuantity)
                $valid = false;
            return $valid;
        });

        Validator::replacer('add_demand_product', function ($message, $attribute, $rule, $parameters) {
            $product = PurchaseDemand::find($parameters[0])
                ->products()
                ->where('product_id', unserialize($parameters[1])[explode('.', $attribute)[1]])
                ->first();
            $message = "Quantity ".$product->name." must be less or same than ".$product->pivot->quantity.
                " in accordance with the purchase demand";
            return $message;
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}

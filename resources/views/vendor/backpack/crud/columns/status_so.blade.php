<?php
    $key = $column['name'];
    $code = $entry->pivot->$key;
    $status = "";
    switch ($code){
        case '0':
            $status = "Barang dicancel";break;
        case '1':
            $status = "Menunggu pengiriman";break;
        case '2':
            $status = "Barang sudah dikirim semua";break;
    }
    if($code == '3'){
        $quantityOrder = \DB::table('delivery_orders')
                ->join('delivery_order_product', 'delivery_orders.id', '=', 'delivery_order_product.delivery_order_id')
                ->where('sales_order_id', $column['po_id'])
                ->where('product_id', $entry->id)
                ->where('status', '1')
                ->sum('quantity');
        $status = "Barang yang dikirim sebanyak ".$quantityOrder;
    }
    else if($code == '4'){
        $quantityReturn = \DB::table('sales_returns')
                ->join('product_sales_return', 'sales_returns.id', '=', 'product_sales_return.sales_return_id')
                ->join('delivery_orders', 'delivery_orders.id', '=', 'sales_returns.delivery_order_id')
                ->join('sales_orders', 'sales_orders.id', '=', 'delivery_orders.sales_order_id')
                ->where('sales_order_id', $column['po_id'])
                ->where('product_id', $entry->id)
                ->where('product_sales_return.status', '1')
                ->sum('quantity');
        $status = "Mengembalikan barang sebanyak ".$quantityReturn;
    }
    else if($code == '5'){
        $quantityOrder = \DB::table('delivery_orders')
                ->join('delivery_order_product', 'delivery_orders.id', '=', 'delivery_order_product.delivery_order_id')
                ->where('sales_order_id', $column['po_id'])
                ->where('product_id', $entry->id)
                ->where('status', '1')
                ->sum('quantity');
        $quantityReturn = \DB::table('sales_returns')
                ->join('product_sales_return', 'sales_returns.id', '=', 'product_sales_return.sales_return_id')
                ->join('delivery_orders', 'delivery_orders.id', '=', 'sales_returns.delivery_order_id')
                ->join('sales_orders', 'sales_orders.id', '=', 'delivery_orders.sales_order_id')
                ->where('sales_order_id', $column['po_id'])
                ->where('product_id', $entry->id)
                ->where('product_sales_return.status', '1')
                ->sum('quantity');
        $status = "Barang yang diterima ".$quantityOrder." dan barang yang diretur ".$quantityReturn;
    }

?>
<td>{{str_limit(strip_tags($status), 80, "[...]") }}</td>
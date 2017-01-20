<?php
    $key = $column['name'];
    $code = $entry->pivot->$key;
    $status = "";
    switch ($code){
        case '1':
            $status = "Menunggu barang dari supplier";break;
        case '0':
            $status = "Semua barang di cancel";break;
        case '2':
            $status = "Semua barang sudah di terima";break;
    }
    if($code == '3'){
        $quantityReceipt = \DB::table('product_receipts')
            ->join('product_product_receipt', 'product_receipts.id', '=', 'product_product_receipt.product_receipt_id')
            ->where('purchase_order_id', $column['po_id'])
            ->where('product_id', $entry->id)
            ->sum('quantity');
        $status = "Barang yang diterima sebanyak ".$quantityReceipt;
    }
    else if($code == '4'){
        $quantityReturn = \DB::table('purchase_returns')
                ->join('product_purchase_return', 'purchase_returns.id', '=', 'product_purchase_return.purchase_return_id')
                ->where('purchase_order_id', $column['po_id'])
                ->where('product_id', $entry->id)
                ->where('status', '1')
                ->sum('quantity');
        $status = "Menunggu barang retur sebanyak ".$quantityReturn;
    }
    else if($code == '5'){
        $quantityReceipt = \DB::table('product_receipts')
                ->join('product_product_receipt', 'product_receipts.id', '=', 'product_product_receipt.product_receipt_id')
                ->where('purchase_order_id', $column['po_id'])
                ->where('product_id', $entry->id)
                ->sum('quantity');
        $quantityReturn = \DB::table('purchase_returns')
                ->join('product_purchase_return', 'purchase_returns.id', '=', 'product_purchase_return.purchase_return_id')
                ->where('purchase_order_id', $column['po_id'])
                ->where('product_id', $entry->id)
                ->where('status', '1')
                ->sum('quantity');
        $status = "Barang yang diterima ".$quantityReceipt." dan barang yang diretur ".$quantityReturn;
    }

?>
<td>{{str_limit(strip_tags($status), 80, "[...]") }}</td>
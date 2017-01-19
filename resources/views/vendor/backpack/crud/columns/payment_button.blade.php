<td>
    <form method="post" action="{{url('pembayaran-pembelian')}}">
        {{ csrf_field() }}
        <input type="hidden" name="purchase_order_id" value="{{$entry->id}}"/>
        <button type="submit" class="btn btn-sm btn-success"  {{$entry->status !=2 ? "disabled" : ""}}>
            <i class="fa fa-usd"></i> Payment
        </button>
    </form>
</td>
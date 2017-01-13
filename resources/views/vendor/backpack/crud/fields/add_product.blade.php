
<div id="add_product">
    <div class="form-group col-md-12">
        <label></label>
        <button type="button" class="btn btn-primary add-product-button" data-style="zoom-in">
            <i class="fa fa-plus"></i> Add Product
        </button>
    </div>
</div>
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    @push('crud_fields_scripts')
        <script>
            $(function(){
                var totalProducts = 0;
                var index = 0;
                var options = "";
                var maxQty = [];
                $.get('{{url("getProducts")}}', function(data, status){
                    totalProducts = data.length;
                    data.forEach(function(d){
                        options += '<option value="'+d.id+'">'+d.name+'</option>';
                    });
                    addProduct();
                    $(document).on("click", '.add-product-button', addProduct);
                    $(document).on("click", '.remove-product-button', removeProduct);
                })
                function addProduct(){
                    index++;
                    var id = 'product_'+index;
                    var html = '<div id="'+id+'">' +
                            '<div class="form-group col-md-8"> <label>Product</label>'+
                            '<select class="form-control" name="product[]">'+
                                options+
                            '</select>'+
                            '</div>'+
                            '<div class="form-group col-md-2">'+
                            '<label>Qty</label>'+
                            '<input name="quantity[]" value="1" class="form-control" type="number" min="1">'+
                            '</div>';
                    if(index != 1){
                        html += '<div class="form-group col-md-2">'+
                                '<label></label>'+
                                '<button type="button" class="btn btn-danger remove-product-button" data-style="zoom-in"'+
                                'style="margin-top: 25px" data-id="'+id+'">'+
                                '<i class="fa fa-minus"></i> Remove'+
                                '</button>'+
                                '</div>';
                    }
                    if(index === totalProducts)
                        $('.add-product-button').attr('disabled', true);
                    $("#add_product").append(html);

                }
                function removeProduct(){
                    $("#"+($(this).attr('data-id'))).remove();
                    index--;
                    $('.add-product-button').removeAttr('disabled');
                }
            });
        </script>
    @endpush
@endif

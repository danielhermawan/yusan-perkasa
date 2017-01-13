<template>
    <form>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Add a new Purchase Order</h3>
            </div>

            <div class="box-body row">

            </div>

            <div class="box-footer">
                <div class="form-group">
                    <span>After saving:</span>
                    <div class="radio">
                        <label>
                            <input type="radio" name="redirect_after_save" value="purchase-order" checked="">
                            go to the table view
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="redirect_after_save" value="purchase-order/create">
                            let me add another item
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="redirect_after_save" value="current_item_edit">
                            edit the new item
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-save"></i> Add</span></button>
                <a href="purchase-order" class="btn btn-default ladda-button" data-style="zoom-in"><span class="ladda-label">Cancel</span></a>
            </div><!-- /.box-footer-->
        </div>
    </form>
</template>
<script>
    export default{

    }

</script>

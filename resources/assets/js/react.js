import React from "react";
import ReactDOM from "react-dom";
import CreatePuchaseOrder from "./views/CreatePurchaseOrder";
import axios from "axios";
import CreateProductReceipt from "./views/CreateProductReceipt";
import CreatePurchaseReturn from "./views/CreatePurchaseReturn";
import CreateSalesOrder from "./views/CreateSalesOrder";
import CreateDeliveryOrder from "./views/CreateDeliveryOrder";
import CreateSalesReturn from "./views/CreateSalesReturn";

axios.defaults.baseURL = 'http://yuan-perkasa.app:8000';

try{
    ReactDOM.render(
        <CreatePuchaseOrder />,
        document.getElementById('create_puchase_order')
    );
}
catch(exception){
    //console.log(exception);
}

try{
    ReactDOM.render(
        <CreateProductReceipt />,
        document.getElementById('create_product_receipt')
    );
}
catch(exception){
    //console.log(exception);
}

try{
    ReactDOM.render(
        <CreatePurchaseReturn />,
        document.getElementById('create_purchase_return')
    );
}
catch(exception){
    //console.log(exception);
}

try{
    ReactDOM.render(
        <CreateSalesOrder />,
        document.getElementById('create_sales_order')
    );
}
catch(exception){
    //console.log(exception);
}

try{
    ReactDOM.render(
        <CreateDeliveryOrder />,
        document.getElementById('create_delivery_order')
    );
}
catch(exception){
    //console.log(exception);
}


try{
    ReactDOM.render(
        <CreateSalesReturn />,
        document.getElementById('create_sales_return')
    );
}
catch(exception){
    //console.log(exception);
}

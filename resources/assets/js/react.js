import React from "react";
import ReactDOM from "react-dom";
import CreatePuchaseOrder from "./views/CreatePurchaseOrder";
import axios from "axios";

axios.defaults.baseURL = 'http://yuan-perkasa.app:8000';

ReactDOM.render(
    <CreatePuchaseOrder />,
    document.getElementById('create_puchase_order')
)

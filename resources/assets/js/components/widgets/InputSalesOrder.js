/**
 * Created by Daniel on 1/17/2017.
 */
import React from "react";
import {dataToSelect} from "../../utils/helpers";
import {Select} from "formsy-react-components";

function InputSalesOrder({onChange, id, sos }){
    const selectSo = dataToSelect(sos, "id", "id",
        sos.length !== 0 ? "Select Sales Order ID" : "Loading Sales Order...");
    return(
        <div>
            <Select
                onChange={onChange}
                value={id}
                disabled={sos.length === 0}
                name="sales_order_id"
                label="Sales Order ID"
                required
                options={ selectSo }
            />
            <div className="row" style={{marginBottom: "7px"}}>
                <div className="col-sm-offset-3 col-sm-3">
                    {   id !== "" &&
                    (
                        <a href={"/sales-order/"+id+"/product"}
                           className="btn btn-default ladda-button">
                                <span className="ladda-label">
                                    Detail SO
                                </span>
                        </a>
                    )
                    }
                </div>
            </div>

        </div>
    );
}

InputSalesOrder.propTypes = {
    onChange: React.PropTypes.func.isRequired,
    id: React.PropTypes.string.isRequired,
    sos: React.PropTypes.array.isRequired
};

export default InputSalesOrder;
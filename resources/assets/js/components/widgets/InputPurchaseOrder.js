/**
 * Created by Daniel on 1/17/2017.
 */
import React from "react";
import {dataToSelect} from "../../utils/helpers";
import {Select} from "formsy-react-components";

function InputPurchaseOrder({onChange, id, pos }){
    const selectPo = dataToSelect(pos, "id", "id",
        pos.length !== 0 ? "Select Purchase Order ID" : "Loading Purchase Order...");
    return(
        <div>
            <Select
                onChange={onChange}
                value={id}
                disabled={pos.length === 0}
                name="purchase_order_id"
                label="Product Order ID"
                required
                options={ selectPo }
            />
            <div className="row" style={{marginBottom: "7px"}}>
                <div className="col-sm-offset-3 col-sm-3">
                    {   id !== "" &&
                        (
                            <a href={"/purchase-order/"+id+"/product"}
                               className="btn btn-default ladda-button">
                                <span className="ladda-label">
                                    Detail PO
                                </span>
                            </a>
                        )
                    }
                </div>
            </div>

        </div>
    );
}

InputPurchaseOrder.propTypes = {
    onChange: React.PropTypes.func.isRequired,
    id: React.PropTypes.string.isRequired,
    pos: React.PropTypes.array.isRequired
};

export default InputPurchaseOrder;
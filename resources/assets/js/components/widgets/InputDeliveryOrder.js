/**
 * Created by Daniel on 1/17/2017.
 */
import React from "react";
import {dataToSelect} from "../../utils/helpers";
import {Select} from "formsy-react-components";

function InputDeliveryOrder({onChange, id, dos }){
    const selectDo = dataToSelect(dos, "id", "id",
        dos.length !== 0 ? "Select Delivery Order ID" : "Loading Delivery Order...");
    return(
        <div>
            <Select
                onChange={onChange}
                value={id}
                disabled={dos.length === 0}
                name="delivery_order_id"
                label="Delivery Order ID"
                required
                options={ selectDo }
            />
            <div className="row" style={{marginBottom: "7px"}}>
                <div className="col-sm-offset-3 col-sm-3">
                    {   id !== "" &&
                    (
                        <a href={"/delivery-order/"+id+"/product"}
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

InputDeliveryOrder.propTypes = {
    onChange: React.PropTypes.func.isRequired,
    id: React.PropTypes.string.isRequired,
    dos: React.PropTypes.array.isRequired
};

export default InputDeliveryOrder;
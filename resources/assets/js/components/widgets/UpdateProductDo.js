/**
 * Created by Daniel on 1/20/2017.
 */
import React from "react";

function UpdateProductDo({products, loadingState}){
    const selectStatus = [
        {
            value: '',
            label: "Select Status"
        },
        {
            value: '1',
            label: "Terkirim"
        },
        {
            value: '0',
            label: "Progress"
        }
    ];
    const productsInput = products.map((p,i) => {
        <div>
            <Input
                name={'product-'+i}
                type="hidden"
                required
                value={p.id}
            />
            <div className="col-md-7">
                <Input
                    name={'productName'}
                    label={"Product"}
                    required
                    value={p.name}
                    disabled={true}
                />
            </div>
            <div className="col-md-5">
                <Select
                    name={'status-'+i}
                    label={"Status"}
                    required
                    options={ selectStatus }
                />
            </div>
        </div>
    });
    return(
        <div>
            <fieldset>
                {
                    loadingState === 1 ? productsInput
                        :
                    loadingState === 0 &&
                    (
                        <div className="row" style={{marginBottom: "7px"}}>
                            <div className="col-sm-offset-3 col-sm-3">
                                <p>Loading Product...</p>
                            </div>
                        </div>
                    )
                }
            </fieldset>
        </div>
    );
}

export default UpdateProductDo;
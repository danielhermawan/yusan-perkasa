/**
 * Created by Daniel on 1/17/2017.
 */
import React from "react";
import _ from "lodash";
import {Input, Select} from "formsy-react-components";

function getProductInput(i, products, remove){
    const selectStatus = [
        {
            value: '',
            label: "Select Status"
        },
        {
            value: '1',
            label: "Retur Barang"
        },
        {
            value: '0',
            label: "Cancel Barang"
        }
    ];
    return <div className="row" key={i}>
        <div className="col-md-6">
            <Select
                name={'product-'+i}
                label={"Product"}
                required
                options={ products }
            />
        </div>
        <div className="col-md-3">
            <Input
                name={'quantity-'+i}
                value={1}
                label={"Qty"}
                type="number"
                min={1}
                placeholder="Qty"
                required
            />
        </div>
        <div className="col-md-3">
            <Select
                name={'status-'+i}
                label={"Status"}
                required
                options={ selectStatus }
            />
        </div>
        { i != 0 &&
        <div className="col-md-3">
            <button type="button" className="btn btn-danger remove-product-button" onClick={
                () => {remove(i)}
            }>
                <i className="fa fa-minus" /> Remove
            </button>
        </div>
        }

    </div>
}

class AddProduct extends React.Component{

    constructor(props) {
        super(props);
        this.state = {
            indexProduct: 1,
        };
        this.removeProduct = this.removeProduct.bind(this);
        this.addProduct = this.addProduct.bind(this);
    }

    addProduct(){
        this.setState({
            indexProduct: this.state.indexProduct + 1
        });
    }

    removeProduct(){
        this.setState({
            indexProduct: this.state.indexProduct - 1
        });
    }

    render(){
        const {products, loadingState} = this.props;
        let selectProducts = products.map(data => {
            return {
                value: data.id,
                label: data.name + ' harga: ' + data.pivot.price
            }
        });
        selectProducts .unshift(
            {
                value: '',
                label: "Select Product"
            }
        );
        let productsInput = [];
        if(loadingState === 1)
            _.times(this.state.indexProduct, i => {
                productsInput.push(getProductInput(i, selectProducts, this.removeProduct));
            });
        return (
            <div>
                {
                    loadingState === 1 &&
                    <div className="row" style={{marginBottom: "7px"}}>
                        <div className="col-sm-3">
                            <button type="button" className="btn btn-primary add-product-button"
                                    onClick={this.addProduct} disabled={products.length === this.state.indexProduct}>
                                <i className="fa fa-plus" /> Add Product
                            </button>
                        </div>
                    </div>
                }
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
}

AddProduct.propTypes = {
    products: React.PropTypes.array.isRequired,
    loadingState: React.PropTypes.number.isRequired
};

export default AddProduct;
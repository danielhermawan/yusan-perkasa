/**
 * Created by Daniel on 1/12/2017.
 */
import React from "react";
import Formsy from "formsy-react";
import _ from "lodash";
import BoxWrapper from "../components/templates/BoxWrapper";
import SubmitButton from "../components/widgets/SubmitButton";
import OptionAfterSaving from "../components/widgets/OptionAfterSaving";
import withFormHandler from "../components/hoc/withFormHandler";
import {Input, Select} from "formsy-react-components";
import {getData, postData} from "../utils/DataHelper";
import {dataToSelect, getParameterByName} from "../utils/helpers";

class CreatePurchase extends React.Component{
    constructor(props){
        super(props);
        this.state = {
            purchaseDemands: [],
            purchase_demand_id: "",
            suppliers: [],
            supplier_id: "",
            products: [],
            indexProduct: -1,
            errors: [],
            isSubmit: false
        };
        this.onSubmit = this.onSubmit.bind(this);
        this.onInputChange = this.onInputChange.bind(this);
        this.onSupplierChange = this.onSupplierChange.bind(this);
        this.removeProduct = this.removeProduct.bind(this);
        this.addProduct = this.addProduct.bind(this);
        this.getProduct = this.getProduct.bind(this);
        this.onDemandChange = this.onDemandChange.bind(this);
    }

    componentDidMount() {
        getData('get-purchase-demand')
            .then(datas => {
                this.setState({
                    purchaseDemands: datas
                });
            });
        getData('get-supplier')
            .then(datas => {
                this.setState({
                    suppliers: datas
                });
            });
        this.setState({
            purchase_demand_id: getParameterByName("purchase-demand")
        });
    }

    getProduct(){
        if(this.state.supplier_id && this.state.purchase_demand_id){
            this.setState({
                indexProduct: 0,
                isSubmit: true
            });
            getData("get-product-supplier/"+this.state.supplier_id+'/'+this.state.purchase_demand_id)
                .then(datas => {
                    this.setState({
                        indexProduct: 1,
                        products: datas,
                        isSubmit: false
                    });
                });
        }
    }

    onInputChange(name, value){
        let state = {};
        state[name] = value;
        this.setState(state);
    }

    addProduct(){
        this.setState({
            indexProduct: this.state.indexProduct + 1
        });
    }

    removeProduct(index){
        this.setState({
            indexProduct: this.state.indexProduct - 1
        });
    }

    onDemandChange(name, value){
        if(value !== "")
            this.setState({
                purchase_demand_id: value
            }, state => {
                this.getProduct();
            });
    }

    onSupplierChange(name, value){
        if(value !== "")
            this.setState({
                supplier_id: value
            }, state => {
                this.getProduct();
            });
    }

    onSubmit(data){
        this.setState({
            isSubmit: true
        });
        data.product = [];
        data.quantity = [];
        _.forOwn(data, (value, key) => {
            if(key.startsWith("product-")){
                data.product.push(value);
                delete data[key];
            }
            else if(key.startsWith("quantity-")){
                data.quantity.push(value);
                delete data[key];
            }
        });
        postData('purchase-order', data)
            .then(respond => {
                if(respond.status === 200){
                    this.setState({
                        isSubmit: false
                    });
                    window.location.pathname = respond.data+"";
                }
            })
            .catch(error => {
                let objErr = error.response.data;
                let errors = [];
                _.forOwn(objErr, (value, key) => {
                    value.forEach(v => {
                        if(!_.some(errors, topic => topic != v))
                        errors.push(v);
                    })
                });
                this.setState({
                    errors: errors,
                    isSubmit: false
                });
            });
    }

    render(){
        const {  enabledSubmit, disabledSubmit, canSubmit} = this.props;
        const selectPurchaseDemand = dataToSelect(this.state.purchaseDemands, "id", "id",
            this.state.purchaseDemands.length !== 0 ? "Select Purchase Demand ID" : "Loading Purchase Demand...");
        const selectSuppliers = dataToSelect(this.state.suppliers, "id", "name",
            this.state.suppliers.length !== 0 ? "Select Supplier" : "Loading Supplier...");
        let selectProducts = this.state.products.map(data => {
            return {
                value: data.id,
                label: data.name + ' harga: ' + data.price
            }
        });
        const errors = this.state.errors.map((e, i) => <li key={i}>{e}</li> );
        selectProducts .unshift(
            {
                value: '',
                label: "Select Product"
            }
        );
        //dataToSelect(this.state.products, "id", "name", "Select Products");
        this.productOptions = [];
        _.times(this.state.indexProduct, i => {
            this.productOptions.push(
                <div className="row" key={i}>
                    <div className="col-md-6">
                        <Select
                            name={'product-'+i}
                            label={"Product"}
                            required
                            options={ selectProducts }
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
                    { i != 0 &&
                        <div className="col-md-3">
                            <button type="button" className="btn btn-danger remove-product-button" onClick={
                                () => {this.removeProduct(i)}
                            }>
                                <i className="fa fa-minus" /> Remove
                            </button>
                        </div>
                    }

                </div>
            );
        });
        return (
            <BoxWrapper title="Add a new Purchase Order">
                {this.state.errors.length != 0 &&
                    <div className="col-md-12">
                        <div className="callout callout-danger">
                            <h4>Please fix the following errors:</h4>
                            <ul>
                                { errors }
                            </ul>
                        </div>
                    </div>
                }
                <Formsy.Form className="form-vertical"
                    onValidSubmit={ this.onSubmit } onValid={ enabledSubmit } onInvalid={ disabledSubmit }>
                    <div className="box-body">
                        <Select
                            onChange={this.onDemandChange}
                            value={this.state.purchase_demand_id}
                            disabled={this.state.purchaseDemands.length === 0}
                            name="purchase_demand_id"
                            label="Product Demand ID"
                            required
                            options={ selectPurchaseDemand }
                        />
                        <div className="row" style={{marginBottom: "7px"}}>
                            <div className="col-sm-offset-3 col-sm-3">
                                {this.state.purchase_demand_id !== "" &&
                                    <a href={"/permintaan-pembelian/"+this.state.purchase_demand_id+"/product"} className="btn btn-default ladda-button">
                                        <span className="ladda-label">
                                            Detail Demand
                                        </span>
                                    </a>
                                }
                            </div>
                        </div>
                        <Input
                            name="due_date"
                            label="Due Date"
                            type="date"
                            placeholder="Due Date"
                            required
                            validations={{
                                matchRegexp: /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/
                            }}
                            validationError="Due Date must be correct date in dd-mm-yyyy format"
                        />
                        <Select
                            onChange={this.onSupplierChange}
                            value={this.state.supplier_id}
                            disabled={this.state.suppliers.length === 0}
                            name="supplier_id"
                            label="Supplier"
                            required
                            options={ selectSuppliers }
                        />
                        {
                            this.state.indexProduct > 0 &&
                            <div className="row" style={{marginBottom: "7px"}}>
                                <div className="col-sm-3">
                                    <button type="button" className="btn btn-primary add-product-button"
                                            onClick={this.addProduct} disabled={this.state.products.length === this.state.indexProduct}>
                                        <i className="fa fa-plus" /> Add Product
                                    </button>
                                </div>
                            </div>
                        }
                        <fieldset>
                            {
                                this.state.indexProduct != 0?
                                this.state.indexProduct != -1 && this.productOptions :
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
                    <div className="box-footer">
                        <OptionAfterSaving
                            route="purchase-order"
                            createRoute="purchase-order/create"/>
                        <SubmitButton isSubmit={this.state.isSubmit} canSubmit={canSubmit} urlBack="purchase-order"/>
                    </div>

                </Formsy.Form>

            </BoxWrapper>
        )
    }
}

export default withFormHandler(CreatePurchase);
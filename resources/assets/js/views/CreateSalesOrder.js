/**
 * Created by Daniel on 1/19/2017.
 */
import React from "react";
import Formsy from "formsy-react";
import BoxWrapper from "../components/templates/BoxWrapper";
import SubmitButton from "../components/widgets/SubmitButton";
import OptionAfterSaving from "../components/widgets/OptionAfterSaving";
import withFormHandler from "../components/hoc/withFormHandler";
import {Input, Select} from "formsy-react-components";
import {getData, postData} from "../utils/DataHelper";
import {dataToSelect} from "../utils/helpers";
import AddProduct from "../components/widgets/AddProducts";
import ErrorView from "../components/widgets/ErrorView";
import _ from "lodash";

class CreateSalesOrder extends React.Component{

    constructor(props) {
        super(props);
        this.state = {
            customers: [],
            customer_id: "",
            products: [],
            loadingProductState: -1,
            errors: [],
            isSubmit: false
        };
        this.onSubmit = this.onSubmit.bind(this);
        this.onCustomerChange = this.onCustomerChange.bind(this);
    }

    componentDidMount() {
        getData('get-customer')
            .then(datas => {
                this.setState({
                    customers: datas
                });
            });
    }

    render(){
        const {  enabledSubmit, disabledSubmit, canSubmit} = this.props;
        const selectCustomer = dataToSelect(this.state.customers, "id", "name",
            this.state.customers.length !== 0 ? "Select Customer" : "Loading Customer...");
        return(
            <BoxWrapper title="Add a new sales order">

                <ErrorView errors={this.state.errors} />

                <Formsy.Form className="form-vertical"
                    onValidSubmit={ this.onSubmit } onValid={ enabledSubmit } onInvalid={ disabledSubmit }>
                    <div className="box-body">
                        <Select
                            onChange={this.onCustomerChange}
                            value={this.state.customer_id}
                            disabled={this.state.customers.length === 0}
                            name="customer_id"
                            label="Customer"
                            required
                            options={ selectCustomer }
                        />
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
                        <AddProduct products={this.state.products} loadingState={this.state.loadingProductState}/>
                    </div>

                    <div className="box-footer">
                        <OptionAfterSaving
                            route="sales-order"
                            createRoute="sales-order/create"/>
                        <SubmitButton isSubmit={this.state.isSubmit} canSubmit={canSubmit} urlBack="sales-order"/>
                    </div>
                </Formsy.Form>

            </BoxWrapper>
        );
    }

    onCustomerChange(name, value){
        if(value !== ""){
            this.setState({
                loadingProductState: 0,
                customer_id: value,
                isSubmit: true
            });
            getData("get-product-customer/"+value)
                .then(datas => {
                    this.setState({
                        loadingProductState: 1,
                        products: datas,
                        isSubmit: false
                    });
                })
                .catch(errors => {
                    console.log(errors);
                });
        }
        else
            this.setState({
                loadingProductState: -1,
                customer_id: value
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
        postData('sales-order', data)
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

}

export default withFormHandler(CreateSalesOrder);
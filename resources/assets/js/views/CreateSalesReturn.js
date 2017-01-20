/**
 * Created by Daniel on 1/20/2017.
 */
import React from "react";
import Formsy from "formsy-react";
import BoxWrapper from "../components/templates/BoxWrapper";
import SubmitButton from "../components/widgets/SubmitButton";
import OptionAfterSaving from "../components/widgets/OptionAfterSaving";
import withFormHandler from "../components/hoc/withFormHandler";
import {Input} from "formsy-react-components";
import {getData, postData} from "../utils/DataHelper";
import {getParameterByName} from "../utils/helpers";
import AddProduct from "../components/widgets/AddProductRetur";
import ErrorView from "../components/widgets/ErrorView";
import _ from "lodash";
import InputDeliveryOrder from "../components/widgets/InputDeliveryOrder";

class CreateSalesReturn extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            dos: [],
            do_id: "",
            products: [],
            loadingProductState: -1,
            errors: [],
            isSubmit: false
        };
        this.onSubmit = this.onSubmit.bind(this);
        this.onDoChange = this.onDoChange.bind(this);
    }

    componentDidMount() {
        getData('get-delivery-order')
            .then(datas => {
                this.setState({
                    dos: datas
                });
            });
        this.setState({
            so_id: getParameterByName("delivery-order")
        });
    }

    render(){
        const {  enabledSubmit, disabledSubmit, canSubmit} = this.props;
        return (
            <BoxWrapper title="Add a new product receipt">

                <ErrorView errors={this.state.errors} />

                <Formsy.Form className="form-vertical"
                             onValidSubmit={ this.onSubmit } onValid={ enabledSubmit } onInvalid={ disabledSubmit }>
                    <div className="box-body">
                        <InputDeliveryOrder onChange={this.onDoChange} id={this.state.do_id} dos={this.state.dos} />
                        <Input
                            name="description"
                            label="Deskripsi"
                            type="text"
                            placeholder="Deskripsi"
                            required
                        />
                        <AddProduct products={this.state.products} loadingState={this.state.loadingProductState}/>
                    </div>

                    <div className="box-footer">
                        <OptionAfterSaving
                            route="retur-penjualan"
                            createRoute="retur-penjualan/create"/>
                        <SubmitButton isSubmit={this.state.isSubmit} canSubmit={canSubmit} urlBack="retur-penjualan"/>
                    </div>

                </Formsy.Form>
            </BoxWrapper>
        )
    }

    onDoChange(name, value){
        if(value !== ""){
            this.setState({
                loadingProductState: 0,
                do_id: value,
                isSubmit: true
            });
            getData("get-product-so-retur/"+value)
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
                so_id: value
            });
    }

    onSubmit(data){
        this.setState({
            isSubmit: true
        });
        data.product = [];
        data.quantity = [];
        data.status = [];
        _.forOwn(data, (value, key) => {
            if(key.startsWith("product-")){
                data.product.push(value);
                delete data[key];
            }
            else if(key.startsWith("quantity-")){
                data.quantity.push(value);
                delete data[key];
            }
            else if(key.startsWith("status-")){
                data.status.push(value);
                delete data[key];
            }
        });
        postData('retur-penjualan', data)
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

export default withFormHandler(CreateSalesReturn);
/**
 * Created by Daniel on 1/13/2017.
 */
import React from "react";

export default function withFormHandler(WrappedComponent){
    return class extends React.Component {
        constructor(props) {
            super(props);
            this.state = {
                canSubmit: false,
                isSubmit: false
            };
            this.enabledSubmit = this.enabledSubmit.bind(this);
            this.disableSubmit = this.disableSubmit.bind(this);
        }

        enabledSubmit(){
            this.setState({
                canSubmit: true
            });
        }

        disableSubmit() {
            this.setState({
                canSubmit: false
            });
        }

        render() {
            return (
                <WrappedComponent
                    {...this.props}
                    {...this.state}
                    enabledSubmit = { this.enabledSubmit }
                    disabledSubmit = { this.disableSubmit } />
            );
        }
    }
}
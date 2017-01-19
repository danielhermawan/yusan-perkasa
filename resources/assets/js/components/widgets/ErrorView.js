/**
 * Created by Daniel on 1/17/2017.
 */
import React from "react";

function ErrorView({errors}){
    const errorsMsg = errors.map((e, i) => <li key={i}>{e}</li> );
    return (
        <div>
            {errors.length != 0 &&
                <div className="col-md-12">
                    <div className="callout callout-danger">
                        <h4>Please fix the following errors:</h4>
                        <ul>
                            { errorsMsg }
                        </ul>
                    </div>
                </div>
            }
        </div>
    );
}

ErrorView.propTypes = {
    errors: React.PropTypes.array.isRequired
};

export default ErrorView;
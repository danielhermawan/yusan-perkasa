/**
 * Created by Daniel on 1/17/2017.
 */
import React from "react";

function DetailButton({ url, label, key}){
    return (
        <div className="row" style={{marginBottom: "7px"}}>
            <div className="col-sm-offset-3 col-sm-3">
                {key !== "" &&
                <a href={url}
                   className="btn btn-default ladda-button">
                    <span className="ladda-label">
                        {label}
                    </span>
                </a>
                }
            </div>
        </div>
    );
}

DetailButton.propTypes = {
    url: React.PropTypes.string.isRequired,
    key: React.PropTypes.string.isRequired,
    label: React.PropTypes.string.isRequired
};

export default DetailButton;
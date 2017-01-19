/**
 * Created by Daniel on 1/13/2017.
 */
import React from "react";

function SubmitButton({isSubmit, canSubmit, urlBack}){
    return (
        <div>
            <div>
                <button type="submit" disabled={!canSubmit || isSubmit}
                        className="btn btn-success ladda-button" style={{marginRight: "10px"}}>
                    <span className="ladda-label"><i className="fa fa-save"/> Add</span>
                </button>
                <a href={urlBack} className="btn btn-default ladda-button"><span className="ladda-label">Cancel</span></a>
            </div>
        </div>
    );
}

SubmitButton.propTypes = {
    isSubmit: React.PropTypes.bool.isRequired,
    canSubmit: React.PropTypes.bool.isRequired,
    urlBack: React.PropTypes.string.isRequired
};

export default SubmitButton;

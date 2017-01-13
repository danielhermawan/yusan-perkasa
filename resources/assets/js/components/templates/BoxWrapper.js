/**
 * Created by Daniel on 1/13/2017.
 */
import React from "react";

function BoxWrapper({children, title}){
    return (
        <div className="box">
            <div className="box-header with-border">
                <h3 className="box-title">{title}</h3>
            </div>
            {children}

        </div>
    );
}

BoxWrapper.propTypes = {
    title: React.PropTypes.string.isRequired
};

export default BoxWrapper;
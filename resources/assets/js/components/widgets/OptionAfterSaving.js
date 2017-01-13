import React from "react";
import {RadioGroup} from "formsy-react-components";

function OptionAfterSaving({route, createRoute}) {
    const options = [
        {value: route, label: 'go to the table view'},
        {value: createRoute, label: 'let me add another item'},
        {value: 'current_item_edit', label: 'edit the new item'}
    ];
    return (
        <RadioGroup
            name="redirect_after_save"
            value={route}
            label="After saving"
            options={options}
            required
        />
    );
}

OptionAfterSaving.propTypes = {
    route: React.PropTypes.string.isRequired,
    createRoute: React.PropTypes.string.isRequired
};


export default OptionAfterSaving;
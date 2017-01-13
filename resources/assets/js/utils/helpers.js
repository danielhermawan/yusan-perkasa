/**
 * Created by Daniel on 1/13/2017.
 */
export function dataToSelect(datas, value, label, title = "Please Select"){
    let results =  datas.map((data) => {
        return {
            value: data[value],
            label: data[label]
        }
    });
    results.unshift(
        {
            value: '',
            label: title
        }
    );
    return results;
}

export function getParameterByName(name, url) {
    if (!url) {
        url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return "";
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
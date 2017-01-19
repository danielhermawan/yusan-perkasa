/**
 * Created by Daniel on 1/13/2017.
 */
import axios from "axios";

export function getData(url){
    return axios.get(url)
        .then(response => {
            return response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
}

export function postData(url, data){
    return axios.post(url, data);

}
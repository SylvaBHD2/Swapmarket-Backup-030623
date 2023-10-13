
function removeFilter(filter) {
    // remove the filter from the url
    var params = new URLSearchParams(location.search);
    // create a string with the parameters
    var newUrl = "";
    for (let param of params) {
        console.log(param);
        let parameter = param[0];
        let values="";
        // split the param[1] to get an array of values
        param[1] = param[1].split(',');
        for (let par of param[1]){
            console.log(par);
            if (par!==filter.value){
                values+=par+',';
                console.log(values);
            }
        }
        // remove the last ","
        if (values.length>0) {
            values = values.slice(0, -1);
            // add the parameter to the new url
            newUrl += `${parameter}=${values}&`;
        }
    }
    // remove the last "&"
    newUrl = newUrl.slice(0, -1);
    console.log(newUrl);
    // change the url
    window.history.replaceState({}, '', `${location.pathname}?${newUrl}`);
    //reload the page
    location.reload();

}

function addFilter(filter) {
    // add the filter to the url
    var params = new URLSearchParams(location.search);
    // create a string with the parameters
    var newUrl = "";

    // 2 possibilities : the parameter already exists or not
    // if it doesn't exist, add it
    console.log(filter.name);
    if (!params.has(filter.name)) {
        // add the parameter to the new url
        newUrl += `${filter.name}=${filter.value}&`;
        // then add the other parameters
        for (let param of params) {
            // add the parameter to the new url
            newUrl += `${param[0]}=${param[1]}&`;
        }
    }
    // if it exists, add the value to the parameter
    else {
        // get the values of the parameter
        let values = params.get(filter.name);
        // add the new value
        values += `,${filter.value}`;
        // add the parameter to the new url
        newUrl += `${filter.name}=${values}&`
        // then add the other parameters
        for (let param of params) {
            // if the parameter is not the one we want to change
            if (param[0] !== filter.name) {
                // add the parameter to the new url
                newUrl += `${param[0]}=${param[1]}&`;
            }
        }
    }
    // remove the last "&"
    newUrl = newUrl.slice(0, -1);
    console.log(newUrl);
    // change the url
    window.history.replaceState({}, '', `${location.pathname}?${newUrl}`);
    //reload the page
    location.reload();

}

function changeSort(sort){
    // remove the sort from the url
    var params = new URLSearchParams(location.search);
    var newUrl = "";
    // create a string with the parameters
    for (let param of params) {
        if(param[0]!=="sort") {
            let parameter = param[0];
            let values = "";
            // split the param[1] to get an array of values
            param[1] = param[1].split(',');
            for (let par of param[1]) {
                console.log(par);
                values += par + ',';
            }
            // remove the last ","
            if (values.length > 0) {
                values = values.slice(0, -1);
                // add the parameter to the new url
                newUrl += `${parameter}=${values}&`;
            }
        }
        console.log(newUrl)
    }
    // add the sort to the url
    newUrl += `sort=${sort.value}`;
    // change the url
    window.history.replaceState({}, '', `${location.pathname}?${newUrl}`);
    //reload the page
    location.reload();
}

function changePage(direction){
    // if direction == previous, go to the previous page
    var params = new URLSearchParams(location.search);
    var currentPage = '0';
    if(params.has("page")){ currentPage = params.get("page")}
    var newUrl = "";
    // create a string with the parameters
    for (let param of params) {
        if(param[0]!=="page") {
            let parameter = param[0];
            let values = "";
            // split the param[1] to get an array of values
            param[1] = param[1].split(',');
            for (let par of param[1]) {
                console.log(par);
                values += par + ',';
            }
            // remove the last ","
            if (values.length > 0) {
                values = values.slice(0, -1);
                // add the parameter to the new url
                newUrl += `${parameter}=${values}&`;
            }
        }
        console.log(newUrl)
    }

    if (direction==="previous"){
        // add the sort to the url
        newUrl += `page=${parseInt(currentPage)-1}`;
    } else if (direction==="next"){
        // add the sort to the url
        newUrl += `page=${parseInt(currentPage)+1}`;
    }
    // change the url
    window.history.replaceState({}, '', `${location.pathname}?${newUrl}`);
    //reload the page
    location.reload();
}
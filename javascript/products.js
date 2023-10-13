


// when the page is loaded :
document.addEventListener("DOMContentLoaded", function() {

    // if there is parameters in the url, check the corresponding checkbox
    let url = new URL(window.location.href);
    var params = url.searchParams;
    let param = params.get('type')
    if (param) {
        param=param.split(',');
        for (let i = 0; i < param.length; i++) {
            document.querySelector(`input[value="${param[i]}"]`).checked = true;
        }
    }
    param = params.get('size')
    if (param) {
        param=param.split(',');
        for (let i = 0; i < param.length; i++) {
            document.querySelector(`input[id="size-${param[i]}"]`).checked = true;
        }
    }
    param = params.get('color')
    if (param) {
        param=param.split(',');
        for (let i = 0; i < param.length; i++) {
            document.querySelector(`input[id="color-${param[i]}"]`).checked = true;
        }
    }
    param = params.get('brand')
    if (param) {
        param=param.split(',');
        for (let i = 0; i < param.length; i++) {
            document.querySelector(`input[id="brand-${param[i]}"]`).checked = true;
        }
    }
    param = params.get('price')
    if (param) {
        param=param.split(',');
        for (let i = 0; i < param.length; i++) {
            document.querySelector(`input[id="price-${param[i]}"]`).checked = true;
        }
    }
    param = params.get('sort')
    if (param) {
        document.querySelector(`select`).value = param;
    }


    // function to add a filter when a checkbox is checked
    let checkboxes = document.querySelectorAll("input[type=checkbox]");
    for (let i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            // add the filter to the list of active filters
            let filter = document.createElement("div");
            filter.classList.add("applied-filter");
            //use what's in the label associated with the checkbox
            filter.innerHTML = `
                <input type="checkbox"  id="show-${checkboxes[i].value}" name="${checkboxes[i].value}" onclick="removeFilter(this)"
                value="${checkboxes[i].value}" checked>
                <label for="show-${checkboxes[i].value}">${checkboxes[i].nextElementSibling.innerHTML}</label>
                `;
            document.querySelector(".applied-filters").appendChild(filter);
        }
    }
});





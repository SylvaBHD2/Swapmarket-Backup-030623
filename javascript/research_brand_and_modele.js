const fs = require('fs');
const { ContextExclusionPlugin } = require('webpack');
const SneaksAPI = require('sneaks-api');
const sneaks = new SneaksAPI();

sneaks.getProducts(" ", 1, function(err, products){
    console.log(products);
});


//getProducts(keyword, limit, callback) takes in a keyword and limit and returns a product array 
sneaks.getProducts("", 10000, function(err, products){
    var contenu_sql = [];
    for(let item of products) {
        contenu_sql.push([item.shoeName.replaceAll("\"","\\\""),item.releaseDate,item.brand.replaceAll("\"","\\\"")]);
    };
    var brand_name = [];
    for(let item of contenu_sql) {
        if (!brand_name.includes(item[2])) {
            brand_name.push(item[2]);
        };
    }
        var final_result = "delete from brand where brand_name != \"\";\ndelete from model where model_id > -1;\n";
        for (let item of brand_name) {
            final_result = final_result + "insert into brand values(\"" + item + "\");\n";
        };
        var tempo = 1;
        for (let item of contenu_sql) {
            final_result = final_result + "insert into model values("+ tempo + ",\"" + item[0] + "\",\"" + item[1] + "\",\"" + item[2] + "\");\n";
            tempo = tempo + 1;
        };
        fs.writeFile('./includes/insertion(old_db).sql', final_result, err => {
            if(err) {
                console.log(err);
            }
            else {
                console.log('fichier ajouter');
            };
        });

});

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function setbrandlist(obj)
{
    $.post("getBrand.php", {brandpy: obj}, function (data) {
        $("#brand_list").html(data);
    });
}
function setArea(obj)
{
    window.location.href = funcUrlDel_t("areaname","brand") + '&areaname=' + obj;
}
function setBrand(obj)
{
    window.location.href = funcUrlDel_t("brand") + '&brand=' + obj;
}
// 删除url中某个参数,并跳转
function funcUrlDel_t(name1, name2) {
    var loca = window.location;
    var baseUrl = loca.origin + loca.pathname + "?";
    var query = loca.search.substr(1);

    if (query.indexOf(name1) > -1 || query.indexOf(name2) > -1) {
        var obj = {}
        var arr = query.split("&");
        for (var i = 0; i < arr.length; i++) {
            arr[i] = arr[i].split("=");
            if (arr[i][0] == name1 || arr[i][0] == name2)
            {
            } else {
                obj[arr[i][0]] = arr[i][1];
            }
        }
        ;
//        delete obj[name];
//        delete obj[name2];
        var url = baseUrl + JSON.stringify(obj).replace(/[\"\{\}]/g, "").replace(/\:/g, "=").replace(/\,/g, "&");
        return url
    } else {
        return loca
    }
    ;

}
// 删除url中某个参数,并跳转
function funcUrlDel(name) {
    var loca = window.location;
    var baseUrl = loca.origin + loca.pathname + "?";
    var query = loca.search.substr(1);

    if (query.indexOf(name) > -1) {
        var obj = {}
        var arr = query.split("&");
        for (var i = 0; i < arr.length; i++) {
            arr[i] = arr[i].split("=");
            obj[arr[i][0]] = arr[i][1];
        }
        ;
        delete obj[name];
//        delete obj[name2];
        var url = baseUrl + JSON.stringify(obj).replace(/[\"\{\}]/g, "").replace(/\:/g, "=").replace(/\,/g, "&");
        return url
    } else {
        return loca
    }
}
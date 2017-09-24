/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    $("#mainSearch").click(function () {
        window.location.href = funcUrlDel("mainkey") + '&mainkey=' + $("#search").val();
    });
});
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
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {
//    resetClass(2, 0);
    $("#mainSearch").click(function () {
        window.location.href = funcUrlDel("mainkey") + '&mainkey=' + $("#search").val();
    });
});
function resetClass(obj, obj2) {
//    $.post("searchClass.php", {varietyid: obj}, function (data) {
//        $("#crop_class_2").html(data);
//    });
    var targetUrl = funcUrlDel("classid", "class2id");
    targetUrl = targetUrl + "&classid=" + obj;
    location.href = targetUrl;
    $("#text_condition").append(obj2);
}
function setClass2id(obj, obj2) {
    $("#text_condition").append(obj2);
    var url = window.location.href;
    var targetUrl = funcUrlDel("classid", "class2id");
    targetUrl = targetUrl + "&class2id=" + obj;
    location.href = targetUrl;
    $("#text_condition").append(obj2);
}


function setYear(obj) {
    $("#text_condition").append(obj);
    var url = window.location.href;
    var targetUrl = funcUrlDel("year");
    targetUrl = targetUrl + "&year=" + obj;
    location.href = targetUrl;
    $("#text_condition").append(obj);
}
function setIsGen(obj) {
    $("#text_condition").append(obj);
    var url = window.location.href;
    var targetUrl = funcUrlDel("isgen");
    targetUrl = targetUrl + "&isgen=" + obj;
    location.href = targetUrl;
    $("#text_condition").append(obj);
}

function gotoUrl(obj) {
    var url = window.location.href;
    var targetUrl = funcUrlDel("type");
    targetUrl = targetUrl + "&type=" + obj;
    location.href = targetUrl;
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
        var url = baseUrl + JSON.stringify(obj).replace(/[\"\{\}]/g, "").replace(/\:/g, "=").replace(/\,/g, "&");
        return url
    } else {
        return loca
    }
    ;
}
// 删除url中某个参数,并跳转
function funcUrlDel(name, name2) {
    var loca = window.location;
    var baseUrl = loca.origin + loca.pathname + "?";
    var query = loca.search.substr(1);

    if (query.indexOf(name) > -1 || query.indexOf(name2) > -1) {
        var obj = {}
        var arr = query.split("&");
        for (var i = 0; i < arr.length; i++) {
            arr[i] = arr[i].split("=");
            if (arr[i][0] == name || arr[i][0] == name2)
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


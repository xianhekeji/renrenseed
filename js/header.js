/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function login()
{
    var url = location.href;
    if (url == "http://www.seed168.com/" || url == "https://www.seed168.com/") {
        url = "https://www.renrenseed.com/";
    }
    url_new = url.replace("#", '');
//    alert(funcUrlDel);
    var obj = new WxLogin({
        id: "login_container",
        appid: "wx64b9c6f2847a57c5",
        scope: "snsapi_login",
        redirect_uri: encodeURIComponent(url_new),
        state: "99",
        style: "white",
        href: "https://www.renrenseed.com/css/loginDialog.css"
    });
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
    }
    ;
}
$(function ($) {
    $("#logout").hover(function () {
        $.post("comm/wxlogout.php.php", {}, function (data) {
//            $("#list").html(data);
        });
    });
    //弹出登录
    $("#example").hover(function () {
        $(this).stop().animate({
            opacity: '1'
        }, 600);
    }, function () {
        $(this).stop().animate({
            opacity: '0.6'
        }, 1000);
    }).on('click', function () {
        $("body").append("<div id='mask'></div>");
        $("#mask").addClass("mask").fadeIn("slow");
        $("#LoginBox").fadeIn("slow");
    });
//
//按钮的透明度
    $("#loginbtn").hover(function () {
        $(this).stop().animate({
            opacity: '1'
        }, 600);
    }, function () {
        $(this).stop().animate({
            opacity: '0.8'
        }, 1000);
    });
//文本框不允许为空---按钮触发
    $("#loginbtn").on('click', function () {
        var txtName = $("#txtName").val();
        var txtPwd = $("#txtPwd").val();
        if (txtName == "" || txtName == undefined || txtName == null) {
            if (txtPwd == "" || txtPwd == undefined || txtPwd == null) {
                $(".warning").css({display: 'block'});
            } else {
                $("#warn").css({display: 'block'});
                $("#warn2").css({display: 'none'});
            }
        } else {
            if (txtPwd == "" || txtPwd == undefined || txtPwd == null) {
                $("#warn").css({display: 'none'});
                $(".warn2").css({display: 'block'});
            } else {
                $(".warning").css({display: 'none'});
            }
        }
    });
//文本框不允许为空---单个文本触发
    $("#txtName").on('blur', function () {
        var txtName = $("#txtName").val();
        if (txtName == "" || txtName == undefined || txtName == null) {
            $("#warn").css({display: 'block'});
        } else {
            $("#warn").css({display: 'none'});
        }
    });
    $("#txtName").on('focus', function () {
        $("#warn").css({display: 'none'});
    });
//
    $("#txtPwd").on('blur', function () {
        var txtName = $("#txtPwd").val();
        if (txtName == "" || txtName == undefined || txtName == null) {
            $("#warn2").css({display: 'block'});
        } else {
            $("#warn2").css({display: 'none'});
        }
    });
    $("#txtPwd").on('focus', function () {
        $("#warn2").css({display: 'none'});
    });
//关闭
    $(".close_btn").hover(function () {
        $(this).css({color: 'black'})
    }, function () {
        $(this).css({color: '#ffffff'})
    }).on('click', function () {
        $("#LoginBox").fadeOut("fast");
        $("#mask").css({display: 'none'});
    }
    );
});
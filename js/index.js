/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//标签切换
var b_3 = b_4 = b_5 = b_6 = true;
$(function () {
    $("ul > li").click(tab);
    function tab() {
        var tab = $(this).attr("title");
        $("#" + tab).show().siblings().hide();
        getData(tab);
    }
    getSelectVal();
    $("#bigname").change(function () {
        getSelectVal();
    });
//    $("#searchBtn").click(function () {
//        $.post("model/index/conditonSearchCrop.php", {bigname: $("#bigname").val(), smallname: $("#smallname").val(), select_year: $("#select_year").val(), select_isgen: $("#select_isgen").val()}, function (data) {
//            $("#list").html(data);
//        });
//    });
    $("#searchBtn").click(function () {
//        $.post("model/crop/croplist.php", {bigname: $("#bigname").val(), smallname: $("#smallname").val(), select_year: $("#select_year").val(), select_isgen: $("#select_isgen").val()}, function (data) {
////            $("#list").html(data);
//        });
        window.location.href = 'model/crop/croplist.php?classid=' + $("#bigname").val() + "&class2id=" + $("#smallname").val() + "&year=" + $("#select_year").val() + "&isgen=" + $("#select_isgen").val();
    });
    $("#mainSearch").click(function () {
        window.location.href = 'model/index/cropSearch.php?mainkey=' + $("#search").val();
    });
    $('.slide .icon li').not('.up,.down').mouseenter(function () {
        $('.slide .info').addClass('hover');
        $('.slide .info li').hide();
        $('.slide .info li.' + $(this).attr('class')).show();//.slide .info li.qq
    });
    $('.slide').mouseleave(function () {
        $('.slide .info').removeClass('hover');
    });

    $('#btn').click(function () {
        $('.slide').toggle();
        if ($(this).hasClass('index_cy')) {
            $(this).removeClass('index_cy');
            $(this).addClass('index_cy2');
        } else {
            $(this).removeClass('index_cy2');
            $(this).addClass('index_cy');
        }

    });

});
function getData(tab) {
    if (tab == "s_content_3" && b_3) {
        $("#" + tab).append("<div class='loading_div'></div>");
        b_3 = false;
        $.post("model/index/getTuijian.php", {}, function (data) {
            $("#s_content_3").html(data);
        });
    } else if (tab == "s_content_4" && b_4) {
        $("#" + tab).append("<div class='loading_div'></div>");
        b_4 = false;
        $.post("model/index/getZuixin.php", {}, function (data) {
            $("#s_content_4").html(data);
        });
    } else if (tab == "s_content_5" && b_5) {
        $("#" + tab).append("<div class='loading_div'></div>");
        b_5 = false;
        $.post("model/index/getBendi.php", {}, function (data) {
            $("#s_content_5").html(data);
        });
    } else if (tab == "s_content_6" && b_6) {
        $("#" + tab).append("<div class='loading_div'></div>");
        b_6 = false;
        $.post("model/index/getArticle.php", {}, function (data) {
            $("#s_content_6").html(data);
        });
    }
}
function getSelectVal() {
    $.getJSON("comm/getCategory_1.php", {bigname: $("#bigname").val()}, function (json) {
        var smallname = $("#smallname");
        $("option", smallname).remove(); //清空原有的选项 
        $.each(json, function (index, array) {
            var option = "<option value='" + array['id'] + "'>" + array['title'] + "</option>";
            smallname.append(option);
        });
    });
}

//页面加载完毕后开始执行的事件

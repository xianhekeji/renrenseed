$(document).ready(function () {
//    nav-li hover e
    var num;
    $('.nav-main>li[id]').hover(function () {
        /*图标向上旋转*/
        $(this).children().removeClass().addClass('hover-up');
        /*下拉框出现*/
        var Obj = $(this).attr('id');
        num = Obj.substring(3, Obj.length);
        $('#box-' + num).slideDown(300);
    }, function () {
        /*图标向下旋转*/
        $(this).children().removeClass().addClass('hover-down');
        /*下拉框消失*/
        $('#box-' + num).hide();
    });
//    hidden-box hover e
    $('.hidden-box').hover(function () {
        /*保持图标向上*/
        $('#li-' + num).children().removeClass().addClass('hover-up');
        $(this).show();
    }, function () {
        $(this).slideUp(200);
        $('#li-' + num).children().removeClass().addClass('hover-down');
    });
});
document.onkeydown = function (event) {
    var target, code, tag;
    if (!event) {
        event = window.event; //针对ie浏览器
        target = event.srcElement;
        code = event.keyCode;
        if (code == 13) {
            tag = target.tagName;
            if (tag == "TEXTAREA") {
                return true;
            } else {
                return false;
            }
        }
    } else {
        target = event.target; //针对遵循w3c标准的浏览器，如Firefox
        code = event.keyCode;
        if (code == 13) {
            tag = target.tagName;
            if (tag == "INPUT") {
                return false;
            } else {
                return true;
            }
        }
    }
};
document.onkeydown = function (event) {
    var target, code, tag;
    if (!event) {
        event = window.event; //针对ie浏览器
        target = event.srcElement;
        code = event.keyCode;
        if (code == 13) {
            tag = target.tagName;
            if (tag == "TEXTAREA") {
                return true;
            } else {
                return false;
            }
        }
    } else {
        target = event.target; //针对遵循w3c标准的浏览器，如Firefox
        code = event.keyCode;
        if (code == 13) {
            tag = target.tagName;
            if (tag == "INPUT") {
                return false;
            } else {
                return true;
            }
        }
    }
};
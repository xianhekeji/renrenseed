// JavaScript Document
$(function () {
    $("#user").focus();
    $("input:text,textarea,input:password").focus(function () {
        $(this).addClass("cur_select");
    });
    $("input:text,textarea,input:password").blur(function () {
        $(this).removeClass("cur_select");
    });


    $(".btn").live('click', function () {
        var user = $("#user").val();
        var pass = $("#pass").val();
        if (user == "") {
            $('<div id="msg" />').html("用户名不能为空！").appendTo('.sub').fadeOut(2000);
            $("#user").focus();
            return false;
        }
        if (pass == "") {
            $('<div id="msg" />').html("密码不能为空！").appendTo('.sub').fadeOut(2000);
            $("#pass").focus();
            return false;
        }
        $.ajax({
            type: "POST",
            url: "login.php?action=login",
            dataType: "json",
            data: {"user": user, "pass": pass},
            beforeSend: function () {
                $('<div id="msg" />').addClass("loading").html("正在登录...").css("color", "#999").appendTo('.sub');
            },
            success: function (json) {
                if (json.success == 1) {
                    $("#login_form").remove();
                    var div = "<div id='result'><p>恭喜您登录成功！</p><p>3秒后自动跳转...</p><p><a href='" + json.url + "' id='to'>返回之前页</a></p><p><a href='#' id='logout'>【退出】</a></p></div>";
                    $("#login").append(div);
                    setTimeout(function () {
                        window.location.href = (json.url == null || json.url == "") ? "www.renrenseed.com" : json.url;
                    }, 3000);

                } else {
                    $("#msg").remove();
                    $('<div id="errmsg" />').html(json.msg).css("color", "#999").appendTo('.sub').fadeOut(2000);
                    return false;
                }
            }
        });
    });

    $("#logout").live('click', function () {
        $.post("login.php?action=logout", function (msg) {
            if (msg == 1) {
                $("#result").remove();
                var div = "<div id='login_form'><p><label>用户名：</label> <input type='text' class='input' name='user' id='user' /></p><p><label>密 码：</label> <input type='password' class='input' name='pass' id='pass' /></p><div class='sub'><input type='submit' class='btn' value='登 录' /></div></div>";
                $("#login").append(div);
            }
        });
    });
});
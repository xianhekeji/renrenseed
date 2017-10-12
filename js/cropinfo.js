/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function checkLogin(obj)
{

    $.ajax({
        url: "../crop/checkLogin.php",
        type: "post",
        data: '',
        processData: false,
        contentType: false,
        success: function (data) {
            if (data > 0) {
                window.location.href = "https://www.renrenseed.com/model/crop/addDianping.php?id=" + obj;
            } else
            {
                alert("请登录后再点评！");
            }
        },
        error: function (e) {
            alert("错误！！");
            window.clearInterval(timer);
        }
    });
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var i = 1;
function add_tupian()
{
    var div0 = document.createElement("div");
    div0.className = 'div_add_img';
    var div1 = document.createElement("div");             //创建一个div
    var div2 = document.createElement("div");
    div2.id = 'imgPreview' + i;
    div2.className = 'div_img';
    var img = document.createElement("img");
    img.className = 'pre_img';
    div2.appendChild(img);
    var Oa = document.createElement("a");
    Oa.href = "javascript:;";
    Oa.className = 'upload';
    Oa.textContent = '选择文件';
    Oa.id = "a_" + i;
    var Oinput = document.createElement("input");
    Oinput.type = 'file';
    Oinput.id = 'add_img' + i;
    Oinput.name = 'myfile[]';
    Oinput.className = 'change';
    Oa.appendChild(Oinput);
    div1.appendChild(Oa);
    div0.appendChild(div2);
    div0.appendChild(div1);
    var picInut = document.getElementById('tianjia');
    picInut.appendChild(div0);
    var obj = document.getElementById("add_img" + i);
    obj.setAttribute("onchange", "PreviewImage(this," + i + ")");
    console.log(div0.innerHTML);
    i++;
}
function PreviewImage(imgFile, j)
{
    var filextension = imgFile.value.substring(imgFile.value.lastIndexOf("."), imgFile.value.length);
    filextension = filextension.toLowerCase();
    if ((filextension != '.jpg') && (filextension != '.gif') && (filextension != '.jpeg') && (filextension != '.png') && (filextension != '.bmp'))
    {
        alert("对不起，系统仅支持标准格式的照片，请您调整格式后重新上传，谢谢 !");
        imgFile.focus();
    } else
    {
        var path;
        if (document.all)//IE
        {
            imgFile.select();
            path = document.selection.createRange().text;
            document.getElementById("imgPreview" + j).innerHTML = "";
            document.getElementById("imgPreview" + j).style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled='true',sizingMethod='scale',src=\"" + path + "\")";//使用滤镜效果      
        } else//FF
        {
            path = window.URL.createObjectURL(imgFile.files[0]);// FF 7.0以上
            //path = imgFile.files[0].getAsDataURL();// FF 3.0
            document.getElementById("imgPreview" + j).innerHTML = "<img id='img" + j + "' class='pre_img' src='" + path + "'/>";
            //document.getElementById("img1").src = path;
        }
    }
}
$(function () {
    get_rate(10);
});
function get_rate(rate) {
    rate = rate.toString();
    console.log("rate" + rate);
    var s;
    var g;
    $("#g").show();
    if (rate.length >= 3) {
        s = 10;
        g = 0;
        $("#g").hide();
    } else if (rate == "10") {
        s = 10;
        g = 0;
    } else {
        s = rate.substr(0, 1);
        g = rate.substr(1, 1);
    }
//    $("#s").text(s);
//    $("#g").text("." + g);
    $(".dp_big_rate_up").animate({width: (parseInt(s) + parseInt(g) / 10) * 14, height: 26}, 1000);
    $(".dp_big_rate span").each(function () {
        $(this).mouseover(function () {
            console.log("dp_bigrate span" + $(this).attr("rate"));
            $(".dp_big_rate_up").width($(this).attr("rate") * 14);
            var i = parseInt($(this).attr("rate"));
            switch (i)
            {
                case 2:
                    $("#s").text("不推荐");
                    $("#g").val("2");
                    break;
                case 4:
                    $("#s").text("一般");
                    $("#g").val("4");
                    break;
                case 6:
                    $("#s").text("较好");
                    $("#g").val("6");
                    break;
                case 8:
                    $("#s").text("推荐");
                    $("#g").val("8");
                    break;
                case 10:
                    $("#s").text("非常推荐");
                    $("#g").val("10");
                    break;
                default:
                    $("#s").text("请选择星级");
                    $("#g").val("10");
                    break;
            }
            $("#g").css("display", "none");
        }).click(function () {
            var score = $(this).attr("rate");
            $("#dp_my_rate").html("您的评分：<span>" + score + "</span>");
            $.ajax({
                type: "POST",
                url: "action.php",
                data: "score=" + score,
                success: function (msg) {
                    //alert(msg);
                    if (msg == 1) {
                        $("#my_rate").html("<span>您已经评过分了！</span>");
                    } else if (msg == 2) {
                        $("#my_rate").html("<span>您评过分了！</span>");
                    } else {
                        // get_rate(msg);
                    }
                }
            });
        })
    })
    $(".dp_big_rate").mouseout(function () {
//        $("#s").text(s);
//        $("#g").text("." + g);
        //$(".big_rate_up").width((parseInt(s) + parseInt(g) / 10) * 14);
    })
}
function submitDianping(cropid)
{
    var form = new FormData(document.getElementById("form_dianping"));
    form.append("fenshu", $("#g").val());
    form.append("crop_id", cropid);
    $.ajax({
        url: "../crop/actionDianping.php",
        type: "post",
        data: form,
        processData: false,
        contentType: false,
        success: function (data) {
            var json = JSON.parse(data);
            if (json.lastid > 0) {
                alert("点评成功！");
                window.location.reload();
            } else
            {
                alert(json.msg);
            }
        },
        error: function (e) {
            alert("错误！！");
            window.clearInterval(timer);
        }
    });
}


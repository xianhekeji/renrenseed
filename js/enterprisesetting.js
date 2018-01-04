var i = 0;
var editor1;
$(function () {
    KindEditor.ready(function (K) {
        var editor = K.editor({
            allowFileManager: true,
            cssPath: 'plugins/code/prettify.css',
            uploadJson: 'upload_json_enterprise.php',
            fileManagerJson: 'file_manager_json_enterprise.php',
        });
        K('#image1').click(function () {
            editor.loadPlugin('image', function () {
                editor.plugin.imageDialog({
                    imageUrl: 'https://www.renrenseed.com' + K('#url1').val(),
                    clickFn: function (url, title, width, height, border, align) {
                        K('#url1').val(url);
                        editor.hideDialog();
                    }
                });
            });
        });
    });
    KindEditor.ready(function (K) {
        editor1 = K.create('textarea[name="introduce"]', {
            cssPath: 'plugins/code/prettify.css',
            uploadJson: 'upload_json_enterprise.php',
            fileManagerJson: 'file_manager_json_enterprise.php',
            allowFileManager: true,
            afterCreate: function () {
                var self = this;
                K.ctrl(document, 13, function () {
                    self.sync();
                    K('form[name=example]')[0].submit();
                });
                K.ctrl(self.edit.doc, 13, function () {
                    self.sync();
                    K('form[name=example]')[0].submit();
                });
            },
            items: [
                'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
                'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                'anchor', 'link', 'unlink', '|', 'about'],
            afterBlur: function () {
                this.sync();
            }//需要添加的  

        });
        prettyPrint();
    });
    $("#name").autocomplete({
        source: "../Action/searchEnterprise.php",
        minLength: 1,
        autoFocus: false,
        select: function (event, ui) {
            // event 是当前事件对象
            ss = ui.item.label.split(";");
            $.post("../Action/searchEnterpriseById.php", {"EnterpriseId": ss[0]}, function (data) {
                $("#flag").empty();
                if (data["EnterpriseFlag"] == 1)
                {
                    $("#flag").append("<text>已作废</text>");
                    $("#flag_qiyong").show();
                    $("#flag_zuofei").hide();
                } else
                {
                    $("#flag").append("<text>已启用</text>");
                    $("#flag_qiyong").hide();
                    $("#flag_zuofei").show();
                }
                var c_img = data["EnterpriseUserAvatar"];
                if (c_img !== null && c_img !== "") {
                    $("#crop_img").empty();
                    var imgurl = '<td><a href="https://www.renrenseed.com/files/companyImgs/' + c_img + '" target="_blank"><img src="https://www.seed168.com/mobileinterface/IE/companyImgs/' + c_img + '" height="100" width="100" /></a></td>';
                    $("#crop_img").append(imgurl);
                }
                //这里你可以处理获取的数据。我使用是json 格式。你也可以使用其它格式。或者为空，让它自己判断得了  
                $("#name").val(data["EnterpriseId"] + ";" + data["EnterpriseName"]);
                $("#address").val(data["EnterpriseAddressDetail"]);
                $("#com_url").val(data["EnterpriseWeb"]);
                $("#star").val(data["EnterpriseCommentLevel"]);
                $("#selected_phone").val(data["EnterpriseTelephone"]);
                $.post("../Action/getHtmlData.php", {"data": data["EnterpriseIntroduce"]}, function (data) {
                    editor1.html(data);
                });






//                $("#introduce").val(stripslashes(data["EnterpriseIntroduce"]));
                $("#select_province").find("option[text='" + data["province"] + "']").attr("selected", true);
                get_select_city_check(data["city"], data["zone"]);
                $("#select_city").find("option[text='" + str + "']").attr("selected", true);
            }, 'json');
        }
    });
});
function htmlspecialchars_decode(str) {
    str = str.replace(/&amp;/g, '&');
    str = str.replace(/&lt;/g, '<');
    str = str.replace(/&gt;/g, '>');
    str = str.replace(/&quot;/g, "'");
    str = str.replace(/&#039;/g, "'");
    return str;
}

$().ready(function () {
    get_select_province();
    $("#select_province").change(function () {//省份部分有变动时，执行getVal()函数 
        //alert($("#select_1").val());
        get_select_city();
    });
    $("#select_city").change(function () {//省份部分有变动时，执行getVal()函数 
        //alert($("#select_1").val());
        get_select_zone();
    });
});
function get_select_province() {
    $.post("../Action/getSelectProvince.php", {sf_id: encodeURI($("#select_province").val())}, function (json) {
        var select_province = $("#select_province");
        $("option", select_province).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
        $.each(json, function (index, array) {
            var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
            select_province.append(option);
        });
    }, "json");
}
function get_select_city() {
    $.post("../Action/getSelectCity.php", {sf_id: $("#select_province").val()}, function (json) {
        var select_city = $("#select_city");
        $("option", select_city).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
        $.each(json, function (index, array) {
            var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
            select_city.append(option);
        });
    }, "json");
}
function get_select_zone() {
    $.post("../Action/getSelectZone.php", {sf_id: $("#select_city").val()}, function (json) {
        var select_zone = $("#select_zone");
        $("option", select_zone).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
        $.each(json, function (index, array) {
            var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
            select_zone.append(option);
        });
    }, "json");
}

function get_select_city_check(str_city, str_zone) {
    $.post("../Action/getSelectCity.php", {sf_id: $("#select_province").val()}, function (json) {
        var select_city = $("#select_city");
        $("option", select_city).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
        $.each(json, function (index, array) {
            var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
            select_city.append(option);
        });
        $("#select_city").find("option[text='" + str_city + "']").attr("selected", true);
        get_select_zone_check(str_zone);
    }, "json");
}
function get_select_zone_check(str) {
    $.post("../Action/getSelectZone.php", {sf_id: $("#select_city").val()}, function (json) {
        var select_zone = $("#select_zone");
        $("option", select_zone).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
        $.each(json, function (index, array) {
            var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
            select_zone.append(option);
        });
        $("#select_zone").find("option[text='" + str + "']").attr("selected", true);
    }, "json");
}
function addPhone() {
    var phone = $.trim($("#phone").val());
    if (phone == "") {
        alert("电话号码不能为空");
    } else {
//        alert($("#select_phone_class").val() + ':' + phone + ';');
//        $("#selected_phone").append("111");
        var aa = $("#select_phone_class").val() + ':' + phone + ';';

        $("#selected_phone").val($("#selected_phone").val() + aa);
        $("#phone").val("");
    }
}
function resetPhone()
{
    $("#selected_phone").val("");
}

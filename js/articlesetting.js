var editor1;
KindEditor.ready(function (K) {
    editor1 = K.create('textarea[name="content1"]', {
        cssPath: '../../../js/plugins/code/prettify.css',
        uploadJson: '../upload_json.php',
        fileManagerJson: '../file_manager_json.php',
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

KindEditor.ready(function (K) {
    var editor = K.editor({
        allowFileManager: true,
        cssPath: '../../../js/plugins/code/prettify.css',
        uploadJson: '../upload_json.php',
        fileManagerJson: '../file_manager_json.php',
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

$(function () {
    get_select_class();
    $("#select_class").change(function () {//省份部分有变动时，执行getVal()函数 
        //alert($("#select_1").val());

    });

    provinces = "";
    $("#au_province").autocomplete({
        source: "../../Action/searchLabel.php",
        minLength: 1,
        autoFocus: false,
        select: function (event, ui) {
            ss = ui.item.label;
            provinces = provinces + ss + ";";
            $("#t_province").empty();
            $("#t_province").append(provinces);
            $("#au_province").val("");
        }
    });
    $("#title").autocomplete({
        source: "../../Action/searchArticle.php",
        minLength: 1,
        autoFocus: false,
        select: function (event, ui) {
            ss = ui.item.label.split(";");
            $.post("../../Action/searchArticleById.php", {"ArticleId": ss[0]}, function (data) {
                $("#titlenew").val(data["ArticleTitle"]);
                $("#url1").val(data["ArticleCover"]);
                $("#t_province").val(data["ArticleLabel"]);
                $.post("../../Action/getHtmlData.php", {"data": data["ArticleContent"]}, function (data) {
                    editor1.html(data);
                });
            }, 'json');
        }
    });
})
function addPhone() {
    var phone = $.trim($("#au_province").val());
    if (phone == "") {
        alert("标签不能为空");
    } else {
        if ($.trim($("#t_province").val()) == "") {
            $("#t_province").val(phone);
        } else {
            $("#t_province").val($("#t_province").val() + ";" + phone);
        }

        $("#au_province").val("");
    }
}
function resetProvince() {
    provinces = "";
    $("#t_province").empty();
    $("#t_province").val("");
}
function get_select_class() {
    $.post("../../Action/getSelectArticleClass.php", {sf_id: encodeURI($("#select_class").val())}, function (json) {
        var select_class = $("#select_class");
        $("option", select_class).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
        $.each(json, function (index, array) {
            var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
            select_class.append(option);
        });
    }, "json");
}

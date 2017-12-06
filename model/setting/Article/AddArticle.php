<?php
require '../../../common.php';
include '../../../wxAction.php';
$htmlData = '';
$arr_province = $_POST ['t_province'];
if (!empty($_POST ['url1'])) {
    $url = $_POST ['url1'];
}
$title = $_POST ['title'];
if (!empty($_POST ['content1'])) {
    if (get_magic_quotes_gpc()) {
        $htmlData = stripslashes($_POST ['content1']);
    } else {
        $htmlData = $_POST ['content1'];
    }
}
;
$data = htmlspecialchars($htmlData);
if (isset($_POST ["add"])) {
    $sql = "insert into WXArticle VALUES ( NULL, '$title', '$data', '$time', '0', '$url','$arr_province' )";
    $result_add = $db->query($sql);
    $result_id = $db->lastInsertId();
    echo "<script>alert(" . $result_id . ")</script>";
}
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>文章添加</title>
        <link rel="stylesheet" href="../../../css/jquery.ui.autocomplete.css">
        <script type="text/javascript" src="../../../js/jquery-1.4.2.js"></script>
        <script type="text/javascript" src="../../../js/jquery.ui.core.js"></script>
        <script type="text/javascript" src="../../../js/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="../../../js/jquery.ui.position.js"></script>
        <script type="text/javascript" src="../../../js/jquery.ui.autocomplete.js"></script>

        <link rel="stylesheet" href="../../../themes/default/default.css" />
        <link rel="stylesheet" href="../../../js/plugins/code/prettify.css" />
        <script charset="utf-8" src="../../../js/kindeditor.js"></script>
        <script charset="utf-8" src="../../../js/lang/zh_CN.js"></script>
        <script charset="utf-8" src="../../../js/plugins/code/prettify.js"></script>
        <script>
            $(function () {
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
                $("#au_province").val("");
            }</script>
        <script>
            KindEditor.ready(function (K) {
                var editor1 = K.create('textarea[name="content1"]', {
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
        </script>
        <script>
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
        </script>
    </head>
    <body>
        <form name="example" method="post" action="AddArticle.php">
            <p>
                标题：<input type="text" name="title" id="title" />
            </p>
            <p>
                封面图片：<input type="text" name="url1" id="url1" value="" /> <input
                    type="button" id="image1" value="选择图片" />（网络图片 + 本地上传）
            </p>
            <tr>
            <p>
                标签 <input type="text" id="au_province" name="au_province" />
                <a class="phone_a" href="javascript:void(0);"  onclick="addPhone()"><strong>+</strong></a>
                <textarea style="width: 300px;" type="text" id="t_province"
                          name="t_province" readonly="readonly"></textarea>
                <input type="button" id="reset_province" value="重置"
                       onclick="resetProvince()" />
            </p>
            <textarea name="content1"
                      style="width: 1000px; height: 600px; visibility: hidden;"></textarea>
            <br />
            <input name="add" type="submit" name="button" value="提交内容" /> (提交快捷键:
            Ctrl + Enter)
            <input name="reset" class="submit" type="submit" value="重置" />

        </form>
    </body>
</html>


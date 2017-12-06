<?php
session_start();
//unset($_SESSION);
//session_destroy();
if (!isset($_SESSION['user'])) {
    $_SESSION['userurl'] = $_SERVER['REQUEST_URI'];
    header("location:../../system.php?"); //重新定向到其他页面
    exit();
} else {
    
}
?>
<!DOCTYPE html>
<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="../../css/jquery.ui.autocomplete.css">
    <script type="text/javascript" src="../../js/jquery-1.4.2.js"></script>
    <script type="text/javascript" src="../../js/jquery.ui.core.js"></script>
    <script type="text/javascript" src="../../js/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="../../js/jquery.ui.position.js"></script>
    <script type="text/javascript" src="../../js/jquery.ui.autocomplete.js"></script>
    <script type="text/javascript">
        $(function () {
            $("#name").autocomplete({
                source: "../Action/searchBrand.php",
                minLength: 1,
                autoFocus: false,
                select: function (event, ui) {

                    // event 是当前事件对象
                    ss = ui.item.label.split(";");
                    $.post("../Action/searchBrandById.php", {"BrandId": ss[0]}, function (data) {
                        //这里你可以处理获取的数据。我使用是json 格式。你也可以使用其它格式。或者为空，让它自己判断得了  
                        $("#name").val(data["BrandId"] + ";" + data["BrandName"]);
                        $("#key").val(data["BrandCompany"] + ";" + data["EnterpriseName"]);
                        var c_img = data["BrandImg"];
                        if (c_img !== null && c_img !== "") {
                            arr_img = c_img.split(";");
                            $("#crop_img").empty();
                            for (var i = 0; i < arr_img.length; i++)
                            {
                                var imgurl = '<td><a href="https://www.renrenseed.com/files/brandImgs/' + arr_img[i] + '" target="_blank"><img src="https://www.renrenseed.com/files/brandImgs/' + arr_img[i] + '" height="100" width="100" /></a></td>';
                                $("#crop_img").append(imgurl);
                            }
                        }
                    }, 'json');
                }
            });
            $("#key").autocomplete({
                source: "../Action/searchEnterprise.php",
                minLength: 1,
                autoFocus: false
            });

        });
    </script>
    <script>
        function addPic1() {
            var addBtn = document.getElementById('addBtn');
            var input = document.createElement("input");
            input.type = 'file';
            input.name = 'myfile[]';
            var picInut = document.getElementById('picInput');
            picInut.appendChild(input);
            if (picInut.children.length == 3) {
                addBtn.disabled = 'disabled';
            }
        }
    </script>
    <head>
        <title>品牌维护</title>
    </head>
    <body>
        <form action="brandSetting.php" method="post"
              enctype='multipart/form-data'
              onkeydown="if (event.keyCode == 13)
                          return false;">
            <table>
                <tr>
                    <td>品牌名称</td>
                    <td><input type="heddin" name="name" id="name" value="" /></td>
                </tr>
                <tr>
                    <td>所属企业</td>
                    <td><input type="text" id="key" name="key" /></td>
                </tr>
                <tr name="crop_img" id="crop_img"></tr>
                <tr>
                    <td>图片</td>
                <br />
                <br />
                <td><div id="picInput">
                        上传图片：<input type="file" name='myfile[]'>
                    </div> <br /> <br /> <input id="addBtn" type="button"
                                                onclick="addPic1()" value="继续添加图片"><br /> <br /></td>
                </tr>

            </table>
            <tr>
            <input name="add" class="submit" type="submit" value="提交" />
            <input name="modify" class="submit" type="submit" value="修改" />
            <input name="reset" class="submit" type="submit" value="重置" />
        </tr>
    </form>
</body>
</html>
<?php
require '../../common.php';
include '../../comm/imgageUnit.php';
include '../../wxAction.php';
$path = DT_ROOT . '/files/brandImgs/';
if (isset($_POST ["add"]) || isset($_POST ["modify"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('品牌名称不能为空')</script>";
        return;
    }

    $company = $_POST ['key'];
    $images = isset($_FILES ["myfile"]) ? $_FILES ["myfile"] : '';
    $site = isset($_REQUEST ['site']) ? $_REQUEST ['site'] : '';
    $name = array();
    $save = array();

    if (!empty($images) && is_array($images ['name'])) {
        foreach ($images ['name'] as $k => $image) {
            if (empty($image))
                continue;
            $name [] = $images ['name'] [$k];
            $save [] = $images ['tmp_name'] [$k];
        }
    } elseif (!empty($images) && !empty($images ['name']) && !empty($images ['tmp_name'])) {
        $name [] = $images ['name'];
        $save [] = $images ['tmp_name'];
    }

    if (!empty($name) && !empty($save)) {
        $insert_name = array();
        $insert_name_min = array();
        $i = 0;
        foreach ($name as $k => $n) {
            if (!is_file($save [$k]))
                continue;
            $rename = 'brand_' . time() . '_' . $i;
            $ext = pathinfo($n, PATHINFO_EXTENSION);
            setBrandImg($save [$k], $path, $rename . '_min' . '.' . $ext, 200, 200);
            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                $insert_name_min[] = $rename . '_min' . '.' . $ext;
                @unlink($save [$k]);
            }
            $i++;
        }
        if (isset($_POST ["modify"])) {
            $insert = implode(";", $insert_name);
            $insert_min = implode(";", $insert_name_min);
            $arrbrandname = explode(';', $_POST ['name']);
            $brand_id = $arrbrandname [0];
            $brand_name_new = $arrbrandname [1];
            $sql = "update AppBrand set BrandName='$brand_name_new',BrandCompany='$company',BrandImg='$insert',BrandImgMin='$insert_min',BrandImgMin='$insert_min' where BrandId=$brand_id";
            $update = $db->query($sql);
            echo "<script>alert(" . $update . ")</script>";
        } else if (isset($_POST ["add"])) {
            $brandname = $_POST ['name'];
            $insert = implode(";", $insert_name);
            $insert_min = implode(";", $insert_name_min);
            $sql = "insert into AppBrand VALUE (NULL,'$brandname','$company',1,'','$insert','$insert_min');";
            $result_add = $db->query($sql);
            $result_id = $db->lastInsertId();
            echo "<script>alert(" . $result_id . ")</script>";
        }
    } else {
        if (isset($_POST ["modify"])) {
            $arr_cropid = explode(';', $_POST ['cropname']);

            $arrbrandname = explode(';', $_POST ['name']);
            $brand_id = $arrbrandname [0];
            $brand_name_new = $arrbrandname [1];
            $insert = implode(";", $insert_name);
            $sql = "update AppBrand set BrandName='$brand_name_new',BrandCompany='$company' where BrandId=$brand_id";
            $update = $db->query($sql);
            echo "<script>alert(" . $update . ")</script>";
        } else if (isset($_POST ["add"])) {
            $brandname = $_POST ['name'];
            $sql = "insert into AppBrand VALUE (NULL,'$brandname','$company',1,'','','');";
            $result_add = $db->query($sql);
            $result_id = $db->lastInsertId();
            echo "<script>alert(" . $result_id . ")</script>";
        }
    }
}
?>
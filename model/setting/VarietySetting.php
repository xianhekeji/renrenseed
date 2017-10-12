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
            $("#variety_1").autocomplete({
                source: "../Action/searchVariety.php",
                minLength: 1,
                autoFocus: false});
            $("#variety_2").autocomplete({
                source: "../Action/searchVariety.php",
                minLength: 1,
                autoFocus: false,
                select: function (event, ui) {
                    // event 是当前事件对象
                    ss = ui.item.label.split(";");
                    $.post("../Action/searchVarietyById.php", {"id": ss[0]}, function (data) {
                        //这里你可以处理获取的数据。我使用是json 格式。你也可以使用其它格式。或者为空，让它自己判断得了  
                        $("#flag").empty();
                        if (data['variety_flag'] == '1')
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
                        $("#variety_1").val(data["varietyclassid"] + ";" + data["varietyname_2"]);
                        $("#variety_icon").empty();
                        $("#variety_img").empty();
                        if (data["variety_icon"].length > 0) {
                            var icon_url = '<td><a href="https://www.renrenseed.com/files/cropImgs/' + data["variety_icon"] + '" target="_blank"><img src="https://www.renrenseed.com/files/cropImgs/' + data["variety_icon"] + '" height="100" width="100" /></a></td>';
                            $("#variety_icon").append(icon_url);
                        }
                        if (data["variety_img"].length > 0) {
                            var img_url = '<td><a href="https://www.renrenseed.com/files/cropImgs/' + data["variety_img"] + '" target="_blank"><img src="https://www.renrenseed.com/files/cropImgs/' + data["variety_img"] + '" height="100" width="100" /></a></td>';
                            $("#variety_img").append(img_url);
                        }
                    }, 'json');
                }
            });
        });</script>
    <head>
        <title>分类维护</title>
    </head>

    <body>
        <form action="VarietySetting.php" method="post"
              enctype='multipart/form-data'>
            <table>
                <tr>
                    <td>分类名称：</td>
                    <td><input type="text" name="variety_2" id="variety_2" />(新建直接输入名称，修改需要检索选择)</td>
                    <td id="flag"></td>
                </tr>
                <tr>
                    <td>所属分类：</td>
                    <td><input type="text" name="variety_1" id="variety_1" />(必须选择添加)</td>
                    <!-- <td id="flag"></td> -->
                </tr>
                <tr>
                    <td>标签：</td>
                    <td><input type="text" name="memo" id="memo" /></td>
                </tr>
                <tr>
                    <td>图标图片：</td>

                    <td><div id="picInput">
                            <input type="file" name='myfile[]'>
                        </div></td>
                <tr name="variety_icon" id="variety_icon"></tr>
                </tr>
                <tr>
                    <td>默认显示图片：</td>


                    <td><div id="picInput">
                            <input type="file" name='myfile_2[]'>
                        </div></td>
                <tr name="variety_img" id="variety_img"></tr>
                </tr>
            </table>
            <tr>
            <input name="add" class="submit" type="submit" value="新建" />
            <input name="modify" class="submit" type="submit" value="修改" />
            <input name="reset" class="submit" type="submit" value="重置" />
            <input id="flag_zuofei" name="flag_zuofei" class="submit"
                   type="submit" value="作废" />
            <input id="flag_qiyong" name="flag_qiyong" class="submit"
                   type="submit" value="启用" />
        </tr>
    </form>
</body>
</html>
<?php
require '../../common.php';
include '../../wxAction.php';
$path = DT_ROOT . '/files/cropImgs/';
if (isset($_POST ["flag_zuofei"])) {
    if (empty($_POST ['variety_2'])) { // 点击提交按钮后才执行
        echo "<script>alert('编码不能为空')</script>";
        return;
    }
    $arr_number = explode(';', $_POST ['variety_2']);
    $number_id = $arr_number [0];
    $sql = "update app_variety set variety_flag=1
	where varietyid='$number_id'";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["flag_qiyong"])) {
    if (empty($_POST ['variety_2'])) { // 点击提交按钮后才执行
        echo "<script>alert('编码不能为空')</script>";
        return;
    }
    $arr_number = explode(';', $_POST ['variety_2']);
    $number_id = $arr_number [0];
    $sql = "update app_variety set variety_flag=0
	where varietyid='$number_id'";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["add"]) || isset($_POST ["modify"])) {

    $memo = $_POST ['memo'];
    if (empty($_POST ['variety_2'])) { // 点击提交按钮后才执行
        echo "<script>alert('分类名称不能为空')</script>";
        return;
    }
    if (empty($_POST ['variety_1'])) { // 点击提交按钮后才执行
        echo "<script>alert('所属分类不能为空')</script>";
        return;
    }
    $arr_variety_1 = explode(';', $_POST ['variety_1']);
    $variety_id_1 = $arr_variety_1 [0];
    // 获取图片begin
    $images = isset($_FILES ["myfile"]) ? $_FILES ["myfile"] : '';
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

        foreach ($name as $k => $n) {
            if (!is_file($save [$k]))
                continue;
            $rename = 'category_icon_' . time();
            $ext = pathinfo($n, PATHINFO_EXTENSION);

            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                @unlink($save [$k]);
            }
        }
        if (!empty($insert_name)) {
            $insert = implode(";", $insert_name);
        } else {
            $insert = '';
        }
    }
    // 获取图片end
    // 获取图片begin
    $images_2 = isset($_FILES ["myfile_2"]) ? $_FILES ["myfile_2"] : '';
    $name_2 = array();
    $save_2 = array();
    if (!empty($images_2) && is_array($images_2 ['name'])) {
        foreach ($images_2 ['name'] as $k => $image_2) {
            if (empty($image_2))
                continue;
            $name_2 [] = $images_2 ['name'] [$k];
            $save_2 [] = $images_2 ['tmp_name'] [$k];
        }
    } elseif (!empty($images_2) && !empty($images_2 ['name']) && !empty($images_2 ['tmp_name'])) {
        $name_2 [] = $images_2 ['name'];
        $save_2 [] = $images_2 ['tmp_name'];
    }

    if (!empty($name_2) && !empty($save_2)) {
        $insert_name_2 = array();
        foreach ($name_2 as $k => $n) {
            if (!is_file($save_2 [$k]))
                continue;
            $rename_2 = 'vategory_img_' . time();
            $ext_2 = pathinfo($n, PATHINFO_EXTENSION);

            if (copy($save_2 [$k], $path . $rename_2 . '.' . $ext_2)) {
                $insert_name_2 [] = $rename_2 . '.' . $ext_2;
                @unlink($save_2 [$k]);
            }
        }
        if (!empty($insert_name_2)) {
            $insert_2 = implode(";", $insert_name_2);
        } else {
            $insert_2 = '';
        }
    }
    // 获取图片end
    if (isset($_POST ["modify"])) {
        $arr_variety_2 = explode(';', $_POST ['variety_2']);
        $variety_id_2 = $arr_variety_2 [0];
        $variety_name_2 = $arr_variety_2 [1];

        $condition = '';
        if (isset($insert) && strlen($insert) > 0) {
            $condition = ",variety_icon='$insert'";
        }
        if (isset($insert_2) && strlen($insert_2) > 0) {
            $condition = $condition . ",variety_img='$insert_2'";
        }
        $sql = "update app_variety set varietyname='$variety_name_2',varietyclassid='$variety_id_1' $condition ,variety_memo='$memo'where varietyid='$variety_id_2'";
        $update = $db->query($sql);
        echo "<script>alert(" . $update . ")</script>";
    } else if (isset($_POST ["add"])) {
        $variety_2 = $_POST ['variety_2'];

        $sql = "insert into app_variety values(null,'$variety_2','$variety_id_1','','$time','','','','$insert','$insert_2','1','$memo')";
        $result_add = $db->query($sql);
        $result_id = $db->lastInsertId();
        echo "<script>alert(" . $result_id . ")</script>";
    }
}
?>
<?php
session_start();
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
            provinces = "";
            $("#au_memo").autocomplete({
                source: "../Action/searchMemo.php",
                minLength: 1,
                autoFocus: false,
                select: function (event, ui) {
                    var provinces = $("#t_memo").val();
                    provinces = provinces + ui.item.label + ";";
                    $("#t_memo").empty();
                    $("#t_memo").append(provinces);
                    $("#au_memo").val("");
                }
            });
            $("#au_province_name").autocomplete({
                source: "../Action/searchProvince.php",
                minLength: 1,
                autoFocus: false
            });
            $("#cropname").autocomplete({
                source: "../Action/searchCrop.php",
                minLength: 1,
                autoFocus: false,
                select: function (event, ui) {
                    // event 是当前事件对象
                    ss = ui.item.label.split(";");
                    $.post("../Action/searchCropById.php", {"CropId": ss[0]}, function (data) {
                        //这里你可以处理获取的数据。我使用是json 格式。你也可以使用其它格式。或者为空，让它自己判断得了  
                        $("#cropname").val(data[0]["CropId"] + ";" + data[0]["VarietyName"]);
                        var c_isgen = data[0]["IsGen"];
                        $("input:radio[value=" + c_isgen + "]").eq(0).attr("checked", 'checked');
                        $("#au_min").val(data[0]["MinGuidePrice"]);
                        $("#au_max").val(data[0]["MaxGuidePrice"]);
                        $("#au_region").val(data[0]["BreedRegion"]);
                        // $("#au_memo").val(data[0]["Memo"]);
                        $("#au_organization").val(data[0]["BreedOrganization"]);
                        $("#au_level").val(data[0]["CropLevel"]);
                        $("#select_1").find("option[text='" + data[0]["VarietyName_1"] + "']").attr("selected", true);
                        get_select_3(data[0]["VarietyName_2"]);
                        $("#flag").empty();
                        if (data[0]['Flag'] == '1')
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

                        $("#t_memo").empty();
                        $("#t_memo").append(data[0]["Memo"]);
                        var c_img = data[0]["CropImgs"];
                        if (c_img !== null && c_img !== "") {
                            arr_img = c_img.split(";");
                            $("#crop_img").empty();
                            for (var i = 0; i < arr_img.length; i++)
                            {
                                var imgurl = '<td><a href="https://www.renrenseed.com/files/cropImgs/' + arr_img[i] + '" target="_blank"><img src="https://www.renrenseed.com/files/cropImgs/' + arr_img[i] + '" height="100" width="100" /></a></td>';
                                $("#crop_img").append(imgurl);
                            }
                        }
                    }, 'json');
                }
            });
        });</script>
    <script language="javascript">
        function get_select_1() {
            $.post("../Action/getselect_1.php", {sf_id: encodeURI($("#select_1").val())}, function (json) {
                var select_1 = $("#select_1");
                $("option", select_1).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
                $.each(json, function (index, array) {
                    var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
                    select_1.append(option);
                });
                get_select_2();
            }, "json");
        }
        function get_select_2() {
            $.post("../Action/getselect_2.php", {sf_id: $("#select_1").val()}, function (json) {
                var select_2 = $("#select_2");
                $("option", select_2).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
                $.each(json, function (index, array) {
                    var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
                    select_2.append(option);
                });
            }, "json");
        }
        function get_select_3(str) {
            $.post("../Action/getselect_2.php", {sf_id: $("#select_1").val()}, function (json) {
                var select_2 = $("#select_2");
                $("option", select_2).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
                $.each(json, function (index, array) {
                    var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
                    select_2.append(option);
                });
                $("#select_2").find("option[text='" + str + "']").attr("selected", true);
            }, "json");
        }
        //下面是页面加载时自动执行一次getVal()函数 
        $().ready(function () {
            get_select_1();
            $("#select_1").change(function () {//省份部分有变动时，执行getVal()函数 
                //alert($("#select_1").val());
                get_select_2();
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
        function resetProvince() {
            provinces = "";
            $("#t_memo").empty();
            $("#au_memo").val("");
        }
    </script>
    <head>
        <title>品种添加</title>
    </head>

    <body>
        <form action="CropSetting.php" method="post"
              enctype='multipart/form-data'>
            <table>
                <tr>
                    <td>品种名称</td>
                    <td><input type="text" name="cropname" id="cropname" /></td>
                    <td id="flag"></td>
                </tr>
                <tr>
                    <td width=100>品种分类</td>
                    <td><select name="select_1" id="select_1" title="选择品种">
                        </select><select name="select_2" id="select_2" title="选择品种">
                        </select></td>
                </tr>

                <tr>
                    <td>转基因</td>
                    <td><input type="radio" name="class" value="非转基因" checked="true">非转基因
                        <input type="radio" name="class" value="是转基因">是转基因</td>
                </tr>
                <tr>
                    <td>最低价格</td>
                    <td><input type="text" id="au_min" name="au_min" /></td>
                </tr>
                <tr>
                    <td>最高价格</td>
                    <td><input type="text" id="au_max" name="au_max" /></td>
                </tr>
                <tr>
                    <td>适宜地区</td>
                    <td><textarea name="au_region" rows="2" cols="20" id="au_region"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr>
                    <td>备注</td>
                    <td><input type="text" id="au_memo" name="au_memo" /> <textarea
                            style="width: 300px;" type="text" id="t_memo"
                            name="t_memo" readonly="readonly"></textarea> <input
                            type="button" id="reset_memo" value="重置"
                            onclick="resetProvince()" /></td>
                </tr>
                <tr>
                <tr>
                    <td>级别</td>
                    <td><input type="text" id="au_level" name="au_level" />(0~5最小单位0.5)</td>
                </tr>
                <tr>
                    <td>育种企业</td>
                    <td><input type="text" id="au_organization" name="au_organization" /></td>
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
include '../../comm/imgageUnit.php';
include '../../wxAction.php';
$path = DT_ROOT . '/files/cropImgs/';
if (isset($_POST ["flag_qiyong"])) {
    if (empty($_POST ['cropname'])) { // 点击提交按钮后才执行
        echo "<script>alert('品种名称不能为空')</script>";
        return;
    }
    $arr_cropid = explode(';', $_POST ['cropname']);
    $crop_id = $arr_cropid [0];
    $sql = "update WXCrop set Flag=0 where CropId=$crop_id";
//    $result_add = mysql_query($sql);
//    $update = mysql_affected_rows();
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["flag_zuofei"])) {
    if (empty($_POST ['cropname'])) { // 点击提交按钮后才执行
        echo "<script>alert('品种名称不能为空')</script>";
        return;
    }
    $arr_cropid = explode(';', $_POST ['cropname']);
    $crop_id = $arr_cropid [0];
    $sql = "update WXCrop set Flag=1 where CropId=$crop_id";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["add"]) || isset($_POST ["modify"])) {
    if (empty($_POST ['select_2'])) { // 点击提交按钮后才执行
        echo "<script>alert('品种分类为必选')</script>";
        return;
    }
    if (empty($_POST ['cropname'])) { // 点击提交按钮后才执行
        echo "<script>alert('品种名称不能为空')</script>";
        return;
    }
    // if (empty ( $_POST ['au_region'] )) { // 点击提交按钮后才执行
    // echo "<script>alert('适宜地区不能为空')</script>";
    // return;
    // }
    $au_max = $_POST ['au_max'];
    $au_min = $_POST ['au_min'];
    $au_memo = $_POST ['t_memo'];
    ;
    $au_organization = $_POST ['au_organization'];
    $app_Variety_1 = $_POST ['select_1'];
    $app_Variety_2 = $_POST ['select_2'];
    $cropName = trim ($_POST ['cropname']);
    $IsGen = $_POST ['class'];
    $images = isset($_FILES ["myfile"]) ? $_FILES ["myfile"] : '';
    $site = isset($_REQUEST ['site']) ? $_REQUEST ['site'] : '';
    $au_region = $_POST ['au_region'];
    $au_level = $_POST ['au_level'];
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
            $rename = 'crop_' . time() . '_' . $i;
//            $rename = $n . time();
            $ext = pathinfo($n, PATHINFO_EXTENSION);
            setShuiyin($save [$k], $path, $rename . '_min' . '.' . $ext, 500, 500);
            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                $insert_name_min [] = $rename . '_min' . '.' . $ext;
                @unlink($save [$k]);
            }
            $i++;
        }
        if (!empty($insert_name)) {
            $insert = implode(";", $insert_name);
            $insert_min = implode(";", $insert_name_min);
            if (isset($_POST ["modify"])) {
                $arr_cropid = explode(';', $_POST ['cropname']);
                $crop_id = $arr_cropid [0];
                $crop_name_new = $arr_cropid [1];
                $sql = "update WXCrop set CropCategory1='$app_Variety_1',CropCategory2='$app_Variety_2',VarietyName='$crop_name_new',
	            		CropImgsMin='$insert_min',CropImgs='$insert',IsGen='$IsGen',MinGuidePrice='$au_min',MaxGuidePrice='$au_max',BreedRegion='$au_region',Memo='$au_memo',BreedOrganization='$au_organization',CropLevel='$au_level'
                        where CropId='$crop_id'";
                $update = $db->query($sql);
                echo "<script>alert(" . $update . ")</script>";
            } else if (isset($_POST ["add"])) {
                $check_sql = "select count(*) count from WXCrop where CropCategory1=$app_Variety_1 and CropCategory2=$app_Variety_2 and VarietyName='$cropName'";
                $check = $db->row($check_sql);
                $count = $check['count'];
                if ($count > 0) {
                    $message = "已存在";
                    echo "<script>alert('" . $message . "')</script>";
                } else {
                    $sql = "insert into WXCrop VALUES (null,'$app_Variety_1','$app_Variety_2','$cropName','$insert','$IsGen','0','$au_min','$au_max','$au_region','$au_memo','$au_organization','$au_level','0','$insert_min',0,'0')";
//                    $result_add = mysql_query($sql);
//                    $result_id = mysql_insert_id();
                    $result_add = $db->query($sql);
                    $result_id = $db->lastInsertId();
                    echo "<script>alert(" . $result_id . ")</script>";
                }
            }
        }
    } else {
        if (isset($_POST ["modify"])) {
            $arr_cropid = explode(';', $_POST ['cropname']);
            $crop_id = $arr_cropid [0];
            $crop_name_new = $arr_cropid [1];
            $sql = "update WXCrop set CropCategory1='$app_Variety_1',CropCategory2='$app_Variety_2',VarietyName='$crop_name_new',
	            		IsGen='$IsGen',MinGuidePrice='$au_min',MaxGuidePrice='$au_max',BreedRegion='$au_region',Memo='$au_memo',BreedOrganization='$au_organization',CropLevel='$au_level'
                        where CropId='$crop_id'";
            $update = $db->query($sql);
            echo "<script>alert(" . $update . ")</script>";
        } else if (isset($_POST ["add"])) {
            $check_sql = "select count(*) count from WXCrop where CropCategory1=$app_Variety_1 and CropCategory2=$app_Variety_2 and VarietyName='$cropName'";
            $check = $db->row($check_sql);
            $count = $check[0]['count'];
            if ($count > 0) {
                $message = "已存在";
                echo "<script>alert('" . $message . "')</script>";
            } else {
                $sql = "insert into WXCrop VALUES (null,'$app_Variety_1','$app_Variety_2','$cropName','','$IsGen','0','$au_min','$au_max','$au_region','$au_memo','$au_organization','$au_level','0','',0,'0')";
                $result_add = $db->query($sql);
                $result_id = $db->lastInsertId();
                echo "<script>alert(" . $result_id . ")</script>";
            }
        }
    }
}
?>

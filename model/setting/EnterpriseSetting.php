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
                        $("#star").val(data["EnterpriseCommentLevel"]);
                        $("#phone").val(data["EnterpriseTelephone"]);
                        $("#introduce").val(data["EnterpriseIntroduce"]);
                        $("#select_province").find("option[text='" + data["province"] + "']").attr("selected", true);
                        get_select_city_check(data["city"], data["zone"]);
                        $("#select_city").find("option[text='" + str + "']").attr("selected", true);
                    }, 'json');
                }
            });
        });
    </script>
    <!--地址选择JS -->
    <script type="text/javascript">
        //下面是页面加载时自动执行一次getVal()函数 
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
    </script>
    <head>
        <title>企业维护</title>
    </head>
    <body>
        <form action="EnterpriseSetting.php" method="post"
              enctype='multipart/form-data'
              onkeydown="if (event.keyCode == 13)
                          return false;">
            <table>
                <tr>
                    <td>名称</td>
                    <td><input type="heddin" name="name" id="name" /></td>
                    <td id="flag" name="flag"></td>
                </tr>
                <tr>
                    <td width=100>地址</td>
                    <td><select name="select_province" id="select_province" title="选择省份">
                        </select><select name="select_city" id="select_city" title="选择市区">
                        </select><select name="select_zone" id="select_zone" title="选择县乡">
                        </select></td>
                </tr>
                <tr>
                    <td>详细地址</td>
                    <td><input type="text" id="address" name="address" /></td>
                </tr>
                <tr>
                    <td>坐标</td>
                    <td>横坐标：<input type="text" id="lat" name="lat" />
                        纵坐标<input type="text" id="lon" name="lon" /></td>

                </tr>
                <tr>
                    <td>星级</td>
                    <td><input type="text" id="star" name="star" />(1-10)</td>
                </tr>
                <tr>
                    <td>联系电话</td>
                    <td><input type="text" id="phone" name="phone" /></td>
                </tr>
                <tr>
                    <td>简介(300字以内)</td>

                    <td> <textarea name="introduce" rows="2" cols="20" id="introduce"
                                   style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr>
                    <td>头像</td>
                <br />
                <td><div id="picInput">
                        上传头像：<input type="file" name='myfile[]'>
                    </div> <br /> <br />
<!--                    <input id="addBtn" type="button"
                                                onclick="addPic1()" value="继续添加图片">-->
                    <br /> <br /></td>
                </tr>
                <tr name="crop_img" id="crop_img"></tr>
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
$path = DT_ROOT . '/files/companyImgs/';
if (isset($_POST ["flag_qiyong"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('经销商名称不能为空')</script>";
        return;
    }
    $arr_id = explode(';', $_POST ['name']);
    $id = $arr_id [0];
    $sql = "update AppEnterprise SET EnterpriseFlag=0 WHERE EnterpriseId=$id";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["flag_zuofei"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('经销商名称不能为空')</script>";
        return;
    }
    $arr_id = explode(';', $_POST ['name']);
    $id = $arr_id [0];
    $sql = "update AppEnterprise SET EnterpriseFlag=1 WHERE EnterpriseId=$id";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["add"]) || isset($_POST ["modify"])) {
    $images = isset($_FILES ["myfile"]) ? $_FILES ["myfile"] : '';
    $site = isset($_REQUEST ['site']) ? $_REQUEST ['site'] : '';
    $dis_province = isset($_POST ['select_province']) ? $_POST ['select_province'] : '';
    $dis_city = isset($_POST ['select_city']) ? $_POST ['select_city'] : '';
    $dis_zone = isset($_POST ['select_zone']) ? $_POST ['select_zone'] : '';
    $dis_address = isset($_POST ['address']) ? $_POST ['address'] : '';
    $dis_star = isset($_POST ['star']) ? $_POST ['star'] : '';
    $dis_phone = isset($_POST ['phone']) ? $_POST ['phone'] : '';
    $dis_lat = isset($_POST ['lat']) ? $_POST ['lat'] : '0';
    $dis_lon = isset($_POST ['lon']) ? $_POST ['lon'] : '0';
    $dis_introduce = isset($_POST ["introduce"]) ? $_POST ["introduce"] : '';

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
        $i = 0;
        foreach ($name as $k => $n) {
            if (!is_file($save [$k]))
                continue;
            $rename = 'enterprise_' . time() . '_' . $i;
            $ext = pathinfo($n, PATHINFO_EXTENSION);

            if (copy($save [$k], $path . $rename . '.' . $ext)) {
                $insert_name [] = $rename . '.' . $ext;
                @unlink($save [$k]);
            }
        }
        if (!empty($insert_name)) {
            $insert = implode(";", $insert_name);
            if (isset($_POST ["modify"])) {
                $arr_id = explode(';', $_POST ['name']);
                $id = $arr_id [0];
                $updatecondition = "EnterpriseAddressDetail ='$dis_address',EnterpriseCommentLevel='$dis_star',EnterpriseTelephone='$dis_phone',EnterpriseUserAvatar='$insert',EnterpriseIntroduce='$dis_introduce',EnterpriseLat='$dis_lat',EnterpriseLon='$dis_lon'";
                if (!empty($dis_province)) {
                    $updatecondition = $updatecondition . ",EnterpriseProvince='$dis_province'";
                }
                if (!empty($dis_city)) {
                    $updatecondition = $updatecondition . ",EnterpriseCity='$dis_city'";
                }
                if (!empty($dis_zone)) {
                    $updatecondition = $updatecondition . ",EnterpriseZone='$dis_zone'";
                }
                $sql = "update AppEnterprise set $updatecondition where EnterpriseId='$id'";

                $update = $db->query($sql);

                echo "<script>alert(" . $update . ")</script>";
            } else if (isset($_POST ["add"])) {
                $dis_name = $_POST ['name'];
                $sql = "insert into AppEnterprise values(NULL,'$dis_name','DistributorTrademark','0','$dis_phone','0','$dis_introduce','$dis_province','$dis_city','$dis_zone','$dis_address','$dis_lat','$dis_lon','EnterpriseUserCode','EnterprisePassword','$insert','$dis_star',0)";

                $result_add = $db->query($sql);
                $result_id = $db->lastInsertId();
                echo "<script>alert(" . $result_id . ")</script>";
            }
        }
    } else {
        if (isset($_POST ["modify"])) {
            $arr_id = explode(';', $_POST ['name']);
            $id = $arr_id [0];
            $updatecondition = "EnterpriseAddressDetail ='$dis_address',EnterpriseCommentLevel='$dis_star',EnterpriseTelephone='$dis_phone',EnterpriseIntroduce='$dis_introduce',EnterpriseLat='$dis_lat',EnterpriseLon='$dis_lon'";
            if (!empty($dis_province)) {
                $updatecondition = $updatecondition . ",EnterpriseProvince='$dis_province'";
            }
            if (!empty($dis_city)) {
                $updatecondition = $updatecondition . ",EnterpriseCity='$dis_city'";
            }
            if (!empty($dis_zone)) {
                $updatecondition = $updatecondition . ",EnterpriseZone='$dis_zone'";
            }
            $sql = "update AppEnterprise set $updatecondition where EnterpriseId='$id'";

            $update = $db->query($sql);
            echo "<script>alert(" . $update . ")</script>";
        } else if (isset($_POST ["add"])) {
            $dis_name = $_POST ['name'];
            $sql = "insert into AppEnterprise values(NULL,'$dis_name','DistributorTrademark','0','$dis_phone','0','$dis_introduce','$dis_province','$dis_city','$dis_zone','$dis_address','$dis_lat','$dis_lon','EnterpriseUserCode','EnterprisePassword','','$dis_star',0)";

            $result_add = $db->query($sql);
            $result_id = $db->lastInsertId();
            echo "<script>alert(" . $result_id . ")</script>";
        }
    }
}
?>
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
            provinces = "";
            $("#cropname").autocomplete({
                source: "../Action/searchCrop.php",
                minLength: 1,
                autoFocus: false,
                select: function (event, ui) {
                    ss = ui.item.label.split(";");
                    $.post("../Action/searchCropById.php", {"CropId": ss[0]}, function (data) {
                        $("#au_org").val(data[0]['BreedOrganization']);
                    }, 'json')
                }
            });

            $("#au_number").autocomplete({
                source: "../Action/searchAuthorize.php",
                minLength: 1,
                autoFocus: false,
                select: function (event, ui) {
                    ss = ui.item.label.split(";");
                    $.post("../Action/searchAuthorizeById.php", {"authorize_id": ss[0]}, function (data) {
                        $("#select_status").find("option[value='" + data[0]["AuthorizeStatus"] + "']").attr("selected", true);
                        $("#flag").empty();
                        if (data[0]['AuFlag'] == '1')
                        {
                            $("#flag").append("<text>已作废</text>");
                            $("#flag_qiyong").show();
                            $("#flag_zuofei").hide();
                        } else if (data[0]['AuFlag'] == '2')
                        {
                            $("#flag").append("<text>已退出</text>");
                            $("#flag_qiyong").hide();
                            $("#flag_zuofei").show();
                        } else
                        {
                            $("#flag").append("<text>已启用</text>");
                            $("#flag_qiyong").hide();
                            $("#flag_zuofei").show();
                        }
                        $("#cropname").val(data[0]["AuCropId"] + ";" + data[0]["AuCropName"]);
                        $("#au_year").val(data[0]["AuthorizeYear"]);
                        $("#au_province_name").val(data[0]["AuthorizeProvince"] + ";" + data[0]["AuthorizeProvinceName"]);
                        $("#au_org").val(data[0]["BreedOrganization"]);
                        $("#au_unit").val(data[0]["AuthorizeUnit"]);
                        $("#au_source").val(data[0]["VarietySource"]);
                        $("#au_featrue").val(data[0]["Features"]);
                        $("#au_region").val(data[0]["BreedRegion"]);
                        $("#au_featrue").val(data[0]["Features"]);
                        $("#au_pro").val(data[0]["Production"]);
                        $("#au_kangxing").val(data[0]["AuKangxing"]);
                        $("#au_pinzhi").val(data[0]["AuPinzhi"]);
                        $("#au_skill").val(data[0]["BreedSkill"]);
                        $("#au_tuichu").val(data[0]["FlagReason"]);
                        provinceids = data[0]["BreedRegionProvince"].split(",");
                        provincenames = data[0]["BreedRegionProvinceName"].split(",");
                        /*  alert(data[0]["BreedRegionProvince"]); */
                        /*                 var provinceids=new array(data[0]["BreedRegionProvince"]);
                         
                         var provincenames=new array(data[0]["BreedRegionProvinceName"]); */

                        var re_pro = "";
                        for (var i = 0; i < provinceids.length; i++)
                        {
                            re_pro = re_pro + provinceids[i] + "," + provincenames[i] + ";";
                        }
                        provinces = re_pro;
                        $("#t_province").append(provinces);
                    }, 'json')
                }
            });
            $("#au_province").autocomplete({
                source: "../Action/searchProvince.php",
                minLength: 1,
                autoFocus: false,
                select: function (event, ui) {
                    ss = ui.item.label.split(";");
                    provinces = provinces + ss + ";";
                    $("#t_province").empty();
                    $("#t_province").append(provinces);
                    $("#au_province").val("");
                }
            });
            $("#au_province_name").autocomplete({
                source: "../Action/searchProvince.php",
                minLength: 1,
                autoFocus: false
            });

        })
        function resetProvince() {
            provinces = "";
            $("#t_province").empty();
            $("#au_province").val("");
        }
    </script>
    <head>
        <title>审定编号维护</title>
    </head>

    <body>
        <form action="AuthorizeSetting.php" method="post"
              enctype='multipart/form-data'>
            <table>
                <tr>
                    <td>审定编码</td>
                    <td><input type="text" id="au_number" name="au_number" /><select name="select_status" id="select_status" title="选择品种">
                            <option value="0" >无</option>
                            <option value="1" >审定</option>
                            <option value="2">引种</option>
                            <option value="3" >登记</option>
                        </select></td>
                    <td id="flag"></td>
                </tr>
                <tr>
                    <td>品种名称</td>
                    <td><input type="text" id="cropname" name="cropname" />
                    </td>
                </tr>

                <tr>
                    <td>审定年份</td>
                    <td><input type="text" id="au_year" name="au_year" /></td>
                </tr>
                <tr>
                    <td>审定省份</td>
                    <td><input type="text" id="au_province_name" name="au_province_name" /></td>
                </tr>
                <tr>
                    <td>培育单位</td>
                    <td><input type="text" id="au_org" name="au_org" /></td>
                </tr>
                <tr>
                    <td>审定单位</td>
                    <td><input type="text" id="au_unit" name="au_unit" /></td>
                </tr>

                <tr>
                    <td>品种来源</td>
                    <td><textarea name="au_source" rows="2" cols="20" id="au_source"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr>
                    <td>特征特性</td>
                    <td><textarea name="au_featrue" rows="2" cols="20" id="au_featrue"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr>
                    <td>抗性表现</td>
                    <td><textarea name="au_kangxing" rows="2" cols="20" id="au_kangxing"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr>
                    <td>品质表现</td>
                    <td><textarea name="au_pinzhi" rows="2" cols="20" id="au_pinzhi"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr>
                    <td>试验产量</td>
                    <td><textarea name="au_pro" rows="2" cols="20" id="au_pro"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr>
                    <td>栽培技术</td>
                    <td><textarea name="au_skill" rows="2" cols="20" id="au_skill"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr>
                    <td>适宜省份</td>
                    <td><input type="text" id="au_province" name="au_province" /> <textarea
                            style="width: 300px;" type="text" id="t_province"
                            name="t_province" readonly="readonly"></textarea> <input
                            type="button" id="reset_province" value="重置"
                            onclick="resetProvince()" /></td>
                </tr>
                <tr>
                    <td>适宜地区</td>
                    <td><textarea name="au_region" rows="2" cols="20" id="au_region"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <tr>
                    <td>退出理由</td>
                    <td><textarea name="au_tuichu" rows="2" cols="20" id="au_tuichu"
                                  style="height: 50px; width: 500px;"></textarea></td>
                </tr>

            </table>
            <tr>
            <input name="add" class="submit" type="submit" value="新建" />
            <input name="modify" class="submit" type="submit" value="修改" />
            <input id="flag_zuofei" name="flag_zuofei" class="submit"
                   type="submit" value="作废" />
            <input id="flag_tuichu" name="flag_tuichu" class="submit"
                   type="submit" value="退出" />
            <input id="flag_qiyong" name="flag_qiyong" class="submit"
                   type="submit" value="启用" />
            <input name="reset" class="submit" type="submit" value="重置" />
        </tr>
    </form>
</body>
</html>
<?php
require '../../common.php';
include '../../wxAction.php';
if (isset($_POST ["flag_tuichu"])) {
    if (empty($_POST ['au_number'])) { // 点击提交按钮后才执行
        echo "<script>alert('编码不能为空')</script>";
        return;
    }
    $arr_number = explode(';', $_POST ['au_number']);
    $number_id = $arr_number [0];
    $au_tuichu = $_POST ['au_tuichu'];
    $sql_update = "update WXAuthorize set AuFlag=2,FlagReason='$au_tuichu' 
	where AuthorizeId='$number_id'";
    $update = $db->query($sql_update);
//    $result_update = mysql_query($sql_update);
//    $update = mysql_affected_rows();
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["flag_zuofei"])) {
    if (empty($_POST ['au_number'])) { // 点击提交按钮后才执行
        echo "<script>alert('编码不能为空')</script>";
        return;
    }
    $arr_number = explode(';', $_POST ['au_number']);
    $number_id = $arr_number [0];
    $sql_update = "update WXAuthorize set AuFlag=1
	where AuthorizeId='$number_id'";
    $update = $db->query($sql_update);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["flag_qiyong"])) {
    if (empty($_POST ['au_number'])) { // 点击提交按钮后才执行
        echo "<script>alert('编码不能为空')</script>";
        return;
    }
    $arr_number = explode(';', $_POST ['au_number']);
    $number_id = $arr_number [0];
    $sql = "update WXAuthorize set AuFlag=0
	where AuthorizeId='$number_id'";
    $update = $db->query($sql_update);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["add"]) || isset($_POST ["modify"])) {
    if (empty($_POST ['cropname'])) { // 点击提交按钮后才执行
        echo "<script>alert('品种不能为空');history.back();</script>";
        return;
    }
    $arr_crop = explode(';', $_POST ['cropname']);
    $select_status = $_POST["select_status"];
    $crop_id = $arr_crop [0];
    $crop_name = $arr_crop [1];
    $au_source = htmlspecialchars($_POST ['au_source']);
    $au_number = $_POST ['au_number'];

    $au_year = $_POST ['au_year'];
    $au_org = $_POST ['au_org'];
    $au_unit = $_POST ['au_unit'];
    $au_tuichu = $_POST ['au_tuichu'];
    $au_featrue = htmlspecialchars($_POST ['au_featrue']);
    $au_pro = htmlspecialchars($_POST ['au_pro']);
    $au_region = htmlspecialchars($_POST ['au_region']);
    $au_kangxing = htmlspecialchars($_POST ['au_kangxing']);
    $au_pinzhi = htmlspecialchars($_POST ['au_pinzhi']);
    $au_skill = htmlspecialchars($_POST ['au_skill']);
    $arr_province = explode(';', rtrim($_POST ['t_province'], ';'));
    $au_province_id = "";
    for ($i = 0; $i < count($arr_province); $i ++) {

        $arr_province_id = explode(',', $arr_province [$i]);
        if ($i == 0) {
            $au_province_id = $arr_province_id [0];
        } else {
            $au_province_id = $au_province_id . "," . $arr_province_id [0];
        }
    }

    $arr_province_name = explode(';', $_POST ['au_province_name']);
    $au_province_name = $arr_province_name [0];

    if (isset($_POST ["add"])) {
        $sql = "insert into WXAuthorize VALUES
	(null,'$au_number','$au_year','$au_org','breedid','$au_source','$au_featrue','$au_pro','$au_region','$au_skill','ownership'
						,'$select_status','$au_province_id','$au_unit','$crop_id','$crop_name','0','$au_province_name','0','$au_kangxing','$au_pinzhi','$au_tuichu')";
        $result_add = $db->query($sql);
        $result_id = $db->lastInsertId();
        updateCropStatus($db, $crop_id);
        echo "<script>alert(" . $result_id . ")</script>";
    } else if (isset($_POST ["modify"])) {
        $arr_number = explode(';', $_POST ['au_number']);
        $number_id = $arr_number [0];
        $sql_update = "update WXAuthorize set AuthorizeYear='$au_year',BreedOrganization='$au_org',VarietySource='$au_source',Features='$au_featrue',
Production='$au_pro',BreedRegion='$au_region',BreedSkill='$au_skill',BreedRegionProvince='$au_province_id',AuthorizeUnit='$au_unit',AuCropId='$crop_id',AuCropName='$crop_name',
AuthorizeProvince='$au_province_name',AuKangxing='$au_kangxing',AuPinzhi='$au_pinzhi',FlagReason='$au_tuichu',AuthorizeStatus=$select_status  
where AuthorizeId='$number_id'";
        $update = $db->query($sql_update);
        updateCropStatus($db, $crop_id);
        echo "<script>alert(" . $update . ")</script>";
    }
}

function updateCropStatus($db, $cropid) {
    $sql_more = "select DISTINCT(AuthorizeStatus) AuthorizeStatus from WXAuthorize where AuCropId=$cropid";
    $result_more = $db->query($sql_more);
    $array = array();
    $shending = false;
    $dengji = false;
    foreach ($result_more as $rows) {
        // 可以直接把读取到的数据赋值给数组或者通过字段名的形式赋值也可以
        if ($rows['AuthorizeStatus'] == 1 || $rows['AuthorizeStatus'] == 2) {
            $shending = true;
        }
        if ($rows['AuthorizeStatus'] == 3) {
            $dengji = true;
        }
    }
    $statu = '';
    if ($shending) {
        $statu = '1';
    }
    if ($dengji) {
        // $statu = $statu . '已登记';
        $statu = '2';
    }
    if (!$shending && !$dengji) {
        $statu = '0';
    }
    $sql_update = "update WXCrop set CropStatus='$statu' where CropId='$cropid'";
    $update = $db->query($sql_update);
}
?>
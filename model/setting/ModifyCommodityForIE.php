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
            $("#key").autocomplete({
                source: "../Action/searchEnterprise.php",
                minLength: 1,
                autoFocus: false
            });

            $("#brand").autocomplete({
                source: "../Action/searchBrand.php",
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
                        // alert(data[0]["VarietyName_1"]+data[0]["VarietyName_2"]);
                        $("#select_1").find("option[text='" + data[0]["VarietyName_1"] + "']").attr("selected", true);
                        get_select_3(data[0]["VarietyName_2"]);
                    }, 'json');
                }
            });
            $("#commodityname").autocomplete({
                source: "../Action/searchCommodity.php",
                minLength: 1,
                autoFocus: false,
                select: function (event, ui) {
                    // event 是当前事件对象
                    ss = ui.item.label.split(";");
                    $.post("../Action/searchCommodityById.php", {"commodityid": ss[0]}, function (data) {
                        //这里你可以处理获取的数据。我使用是json 格式。你也可以使用其它格式。或者为空，让它自己判断得了	
                        var c_class = data["CommodityClass"];
                        $("input:radio[value=" + c_class + "]").eq(0).attr("checked", 'checked');
                        $("#select_1").find("option[text='" + data["VarietyName_1"] + "']").attr("selected", true);
                        get_select_3(data["VarietyName_2"]);
                        $("#name").val(data["CommodityName"]);
                        $("#price").val(data["CommodityPrice"]);
                        $("#spec").val(data["CommoditySpec"]);
                        $("#describe").val(data["CommodityDescribe"]);
                        $("#brand").val(data["BrandId"] + ";" + data["BrandName"]);
                        $("#key").val(data["EnterpriseId"] + ";" + data["EnterpriseName"]);
                        $("#cropname").val(data["CropId"] + ";" + data["VarietyName"]);
                        $("#flag").empty();
                        if (data["CommodityFlag"] == 1)
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
                        var c_img = data["CommodityImgs"];
                        if (c_img !== null && c_img !== "") {
                            arr_img = c_img.split(";");
                            $("#commodityimg").empty();
                            for (var i = 0; i < arr_img.length; i++)
                            {
                                var imgurl = '<td><a href="http://www.renrenseed.com/files/commodityImgs/' + arr_img[i] + '" target="_blank"><img src="http://www.renrenseed.com/files/commodityImgs/' + arr_img[i] + '" height="100" width="100" /></a></td>';
                                $("#commodityimg").append(imgurl);
                            }
                        }
                    }, 'json');
                }
            });
        });
    </script>
    <script language="javascript">

        function get_select_1() {
            $.post("../Action/getselect_1.php", {sf_id: encodeURI($("#select_1").val())}, function (json) {
                var select_1 = $("#select_1");
                $("option", select_1).remove(); //清空原有的选项，也可使用 ds_id.empty(); 
                $.each(json, function (index, array) {
                    var option = "<option value='" + array['ds_id'] + "'>" + array['ds_name'] + "</option>";
                    select_1.append(option);
                    get_select_2();
                });
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
                get_select_2();
            });
        });
    </script>
    <script>
        function check(v) {
            var r = /^[0-9]+.?[0-9]*$/;
            if (!r.test(v)) { //isNaN也行的,正则可以随意扩展
                alert('只能输入数字');
            }
        }
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
        <title>商品维护</title>
    </head>
    <body>
        <form action="ModifyCommodityForIE.php" method="post"
              enctype='multipart/form-data'
              onkeydown="if (event.keyCode == 13)
                          return false;">
            <table>
                <tr>
                    <td>搜索名称</td>
                    <td><input type="text" id="commodityname" name="commodityname" /></td>

                </tr>
                <tr>
                    <td>商品分类</td>
                    <td><input type="radio" name="class" value="种子" checked="true">种子 <input
                            type="radio" name="class" value="农药">农药<input type="radio"
                            name="class" value="化肥">化肥</td>
                </tr>
                <tr>
                    <td>商品名称</td>
                    <td><input type="heddin" name="name" value="" id="name" /></td>
                    <td id="flag" name="flag"></td>
                </tr>
                <tr>
                    <td>商品价格</td>
                    <td><input type="heddin" name="price" id="price" value="0"
                               onpropertychange='check(this.value)' oninput='check(this.value)' /></td>
                </tr>
                <tr>
                    <td>品牌</td>
                    <td><input type="text" name="brand" id="brand" /></td>
                </tr>
                <tr>
                    <td>商品规格</td>
                    <td><input type="heddin" name="spec" id="spec" value="0" /></td>
                </tr>
                <tr>
                    <td>品种名称</td>
                    <td><input type="text" id="cropname" name="cropname" /></td>
                </tr>
                <tr>
                    <td width=100>品种</td>
                    <td><select name="select_1" id="select_1" title="选择品种"
                                style="width: 100px">
                        </select></td>
                    <td><select name="select_2" id="select_2" title="选择品种"
                                style="width: 100px">
                        </select></td>
                </tr>

                <tr>
                    <td>商品描述</td>
                    <td><textarea name="describe" rows="2" cols="20" id="describe"
                                  style="height: 100px; width: 500px;"></textarea></td>
                </tr>
                <!-- 			<tr>
                        <td>品牌</td>
                        <td><input type="text" name="brand" id="brand" /></td>
                </tr> -->
                <tr>
                    <td>所属企业</td>
                    <td><input type="text" id="key" name="key" /></td>

                </tr>
                <tr name="commodityimg" id="commodityimg"></tr>

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
$path = DT_ROOT . '/files/commodityImgs/';
if (isset($_POST ["flag_qiyong"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('商品名称不能为空')</script>";
        return;
    }
    $arr_commodityid = explode(';', $_POST ['commodityname']);
    $app_commodityid = $arr_commodityid [0];
    $sql = "update AppCommodity SET  CommodityFlag=0
	where CommodityId=$app_commodityid";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}
if (isset($_POST ["flag_zuofei"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('商品名称不能为空')</script>";
        return;
    }
    $arr_commodityid = explode(';', $_POST ['commodityname']);
    $app_commodityid = $arr_commodityid [0];
    $sql = "update AppCommodity SET CommodityFlag=1 
	where CommodityId=$app_commodityid";
    $update = $db->query($sql);
    echo "<script>alert(" . $update . ")</script>";
}

if (isset($_POST ["add"]) || isset($_POST ["modify"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('商品名称不能为空');history.back();</script>";
        return;
    }
    /*
     * if (empty ( $_POST ['price'] )) { // 点击提交按钮后才执行
     * echo "<script>alert('商品价格不能为空');history.back();</script>";
     * return;
     * }
     */
    if (empty($_POST ['describe'])) { // 点击提交按钮后才执行
        echo "<script>alert('商品描述不能为空');history.back();</script>";
        return;
    }
    if (empty($_POST ['key'])) { // 点击提交按钮后才执行
        echo "<script>alert('所属企业不能为空');history.back();</script>";
        return;
    }

    /*
     * if (empty ( $_POST ['brand'] )) { // 点击提交按钮后才执行
     * echo "<script>alert('品牌不能为空')</script>";
     * return;
     * }
     */
    /*
     * if (empty ( $_POST ['spec'] )) { // 点击提交按钮后才执行
     * echo "<script>alert('规格不能为空');history.back();</script>";
     * return;
     * }
     */
    if (empty($_POST ['cropname'])) { // 点击提交按钮后才执行
        echo "<script>alert('品种不能为空');history.back();</script>";
        return;
    }
    $arr_crop = explode(';', $_POST ['cropname']);
    $crop_id = $arr_crop [0];
    $crop_name = $arr_crop [1];
    $app_commodity_title = $_POST ['name'];
    $app_commodity_name = $_POST ['name'];
    $app_commodity_class = $_POST ['class'];
    $app_commodity_price = $_POST ['price'];
    $app_commodity_describe = $_POST ['describe'];
    $app_Variety_1 = $_POST ['select_1'];
    $app_Variety_2 = $_POST ['select_2'];

    $arr_brand = explode(';', $_POST ['brand']);
    $app_brand = $arr_brand [0];

    $app_spec = $_POST ['spec'];
    $arr_companyid = explode(';', $_POST ['key']);
    $companyid = $arr_companyid [0];
    $arr_commodityid = explode(';', $_POST ['commodityname']);
    $app_commodityid = $arr_commodityid [0];

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

            $rename = 'commodity_' . time() . '_' . $i;
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
                $sql = "update AppCommodity SET CommodityTitle='$app_commodity_title',CommodityName='$app_commodity_name'
			,CommodityPrice='$app_commodity_price',CommodityDescribe='$app_commodity_describe',
`Owner`='$companyid',CommodityImgs='$insert',CommodityVariety='$crop_id',CommodityClass='$app_commodity_class',CommodityVariety_1='$app_Variety_1',CommodityVariety_2='$app_Variety_2'
,CommoditySpec='$app_spec',CommodityBrand='$app_brand' ,CommodityImgsMin='$insert_min' 
where CommodityId=$app_commodityid";
                $update = $db->query($sql);
                echo "<script>alert(" . $update . ")</script>";
            } else if (isset($_POST ["add"])) {
                $insert = implode(";", $insert_name);
                $sql = "INSERT INTO AppCommodity VALUE (NULL, '$app_commodity_title', '$app_commodity_name', '$app_commodity_price', '$time', '0',
		'$time', '$app_commodity_describe', '$companyid', '0', '$insert', '$crop_id', '$app_commodity_class', '1','$app_Variety_1','$app_Variety_2',0,'$app_brand','$app_spec','0','$insert_min');";
                $result_add = $db->query($sql);
                $result_id = $db->lastInsertId();
                echo "<script>alert(" . $result_id . ")</script>";
            }
        }
    } else {
        if (isset($_POST ["modify"])) {
            $sql = "update AppCommodity SET CommodityTitle='$app_commodity_title',CommodityName='$app_commodity_name'
				,CommodityPrice='$app_commodity_price',CommodityDescribe='$app_commodity_describe',
				`Owner`='$companyid',CommodityVariety='$crop_id',CommodityClass='$app_commodity_class',CommodityVariety_1='$app_Variety_1',CommodityVariety_2='$app_Variety_2'
				,CommoditySpec='$app_spec',CommodityBrand='$app_brand' 
				where CommodityId=$app_commodityid";
            $update = $db->query($sql);
            echo "<script>alert(" . $update . ")</script>";
        } else if (isset($_POST ["add"])) {
            $sql = "INSERT INTO AppCommodity VALUE (NULL, '$app_commodity_title', '$app_commodity_name', '$app_commodity_price', '$time', '0',
		'$time', '$app_commodity_describe', '$companyid', '0', '', '$crop_id', '$app_commodity_class', '1','$app_Variety_1','$app_Variety_2',0,'$app_brand','$app_spec','0','');";
            $result_add = $db->query($sql);
            $result_id = $db->lastInsertId();
            echo "<script>alert(" . $result_id . ")</script>";
        }
    }
}
?>

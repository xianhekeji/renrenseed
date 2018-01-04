<?php

require '../../commonNew.php';
require DT_ROOT . '/model/setting/Class/RRZ_enterprise.php';
include '../../wxAction.php';
$path = DT_ROOT . '/files/companyImgs/';
$data = array();
//数据连接初始化
if (isset($_POST ["flag_qiyong"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('经销商名称不能为空')</script>";
        return;
    }
    $arr_id = explode(';', $_POST ['name']);
    $id = $arr_id [0];
    $eninfo = new RRZ_enterprise($id);
    $eninfo->__construct($id);
    $result = $eninfo->setFlag(0);
    echo "<script>alert('" . $result . "')</script>";
}
if (isset($_POST ["flag_zuofei"])) {
    if (empty($_POST ['name'])) { // 点击提交按钮后才执行
        echo "<script>alert('经销商名称不能为空')</script>";
        return;
    }
    $arr_id = explode(';', $_POST ['name']);
    $id = $arr_id [0];
    $eninfo = new RRZ_enterprise($id);
    $eninfo->__construct($id);
    $result = $eninfo->setFlag(1);

    echo "<script>alert('" . $result . "')</script>";
}

if (isset($_POST ["add"]) || isset($_POST ["modify"])) {
    $images = isset($_FILES ["myfile"]) ? $_FILES ["myfile"] : '';
    $site = isset($_REQUEST ['site']) ? $_REQUEST ['site'] : '';
    $dis_province = isset($_POST ['select_province']) ? $_POST ['select_province'] : '';
    $dis_city = isset($_POST ['select_city']) ? $_POST ['select_city'] : '';
    $dis_zone = isset($_POST ['select_zone']) ? $_POST ['select_zone'] : '';
    $dis_address = isset($_POST ['address']) ? $_POST ['address'] : '';
    $dis_star = isset($_POST ['star']) ? $_POST ['star'] : '';
    $dis_phone = isset($_POST ['selected_phone']) ? $_POST ['selected_phone'] : '';
    $dis_lat = isset($_POST ['lat']) ? $_POST ['lat'] : '0';
    $dis_lon = isset($_POST ['lon']) ? $_POST ['lon'] : '0';
    $dis_com_url = isset($_POST ['com_url']) ? $_POST ['com_url'] : '';
    $dis_introduce = addslashes(htmlspecialchars($_POST ['introduce']));
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
                $eninfo = new RRZ_enterprise($id);
                $eninfo->__construct($id);
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
                $result = $eninfo->updateInfo($sql);
                echo "<script>alert(" . $result . ")</script>";
            } else if (isset($_POST ["add"])) {
                $dis_name = $_POST ['name'];
                $eninfo = new RRZ_enterprise(0);
                $eninfo->__construct(0);
                $para = array();
                $para['enterpriseName'] = $dis_name;
                $para['enterpriseTelephone'] = $dis_phone;
                $para['enterpriseIntroduce'] = $dis_introduce;
                $para['enterpriseProvince'] = $dis_province;
                $para['enterpriseCity'] = $dis_city;
                $para['enterpriseZone'] = $dis_zone;
                $para['enterpriseAddressDetail'] = $dis_address;
                $para['enterpriseLat'] = $dis_lat;
                $para['enterpriseLon'] = $dis_lon;
                $para['enterpriseCommentLevel'] = $dis_star;
                $para['enterpriseUserAvatar'] = $insert;
                $para['enterpriseWeb'] = $dis_com_url;

                $result = $eninfo->addNew($para);
                echo "<script>alert(" . $result . ")</script>";
            }
        }
    } else {
        if (isset($_POST ["modify"])) {
            $arr_id = explode(';', $_POST ['name']);
            $id = $arr_id [0];
            $name = $arr_id [1];
            $eninfo = new RRZ_enterprise($id);
            $eninfo->__construct($id);
            $updatecondition = "EnterpriseName ='$name',EnterpriseAddressDetail ='$dis_address',EnterpriseCommentLevel='$dis_star',EnterpriseTelephone='$dis_phone',EnterpriseIntroduce='$dis_introduce',EnterpriseLat='$dis_lat',EnterpriseLon='$dis_lon',EnterpriseWeb='$dis_com_url'";
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
            $result = $eninfo->updateInfo($sql);
            echo "<script>alert(" . $result . ")</script>";
        } else if (isset($_POST ["add"])) {
            $dis_name = $_POST ['name'];
            $eninfo = new RRZ_enterprise(0);
            $eninfo->__construct(0);
            $para = array();
            $para['enterpriseName'] = $dis_name;
            $para['enterpriseTelephone'] = $dis_phone;
            $para['enterpriseIntroduce'] = $dis_introduce;
            $para['enterpriseProvince'] = $dis_province;
            $para['enterpriseCity'] = $dis_city;
            $para['enterpriseZone'] = $dis_zone;
            $para['enterpriseAddressDetail'] = $dis_address;
            $para['enterpriseLat'] = $dis_lat;
            $para['enterpriseLon'] = $dis_lon;
            $para['enterpriseCommentLevel'] = $dis_star;
            $para['enterpriseWeb'] = $dis_com_url;
            $result = $eninfo->addNew($para);
            echo "<script>alert(" . $result . ")</script>";
        }
    }
}
echo $twig->render('setting/EnterpriseSetting.xhtml', $data);

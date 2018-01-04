<?php

class RRZ_enterprise {

    private $enterpriseId;
    private $enterpriseName; //企业名称
    private $enterpriseBusScrope; //企业名称
    private $enterpriseLevel; //推荐标志
    private $enterpriseTelephone; //企业电话
    private $enterpriseFlag; //作废标记
    private $enterpriseIntroduce; //企业简介
    private $enterpriseProvince; //所属省份
    private $enterpriseCity; //所属市
    private $enterpriseZone; //所属乡镇
    private $enterpriseAddressDetail; //详细地址
    private $enterpriseLat; //经度
    private $enterpriseLon; //纬度
    private $enterpriseUserCode; //关联用户（未用）
    private $enterprisePassword; //关联用户密码（未用）
    private $enterpriseUserAvatar; //头像
    private $enterpriseCommentLevel; //点评级别（功能已关闭）
    private $enterpriseWeb; //点评级别（功能已关闭）
    private $enterpriseOrderNo; //自定义排序
    private $enterpriseInfo; //存储数据库返回信息的数组变量.
    private $db;

    //设置企业信息
    public function __construct($id) {
        require (DT_ROOT . "/data/dbClass.php"); //包含配置信息.
//        require_once DT_ROOT . '/data/XH_Pdo.class.php';
//        $db = XH_Pdo::getInstance();
        $this->db = $db;
        $sql = "select * from AppEnterprise where EnterpriseId=$id";
        $this->enterpriseInfo = $db->row($sql); //返回查询结果到数组
        $this->getInfo(); //调用传递信息的方法.
//        $db->CloseConnection();
    }

    function getInfo() {
        $this->enterpriseId = $this->enterpriseInfo["EnterpriseId"];
        $this->enterpriseName = $this->enterpriseInfo["EnterpriseName"];
        $this->enterpriseBusScrope = $this->enterpriseInfo["EnterpriseBusScrope"];
        $this->enterpriseLevel = $this->enterpriseInfo["EnterpriseLevel"];
        $this->enterpriseTelephone = $this->enterpriseInfo["EnterpriseTelephone"];
        $this->enterpriseFlag = $this->enterpriseInfo["EnterpriseFlag"];
        $this->enterpriseIntroduce = $this->enterpriseInfo["EnterpriseIntroduce"];
        $this->enterpriseProvince = $this->enterpriseInfo["EnterpriseProvince"];
        $this->enterpriseCity = $this->enterpriseInfo["EnterpriseCity"];
        $this->enterpriseZone = $this->enterpriseInfo["EnterpriseZone"];
        $this->enterpriseAddressDetail = $this->enterpriseInfo["EnterpriseAddressDetail"];
        $this->enterpriseLat = $this->enterpriseInfo["EnterpriseLat"];
        $this->enterpriseLon = $this->enterpriseInfo["EnterpriseLon"];
        $this->enterpriseUserCode = $this->enterpriseInfo["EnterpriseUserCode"];
        $this->enterprisePassword = $this->enterpriseInfo["EnterprisePassword"];
        $this->enterpriseUserAvatar = $this->enterpriseInfo["EnterpriseUserAvatar"];
        $this->enterpriseCommentLevel = $this->enterpriseInfo["EnterpriseCommentLevel"];
        $this->enterpriseOrderNo = $this->enterpriseInfo["EnterpriseOrderNo"];
        $this->enterpriseWeb = $this->enterpriseInfo["EnterpriseWeb"];
    }

    //返回每个属性的public 方法.
    public function getEnInfo() {
        return $this->enterpriseInfo;
    }

    //返回每个属性的public 方法.
    public function setFlag($flag) {
        $sql = "update AppEnterprise SET EnterpriseFlag=$flag WHERE EnterpriseId= $this->enterpriseId";
        $update = $this->db->query($sql);
        if ($update == 1) {
            if ($flag == 0) {
                return '启用成功--' . $update;
            } else {
                return '作废成功--' . $update;
            }
        } else {
            return '操作失败--' . $update;
        }
    }

    public function addNew($param) {
        foreach ($param as $key => $value) {
            $this->$key = $value;
        }
        $sql = "insert into AppEnterprise values(NULL,'$this->enterpriseName','DistributorTrademark','0','$this->enterpriseTelephone','0','$this->enterpriseIntroduce','$this->enterpriseProvince','$this->enterpriseCity','$this->enterpriseZone','$this->enterpriseAddressDetail','$this->enterpriseLat','$this->enterpriseLon','$this->enterpriseUserCode','$this->enterprisePassword','$this->enterpriseUserAvatar','$this->enterpriseCommentLevel',0,'$this->enterpriseWeb')";
        $result_add = $this->db->query($sql);
        return $this->db->lastInsertId();
    }

    public function updateInfo($sql) {
        return $this->db->query($sql);
    }

}

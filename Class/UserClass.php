<?php

class UserClass {

    private $userId;
    private $userName;
    private $userPhone;
    private $userCreateTime;
    private $userProvince;
    private $userCity;
    private $userZone;
    private $userAddressDetail;
    private $userSex;
    private $userLastLat;
    private $userLastLon;
    private $userAvatar;
    private $userOpenId;
    private $userGZHOpenId;
    private $userWebOpenId;
    private $userUnionId;
    private $userLastLoginTimeWX;
    private $userLastLoginTimeWEB;
    private $userLastLoginTimeGZH;
    private $db;
    private $columns; //文章数据表字段集合

    public function __construct() {
        require (DT_ROOT . "/data/dbClass.php"); //包含配置信息.
        $this->db = $db;
        $this->columns = $this->db->getColumn("WXUser", null);
    }

    function checkInfo($unionid) {
        $sql = "select * from WXUser where UserUnionId=$unionid";
        $result = $this->db->query($sql); //返回查询结果到数组
        return count($result) > 0 ? true : false;
    }

    function setInfo($id) {
        $sql = "select * from WXUser where UserId=$id";
        $result = $this->db->row($sql); //返回查询结果到数组
        foreach ($this->columns as $column) {
            $aa = "set" . "$column";
            $this->$aa($result["$column"]);
        }
        return $result;
    }

    function updateInfo($param) {
        $condition = '';
        foreach ($this->columns as $column) {
            if (!empty($param[$column])) {
                if (empty($condition)) {
                    $condition = $column . "='" . $param[$column] . "'";
                } else {
                    $condition = $condition . "," . $column . "='" . $param[$column] . "'";
                }
            }
        }
        $sql = "update WXUser set $condition where UserId=$this->userId";
        return $this->db->query($sql);
    }

    function insertInfo($param) {
        $condition = '';
        foreach ($this->columns as $column) {
            if (!empty($param[$column])) {
                if (empty($condition)) {
                    $condition = $column . "='" . $param[$column] . "'";
                } else {
                    $condition = $condition . "," . $column . "='" . $param[$column] . "'";
                }
            }
        }
        $sql = "insert into WXUser set $condition ";
        $this->db->query($sql);
        return $this->db->lastInsertId();
    }

    function getUserId() {
        return $this->userId;
    }

    function getUserName() {
        return $this->userName;
    }

    function getUserPhone() {
        return $this->userPhone;
    }

    function getUserCreateTime() {
        return $this->userCreateTime;
    }

    function getUserProvince() {
        return $this->userProvince;
    }

    function getUserCity() {
        return $this->userCity;
    }

    function getUserZone() {
        return $this->userZone;
    }

    function getUserAddressDetail() {
        return $this->userAddressDetail;
    }

    function getUserSex() {
        return $this->userSex;
    }

    function getUserLastLat() {
        return $this->userLastLat;
    }

    function getUserLastLon() {
        return $this->userLastLon;
    }

    function getUserAvatar() {
        return $this->userAvatar;
    }

    function getUserOpenId() {
        return $this->userOpenId;
    }

    function getUserGZHOpenId() {
        return $this->userGZHOpenId;
    }

    function getUserWebOpenId() {
        return $this->userWebOpenId;
    }

    function getUserUnionId() {
        return $this->userUnionId;
    }

    function getUserLastLoginTimeWX() {
        return $this->userLastLoginTimeWX;
    }

    function getUserLastLoginTimeWEB() {
        return $this->userLastLoginTimeWEB;
    }

    function getUserLastLoginTimeGZH() {
        return $this->userLastLoginTimeGZH;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setUserName($userName) {
        $this->userName = $userName;
    }

    function setUserPhone($userPhone) {
        $this->userPhone = $userPhone;
    }

    function setUserCreateTime($userCreateTime) {
        $this->userCreateTime = $userCreateTime;
    }

    function setUserProvince($userProvince) {
        $this->userProvince = $userProvince;
    }

    function setUserCity($userCity) {
        $this->userCity = $userCity;
    }

    function setUserZone($userZone) {
        $this->userZone = $userZone;
    }

    function setUserAddressDetail($userAddressDetail) {
        $this->userAddressDetail = $userAddressDetail;
    }

    function setUserSex($userSex) {
        $this->userSex = $userSex;
    }

    function setUserLastLat($userLastLat) {
        $this->userLastLat = $userLastLat;
    }

    function setUserLastLon($userLastLon) {
        $this->userLastLon = $userLastLon;
    }

    function setUserAvatar($userAvatar) {
        $this->userAvatar = $userAvatar;
    }

    function setUserOpenId($userOpenId) {
        $this->userOpenId = $userOpenId;
    }

    function setUserGZHOpenId($userGZHOpenId) {
        $this->userGZHOpenId = $userGZHOpenId;
    }

    function setUserWebOpenId($userWebOpenId) {
        $this->userWebOpenId = $userWebOpenId;
    }

    function setUserUnionId($userUnionId) {
        $this->userUnionId = $userUnionId;
    }

    function setUserLastLoginTimeWX($userLastLoginTimeWX) {
        $this->userLastLoginTimeWX = $userLastLoginTimeWX;
    }

    function setUserLastLoginTimeWEB($userLastLoginTimeWEB) {
        $this->userLastLoginTimeWEB = $userLastLoginTimeWEB;
    }

    function setUserLastLoginTimeGZH($userLastLoginTimeGZH) {
        $this->userLastLoginTimeGZH = $userLastLoginTimeGZH;
    }

}

?>

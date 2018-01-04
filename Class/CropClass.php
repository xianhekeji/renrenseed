<?php

class ArticleClass {

    private $cropId; //唯一ID
    private $cropCategory1; //文章标题
    private $cropCategory2; //文章内容
    private $varietyName; //文章创建时间
    private $cropImgs; //文章作废标记
    private $isGen; //文章封面
    private $flag; //文章标签
    private $minGuidePrice; //文章数据表字段集合
    private $maxGuidePrice; //文章数据表字段集合
    private $breedRegion; //文章数据表字段集合
    private $memo; //文章数据表字段集合
    private $breedOrganization; //文章数据表字段集合
    private $cropLevel; //文章数据表字段集合
    private $cropOrderNo; //文章数据表字段集合
    private $cropImgsMin; //文章数据表字段集合
    private $cropStatus; //文章数据表字段集合
    private $cropVipStatus; //文章数据表字段集合
    private $cropClickCount; //文章数据表字段集合
    private $columns; //文章数据表字段集合

    public function __construct() {
        require (DT_ROOT . "/data/dbClass.php"); //包含配置信息.
        $this->db = $db;
        $this->columns = $this->db->getColumn("WXCrop", null);
    }

    function setInfo($id) {
        $sql = "select * from WXCrop where CropId=$id";
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
        $sql = "update WXArticle set $condition where ArticleId=$this->articleId";
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
        $sql = "insert into WXArticle set $condition ";
        $this->db->query($sql);
        return $this->db->lastInsertId();
    }

    function getCropId() {
        return $this->cropId;
    }

    function getCropCategory1() {
        return $this->cropCategory1;
    }

    function getCropCategory2() {
        return $this->cropCategory2;
    }

    function getVarietyName() {
        return $this->varietyName;
    }

    function getCropImgs() {
        return $this->cropImgs;
    }

    function getIsGen() {
        return $this->isGen;
    }

    function getFlag() {
        return $this->flag;
    }

    function getMinGuidePrice() {
        return $this->minGuidePrice;
    }

    function getMaxGuidePrice() {
        return $this->maxGuidePrice;
    }

    function getBreedRegion() {
        return $this->breedRegion;
    }

    function getMemo() {
        return $this->memo;
    }

    function getBreedOrganization() {
        return $this->breedOrganization;
    }

    function getCropLevel() {
        return $this->cropLevel;
    }

    function getCropOrderNo() {
        return $this->cropOrderNo;
    }

    function getCropImgsMin() {
        return $this->cropImgsMin;
    }

    function getCropStatus() {
        return $this->cropStatus;
    }

    function getCropVipStatus() {
        return $this->cropVipStatus;
    }

    function getCropClickCount() {
        return $this->cropClickCount;
    }

    function setCropId($cropId) {
        $this->cropId = $cropId;
    }

    function setCropCategory1($cropCategory1) {
        $this->cropCategory1 = $cropCategory1;
    }

    function setCropCategory2($cropCategory2) {
        $this->cropCategory2 = $cropCategory2;
    }

    function setVarietyName($varietyName) {
        $this->varietyName = $varietyName;
    }

    function setCropImgs($cropImgs) {
        $this->cropImgs = $cropImgs;
    }

    function setIsGen($isGen) {
        $this->isGen = $isGen;
    }

    function setFlag($flag) {
        $this->flag = $flag;
    }

    function setMinGuidePrice($minGuidePrice) {
        $this->minGuidePrice = $minGuidePrice;
    }

    function setMaxGuidePrice($maxGuidePrice) {
        $this->maxGuidePrice = $maxGuidePrice;
    }

    function setBreedRegion($breedRegion) {
        $this->breedRegion = $breedRegion;
    }

    function setMemo($memo) {
        $this->memo = $memo;
    }

    function setBreedOrganization($breedOrganization) {
        $this->breedOrganization = $breedOrganization;
    }

    function setCropLevel($cropLevel) {
        $this->cropLevel = $cropLevel;
    }

    function setCropOrderNo($cropOrderNo) {
        $this->cropOrderNo = $cropOrderNo;
    }

    function setCropImgsMin($cropImgsMin) {
        $this->cropImgsMin = $cropImgsMin;
    }

    function setCropStatus($cropStatus) {
        $this->cropStatus = $cropStatus;
    }

    function setCropVipStatus($cropVipStatus) {
        $this->cropVipStatus = $cropVipStatus;
    }

    function setCropClickCount($cropClickCount) {
        $this->cropClickCount = $cropClickCount;
    }

}

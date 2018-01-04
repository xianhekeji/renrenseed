<?php

//$luckDrawClass = new LuckDrawClass();
//$luckinfo = array();
//$luckinfo['LuckUserUinoId'] = "name";
//$luckinfo['LuckContent'] = "12111";
//$luckinfo['LuckUserName'] = "1111";
//$result = $luckDrawClass::insertInfo($luckinfo);
//var_dump($result);

Class LuckDrawClass {

    protected static $LuckId;
    protected static $LuckUserUinoId;
    protected static $LuckContent;
    protected static $LuckUserName;
    protected static $LuckRecordTime;
    protected static $this_db;
    protected static $columns; //文章数据表字段集合

    public function __construct() {
        require ("../data/dbClass.php"); //包含配置信息.
        self::$this_db = $db;
        self::$columns = self::$this_db->getColumn("WXLuckDrawRecord", null);
    }

    public static function checkInfo($id) {
        $sql = "select LuckId from WXLuckDrawRecord where LuckUserUinoId='$id' and DATE_FORMAT(NOW(),'%m-%d-%Y')=DATE_FORMAT(LuckRecordTime,'%m-%d-%Y')";
        $result = self::$this_db->query($sql); //返回查询结果到数组
        return count($result) > 0 ? true : false;
    }

    public static function setInfo($id) {
        $sql = "select * from WXLuckDrawRecord where LuckId=$id";
        $result = self::$this_db->row($sql); //返回查询结果到数组
        foreach (self::$columns as $column) {
            $aa = "set" . "$column";
            $this->$aa($result["$column"]);
        }
        return $result;
    }

    public static function updateInfo($param) {
        $condition = '';
        foreach (self::$columns as $column) {
            if (!empty($param[$column])) {
                if (empty($condition)) {
                    $condition = $column . "='" . $param[$column] . "'";
                } else {
                    $condition = $condition . "," . $column . "='" . $param[$column] . "'";
                }
            }
        }
        $sql = "update WXLuckDrawRecord set $condition where LuckId=$LuckId";
        return self::$this_db->query($sql);
    }

    public static function insertInfo($param) {
        $condition = '';
        foreach (self::$columns as $column) {
            if (!empty($param[$column])) {
                if (empty($condition)) {
                    $condition = $column . "='" . $param[$column] . "'";
                } else {
                    $condition = $condition . "," . $column . "='" . $param[$column] . "'";
                }
            }
        }
        $sql = "insert into WXLuckDrawRecord set $condition ";
        self::$this_db->query($sql);
        return self::$this_db->lastInsertId();
    }

    static function getLuckId() {
        return self::$LuckId;
    }

    static function getLuckUserUinoId() {
        return self::$LuckUserUinoId;
    }

    static function getLuckContent() {
        return self::$LuckContent;
    }

    static function getLuckUserName() {
        return self::$LuckUserName;
    }

    static function getLuckRecordTime() {
        return self::$LuckRecordTime;
    }

    static function setLuckId($LuckId) {
        self::$LuckId = $LuckId;
    }

    static function setLuckUserUinoId($LuckUserUinoId) {
        self::$LuckUserUinoId = $LuckUserUinoId;
    }

    static function setLuckContent($LuckContent) {
        self::$LuckContent = $LuckContent;
    }

    static function setLuckUserName($LuckUserName) {
        self::$LuckUserName = $LuckUserName;
    }

    static function setLuckRecordTime($LuckRecordTime) {
        self::$LuckRecordTime = $LuckRecordTime;
    }

}

?>
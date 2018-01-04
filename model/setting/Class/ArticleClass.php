<?php

class ArticleClass {

    private $articleId; //唯一ID
    private $articleTitle; //文章标题
    private $articleContent; //文章内容
    private $articleCreateTime; //文章创建时间
    private $articleFlag; //文章作废标记
    private $articleCover; //文章封面
    private $articleLabel; //文章标签
    private $articleClassId; //文章分类ID
    private $articleReadCount; //已读数量
    private $columns; //文章数据表字段集合

    public function __construct() {
        require (DT_ROOT . "/data/dbClass.php"); //包含配置信息.
        $this->db = $db;
        $this->columns = $this->db->getColumn("WXArticle", null);
    }

    function setInfo($id) {
        $sql = "select * from WXArticle where ArticleId=$id";
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
//        echo $sql;
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
//        echo $sql;
    }

    function getArticleId() {
        return $this->articleId;
    }

    function getArticleTitle() {
        return $this->articleTitle;
    }

    function getArticleContent() {
        return $this->articleContent;
    }

    function getArticleCreateTime() {
        return $this->articleCreateTime;
    }

    function getArticleFlag() {
        return $this->articleFlag;
    }

    function getArticleCover() {
        return $this->articleCover;
    }

    function getArticleLabel() {
        return $this->articleLabel;
    }

    function setArticleId($articleId) {
        $this->articleId = $articleId;
    }

    function setArticleTitle($articleTitle) {
        $this->articleTitle = $articleTitle;
    }

    function setArticleContent($articleContent) {
        $this->articleContent = $articleContent;
    }

    function setArticleCreateTime($articleCreateTime) {
        $this->articleCreateTime = $articleCreateTime;
    }

    function setArticleFlag($articleFlag) {
        $this->articleFlag = $articleFlag;
    }

    function setArticleCover($articleCover) {
        $this->articleCover = $articleCover;
    }

    function setArticleLabel($articleLabel) {
        $this->articleLabel = $articleLabel;
    }

    function getArticleClassId() {
        return $this->articleClassId;
    }

    function setArticleClassId($articleClassId) {
        $this->articleClassId = $articleClassId;
    }
    function getArticleReadCount() {
        return $this->articleReadCount;
    }

    function setArticleReadCount($articleReadCount) {
        $this->articleReadCount = $articleReadCount;
    }


}

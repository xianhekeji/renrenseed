<?php
require '../../../common.php';
include '../../../wxAction.php';
include '../Class/ArticleClass.php';
$htmlData = '';
$article_class = new ArticleClass();

$arr_province = isset($_POST ['t_province']) ? $_POST ['t_province'] : '';
//if (!empty($_POST ['url1'])) {
//    $url = $_POST ['url1'];
//}
$url = !empty($_POST ['url1']) ? $_POST ['url1'] : '';
$titlenew = isset($_POST ['titlenew']) ? $_POST ['titlenew'] : '';
$title = isset($_POST ['title']) ? $_POST ['title'] : '';
$htmlData = isset($_POST ['content1']) ? stripslashes($_POST ['content1']) : '';
$class = isset($_POST ['select_class']) ? $_POST ['select_class'] : '';
$data = htmlspecialchars($htmlData);
if (isset($_POST ["add"])) {
    $param = array();
    $param['ArticleTitle'] = $titlenew;
    $param['ArticleContent'] = $data;
    $param['ArticleCover'] = $url;
    $param['ArticleLabel'] = $arr_province;
    $param['ArticleClassId'] = $class;
    $insert = $article_class->insertInfo($param);
    echo "<script>alert(" . $insert . ")</script>";
}
if (isset($_POST ["modify"])) {
    $arr_id = explode(';', $_POST ['title']);
    $id = $arr_id [0];
    $article_class->setInfo($id);
    $param = array();
    $param['ArticleTitle'] = $titlenew;
    $param['ArticleContent'] = $data;
    $param['ArticleCover'] = $url;
    $param['ArticleLabel'] = $arr_province;
    $param['ArticleClassId'] = $class;
    $update = $article_class->updateInfo($param);
    echo "<script>alert(" . $update . ")</script>";
}
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>文章添加</title>
        <link rel="stylesheet" href="../../../css/jquery.ui.autocomplete.css">
        <script type="text/javascript" src="../../../js/jquery-1.4.2.js"></script>
        <script type="text/javascript" src="../../../js/jquery.ui.core.js"></script>
        <script type="text/javascript" src="../../../js/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="../../../js/jquery.ui.position.js"></script>
        <script type="text/javascript" src="../../../js/jquery.ui.autocomplete.js"></script>
        <link rel="stylesheet" href="../../../themes/default/default.css" />
        <link rel="stylesheet" href="../../../js/plugins/code/prettify.css" />
        <script charset="utf-8" src="../../../js/kindeditor.js"></script>
        <script charset="utf-8" src="../../../js/lang/zh_CN.js"></script>
        <script charset="utf-8" src="../../../js/plugins/code/prettify.js"></script>
        <script type="text/javascript" src="../../../js/articlesetting.js" ></script>
    </head>
    <body>
        <form name="example" method="post" action="AddArticle.php">
            <p>
                搜索：<input type="text" id="title" name="title" id="title" />
            </p>
            <p>
                标题：<input type="text" id="titlenew" name="titlenew" id="title" />
                <select name="select_class" id="select_class" title="选择类别"></select>
            </p>

            <p>
                封面图片：<input type="text" name="url1" id="url1" value="" /> <input
                    type="button" id="image1" value="选择图片" />（网络图片 + 本地上传）
            </p>
            <tr>
            <p>
                标签 <input type="text" id="au_province" name="au_province" />
                <a class="phone_a" href="javascript:void(0);"  onclick="addPhone()"><strong>+</strong></a>
                <textarea style="width: 300px;" type="text" id="t_province"
                          name="t_province" readonly="readonly"></textarea>
                <input type="button" id="reset_province" value="重置"
                       onclick="resetProvince()" />
            </p>
            <textarea name="content1"
                      style="width: 1000px; height: 600px; visibility: hidden;"></textarea>
            <br />
            <input name="add" class="submit" type="submit" value="新建" />(新建快捷键:
            Ctrl + Enter)
            <input name="modify" class="submit" type="submit" value="修改" />
            <input name="reset" class="submit" type="submit" value="重置" />
        </form>
    </body>
</html>


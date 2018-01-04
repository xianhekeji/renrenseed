<?php

require '../common.php';
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();

class wechatCallbackapiTest {

    private $_msg_template = array(
        'text' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>', //文本回复XML模板
        'image' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>', //图片回复XML模板
        'music' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music></xml>', //音乐模板
        'news' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>%s</ArticleCount><Articles>%s</Articles></xml>', // 新闻主体
        'news_item' => '<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>', //某个新闻模板
    );
    private $db;

    public function responseMsg() {
        require (DT_ROOT . "/data/dbClass.php"); //包含配置信息.
        $this->db = $db;
        $postStr = file_get_contents("php://input", 'r');
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            switch ($postObj->MsgType) {
                case 'event':
//判断具体的时间类型（关注、取消、点击）
                    $event = $postObj->Event;
                    if ($event == 'subscribe') { // 关注事件
                        $this->_doSubscribe($postObj);
                    } elseif ($event == 'CLICK') {//菜单点击事件
//  $this->_doClick($request_xml);
                    } elseif ($event == 'VIEW') {//连接跳转事件
//  $this->_doView($request_xml);
                    } else {
                        
                    }
                    break;
                case 'text'://文本消息
                    $this->_doText($postObj);
                    break;
            }
        }
    }

    /*
      关注后回复
     *  */

    private function _doSubscribe($postObj) {
        $content = '欢迎关注人人种品种大全！在这里您可以在“品种大全”菜单中查询和浏览数万个主要农作物的品种审定信息、非主要农作物登记信息、品种的经营企业信息、种业资讯、品种图库和种植者的点评等。您还可以通过“进入社区”菜单进入官方社区，发表观点和互相交流。我们一直在努力为您的种业生活提供便利，希望您使用愉快！温馨提示：公众号直接留言品种名称，可以直接查询出品种结果，快来试试吧。';
        $this->_msgText($postObj->FromUserName, $postObj->ToUserName, $content);
    }

    /*
     * 获取文本信息后处理逻辑
     *  */

    private function _doText($postObj) {
        $keyword = trim($postObj->Content);
        $to = trim($postObj->FromUserName);
        print str_repeat(" ", 4096);
        echo '';
        ob_flush();
        flush();
        $sql = "select *,b.varietyname c1,c.varietyname c2,
            case when (ISNULL(CropImgsMin)  or CropImgsMin='') then c.variety_img else a.CropImgsMin end img,
  case when (ISNULL(CropImgsMin)  or CropImgsMin='') then 1 else 0 end isCrop  from WXCrop a
left join app_variety b on a.CropCategory1=b.varietyid
left join app_variety c on a.CropCategory2=c.varietyid 
where  a.VarietyName='" . $keyword . "' or a.VarietyName=CONCAT('" . $keyword . "','号') or CONCAT(a.VarietyName,'号')='" . $keyword . "' 
                limit 0,1";
        $result = $this->db->query($sql);
        if (count($result) == 0) {
            $this->_msgKefu($to, "text", null, "很遗憾,没有找到该品种，请确保品种名称完整", null);
            exit();
        } else {
            $url = explode(';', $result[0]['img']);
            $result[0]['img'] = $url;
            $id = $result[0]['CropId'];
            $name = $result[0]['c1'] . "-" . $result[0]['c2'] . "-" . $result[0]['VarietyName'];
            $path = DT_ROOT . '/files/cropImgs/' . $url[0];
            $data = array('media' => new CURLFile(realpath($path)));
            $img_result = json_decode($this->getMediaID('image', $data), true);
            $img_id = $img_result["media_id"];
            $this->_msgKefu($to, "miniprogrampage", $id, $name, $img_id);
            $userinfo = json_decode($this->getUserInfo($to), true);
            $this->doLuckDraw($to, $userinfo);
        }
    }

    /**
     * 发送文本信息
     * @param  [type] $to      目标用户ID
     * @param  [type] $from    来源用户ID
     * @param  [type] $content 内容
     * @return [type]          [description]
     */
    private function _msgText($to, $from, $content) {
        $response = sprintf($this->_msg_template['text'], $to, $from, time(), $content);
        die($response);
    }

    /*
      发送客服消息
     *  */

    private function _msgKefu($to, $type, $id, $content, $img_id = null) {
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $this->getAccessToken();
        $page = "pages/itemCrop/itemCrop?CropId=" . $id;
        switch ($type) {
            case "text":
                $result = array(
                    "touser" => $to,
                    "msgtype" => 'text',
                    "text" => array(
                        "content" => urlencode($content),
                ));
                break;
            case "miniprogrampage":
                $result = array(
                    "touser" => $to,
                    "msgtype" => 'miniprogrampage',
                    "miniprogrampage" => array(
                        "title" => urlencode($content),
                        "appid" => "wx9b0627271d916fe3",
                        "pagepath" => $page,
                        "thumb_media_id" => $img_id
                ));
                break;
        }
        $data = urldecode(json_encode($result));
        $this->httpPost($url, $data);
    }

    /*
     * 回复抽奖功能
     * @param  [type] $to      目标用户ID
     * @param  [type] $userinfo    目标用户信息
     *  */

    public function checkUser($userinfo) {
        require '../Class/UserClass.php';
        $userClass = new UserClass();
        if ($userClass->checkInfo($userinfo['unionid'])) {
            $this->_msgKefu($to, "text", null, "已领奖", null);
        } else {
            $arr = array();
            $arr['UserGZHOpenId'] = $userinfo['openid'];
            $arr['UserName'] = $userinfo['nickname'];
            $arr['UserSex'] = $userinfo['sex'];
            $arr['UserCity'] = $userinfo['city'];
            $arr['UserProvince'] = $userinfo['province'];
            $arr['UserAvatar'] = $userinfo['headimgurl'];
            $time = date('Y-m-d H:i:s', time());
            $arr['UserLastLoginTimeWX'] = $time;
            $arr['UserLastLoginTimeWEB'] = $time;
            $arr['UserLastLoginTimeGZH'] = $time;
            $userClass->insertInfo($arr);
            $this->_msgKefu($to, "text", null, "已领奖", null);
        }
    }

    /*
     * 回复抽奖功能
     * @param  [type] $to      目标用户ID
     * @param  [type] $userinfo    目标用户信息
     *  */

    public function doLuckDraw($to, $userinfo) {
        //  if ($userinfo['nickname'] == "百丰农资-赵" || $userinfo['nickname'] == "巨丰种业（李）" || $userinfo['nickname'] == "Wei") {
        //$this->checkUser($userinfo);
        require 'LuckDrawTool.php';
        require '../Class/LuckDrawClass.php';
        $luckDrawTool = new LuckDrawTool();
        $result = $luckDrawTool::getDraw();
        $luckDrawClass = new LuckDrawClass();
        $luckinfo = array();
        $lastid = 0;
        if (!($luckDrawClass::checkInfo($userinfo['unionid'])) && json_decode($result)->msg == 1) {
            $luckinfo['LuckUserUinoId'] = $userinfo['unionid'];
            $luckinfo['LuckContent'] = json_decode($result)->prize_title;
            $luckinfo['LuckUserName'] = $userinfo['nickname'];
            $luckinfo['LuckDrawId'] = json_decode($result)->id;
            $lastid = $luckDrawClass::insertInfo($luckinfo);
            if ($lastid > 0) {
                $this->_msgKefu($to, "text", null, json_decode($result)->prize_title, null);
            }
        }
        //  }
    }

    /*
      发送文章
     *  */

    private function _msgNews($to, $from, $item_list = array()) {
        //拼凑文章部分
        $item_str = '';
        foreach ($item_list as $item) {
            $item_str .= sprintf($this->_msg_template['news_item'], $item['title'], $item['desc'], $item['picurl'], $item['url']);
        }
        //拼凑主体部分
        $response = sprintf($this->_msg_template['news'], $to, $from, time(), count($item_list), $item_str);
        die($response);
    }

    function getUserInfo($data) {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$data&lang=zh_CN";
        $result = $this->httpGet($url, null);
        return $result;
    }

    /*
     * 上传临时素材（永久素材也可以）：图片,语音，视频，缩略图
     * 储存到微信公众平台服务器，3天
     * 可通过上传后返回的media_id再次去取得该图片
     */

    function getMediaID($type, $data) {
        $access_token = $this->getAccessToken();
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
        $result = $this->httpPost($url, $data);
        return $result;
    }

    public function getAccessToken() {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3b7ff48efe8b5670&secret=a1c06947ff25a5ef12135aa5e186f65c";
        $output = $this->https_request($url);
        $jsoninfo = json_decode($output, true);
        $access_token = $jsoninfo["access_token"];
        return $access_token;
    }

    public function https_request($url, $data = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    function httpPost($url, $params) {
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_TIMEOUT, 30);   //只需要设置一个秒的数量就可以 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if ($response === false) {
            if (curl_errno($ch) == CURLE_OPERATION_TIMEDOUT) {
                //超时的处理代码  
                $response = "time out";
            } else {
                $response = "error" . curl_errno($ch);
            }
        }
        return $response;
    }

    function httpGet($url, $params) {
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_TIMEOUT, 30);   //只需要设置一个秒的数量就可以 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if ($response === false) {
            if (curl_errno($ch) == CURLE_OPERATION_TIMEDOUT) {
                //超时的处理代码  
                $response = "time out";
            } else {
                $response = "error" . curl_errno($ch);
            }
        }
        return $response;
    }

}

?>

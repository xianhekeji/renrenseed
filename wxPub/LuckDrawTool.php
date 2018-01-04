<?php

//$luckDrawTool = new LuckDrawTool();
//$result = $luckDrawTool::getDraw();
//var_dump($result);
//echo json_decode($result);

Class LuckDrawTool {

    protected static $prize_arr;
    protected static $this_db;

    public static function getDraw() {
        require ( "../data/dbClass.php"); //包含配置信息.
        $this_db = $db;
        $sql = "select id,min,max,probability v,begintime,endtime,flag,prize,prize_count from WXLuckdrawConfig where flag=1 and NOW()> begintime and  NOW()<endtime";
        $result = $this_db->query($sql); //返回查询结果到数组
        if (count($result) > 0) {
            $prize_arr = $result;
            foreach ($prize_arr as $key => $val) {
                $arr[$val['id']] = $val['v'];
            }
            $prize_id = self::getRand($arr); //根据概率获取奖品id 
            $data['msg'] = 1;
            $data['id'] = $prize_arr[$prize_id - 1]["id"];
            // $title = self::randFloat($prize_arr[$prize_id]['min'], $prize_arr[$prize_id]['max']);
            // $data['prize_title'] = round($title, 2); //中奖奖品 
            $title = $prize_arr[$prize_id - 1]["prize"];
            $count = $prize_arr[$prize_id - 1]["prize_count"];
            $sql = "select count(*) count from WXLuckDrawRecord where LuckDrawId=" . $data['id'];
            $count_result = $this_db->row($sql); //返回查询结果到数组
            if ($count_result["count"] < $count) {
                $data['prize_title'] = $title;
            } else {
                $sql = "select prize  from WXLuckdrawConfig where isLuck=0 and flag=1 and NOW()> begintime and  NOW()<endtime limit 0,1";
                $w_result = $this_db->row($sql); //返回查询结果到数组
                $data['prize_title'] = $w_result["prize"];
            }
            return json_encode($data);
        } else {
            $data['msg'] = 0;
            $data['prize_title'] = "无活动";
            return json_encode($data);
        }
    }

    /**
     * 生成0~1随机小数
     * @param Int  $min
     * @param Int  $max
     * @return Float
     */
    public static function randFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    public static function getRand($proArr) { //计算中奖概率 
        $rs = ''; //z中奖结果 
        $proSum = array_sum($proArr); //概率数组的总概率精度 
        //概率数组循环 
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $rs = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset($proArr);
        return $rs;
    }

}

?>
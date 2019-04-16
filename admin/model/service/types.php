<?php
namespace xh\run\admin\model;

class types{
    
    //获取类型
    public function get($type){
        if ($type == 1) return '微信';
        if ($type == 2) return '支付宝';
    }
    
}
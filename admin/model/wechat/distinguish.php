<?php
namespace xh\run\admin\model;

class distinguish{
   
    //识别状态
    public function status($code){
        if ($code == 1) echo '未登录';
        if ($code == 2) echo '<span style="color:#0097a7;">登录中..</span>';
        if ($code == 3) echo '<span style="color:#ffeb3b;">正在登录..</span>';
        if ($code == 4) echo '<span style="color:#4caf50;">在线</span>';
        if ($code == 5) echo '<span style="color:red;">微信异常</span>';
        if ($code == 6) echo '<span style="color:#6a1b9a;">安全注销中..</span>';
        if ($code == 7) echo '<span style="color:#6a1b9a;">扫码登录中..</span>';
    }
    
}
<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
require_once __DIR__.'/definitions.php';
require_once __DIR__.'/OperateDB/DbMgrInterface.php';
if(isset($_SESSION['userId'])) {
    header ('location: ./../../');
    exit();
} else if(isset($_REQUEST['resetaccount']) && trim($_REQUEST['resetaccount']) != '') {
    $verifyLink = trim($_REQUEST['resetaccount']);
    if (strlen($verifyLink) > 64) {
        $secureLink = substr($verifyLink, 32, 32);
        $linkId = substr($verifyLink, 64);
        $linkUser = DB_Query('Select userinfo.email, userinfo.userstatus from userinfo left join resetlinks on userinfo.id = resetlinks.userId where resetlinks.linkId='.$linkId.' and resetlinks.resetLink="'.$secureLink.'"','ASSOC','');
        if(is_array($linkUser)){
            if(count($linkUser) == 1) {
                if($linkUser[0]['userstatus'] == USER_ACTIVE) {
                    $userEmail = $linkUser[0]['email'];
                    require_once __DIR__.'./../resetpassword.php';
                } else {
                    header ('location: ./../../');
                    exit();
                }
            } else {
                header ('location: ./../../');
                exit();
            }
        } else {
            header ('location: ./../../');
            exit();
        }
    } else {
        header ('location: ./../../');
        exit();
    }
} else {
    header ('location: ./../../');
    exit();
}
?>
<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
require_once __DIR__.'/definitions.php';
require_once __DIR__.'/OperateDB/DbMgrInterface.php';
if(isset($_SESSION['userId'])) {
    header ('location: ./../../');
    exit();
} else if(isset($_REQUEST['verifyaccount']) && trim($_REQUEST['verifyaccount']) != '') {
    $verifyLink = trim($_REQUEST['verifyaccount']);
    if (strlen($verifyLink) > 32) {
        $secureLink = substr($verifyLink, 0, 32);
        $linkId = substr($verifyLink, 32);
        $linkUser = DB_Query('Select userinfo.email, userinfo.userstatus from userinfo left join verificationlinks on userinfo.id = verificationlinks.userId where verificationlinks.linkId='.$linkId.' and verificationlinks.verificationLink="'.$secureLink.'"','ASSOC','');
        if(is_array($linkUser)){
            if(count($linkUser) == 1) {
                if($linkUser[0]['userstatus'] == USER_UNVERIFIED) {
                    $userEmail = $linkUser[0]['email'];
                    require_once __DIR__.'./../register.php';
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
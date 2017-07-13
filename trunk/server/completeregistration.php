<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
require_once __DIR__.'/definitions.php';
require_once __DIR__.'/OperateDB/DbMgrInterface.php';
require_once __DIR__.'/Mail/MailMgr.php';
require_once __DIR__.'/mailMgr.php';
if(isset($_SESSION['userId'])) {
    header('location: ./../');
    exit();
} else if(isset($_REQUEST['securelink']) && isset($_REQUEST['linkid'])) {
    $linkid = $_REQUEST['linkid'];
    $reflink = $_REQUEST['securelink'];
    $password = $_REQUEST['registerpswd'];
    $fname = $_REQUEST['firstname'];
    $lname = $_REQUEST['lastname'];
    $error = '';
    $userId = DB_Read(array(
        'Table'=>'verificationlinks',
        'Fields'=>'userId',
        'clause'=>'linkId="'.$linkid.'" and verificationlink = "'.$reflink.'"'
    ),'ASSOC','');
    if(is_array($userId) && count($userId) == 1) {
        $userId = $userId[0]['userId'];
        $updateUser = Db_Update(array(
            'Table' => 'userinfo',
            'Fields'=> array(
                'userstatus' => USER_ACTIVE,
                'password' => md5($password),
                'name' => $fname.' '.$lname
            ),
            'clause' => 'id='.$userId
        ));
        if($updateUser) {
            $deleteVerificationLink = DB_Delete(array(
                'Table' => 'verificationlinks',
                'clause' => 'linkId='.$linkid
            ));
        } else {
            $error = 'Failed to update user data';
        }
    } else {
        $error = 'User does not found';
    }
    if($error != '') {
        header('location: ./accounts/verify/'.$reflink.$linkid.'?error='.$error);
    } else {
        header('location: ./../');
    }
    exit();
} else {
    header('location ./../');
    exit();
}
?>
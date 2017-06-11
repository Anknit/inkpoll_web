<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
require_once __DIR__.'/OperateDB/DbMgrInterface.php';
require_once __DIR__.'/definitions.php';
require_once __DIR__.'/polleditor.php';
require_once __DIR__.'/voteCaster.php';
require_once __DIR__.'/authMgr.php';
require_once __DIR__.'/pollCategories.php';
require_once __DIR__.'/pollReader.php';
    if(isset($_REQUEST['request'])) {
        $req = trim($_REQUEST['request']);
        if(validateRequest($req)){
            $response = array('status'=> false);
            $reqData = array();
            if(isset($_REQUEST['data'])) {
                $reqData = $_REQUEST['data'];
            }
            $response = serveRequest($req, $reqData);
            echo json_encode($response);
        } else {
            echo "Unauthorized Request";
            die();
        }
    } else {
        echo "Bad Request";
        die();
    }

function validateRequest ($request){
    $validation = true;
    if($_REQUEST['request'] == 'newpoll') {
        if(!isset($_SESSION['userId'])) {
            $validation = false;
        }
    }
    return $validation;
}
function serveRequest ($request, $data = array()){
    switch ($request) {
        case 'newpoll':
            $reqHandler = new PollEditor();
            return $reqHandler->create($data);
            break;
        case 'castvote':
            $reqHandler = new VoteCaster();
            return $reqHandler->castvote($data);
            break;
        case 'readPolls':
            $reqHandler = new PollReader();
            return $reqHandler->getPolls($data);
            break;
        case 'getCategories':
            $reqHandler = new PollCategories();
            return $reqHandler->getCategories();
            break;
        case 'login':
            $reqHandler = new AuthMgr();
            return $reqHandler->login();
            break;
        case 'logout':
            $reqHandler = new AuthMgr();
            return $reqHandler->logout();
            break;
        case 'userdata':
            $reqHandler = new AuthMgr();
            return $reqHandler->getUserData();
            break;
        default:
            break;
    }
}
?>
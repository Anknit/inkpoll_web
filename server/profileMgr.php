<?php
class ProfileMgr {
    public function __construct() {
        
    }
    public function __destruct() {
        
    }
    public function getProfileData() {
        $error = '';
        $status = false;
        $respArray = array();
        if(isset($_SESSION['userId'])) {
            $readUserData = DB_Read(array(
                'Table' => 'userinfo',
                'Fields'=> 'email, name, age, gender, country',
                'clause'=> 'id = '.$_SESSION['userId']
            ),'ASSOC','');
            if(is_array($readUserData) && count($readUserData) == 1) {
                $respArray['profileData'] = $readUserData[0];
                $status = true;
            } else {
                $error = 'Failed to read user profile data';
            }
        } else {
            $error = 'Unauthorised Access';
        }
        return array('status'=>$status, 'error'=> $error, 'data'=>$respArray);
    }
}
?>
<?php
    class AuthMgr extends mailAccess {
        public function __construct() {
            parent::__construct();
        }
        public function __destruct() {
            
        }
        public function signup($data) {
            $error = '';
            $status = false;
            $responseArray = array();
            if(isset($data['authData']) && isset($data['authData']['email']) && trim($data['authData']['email']) != '') {
                $data['email'] = $data['authData']['email'];
                $checkUserExist = $this->checkDbUserExist($data['email']);
                if($checkUserExist['status']) {
                    if(!$checkUserExist['data']['exist']) {
                        $insertUser = $this->insertUserDB($data, 'EMAIL');
                        if($insertUser['status']) {
                            $generateVerifyLink = $this->generateVerifyLink($insertUser['data'], $data['email']);
                            if($generateVerifyLink['status']) {
                                $sendVerifyLink = $this->sendVerifyLink($generateVerifyLink['data'], $data['email']);
                                if($sendVerifyLink['status']) {
                                    $status = true;
                                } else {
                                    $error = $sendVerifyLink['error'];
                                }
                            } else {
                                $error = $generateVerifyLink['error'];
                            }
                        } else {
                            $error = $insertUser['error'];
                        }
                    } else {
                        $error = 'User already registered with this email address';
                    }
                } else {
                    $error = $checkUserExist['error'];
                }
            } else {
                $error = 'Invalid email address';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        public function login($data) {
            $error = '';
            $status = false;
            $sessionRemember = false;
            $responseArray = array();
            
            if(isset($data['vendor'])){
                $authResponse = $this->verifyAuth($data);
                if($authResponse['status']) {
                    $responseArray = $authResponse['data'];
                    $userExist = $this->checkDbUserExist($responseArray['email']);
                    if($userExist['status']) {
                        if($data['vendor'] == 'EMAIL') {
                            if($userExist['data']['exist']) {
                                if($userExist['data']['userData']['userstatus'] == USER_ACTIVE) {
                                    $verifyPswd = $this->verifyUserPassword($responseArray['email'], $data['authData']['password']);
                                    if($verifyPswd['status']) {
                                        $responseArray = $userExist['data']['userData'];
                                        if($data['authData']['remember']) {
                                            $sessionRemember = true;
                                        }
                                        $status = true;
                                    } else {
                                        $error = $verifyPswd['error'];
                                    }
                                } else {
                                    $error = 'User account is not active';
                                }
                            } else {
                                $error = 'User is not registered';
                            }
                        } else {
                            if(!$userExist['data']['exist']) {
                                $insertUser = $this->insertUserDB($responseArray, $data['vendor']);
                                if($insertUser['status']) {
                                    $responseArray['id'] = $insertUser['data']['newuserid'];
                                    $responseArray['usertype'] = $insertUser['data']['newusertype'];
                                    $status = true;
                                } else {
                                    $error = 'Failed to insert New User in database. '.$insertUser['error'];
                                }
                            } else {
                                if($userExist['data']['userData']['userstatus'] == USER_ACTIVE || $userExist['data']['userData']['userstatus'] == USER_UNVERIFIED) {
                                    $addUserId = false;
                                    $updateData = array();
                                    if($data['vendor'] == 'GOOGLE' && ($userExist['data']['userData']['googleuserid'] == '' || $userExist['data']['userData']['googleuserid'] == null)) {
                                        $addUserId = true;
                                        $updateData['googleuserid'] = $authResponse['data']['userid'];
                                    } else if($data['vendor'] == 'FACEBOOK' && ($userExist['data']['userData']['facebookuserid'] == '' || $userExist['data']['userData']['facebookuserid'] == null)) {
                                        $addUserId = true;
                                        $updateData['facebookuserid'] = $authResponse['data']['userid'];
                                    }
                                    if($addUserId) {
                                        if($userExist['data']['userData']['userstatus'] == USER_UNVERIFIED){
                                            $updateData['userstatus'] = USER_ACTIVE;
                                        }
                                        DB_Update(array(
                                            'Table' => 'userinfo',
                                            'Fields'=> $updateData,
                                            'clause'=> 'id = '.$userExist['data']['userData']['id']
                                        ));
                                    }
                                    $responseArray = $userExist['data']['userData'];
                                    $status = true;
                                } else {
                                    $error = 'User account is not active';
                                }
                            }
                        }
                        if($status){
                            $this->setUserSession($responseArray, $sessionRemember);
                            $responseArray = $this->getUserData()['data'];
                        }
                    } else {
                        $error = 'Failed to check user existence. '.$userExist['error'];
                    }
                } else {
                    $error = 'Authentication failed. '.$authResponse['error'];
                }
            } else {
                $error = 'Auth vendor not defined';
            }

            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        public function logout() {
            $error = '';
            $status = false;
            $responseArray = array();

            unset($_SESSION['fb_access_token']);
            unset($_SESSION['fb_userid']);
            unset($_SESSION['userId']);
            unset($_SESSION['userName']);
            unset($_SESSION['userEmail']);
            unset($_SESSION['userType']);

            $status = true;
            
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        public function forgotpswd($data) {
            $error = '';
            $status = false;
            $responseArray = array();
            if(isset($data['authData']) && isset($data['authData']['email']) && trim($data['authData']['email']) != '') {
                $email = $data['authData']['email'];
                $checkUserExist = $this->checkDbUserExist($email);
                if($checkUserExist['status']) {
                    if($checkUserExist['data']['exist']) {
                        if($checkUserExist['data']['userData']['userstatus'] == USER_ACTIVE) {
                            $generateResetLink = $this->generateResetLink($checkUserExist['data']['userData'], $email);
                            if($generateResetLink['status']) {
                                $sendResetLink = $this->sendResetLink($generateResetLink['data'], $email);
                                if($sendResetLink['status']) {
                                    $status = true;
                                } else {
                                    $error = $sendResetLink['error'];
                                }
                            } else {
                                $error = $generateResetLink['error'];
                            }
                        } else {
                            $error = 'User account is not active';
                        }
                    } else {
                       $error = 'User with this email is not registered';
                    }
                } else {
                    $error = $checkUserExist['error'];
                }
            } else {
                $error = 'Invalid email address';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        public function getUserData() {
            $status = false;
            $error = '';
            if(isset($_SESSION['userId'])) {
                $status = true;
                $responseArray = array(
                    'userId' => $_SESSION['userId'],
                    'userName' => $_SESSION['userName'],
                    'userEmail' => $_SESSION['userEmail'],
                    'userType' => $_SESSION['userType']
                );
            } else {
                $error = 'User session is not active';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        
        private function checkDbUserExist ($email) {
            $response = array('status' => false, 'data' => array(), 'error' => '');
            $userData = DB_Read(array(
                'Table' => 'userinfo',
                'Fields'=> 'id,name,email,usertype,userstatus',
                'clause'=> 'email = "'.$email.'"'
            ),'ASSOC','');
            if(is_array($userData)) {
                $response['status'] = true;
                if(count($userData) == 1) {
                    $response['data']['exist'] = true;
                    $response['data']['userData'] = $userData[0];
                } else {
                    $response['data']['exist'] = false;
                }
            } else {
                $response['error'] = 'Failed to read from database';
            }
            return $response;
        }
        private function generateVerifyLink($userdata, $email) {
            $status = false;
            $error = '';
            $responseArray=array();
            $userid = $userdata['newuserid'];
            
            $checkLinkExist = DB_Read(array(
                'Table' =>'verificationlinks',
                'Fields'=>'*',
                'clause'=>'userId ='.$userid
                
            ),'ASSOC','');
            if(is_array($checkLinkExist) && count($checkLinkExist) == 1) {
                $secureLink = $checkLinkExist[0]['verificationLink'];
                $storeLink = $checkLinkExist[0]['linkId'];
            } else {
                $secureLink = md5($email.$userid);
                $storeLink = DB_Insert(array(
                    'Table' => 'verificationlinks',
                    'Fields'=> array(
                        'userId' => $userid,
                        'verificationLink' => $secureLink
                    )
                ));
            }
            if($storeLink) {
                $status = true;
                $responseArray['verifyLink'] = $secureLink.$storeLink;
            } else {
                $error = 'Failed to insert verification link in database';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        private function sendVerifyLink($data, $email) {
            $status = false; $error='';$responseArray = array();
            $sendLink = $this->sendVerificationLink($email, $data['verifyLink']);  // Mail Class
            if($sendLink['error'] == 0) {
                $status = true;
                $responseArray['response'] = $sendLink['data'];
            } else {
                $error = $sendLink['error'];
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        private function generateResetLink($userdata, $email) {
            $status = false;
            $error = '';
            $responseArray=array();
            $userid = $userdata['id'];
            
            $checkLinkExist = DB_Read(array(
                'Table' =>'resetlinks',
                'Fields'=>'*',
                'clause'=>'userId ='.$userid
            ),'ASSOC','');
            if(is_array($checkLinkExist) && count($checkLinkExist) == 1) {
                $secureLink = $checkLinkExist[0]['resetLink'];
                $storeLink = $checkLinkExist[0]['linkId'];
            } else {
                $secureLink = md5($email.$userid);
                $storeLink = DB_Insert(array(
                    'Table' => 'resetlinks',
                    'Fields'=> array(
                        'userId' => $userid,
                        'resetLink' => $secureLink
                    )
                ));
            }
            if($storeLink) {
                $status = true;
                $responseArray['resetLink'] = md5(time()).$secureLink.$storeLink;
            } else {
                $error = 'Failed to insert reset link in database';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        private function sendResetLink($data, $email) {
            $status = false; $error='';$responseArray = array();
            $sendLink = $this->sendPasswordResetLink($email, $data['resetLink']);  // Mail Class
            if($sendLink['error'] == 0) {
                $status = true;
                $responseArray['response'] = $sendLink['data'];
            } else {
                $error = $sendLink['error'];
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        private function verifyFbAuth() {
            $status = false;
            $error = '';
            $responseArray = array();
            require_once __DIR__.'/Facebook/autoload.php';
            
            $fb = new Facebook\Facebook([
                  'app_id' => '1356379634397148',
                  'app_secret' => '3ed4818e4459b38e3b00595fa0acb21c',
                  'default_graph_version' => 'v2.4',
            ]);

            $helper = $fb->getJavaScriptHelper();

            try {
                $accessToken = $helper->getAccessToken();
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                $error .= 'Graph returned an error: ' . $e->getMessage();
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                $error .= 'Facebook SDK returned an error: ' . $e->getMessage();
            }

            if (! isset($accessToken)) {
                $error =  'No cookie set or no OAuth data could be obtained from cookie.'.' '.$error;
            } else {
                try {
                    $userData = $fb->get('/me?fields=id,name,email', $accessToken->getValue());
                } catch(Facebook\Exceptions\FacebookResponseException $e) {
                    // When Graph returns an error
                    $error .= 'Graph returned an error: ' . $e->getMessage();
                } catch(Facebook\Exceptions\FacebookSDKException $e) {
                    // When validation fails or other local issues
                    $error .= 'Facebook SDK returned an error: ' . $e->getMessage();
                }
                if(isset($userData)) {
/*
                    $_SESSION['fb_access_token'] = (string) $accessToken->getValue();
                    $_SESSION['fb_userid'] = $userId;
*/
                    $responseArray['accessToken'] = $accessToken->getValue();
                    $userData = $userData->getGraphUser();
                    $responseArray['userid'] = $userData->getId();
                    $responseArray['name'] = $userData->getName();
                    $responseArray['email'] = $userData->getField('email');
                    $status = true;
                } else {
                    $error .= 'Failed to get Fb User Id';
                }
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        private function verifyGoogleAuth($token) {
            $status = false;
            $error = '';
            $responseArray = array();
            $CLIENT_ID = '418125885627-j2a16gbm8m1i62qqe820fspdkvb7fqop.apps.googleusercontent.com';
            
            require_once __DIR__.'/Google/vendor/autoload.php';
            
            $client = new Google_Client(['client_id' => $CLIENT_ID]);
            $payload = $client->verifyIdToken($token);
            if ($payload) {
                $status = true;
                $responseArray['userid'] = $payload['sub'];
                $responseArray['email'] = $payload['email'];
                $responseArray['name'] = $payload['name'];
            } else {
                $error = 'Authentication Failure';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        private function verifyEmailAuth($authData) {
            $status = true;
            $error = '';
            
            $responseArray = array('userid' => $authData['email']);
            $responseArray = array('email' => $authData['email']);
            
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $responseArray;
            }
            return $resData;
        }
        private function verifyAuth($data) {
            $status = false;
            $error = '';
            $resData = array();
            if($data['vendor'] == 'GOOGLE') {
                if(isset($data['token'])) {
                    $authResponse = $this->verifyGoogleAuth($data['token']);
                } else {
                    $error = 'Auth token absent';
                }
            } else if($data['vendor'] == 'FACEBOOK') {
                $authResponse = $this->verifyFbAuth();
            } else if($data['vendor'] == 'EMAIL') {
                $authResponse = $this->verifyEmailAuth($data['authData']);
            } else {
                $error = 'Unknown auth vendor';
            }
            if($error == '') {
                $status = $authResponse['status'];
            }
            if($status) {
                $resData = $authResponse['data'];
            } else {
                $error .=  $authResponse['error'];
            }
            return array('status' => $status, 'error' => $error, 'data' => $resData);
        }
        private function insertUserDB($data, $vendor) {
            $error = '';
            $status = false;
            $responseArray = array();
            $insertArrFields = array(
                'email' => $data['email'],
                'password' => '',
                'usertype' => USER_NORMAL
            );
            if($vendor == 'EMAIL') {
                $insertArrFields['userstatus'] = USER_UNVERIFIED;
            } else {
                $insertArrFields['userstatus'] = USER_ACTIVE;
                $insertArrFields['name'] = $data['name'];
                if($vendor == 'GOOGLE') {
                    $insertArrFields['googleuserid'] = $data['userid'];
                } else if($vendor == 'FACEBOOK') {
                    $insertArrFields['facebookuserid'] = $data['userid'];
                }
            }
            $insertUser = DB_Insert(array(
                'Table' => 'userinfo',
                'Fields'=> $insertArrFields
            ));
            if($insertUser) {
                $status = true;
                $responseArray['newuserid'] = $insertUser;
                $responseArray['newusertype'] = USER_NORMAL;
            } else {
                $error = 'Failed to insert new user entry in database.';
            }
            return array('status'=>$status, 'error' => $error, 'data' => $responseArray);
        }
        private function setUserSession($data, $isRemember=false) {
            session_destroy();
            unset($_SESSION);
            if($isRemember) {
                session_set_cookie_params(101010101010,"/");
            }
            session_start();
            $_SESSION['userId'] = $data['id'];
            $_SESSION['userName'] = $data['name'];
            $_SESSION['userEmail'] = $data['email'];
            $_SESSION['userType'] = $data['usertype'];
        }
        private function verifyUserPassword($email, $password) {
            $response = array('status' => false, 'data' => array(), 'error' => '');
            $userData = DB_Read(array(
                'Table' => 'userinfo',
                'Fields'=> 'password',
                'clause'=> 'email = "'.$email.'"'
            ),'ASSOC','');
            if(is_array($userData)) {
                if($userData[0]['password'] == md5($password)) {
                    $response['status'] = true;
                } else {
                    $response['error'] = 'Incorrect password';
                }
            } else {
                $response['error'] = 'Failed to read from database';
            }
            return $response;
        }
    }
?>

<?php
    require_once __DIR__.'/Facebook/autoload.php';

    class AuthMgr {
        public function __construct() {
            
        }
        public function __destruct() {
            
        }
        public function login($data) {
            $error = '';
            $status = false;
            $responseArray = array();
            
            if(isset($data['vendor'])){
                if($data['vendor'] == 'GOOGLE') {
                    if(isset($data['token'])) {
                        $authResponse = $this->verifyGoogleAuth($data['token']);
                    } else {
                        $error = 'Auth token absent';
                    }
                } else if($data['vendor'] == 'FACEBOOK') {
                    $authResponse = $this->verifyFbAuth();
                } else {
                    $error = 'Unknown auth vendor';
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
        
        private function checkDbUserExist ($userId) {
            $response = array('status' => false);
            $userData = DB_Read(array(
                'Table' => 'userinfo',
                'Fields'=> 'id,name,email,usertype',
                'clause'=> 'userid = '.$userId
            ),'ASSOC','');
            if($userData && is_array($userData)){
                $response['status'] = true;
                $response['data'] = $userData[0];
            }
            return $response;
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
        
        public function getUserData() {
            $status = false;
            $error = '';
            if(isset($_SESSION['userId'])) {
                $status = true;
                $responseArray = array(
                    'userId' => $_SESSION['userId'],
                    'userName' => $_SESSION['userName'],
                    'userEmail' => $_SESSION['userEmail'],
                    'userType' => $_SESSION['userType'],
                    'fbUserId' => $_SESSION['fb_userid'],
                    'accessToken' => $_SESSION['fb_access_token']
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
        
        private function verifyFbAuth() {
            $status = false;
            $error = '';
            $responseArray = array();
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
                    $userId = $helper->getUserId();
                } catch(Facebook\Exceptions\FacebookResponseException $e) {
                    // When Graph returns an error
                    $error .= 'Graph returned an error: ' . $e->getMessage();
                } catch(Facebook\Exceptions\FacebookSDKException $e) {
                    // When validation fails or other local issues
                    $error .= 'Facebook SDK returned an error: ' . $e->getMessage();
                }
                if(isset($userId)) {
                    $_SESSION['fb_access_token'] = (string) $accessToken->getValue();
                    $_SESSION['fb_userid'] = $userId;
                    $responseArray['accessToken'] = $accessToken->getValue();
                    $responseArray['fbUserId'] = $userId;
                    $userExist = $this->checkDbUserExist($userId);
                    if(!$userExist['status']) {
                        try{
                            $response = $fb->get('/me?fields=name,email', $accessToken->getValue());
                        } catch(Facebook\Exceptions\FacebookResponseException $e) {
                            $error .= 'Graph returned an error: ' . $e->getMessage();
                        } catch(Facebook\Exceptions\FacebookSDKException $e) {
                            $error .= 'Facebook SDK returned an error: ' . $e->getMessage();
                        }

                        if(isset($response)) {
                            $user = $response->getGraphUser();
                            $_SESSION['userName'] = $user->getName();
                            $_SESSION['userEmail'] = $user->getEmail();
                            $_SESSION['userType'] = USER_NORMAL;
                            $responseArray['userName'] = $user->getName();
                            $responseArray['userEmail'] = $user->getEmail();
                            $responseArray['userType'] = USER_NORMAL;
                            $insertUser = DB_Insert(array(
                                'Table' => 'userinfo',
                                'Fields'=> array(
                                    'userid' => $userId,
                                    'name'   => $user->getName(),
                                    'email'  => $user->getEmail(),
                                    'usertype' => USER_NORMAL,
                                    'password' => ''
                                )
                            ));
                            if($insertUser){
                                $_SESSION['userId'] = $insertUser;
                                $responseArray['userId'] = $insertUser;
                                $status = true;
                            } else {
                                $error .= 'Failed to insert user in database';
                            }
                        } else {
                            $error .= 'Failed to get Name and Email of user';
                        }

                    } else {
                        $status = true;
                        $_SESSION['userId'] = $userExist['data']['id'];
                        $_SESSION['userName'] = $userExist['data']['name'];
                        $_SESSION['userEmail'] = $userExist['data']['email'];
                        $_SESSION['userType'] = $userExist['data']['usertype'];
                        $responseArray['userId'] = $userExist['data']['id'];
                        $responseArray['userName'] = $userExist['data']['name'];
                        $responseArray['userEmail'] = $userExist['data']['email'];
                        $responseArray['userType'] = $userExist['data']['usertype'];
                    }
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
            
            require_once __DIR__.'/Google/autoload.php';
            
            $client = new Google_Client(['client_id' => $CLIENT_ID]);
            $payload = $client->verifyIdToken($id_token);
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
    }
?>

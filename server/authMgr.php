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
                $authResponse = $this->verifyAuth($data);
                if($authResponse['status']) {
                    $responseArray = $authResponse['data'];
                    $userExist = $this->checkDbUserExist($responseArray['userid'], $data['vendor']);
                    if($userExist['status']) {
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
                            $responseArray = $userExist['data']['userData'];
                            $status = true;
                        }
                        if($status){
                            $this->setUserSession($responseArray);
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
        
        private function checkDbUserExist ($userId, $vendor) {
            $response = array('status' => false, 'data' => array(), 'error' => '');
            $userData = DB_Read(array(
                'Table' => 'userinfo',
                'Fields'=> 'id,name,email,usertype',
                'clause'=> 'userid = '.$userId. ' and authvendor = "'.$vendor.'"'
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
            $insertUser = DB_Insert(array(
                'Table' => 'userinfo',
                'Fields'=> array(
                    'userid' => $data['userid'],
                    'name'   => $data['name'],
                    'email'  => $data['email'],
                    'usertype' => USER_NORMAL,
                    'password' => '',
                    'authvendor' => $vendor
                )
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
        
        private function setUserSession($data) {
            $_SESSION['userId'] = $data['id'];
            $_SESSION['userName'] = $data['name'];
            $_SESSION['userEmail'] = $data['email'];
            $_SESSION['userType'] = $data['usertype'];
        }
    }
?>

<?php
    require_once __DIR__.'/Facebook/autoload.php';

    class AuthMgr {
        public function __construct() {
            
        }
        public function __destruct() {
            
        }
        public function login() {
            $error = '';
            $status = false;
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
    }
?>
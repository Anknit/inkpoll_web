<?php
    class PollMetaData {
        public function __constuct(){
            
        }
        public function __destruct(){
            
        }
        public function getPollComments ($data) {
            $error = '';
            $status = false;
            if(isset($data['id'])) {
                $start = 0;
                $count = 4;
                $query = 'Select pollcomments.*, userinfo.name as username from pollcomments left join userinfo on pollcomments.userid = userinfo.id where pollcomments.pollid = '.$data['id'].' order by pollcomments.updatedon desc';
                if(isset($data['startindex'])) {
                    $start = $data['startindex'];
                }
                if(isset($data['count'])) {
                    $count = $data['count'];
                }
                $query .= ' limit '.$start.', '.$count;
                $readComments = DB_Query($query, 'ASSOC', '');
                if(is_array($readComments)) {
                    $status = true;
                    $data = $readComments;
                } else {
                    $error = 'Failed to read comments';
                }
            } else {
                $error = 'Missing request arguments';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $data;
            }
            return $resData;
        }
        public function addComment ($data) {
            $error = '';
            $status = false;
            if (isset($data['id']) && isset($data['text']) && isset($data['parentid']) && !empty(trim($data['text']))) {
                $insertComment = DB_Insert(array(
                    'Table' => 'pollcomments',
                    'Fields'=> array(
                        'pollid' => $data['id'],
                        'commenttext' => trim($data['text']),
                        'updatedon' => 'now()',
                        'userid' => $_SESSION['userId'],
                        'parentcommentid' => $data['parentid']
                    )
                ));
                if($insertComment) {
                    $status = true;
                    $data = array('id' => $insertComment, 'userid' => $_SESSION['userId'], 'commenttext' => trim($data['text']), 'username' => $_SESSION['userName'], 'parentcommentid' => $data['parentid']);
                } else {
                    $error = 'Failed to insert comment in database';
                }
            } else {
                $error = 'Missing request arguments';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $data;
            }
            return $resData;
        }
        public function savefavaction ($data){
            $error = '';
            $status = false;
            if (isset($data['id']) && isset($data['action']) && !empty($data['id'])) {
                $isfavactionpresent = $this->checkfavactionentry($data['id'],$_SESSION['userId']);
                if($data['action'] == 'favorite'){
                    $useraction = 1;
                } else if ($data['action'] == 'unfavorite'){
                    $useraction = 0 ;
                } else {
                    $error = 'Invalid Favorite action';
                }
                if ($error == ''){
                    if($isfavactionpresent){
                        $favEntry = DB_Update(array(
                        'Table' => 'pollfavorites',
                            'Fields' => array(
                            'updatedon' => 'now()',
                                'favaction' => $useraction
                            ),
                            'clause'=> 'userid = '.$_SESSION['userId'].' and pollid = '.$data['id']
                        ));
                    } else {
                        $favEntry = DB_Insert(array(
                        'Table' => 'pollfavorites',
                            'Fields' => array(
                            'pollid' => $data['id'],
                                'userid' => $_SESSION['userId'],
                                'favaction' => $useraction,
                                'updatedon' => 'now()'
                            )
                        ));
                    }
                    if($favEntry) {
                        $status = true;
                        $data = $favEntry;
                    } else {
                        $error = 'Failed to save Favorites status in database';
                    }
                }
            } else {
                $error = 'Missing request arguments';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $data;
            }
            return $resData;
        }
        public function changeUserLike ($data) {
            $error = '';
            $status = false;
            if(isset($data['id']) && isset($data['action']) && !empty($data['id'])) {
                $isLikePresent = $this->checkLikeEntry($data['id'],$_SESSION['userId']);
                if($data['action'] == 'like') {
                    $uservote = 1;
                } else if ($data['action'] == 'dislike') {
                    $uservote = -1;
                } else if ($data['action'] == 'unlike') {
                    $uservote = 0;
                } else {
                    $error = 'Invalid like action';
                }
                if($error == '') {
                    if($isLikePresent) {
                        $likeEntry = DB_Update(array(
                            'Table' => 'polllikes',
                            'Fields'=> array(
                                'likedon' => 'now()',
                                'likescore'=>$uservote
                            ),
                            'clause'=> 'userid = '.$_SESSION['userId'].' and pollid = '.$data['id']
                        ));
                    } else {
                        $likeEntry = DB_Insert(array(
                            'Table' => 'polllikes',
                            'Fields'=> array(
                                'likedon' => 'now()',
                                'likescore'=>$uservote,
                                'pollid' => $data['id'],
                                'userid' => $_SESSION['userId']
                            )
                        ));
                    }
                    if($likeEntry) {
                        $status = true;
                        $data = $likeEntry;
                    } else {
                        $error = 'Failed to save like status in database';
                    }
                }
            } else {
                $error = 'Missing request arguments';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = $data;
            }
            return $resData;
        }
        private function checkLikeEntry($pollid, $userid) {
            $output = false;
            $readEntry = DB_Read(array(
                'Table' =>'polllikes',
                'Fields'=>'likescore',
                'clause'=>'userid = '.$userid.' and pollid = '.$pollid
            ),'ASSOC','');
            if(is_array($readEntry) && count($readEntry) == 1) {
                $output = true;
            }
            return $output;
        }
        private function checkfavactionentry($pollid,$userid){
            $output = false;
            $readEntry = DB_Read(array(
                'Table' => 'pollfavorites',
                'Fields' => 'favaction',
                'clause' => 'userid = '.$userid.' and pollid ='.$pollid 
            ),'ASSOC','');
            if(is_array($readEntry) && count($readEntry) == 1){
                $output = true;
            }
            return $output;
        }
    }
?>
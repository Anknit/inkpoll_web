<?php
    class PollMetaData {
        public function __constuct(){
            
        }
        public function __destruct(){
            
        }
        public function getPollComments () {
            return array(
                'status'=>true,
                'data'=>array(
                    array(
                        'userid'=> '6',
                        'username'=> 'Ankit Agarwal',
                        'commentid'=> '1',
                        'commentText'=> 'This is a fake comment',
                        'parentComment'=> '0'
                    ),
                    array(
                        'userid'=> '7',
                        'username'=> 'Robert Downey',
                        'commentid'=> '2',
                        'commentText'=> 'Is this cool',
                        'parentComment'=> '0',
                        'childComments' => array(
                            array(
                                'userid'=> '8',
                                'username'=> 'Jake smith',
                                'commentid'=> '3',
                                'commentText'=> 'This is something not cool',
                                'parentComment'=> '2'
                            )
                        )
                    )
                )
            );
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
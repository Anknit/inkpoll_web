<?php
    class PollEditor {
        public function __constuct(){
            
        }
        public function __destruct(){
            
        }
        public function create($pollData){
            $error = '';
            $status = false;
            $pollData = json_decode($pollData, true);
            if(isset($pollData['question'])) {
                $userId = $_SESSION['userId'];
                $insertFieldsArr = array(
                    'pollQuestion' => $pollData['question'],
                    'userId' => $userId,
                    'catId' => $pollData['category'],
                    'status' => POLL_PENDING,
                    'anonymousvote' => (int)($pollData['anonvote'] == true),
                    'createdon' => 'now()',
                    'updatedon' => 'now()'
                );
                $insertQues = DB_Insert(array(
                    'Table'=>'pollitem',
                    'Fields' =>$insertFieldsArr
                ));
                if($insertQues) {
                    if(isset($pollData['pollImage']) && $pollData['pollImage']) {
                        $imageUrl = '';
                        if($pollData['pollType'] == 'upload') {
                            $file = $_FILES['imagefile'];
                            $uploadPollImagePath = 'uploads/polls/'.$userId.'/';
                            if (!is_dir('/var/www/html/feeddasm/'.$uploadPollImagePath)) {
                                mkdir('/var/www/html/feeddasm/'.$uploadPollImagePath);         
                            }
                            $uploadPollImagePath .= $insertQues.'-'.time().'-'.$file['name'].'.'.explode('/', $file['type'])[1];
                            if(move_uploaded_file($file['tmp_name'],'/var/www/html/feeddasm/'.$uploadPollImagePath)){
                                $imageUrl = $uploadPollImagePath;
                            }
                        } else {
                            $imageUrl = $_REQUEST['imagefile'];
                        }
                        if($imageUrl != '') {
                            DB_Update(array(
                                'Table' => 'pollitem',
                                'Fields'=> array(
                                    'imageurl' => $imageUrl
                                ),
                                'clause' => 'id = '.$insertQues
                            ));
                        }
                    }
                    $valueString = '';
                    for($i=0; $i< count($pollData['optionArr']); $i++) {
                        if($valueString != '') {
                           $valueString .= ' , '; 
                        }
                        $valueString .= '("'.$insertQues.'", "'.$pollData["optionArr"][$i].'")';
                    }
                    $insertQuery = 'Insert into polloptions (`pollId`, `optionText`) values '.$valueString;
                    $insertOption = DB_Query($insertQuery, 'RESULT');
                    if($insertOption == 0) {
                        $status = true;
                    } else {
                        $error = 'Failed to add options';
                    }
                } else {
                    $error = 'Failed to add poll';
                }
            } else{
                $error = 'Unknown Data';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = array('pollid' => $insertQues);
            }
            return $resData;
        }   
    } 
?>

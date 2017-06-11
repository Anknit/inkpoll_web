<?php
    class PollEditor {
        public function __constuct(){
            
        }
        public function __destruct(){
            
        }
        public function create($pollData){
            $error = '';
            $status = false;
            
            $userId = $_SESSION['userId'];
            
            $insertQues = DB_Insert(array(
                'Table'=>'pollitem',
                'Fields' =>array(
                    'pollQuestion' => $pollData['question'],
                    'userId' => $userId,
                    'catId' => $pollData['category'],
                    'anonymousvote' => ($pollData['anonvote'] == true)
                )
            ));
            if($insertQues) {
                $valueString = '';
                for($i=0; $i< count($pollData['optionArr']); $i++) {
                    if($valueString != '') {
                       $valueString .= ' , '; 
                    }
                    $valueString .= '("'.$insertQues.'", "'.$pollData["optionArr"][$i].'")';
                }
                $insertQuery = 'Insert into polloptions (`pollId`, `optionText`) values '.$valueString;
                $insertOption = DB_Query($insertQuery);
                if($insertOption == 0) {
                    $status = true;
                } else {
                    $error = 'Failed to add options';
                }
            } else {
                $error = 'Failed to add poll';
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

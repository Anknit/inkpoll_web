<?php
    class PollReader {
        public function __constuct(){
            
        }
        public function __destruct(){
            
        }
        public function getPolls ($readData) {
            $error = '';
            $status = false;
            
            // UserID needs to be fetched
            $userId = 1;

            $count = 10;
            $pageIndex = 1;
            
            if(isset($readData['count'])) {
                $count = (int)$readData['count'];
            }
            if(isset($readData['index'])) {
                $pageIndex = (int)$readData['index'];
            }
            
            $offset =  ($pageIndex - 1) * $count;
            
            $readPollQuestion = DB_Query('Select pollitem.id, pollitem.userId, pollitem.pollQuestion as questionText, userinfo.id as userId, userinfo.name as createdby, pollresponses.optionId as userChoice from pollitem left join userinfo on pollitem.userId = userinfo.id left join pollresponses on pollitem.id = pollresponses.pollId and pollresponses.userId = '.$userId.' order by id desc limit '.$offset.','.$count, 'ASSOC', '', 'id');
//            echo json_encode($readPollQuestion); die();
            if($readPollQuestion) {
                $readPollOptions = DB_Read(array(
                    'Table' => 'polloptions',
                    'Fields'=> '*',
                    'clause' => 'pollId in ('.implode(array_keys($readPollQuestion), ",").')',
                    'order' => 'optionId asc'
                ),'ASSOC','','optionId');
                if($readPollOptions) {
                    foreach($readPollOptions as $optionId => $data) {
                        if(!isset($readPollQuestion[$data['pollId']]['optionArr'])) {
                            $readPollQuestion[$data['pollId']]['optionArr'] = array();
                        }
                        $readPollQuestion[$data['pollId']]['optionArr'][] = array('id'=>$optionId, 'optionText' => $data['optionText']);
                    }
                    $responseArray = array();
                    foreach($readPollQuestion as $key => $value) {
                        $responseArray[] = $value;
                    }
                    $status = true;
                } else {
                    $error = 'Failed to read options for voting';
                }
            } else {
                $error = 'Failed to read poll questions';
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
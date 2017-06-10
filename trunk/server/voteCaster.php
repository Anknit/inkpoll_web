<?php
    class VoteCaster {
        public function __constuct(){
            
        }
        public function __destruct(){
            
        }
        public function castvote ($voteData) {
            $error = '';
            $status = false;
            
            //User Id needs to be fetched
            $userId = 1;
            
            $alreadyVoted = $this->checkVoteExist($voteData, $userId);
            if(!$alreadyVoted) {
                $validOption = $this->isOptionValid($voteData);
                if($validOption) {
                    $insertVote = DB_Insert(array(
                        'Table' => 'pollresponses',
                        'Fields'=>  array(
                            'pollId' => $voteData["pollItemId"],
                            'optionId'=> $voteData["voteOption"],
                            'userId' => $userId
                        )
                    ));
                    if($insertVote) {
                        $status = true;
                    } else {
                        $error = 'Failed to cast vote';
                    }
                } else {
                    $error = 'You have voted for an invalid option';
                }
            } else {
                $error = 'You have already voted for this poll';
            }
            $resData = array('status' => $status);
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $resData['data'] = array('voteid' => $insertVote);
            }
            return $resData;
            
        }
        private function checkVoteExist ($voteData, $userId) {
            $alreadyVoted = true;
            $runQuery = DB_Query('Select count(*) as oldresponse from pollresponses where userId = "'.$userId.'" and pollId = "'.$voteData["pollItemId"].'"');
            if($runQuery[0]['oldresponse'] == 0) {
                $alreadyVoted = false;
            }
            return $alreadyVoted;
        }
        private function isOptionValid ($voteData) {
            $validResponse = false;
            $query = 'Select count(*) as voteoption from polloptions where optionId = "'.$voteData["voteOption"].'" and pollId = "'.$voteData["pollItemId"].'"';
            $runQuery = DB_Query($query);
            if($runQuery[0]['voteoption'] == 1) {
                $validResponse = true;
            }
            return $validResponse;
        }
    }
?>
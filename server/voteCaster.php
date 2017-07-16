<?php
    class VoteCaster extends PollReader {
        public function __constuct(){
            parent::__construct();
        }
        public function __destruct(){
            
        }
        public function castvote ($voteData) {
            $error = '';
            $status = false;
            
            //User Id needs to be fetched
            $userId = 0;
            $editVoteMode = true; // Flag to allow re vote option
            if(isset($_SESSION['userId'])) {
                $userId = $_SESSION['userId'];
                $alreadyVoted = $this->checkVoteExist($voteData, $userId);
                $voteAllowed = true;
                if($alreadyVoted) {
                    if(!$editVoteMode) {
                        $error = 'You have already voted for this poll';
                    }
                }
            } else {
                $voteAllowed = $this->isAnonVoteAllowed($voteData);
                $alreadyVoted = false;
                if(!$voteAllowed) {
                    $error = 'Anonymous vote not allowed for this poll. You must sign in.';
                }
            }
            
            if($voteAllowed) {
                $validOption = $this->isOptionValid($voteData);
                if($validOption) {
                    if(!$alreadyVoted) {
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
                        if($editVoteMode) {
                            $updateVote = DB_Update(array(
                                'Table' => 'pollresponses',
                                'Fields'=>  array(
                                    'pollId' => $voteData["pollItemId"],
                                    'optionId'=> $voteData["voteOption"],
                                    'userId' => $userId
                                ),
                                'clause' => 'pollId ='.$voteData["pollItemId"].' and userId ='.$userId
                            ));
                            if($updateVote) {
                                $status = true;
                            } else {
                                $error = 'Failed to update vote';
                            }
                        }
                    }
                } else {
                    $error = 'You have voted for an invalid option';
                }
            }
            $resData = array('status' => $status,'data'=>array());
            if(!$status) {
                $resData['error'] = $error;
            } else {
                $readPollData = array('count'=>1,'pollid'=>$voteData["pollItemId"]);
                $updatedPollData = $this->getPolls($readPollData);
                if($updatedPollData['status']) {
                    $resData['data'] = array('optionArr' => $updatedPollData['data'][0]['optionArr']);
                }
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
        private function isAnonVoteAllowed ($voteData) {
            $voteAllowed = false;
            $runQuery = DB_Query('Select anonymousvote from pollitem where id = "'.$voteData["pollItemId"].'"');
            if($runQuery[0]['anonymousvote'] == "1") {
                $voteAllowed = true;
            }
            return $voteAllowed;
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
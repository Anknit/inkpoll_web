<?php
    class PollReader {
        public function __constuct(){
            
        }
        public function __destruct(){
            
        }
        public function getPolls ($readData) {
            $error = '';
            $status = false;
            
            $userId = -1;
            
            // UserID needs to be fetched
            if(isset($_SESSION['userId'])) {
                $userId = $_SESSION['userId'];
            }

            $count = 10;
            $pageIndex = 1;
            
            if(isset($readData['count'])) {
                $count = (int)$readData['count'];
            }
            if(isset($readData['index'])) {
                $pageIndex = (int)$readData['index'];
            }
            
            $offset =  ($pageIndex - 1) * $count;
            
            $query = 'Select pollitem.id, pollitem.userId, pollitem.anonymousvote, pollitem.pollQuestion as questionText, userinfo.id as userId, userinfo.name as createdby, pollresponses.optionId as userChoice, pollcategories.catName as pollcategory, polllikes.likescore as userlikescore, pollfavorites.favaction as userfavscore from pollitem left join userinfo on pollitem.userId = userinfo.id left join pollresponses on pollitem.id = pollresponses.pollId and pollresponses.userId = '.$userId.' left join pollcategories on pollitem.catId = pollcategories.catId left join polllikes on pollitem.id = polllikes.pollid and polllikes.userid = '.$userId.' left join pollfavorites on pollitem.id = pollfavorites.pollid and pollfavorites.userid = '.$userId;
            
            if(isset($readData['category'])) {
                $query .= ' where pollitem.catId = '.$this->getCatId($readData['category']);
            }
            if(isset($readData['pollid'])) {
                $query .= ' where pollitem.id = '.$readData['pollid'];
            }
            
            $query  .= ' order by pollitem.id desc limit '.$offset.','.$count;
            
            $readPollQuestion = DB_Query($query, 'ASSOC', '', 'id');
            if(is_array($readPollQuestion)) {
                if(count($readPollQuestion) > 0) {
                    $readPollOptions = DB_Read(array(
                        'Table' => 'polloptions',
                        'Fields'=> '*',
                        'clause' => 'pollId in ('.implode(array_keys($readPollQuestion), ",").')',
                        'order' => 'optionId asc'
                    ),'ASSOC','','optionId');
                    $readpolllikes = DB_Query('SELECT likescore, pollid, COUNT(*) as count FROM pollapp.polllikes WHERE likescore IN (1, -1) and pollid IN ('.implode(array_keys($readPollQuestion), ",").') GROUP BY likescore, pollid ', 'ASSOC', '');
                    $readpollOptionVotes = DB_Query('SELECT optionid, pollid, COUNT(*) as count FROM pollapp.pollresponses WHERE pollid IN ('.implode(array_keys($readPollQuestion), ",").') GROUP BY optionid, pollid ', 'ASSOC', '', 'optionid');
                    for($i=0; $i< count($readpolllikes); $i++) {
                        if($readpolllikes[$i]['likescore'] == 1) {
                            $readPollQuestion[$readpolllikes[$i]['pollid']]['likecount'] = (int)$readpolllikes[$i]['count'];
                        } else {
                            $readPollQuestion[$readpolllikes[$i]['pollid']]['dislikecount'] = (int)$readpolllikes[$i]['count'];
                        }
                    }
                    if($readPollOptions) {
                        foreach($readPollOptions as $optionId => $data) {
                            if(!isset($readPollQuestion[$data['pollId']]['optionArr'])) {
                                $readPollQuestion[$data['pollId']]['optionArr'] = array();
                            }
                            $readPollQuestion[$data['pollId']]['optionArr'][] = array('id'=>$optionId, 'optionText' => $data['optionText'], 'optionVotes' => (($readpollOptionVotes[$optionId]['count'])?$readpollOptionVotes[$optionId]['count']:0));
                        }
                        $responseArray = array();
                        foreach($readPollQuestion as $key => $value) {
                            $value['userlikescore'] = (empty($value['userlikescore']))?0:$value['userlikescore'];
                            $value['userfavscore'] = (empty($value['userfavscore']))?0:$value['userfavscore'];
                            if(!isset($value['likecount'])) {
                                $value['likecount'] = 0;
                            }
                            if(!isset($value['dislikecount'])) {
                                $value['dislikecount'] = 0;
                            }
                            $responseArray[] = $value;
                        }
                        $status = true;
                    } else {
                        $error = 'Failed to read options for voting';
                    }
                } else {
                    $status = true;
                    $responseArray = array();
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
        private function getCatId($catName) {
            $catId = 0;
            if(isset($catName)) {
                $queryResponse = DB_Read(array(
                    'Table' => 'pollcategories',
                    'Fields'=> 'catId',
                    'clause'=> 'catName = "'.$catName.'"'
                ),'ASSOC', '');
                if($queryResponse) {
                    $catId = $queryResponse[0]['catId'];
                }
            }
            return $catId;
        }
        public function getUserPolls ($readData) {
            $status = false;
            $error = '';
            $responseArray = array();
            
            $userId = $readData['userid'];
            $pageIndex = $readData['page'];
            $sortorder = $readData['order'];
            $count = 50;
            
            $offset = ($pageIndex - 1) * $count;

            $query = 'Select pollitem.id, pollitem.pollQuestion as questionText from pollitem';
            $query .= ' where pollitem.userId = '.$userId;
            $query .= ' order by ';
            switch($sortorder) {
                case 'newest':
                    $query .= 'createdon desc';
                    break;
                case 'votes':
                    $query .= 'createdon desc';
                    break;
                case 'views':
                    $query .= 'createdon desc';
                    break;
                default:
                    $query .= 'createdon desc';
                    break;
            }
            $query .= ' limit '.$offset.','.$count;
            
            $readUserPolls = DB_Query($query, 'ASSOC', '');
            if(is_array($readUserPolls)) {
                $responseArray['polllist'] = $readUserPolls;
            } else {
                $error = 'Failed to read polls from database';
            }
            $readTotalPolls = DB_Query('Select count(*) as total from pollitem where userId ='.$userId);
            if(is_array($readTotalPolls)) {
                $responseArray['totalPolls'] = $readTotalPolls[0]['total'];
                $responseArray['totalPages'] = ceil(((int)$readTotalPolls[0]['total'])/$count);
            } else {
                $error = 'Failed to get total polls of user';
            }
            if($error == ''){
                $status = true;
            }
            return array('status' => $status, 'data' => $responseArray, 'error' => $error);
        }
        public function getUserVotePolls ($readData) {
            $status = false;
            $error = '';
            $responseArray = array();
            
            $userId = $readData['userid'];
            $pageIndex = $readData['page'];
            $sortorder = $readData['order'];
            $count = 50;
            
            $offset = ($pageIndex - 1) * $count;

            $query = 'Select pollitem.id, pollitem.pollQuestion as questionText FROM pollitem right join pollresponses on pollitem.id = pollresponses.pollId where pollresponses.userId = '.$userId;
            $query .= ' order by ';
            switch($sortorder) {
                case 'newest':
                    $query .= 'pollresponses.createdon desc';
                    break;
                case 'votes':
                    $query .= 'pollresponses.createdon desc';
                    break;
                case 'views':
                    $query .= 'pollresponses.createdon desc';
                    break;
                default:
                    $query .= 'pollresponses.createdon desc';
                    break;
            }
            $query .= ' limit '.$offset.','.$count;
            
            $readUserPolls = DB_Query($query, 'ASSOC', '');
            if(is_array($readUserPolls)) {
                $responseArray['polllist'] = $readUserPolls;
            } else {
                $error = 'Failed to read polls from database';
            }
            $readTotalPolls = DB_Query('Select count(*) as total from pollitem right join pollresponses on pollitem.id = pollresponses.pollId where pollresponses.userId = '.$userId);
            if(is_array($readTotalPolls)) {
                $responseArray['totalVotes'] = $readTotalPolls[0]['total'];
                $responseArray['totalPages'] = ceil(((int)$readTotalPolls[0]['total'])/$count);
            } else {
                $error = 'Failed to get total polls of user';
            }
            if($error == ''){
                $status = true;
            }
            return array('status' => $status, 'data' => $responseArray, 'error' => $error);
        }
        public function getUserFavPolls ($readData) {
            $status = false;
            $error = '';
            $responseArray = array();
            
            $userId = $readData['userid'];
            $pageIndex = $readData['page'];
            $sortorder = $readData['order'];
            $count = 50;
            
            $offset = ($pageIndex - 1) * $count;

            $query = 'Select pollitem.id, pollitem.pollQuestion as questionText FROM pollitem right join pollfavorites on pollitem.id = pollfavorites.pollid where pollfavorites.userid = '.$userId;
            $query .= ' order by ';
            switch($sortorder) {
                case 'newest':
                    $query .= 'pollfavorites.updatedon desc';
                    break;
                case 'votes':
                    $query .= 'pollfavorites.updatedon desc';
                    break;
                case 'views':
                    $query .= 'pollfavorites.updatedon desc';
                    break;
                default:
                    $query .= 'pollfavorites.updatedon desc';
                    break;
            }
            $query .= ' limit '.$offset.','.$count;
            
            $readUserPolls = DB_Query($query, 'ASSOC', '');
            if(is_array($readUserPolls)) {
                $responseArray['polllist'] = $readUserPolls;
            } else {
                $error = 'Failed to read polls from database';
            }
            $readTotalPolls = DB_Query('Select count(*) as total from pollitem right join pollfavorites on pollitem.id = pollfavorites.pollid where pollfavorites.userid = '.$userId);
            if(is_array($readTotalPolls)) {
                $responseArray['totalFavs'] = $readTotalPolls[0]['total'];
                $responseArray['totalPages'] = ceil(((int)$readTotalPolls[0]['total'])/$count);
            } else {
                $error = 'Failed to get total fav polls of user';
            }
            if($error == ''){
                $status = true;
            }
            return array('status' => $status, 'data' => $responseArray, 'error' => $error);
        }
     } 
?>
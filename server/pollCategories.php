<?php
class PollCategories {
    public function __construct () {
        
    }
    public function __destruct () {
        
    }
    public function getCategories() {
        $error = '';
        $status = false;

        $readPollCategories = DB_Read(array(
            'Table' => 'pollcategories',
            'Fields' => '*',
            'clause' => 'status = '.CATS_VERIFIED
        ), 'ASSOC', '');
        if($readPollCategories) {
            $status = true;
            $responseArray = $readPollCategories;
        } else {
            $error = 'Failed to read poll categories';
        }

        $resData = array('status' => $status);
        if(!$status) {
            $resData['error'] = $error;
        } else {
            $resData['data'] = $responseArray;
        }
        return $resData;
    }
    public function addCategory($data) {
        $error = '';
        $status = false;

        $addPollCategory = DB_Insert(array(
            'Table' => 'pollcategories',
            'Fields' => array(
                'catName' => $data['catName'],
                'parentCat' => $data['parentCat'],
                'catDescription' => $data['catDescription'],
                'catStatus' => CATS_VERIFIED,
                'createdOn' => 'now()'
            )
        ));
        if($addPollCategory) {
            $responseArray = array('id' => $addPollCategory);
        } else {
            $error = 'Failed to add poll category';
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

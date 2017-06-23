<?php
function getogtags () {
    $categoryPage = '#^/feeddasm/polls/(?P<category>[A-Za-z0-9-]+)$#';
    $pollPage = '#^/feeddasm/polls/(?P<id>[0-9]+)/(?P<pollname>[A-Za-z0-9-]+)$#';
    $path = $_SERVER['REQUEST_URI'];
    
/*
    $url = 'http://umaginesoft.com';
    $urlsuffix = '/heritageaviation/data/pollapp/feeddasm/';

*/
    $url = 'http://localhost';
    $urlsuffix = '/feeddasm/';
/*
    
    $url = 'http://inkpoll.com';
    $urlsuffix = '/';
*/
    
    $title = 'Inkpoll';
    $description = 'Create polls, give your opinion and share with the community';
    $image = $url.$urlsuffix.'images/logo-v3.png';
    $url .= $path;
    $type = 'website';
    
    if ($path == $urlsuffix.'') {
        $pageType = 'Home';
    } else if($path == $urlsuffix.'about') {
        $pageType = 'About';
        $title = 'About | '.$title;
    } else if($path == $urlsuffix.'privacy') {
        $pageType = 'Privacy';
        $title = 'Privacy Policy | '.$title;
    } else if($path == $urlsuffix.'terms') {
        $pageType = 'Terms';
        $title = 'Terms of Use | '.$title;
    } else if (preg_match($categoryPage, $path, $matches)) {
        $pageType = 'Category';
        $category = $matches['category'];
        $title = $category.' - Polls | '.$title;
        $type = 'article';
        $description = 'Find the latest polls and share your opinion on polls related to '.$category;
    } else if(preg_match($pollPage, $path, $matches)) {
        $pageType = 'Pollpage';
        $pollId = $matches['id'];
        $pollName = $matches['pollname'];
        $readPollData = DB_Read(array(
            'Table'=>'pollitem',
            'Fields'=>'pollQuestion',
            'clause'=>'id='.$pollId
        ),'ASSOC','');
        if(is_array($readPollData)){
            $pollTitle = $readPollData[0]['pollQuestion'];
            $type = 'article';
            $title = $pollTitle;
            $description = 'What is your opinion. '.$pollTitle;
        }
    } else {
        $pageType = 'Home';
    }
    function renderMetaTags($url, $title, $description, $image, $type) {
        echo '<meta property="og:url" content="'.$url.'" />';
        echo '<meta property="og:type" content="'.$type.'" />';
        echo '<meta property="og:title" content="'.$title.'" />';
        echo '<meta property="og:description" content="'.$description.'" />';
        echo '<meta property="og:image" content="'.$image.'" />';
        echo '<meta name="title" content="'.$title.'" />';
        echo '<meta name="description" content="'.$description.'" />';
        echo '<meta name="keywords" content="polls, opinion, share opinion, create polls, share polls" />';
    }
    renderMetaTags($url, $title, $description, $image, $type);
    
}
?>
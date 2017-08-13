<?php
function getogtags () {
    $categoryPage = 'polls/(?P<category>[A-Za-z0-9-]+)$#';
    $pollPage = 'polls/(?P<id>[0-9]+)/(?P<pollname>[\s\S]+)$#';
    $path = $_SERVER['REQUEST_URI'];
    
    $url = root_path;
    $urlsuffix = base_href;

    $categoryPage = '#^'.$urlsuffix.$categoryPage;
    $pollPage = '#^'.$urlsuffix.$pollPage;
    
    $title = 'Inkpoll';
    $description = 'Create polls, give your opinion and share with the community';
    $imagebase = $url.$urlsuffix;
    $image = $imagebase.'images/logo-v3.png';
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
            'Fields'=>'pollQuestion, imageurl',
            'clause'=>'id='.$pollId
        ),'ASSOC','');
        if(is_array($readPollData)){
            $pollTitle = $readPollData[0]['pollQuestion'];
            if($readPollData[0]['imageurl'] != '' || $readPollData[0]['imageurl'] != null) {
                $image = '';
                if(!strpos($readPollData[0]['imageurl'], '://')) {
                    $image = $imagebase; 
                }
                $image .= $readPollData[0]['imageurl'];
            }
            $type = 'article';
            $title = $pollTitle;
            $description = 'What is your opinion. '.$pollTitle;
        }
    } else {
        $pageType = 'Home';
    }
    function renderMetaTags($url, $title, $description, $image, $type) {
        echo "<meta property=\"og:url\" content=\"".$url."\" />";
        echo "<meta property=\"og:type\" content=\"".$type."\" />";
        echo "<meta property=\"og:title\" content=\"".htmlspecialchars($title)."\" />";
        echo "<meta property=\"og:description\" content=\"".htmlspecialchars($description)."\" />";
        echo "<meta property=\"og:image\" content=\"".$image."\" />";
        echo "<meta property=\"twitter:url\" content=\"".$url."\" />";
        echo "<meta property=\"twitter:card\" content=\"summary_large_image\" />";
        echo "<meta property=\"twitter:title\" content=\"".htmlspecialchars($title)."\" />";
        echo "<meta property=\"twitter:description\" content=\"".htmlspecialchars($description)."\" />";
        echo "<meta property=\"twitter:image\" content=\"".$image."\" />";
        echo "<meta name=\"title\" content=\"".htmlspecialchars($title)."\" />";
        echo "<meta name=\"description\" content=\"".htmlspecialchars($description)."\" />";
        echo "<meta name=\"keywords\" content=\"polls, opinion, share opinion, create polls, share polls\" />";
    }
    renderMetaTags($url, $title, $description, $image, $type);
    
}
?>

<?php
$mode = 'dev';
if($_SERVER['SERVER_NAME'] == 'localhost') {
    $mode = 'dev';
} else if($_SERVER['SERVER_NAME'] == 'inkpoll.com' || $_SERVER['SERVER_NAME'] == 'www.inkpoll.com') {
    $mode = 'prod';
}
if($mode == 'dev') {
    define("base_href", '/web/inkpoll_web/');
    define("root_path", 'http://localhost');

} else if($mode == 'prod') {
    define("base_href", '/');
    define("root_path", 'https://inkpoll.com');
}
?>

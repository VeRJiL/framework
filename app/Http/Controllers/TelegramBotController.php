<?php

$botToken = "asdfsadfsadfsadsdfsdf";
$chat_id = "@dabatestbot";
$message = "hi";
$bot_url    = "https://api.telegram.org/bot$botToken/";
$url = $bot_url."sendMessage?chat_id=".$chat_id."&text=".urlencode($message);
echo $url;
die();
file_get_contents($url);

?>

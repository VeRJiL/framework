<?php

$botToken = "384580432:AAFCoy7Vz9OvktjrgX1spAZhj5T8QLx8Qn0";
$chat_id = "@dabatestbot";
$message = "hi";
$bot_url    = "https://api.telegram.org/bot$botToken/";
$url = $bot_url."sendMessage?chat_id=".$chat_id."&text=".urlencode($message);
echo $url;
die();
file_get_contents($url);

?>
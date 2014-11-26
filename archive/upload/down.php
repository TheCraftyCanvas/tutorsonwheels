<?php

set_time_limit(0);

//File to save the contents to
$fp = fopen ('wp-bak.zip', 'w+');

$url =  "http://painspecialistsofgreaterchicago.com/wp-bak.zip";

//Here is the file we are downloading, replace spaces with %20
$ch = curl_init(str_replace(" ","%20",$url));

curl_setopt($ch, CURLOPT_TIMEOUT, 500);

//give curl the file pointer so that it can write to it
curl_setopt($ch, CURLOPT_FILE, $fp);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$data = curl_exec($ch);//get curl response

//done
curl_close($ch);

?>
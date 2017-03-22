#!/usr/bin/php
<?php
$domoticzurl = "http://127.0.0.1:8080/json.htm?";
$json_string = file_get_contents($domoticzurl."type=devices&rid=6");
$parsed_json = json_decode($json_string, true);
$test_link = "/home/pi/domoticz/scripts/php/water.txt";
$test_data = fopen ($test_link, "w+");
fwrite ($test_data, print_R($parsed_json, TRUE));
fclose ($test_data);
$parsed_json = $parsed_json['result'][0];
$waterToday = filter_var($parsed_json['CounterToday'], FILTER_SANITIZE_NUMBER_INT);
//echo "WaterToday: ".$waterToday." liter \n";
if ($waterToday > "120") {
  $run_update = file_get_contents($domoticzurl."type=command&param=udevice&idx=539&svalue=1");
  //echo "run_update: ".$run_update." \n";
  $logmsg = "Waterontharder is geregeneerd.";
} else {
  $logmsg = "Waterontharder is niet geregeneerd.";
  //echo "kleiner dan 120 \n";
}
file_get_contents($domoticzurl.'type=command&param=addlogmessage&message='.urlencode($logmsg));
?>

<?php
function sendFCM(){

$url = 'https://fcm.googleapis.com/fcm/send';
//SERVER KEY
$apiKey = 'AAAATdFCeLQ:APA91bEpDD3swwTSryOppppyMLzd_HwLDsP0TBXU9CoFvlw1yQGM-_ju_vCtndAywlVePshOsxhv67zmdT7NMQoFETLOTdGU4tTshKw32NSZ0gPRmHz6OvwuWWPE9hA77U7JaaVUj8AI';

$headers = array (
    'Authorization:key='.$apiKey,
    'Content-Type:application/json'
);

//Notification content
$notifData = [
    'title' => 'My New Notification',
    'body' => 'My Notification Body',
    //image => 'IMAGE -URL',
    //'click_action' => 'activities.notifhandler'
];

$dataPayload = [
    'to' => 'VIP',
    'date' => '2022-05-11',
    'other_data' => 'Data Pkasemer'
];


//Create API body

$notifBody = [
    'notification' => $notifData,
    'data' => $dataPayload,
    //optional in seconds, max times is 4 weeks
    'time_to_live' => 3600,
    // 'to' => 'Token or Reg_id'
    'to' => 'topics/newoffer'
    //'registration_ids = Array of registration_ids or token
];


$ch  = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notifBody));

//Execute
$result = curl_exec($ch);
print($result);

curl_close($ch);

}

sendFCM();

?>
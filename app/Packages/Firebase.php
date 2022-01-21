<?php
namespace App\Packages;


class Firebase
{
    //topics
    public static function send($to, $message) {
        $fields = array(
            'to' => '/topics/'.$to,
            'data' => $message,
            'notification' => $message,
        );
        return self::sendPushNotification($fields);
    }
    //device_id
    public static function sendMultiple($registration_ids, $message) {
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
            'notification' => $message,
        );

        return self::sendPushNotification($fields);
    }

    private static function sendPushNotification($fields) {

        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=AAAAlj-bHlc:APA91bH2mUE_wsNdmOc2lGAiCv8TeI1KxhGFHo0EenLMD8x9BH2PW1mh5FV7_l6VaY7vUl03HamU5qDjnM5zdzzdjSbSlPJiWlKri7Uk6cxVcsNFK7BxwTcwT7QZANEqV4Q2PmdJbGdV',
            'Content-Type: application/json'
        );
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }
}

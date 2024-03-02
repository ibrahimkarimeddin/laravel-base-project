<?php

namespace App\Services;

use Exception;

/**
 * Class FirebaseService
 * @package App\Services
 */
class FirebaseService
{
    public static function validate_user_using_uid($id_token, $user_uid, $phone)
    {
        try {
            $auth = app('firebase.auth');
            $verifiedIdToken = $auth->verifyIdToken($id_token);
            $uid = $verifiedIdToken->claims()->get('sub');
            $user = $auth->getUser($uid);

            if ($user->uid == $user_uid && $user->phoneNumber == $phone) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    public static function sendNotification($users, $title, $body, $additional_data = [])
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        // server key
        $serverKey = env('FCM_SERVER_KEY');

        //array of token
        $fcmTokens = $users->pluck('fcm_token');
        $data = [
            "registration_ids" => $fcmTokens,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
            
            
            
        ];

        $encodedData = json_encode($data);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // dd($result);
        curl_close($ch);
           
        return "send notification";
    }
}

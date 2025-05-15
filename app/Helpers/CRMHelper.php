<?php

namespace App\Helpers;

class CRMHelper
{

    public static function createLead($receiver, $title, $description, $name, $email, $mobileNo = "", $extraData = [])
    {
        $extraData['referer'] = CodeHelper::getReferrer();

        $postData = [
            "received_id" => $receiver,
            "title" => $title,
            "description" => $description,
            "name" => $name,
            "email" => $email,
            "mobile_no" => $mobileNo,
            'extraData' => $extraData
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('CRM_PANEL_URl') . '/create-lead',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }


    public static function sendMessage($userId, $message, $receiverId)
    {
        $postData = [
            "userId" => $userId,
            "message" => $message,
            "receiverId" => $receiverId
        ];

        // print_r($postData);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('CRM_PANEL_URl') . '/create-chat-messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        // var_dump($response);die;
        return $response;
    }

}


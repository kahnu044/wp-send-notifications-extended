<?php

/**
 * PNR API Management
 */
function wpsne_check_API_APP_Id()
{
    $wpsne_api_key = get_option('wpsne_api_key');
    $wpsne_app_id =  get_option('wpsne_app_id');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players?app_id=" . $wpsne_app_id);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Basic ' . $wpsne_api_key
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response);
    if (property_exists($data, "errors",)) {

        $data = array(
            "error" => true,
            "value" => $data->errors[0]
        );
        return $data;
    } else {
        $data = array(
            "error" => false,
            'status' => 'success'
        );
        return $data;
    }
}

//send onesignal notification to all users
function wpsne_send_message($wpsne_heading, $wpsne_content, $sentTo, $addButton, $wpsne_button_text, $wpsne_button_link, $player_id)
{

    // get api and app id from db
    $wpsne_api_key = get_option('wpsne_api_key');
    $wpsne_app_id =  get_option('wpsne_app_id');

    $heading      = array(
        "en" => $wpsne_heading
    );

    $content      = array(
        "en" => $wpsne_content
    );

    $btn_text_link = array();
    if ($addButton == true) {
        array_push($btn_text_link, array(
            "text" => $wpsne_button_text,
            "url" => $wpsne_button_link
        ));
    }

    $fields = array(
        'app_id' => $wpsne_app_id,
        'headings' => $heading,
        'contents' => $content,
        'web_buttons' => $btn_text_link
    );

    if ($sentTo == 'all') {
        //for all user
        $fields['included_segments'] = array("0" => 'Subscribed Users');
    } else {
        //for specific user
        $fields['include_player_ids'] = array("0" => $player_id);
    }

    $fields = json_encode($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic ' . $wpsne_api_key
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response);
}

// get total no of subscribed device
function wpsne_get_all_subscribers()
{
    $wpsne_api_key = get_option('wpsne_api_key');
    $wpsne_app_id =  get_option('wpsne_app_id');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players?app_id=" . $wpsne_app_id);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Basic ' . $wpsne_api_key
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response);
    return $data->total_count;
}

//get all device info by sort and limit for pagination
function wpsne_get_all_devices($per_page, $offset)
{
    $wpsne_api_key = get_option('wpsne_api_key');
    $wpsne_app_id =  get_option('wpsne_app_id');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/players?app_id=" . $wpsne_app_id . "&limit={$per_page}&offset={$offset}");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Basic ' . $wpsne_api_key
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response);
}

//auto send notifications
function wpsne_auto_send_notifications($wpsne_heading, $wpsne_title, $wpsne_button_text, $wpsne_button_link, $defaultImg)
{

    $wpsne_api_key = get_option('wpsne_api_key');
    $wpsne_app_id =  get_option('wpsne_app_id');
    $heading      = array(
        "en" => $wpsne_heading
    );
    $content      = array(
        "en" => $wpsne_title
    );
    $btn_text_link = array();
    array_push($btn_text_link, array(
        "text" => $wpsne_button_text,
        "url" => $wpsne_button_link
    ));

    $fields = array(
        'app_id' => $wpsne_app_id,
        'headings' => $heading,
        'contents' => $content,
        'web_buttons' => $btn_text_link,
    );

    //for all user
    $fields['included_segments'] = array("0" => 'Subscribed Users');

    //check image set or not
    if ($defaultImg) {
        $fields['chrome_web_image'] = $defaultImg;
    }
    $fields = json_encode($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic ' . $wpsne_api_key
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

//get total notifications
function wpsne_get_total_notifications()
{

    $wpsne_api_key = get_option('wpsne_api_key');
    $wpsne_app_id =  get_option('wpsne_app_id');

    //start api call from here
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://onesignal.com/api/v1/notifications?app_id=" . $wpsne_app_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic " . $wpsne_api_key,
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $data = json_decode($response);
    return $data->total_count;
}

//get all notification history
function wpsne_get_all_notifications($per_page, $offset)
{

    $wpsne_api_key = get_option('wpsne_api_key');
    $wpsne_app_id =  get_option('wpsne_app_id');

    //start api call from here
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://onesignal.com/api/v1/notifications?app_id=" . $wpsne_app_id . "&limit={$per_page}&offset={$offset}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic " . $wpsne_api_key,
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $data = json_decode($response);
    return $data;
}

//get location by ip
function wpsne_get_user_location($ip)
{

    $details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));

    return  $details->city . '(' . $details->country . ')';
}

//delete subscriber
function wpsne_remove_subscribers($device_id)
{

    // get api and app id from db
    $wpsne_api_key = get_option('wpsne_api_key');
    $wpsne_app_id =  get_option('wpsne_app_id');

    //start api call from here
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://onesignal.com/api/v1/players/{$device_id}?app_id=" . $wpsne_app_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic " . $wpsne_api_key,
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    return $response;
}

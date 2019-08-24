<?php

function get_steem_profile($username)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://steemit.com/@" . $username . ".json",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Postman-Token: b9545955-534a-48a4-966b-98ca22fcfb95"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return (object) ["user" => "No account found", "status" => "404"];
    } else {
        try {
            return json_decode($response);
        } catch (Exception $exception) {
            return (object) ["user" => "User Profiles unavailable. Try again in a few minutes!", "status" => "500"];
        }
    }
}
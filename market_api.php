<?php

define("RPC_ENDPOINT", "https://api.steem-engine.com/rpc/");

function market_sells($account, $symbol) {
    $query = [];
    if($account != "") {
        $query["account"] = strtolower($account);
    }
    if($symbol != "") {
        $query["symbol"] = strtoupper($symbol);
    }

    $query = json_encode($query);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => RPC_ENDPOINT . "contracts",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "[{\"method\": \"find\", \"jsonrpc\": \"2.0\", \"params\": {\"contract\": \"market\", \"table\": \"sellBook\", \"query\": $query, \"limit\": 1000, \"offset\": 0, \"indexes\": []}, \"id\": 1}]",
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "User-Agent: steemengine v0.5.0"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return (object)["error" => "Unknown"];
    } else {
        return json_decode($response);
    }

}

function market_buys($account, $symbol) {
    $query = [];
    if($account != "") {
        $query["account"] = strtolower($account);
    }
    if($symbol != "") {
        $query["symbol"] = strtoupper($symbol);
    }

    $query = json_encode($query);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => RPC_ENDPOINT . "contracts",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "[{\"method\": \"find\", \"jsonrpc\": \"2.0\", \"params\": {\"contract\": \"market\", \"table\": \"buyBook\", \"query\": $query, \"limit\": 1000, \"offset\": 0, \"indexes\": []}, \"id\": 1}]",
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/json",
            "User-Agent: steemengine v0.5.0"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return (object)["error" => "Unknown"];
    } else {
        return json_decode($response);
    }

}
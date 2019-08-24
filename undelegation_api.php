<?php

define("RPC_ENDPOINT", "https://api.steem-engine.com/rpc/");

function undelegation_api($account, $symbol)
{
    $json_query = [];

    if ($account != "") {
        $json_query["account"] = strtolower($account);

        /*if (isset($_GET["ownspfvfvsdvdsv"]) and isset($_GET["symbol"])) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => ACCOUNT_ENDPOINT . $json_query["to"],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
                CURLOPT_HTTPHEADER => array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                    "User-Agent: steemengine v0.5.0"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);
        }*/
    }

    if ($symbol != "") {
        $json_query["symbol"] = strtoupper($symbol);
    }

    $json_query = json_encode($json_query);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => RPC_ENDPOINT . "contracts",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "[{\"method\": \"find\", \"jsonrpc\": \"2.0\", \"params\": {\"contract\": \"tokens\", \"table\": \"pendingUndelegations\", \"query\": " . $json_query . ", \"limit\": 1000, \"offset\": 0, \"indexes\": []}, \"id\": 1}]",
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
        return json_encode(["error" => $err]);
    } else {
        return $response;
    }
}
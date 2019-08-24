<?php

define("RPC_ENDPOINT", "https://api.steem-engine.com/rpc/");

function self_power($to, $symbol) {
    $my_sp = false;
    if ($to != "") {
        if ($symbol != "") {
            $query = "{";
            $query .= "\"account\": \"" . $to . "\",";
            $query .= "\"symbol\": \"" . strtoupper($symbol) . "\"";
            $query .= "}";

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => RPC_ENDPOINT . "contracts",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "[{\"method\": \"find\", \"jsonrpc\": \"2.0\", \"params\": {\"contract\": \"tokens\", \"table\": \"balances\", \"query\": $query, \"limit\": 1000, \"offset\": 0, \"indexes\": []}, \"id\": 1}]",
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
            } else {
                try {
                    $response = json_decode($response);
                    if (sizeof($response[0]->result) > 0) {
                        $my_sp = $response[0]->result[0]->stake;
                    }
                } catch (Exception $exception) {
                    $my_sp = false;
                }
            }
        }
    }

    return $my_sp;
}

function delegation_api($to, $from, $symbol)
{
    $json_query = [];

    if ($to != "") {
        $json_query["to"] = strtolower($to);
    }

    if ($from != "") {
        $json_query["from"] = $from;
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
        CURLOPT_POSTFIELDS => "[{\"method\": \"find\", \"jsonrpc\": \"2.0\", \"params\": {\"contract\": \"tokens\", \"table\": \"delegations\", \"query\": " . $json_query . ", \"limit\": 1000, \"offset\": 0, \"indexes\": []}, \"id\": 1}]",
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
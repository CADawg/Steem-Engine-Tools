<?php
/**
 * Created by PhpStorm.
 * User: Conor Howland
 * Date: 24/08/2019
 * Time: 16:41
 */

namespace SnaddyvitchDispenser\SteemEngine;


class SteemEngineAPI
{

    private $RPC_URL;

    function __construct($rpc="https://api.steem-engine.com/rpc/")
    {
        $this->RPC_URL = $rpc;
    }

    /**
     * Query a Contract on the Steem Engine network
     * @param string $contract Name of the contract to read
     * @param string $table Table to query
     * @param array $query Query for table as array
     * @param int $limit Limit of Results (Max 1000)
     * @param int $offset Offset from 0
     * @return bool|object
     */

    function query_contract($contract = "tokens", $table="balances", $query = [], $limit = 1000, $offset = 0) {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->RPC_URL . "contracts",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([["method" => "find", "jsonrpc" => "2.0", "params" => ["contract" =>  $contract, "table" =>  $table, "query" => $query, "limit" => $limit, "offset " => $offset, "indexes" => []],  "id" => 1]]),
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
                return false;
            } else {
                $result = json_decode($response);
                if (isset($result[0]->result)) {
                    return $result[0]->result;
                } else {
                    return false;
                }
            }
        } catch (\Exception $exception) {
            return false;
        }
    }

}
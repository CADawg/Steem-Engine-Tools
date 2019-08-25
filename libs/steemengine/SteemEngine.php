<?php
/**
 * Created by PhpStorm.
 * User: Conor Howland
 * Date: 24/08/2019
 * Time: 16:36
 */

namespace SnaddyvitchDispenser\SteemEngine;

class SteemEngine
{

    private $RPC_URL;

    function __construct($rpc="https://api.steem-engine.com/rpc/")
    {
        $this->RPC_URL = $rpc;
    }

    /**
     * Query a Contract on the Steem Engine network
     * @param array $location Contract,Table array
     * @param array $query Query for table as array
     * @param array $modifiers Limit and offset
     * @return bool|object
     */

    function query_contract($location = "tokens/balances", $query = []) {
        try {
            $location = explode("/", $location);
            if (sizeof($location) < 2) {
                return false;
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->RPC_URL . "contracts",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([["method" => "find", "jsonrpc" => "2.0", "params" => ["contract" =>  $location[0], "table" =>  $location[1], "query" => $query, "limit" => 1000, "offset " => 0, "indexes" => []],  "id" => 1]]),
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


    /**
     * Get a user's balances
     * @param string $user Username to query for
     * @return bool|object Result
     */
    function get_user_balances($user = "null") {
        return $this->query_contract("tokens/balances", [ "account" => $user ]);
    }

    /**
     * Get all market sells
     * @param string $user User to limit on
     * @param string $token Token to limit on
     * @return bool|object Result
     */
    function get_market_sells($user = "null", $token = "") {
        $query = [];
        if (strlen($user) > 0) {
            $query["account"] = strtolower($user);
        }
        if (strlen($token) > 0) {
            $query["symbol"] = strtoupper($token);
        }

        return $this->query_contract("market/sellBook", $query);
    }

    /**
     * Get all market buys
     * @param string $user User to limit on
     * @param string $token Token to limit on
     * @return bool|object Result
     */
    function get_market_buys($user = "null", $token = "") {
        $query = [];
        if (strlen($user) > 0) {
            $query["account"] = strtolower($user);
        }
        if (strlen($token) > 0) {
            $query["symbol"] = strtoupper($token);
        }

        return $this->query_contract("market/buyBook", $query);
    }

    /**
     * Get all tokens and associated metadata
     * @return bool|object Tokens in existence
     */
    function get_tokens() {
        return $this->query_contract("tokens/tokens", []);
    }

    /**
     * @param $user string Username to search
     * @param $token string Token Symbol
     * @return bool|object Result
     */
    function get_undelegations($user = "", $token = "") {
        $query = [];
        if (strlen($user) > 0) {
            $query["account"] = strtolower($user);
        }
        if (strlen($token) > 0) {
            $query["symbol"] = strtoupper($token);
        }

        return $this->query_contract("tokens/pendingUndelegations", $query);
    }

    /**
     * @param array $query Query to use
     * @return bool|object Result
     */
    function get_delegations($query = []) {
        $finalQuery = [];

        foreach ($query as $item=>$value) {
            if (strlen($value) > 0) {
                $finalQuery[$item] = $value;
            }
        }

        return $this->query_contract("tokens/delegations", $finalQuery);
    }

    /**
     * @param string $user Username
     * @param string $token Token
     * @return bool|object Result
     */
    function get_user_balance_one($user = "NULL", $token="PAL") {
        return $this->query_contract("tokens/balances", ["account" => $user, "symbol" => $token]);
    }


}
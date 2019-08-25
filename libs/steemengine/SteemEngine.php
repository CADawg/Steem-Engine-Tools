<?php
/**
 * Created by PhpStorm.
 * User: Conor Howland
 * Date: 24/08/2019
 * Time: 16:36
 */

namespace SnaddyvitchDispenser\SteemEngine;

require "SteemEngineAPI.php";

class SteemEngine
{
    private $SteemEngineAPI;

    function __construct($rpc="https://api.steem-engine.com/rpc/")
    {
        $this->SteemEngineAPI = new SteemEngineAPI($rpc);
    }


    /**
     * Get a user's balances
     * @param string $user Username to query for
     * @return bool|object Result
     */
    function get_user_balances($user = "null") {
        return $this->SteemEngineAPI->query_contract("tokens/balances", [ "account" => $user ]);
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

        return $this->SteemEngineAPI->query_contract("market/sellBook", $query);
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

        return $this->SteemEngineAPI->query_contract("market/buyBook", $query);
    }

    /**
     * Get all tokens and associated metadata
     * @return bool|object Tokens in existence
     */
    function get_tokens() {
        return $this->SteemEngineAPI->query_contract("tokens/tokens", []);
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

        return $this->SteemEngineAPI->query_contract("tokens/pendingUndelegations", $query);
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

        return $this->SteemEngineAPI->query_contract("tokens/delegations", $finalQuery);
    }

    /**
     * @param string $user Username
     * @param string $token Token
     * @return bool|object Result
     */
    function get_user_balance_one($user = "NULL", $token="PAL") {
        return $this->SteemEngineAPI->query_contract("tokens/balances", ["account" => $user, "symbol" => $token]);
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: Conor Howland
 * Date: 24/08/2019
 * Time: 16:36
 */

namespace SnaddyvitchDispenser\SteemEngine;

require_once "SteemEngineAPI.php";
use SnaddyvitchDispenser\SteemEngine\SteemEngineAPI;

class SteemEngine
{
    /**
     * @var $username string The username of the User
     */

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
        return $this->SteemEngineAPI->query_contract("tokens", "balances", [ "account" => $user ]);
    }

    function get_market_sells($user = "null", $token = "") {
        $query = [];
        $query["account"] = strtolower($user);
        if (strlen($token) > 0) {
            $query["token"] = strtoupper($token);
        }

        return $this->SteemEngineAPI->query_contract("market", "sellBook", $query);
    }

    function get_market_buys($user = "null", $token = "") {
        $query = [];
        $query["account"] = strtolower($user);
        if (strlen($token) > 0) {
            $query["token"] = strtoupper($token);
        }

        return $this->SteemEngineAPI->query_contract("market", "buyBook", $query);
    }

    function get_tokens() {
        $query = [];

        return $this->SteemEngineAPI->query_contract("tokens", "tokens", $query);
    }



}
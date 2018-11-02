<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/8/4
 * Time: 下午1:20
 */

namespace App\HttpController;


use App\Rpc\RpcServer;
use EasySwoole\EasySwoole\Logger;
use EasySwoole\EasySwoole\Trigger;
use EasySwoole\Rpc\Bean\Response;

class Rpc extends Base
{
    /*
     * 具体使用看https://github.com/easy-swoole/rpc/
     */
    function index()
    {
        $msg = null;
        $t = microtime(true);
        $client = RpcServer::getInstance()->client();
        $client->addCall('serviceOne','funcOne',1231,231,321)
            ->success(function (Response $response)use(&$msg){
                $msg = $response->getMessage();
            })
            ->fail(function (Response $response)use(&$msg){
                $msg = $response->__toString();
                Logger::getInstance()->console($response->__toString());
            });
//        $client->addCall('serviceOne','task')
//            ->success(function (Response $response){
//                Logger::getInstance()->console($response->__toString());
//            })
//            ->fail(function (Response $response){
//                Logger::getInstance()->console($response->__toString());
//            });
        $client->exec(0.5);

        $t = round(microtime(true) - $t,3);
        $this->response()->write("rpc take {$t} s and mgs is {$msg}");
    }

    function allNodes()
    {
        var_dump(RpcServer::getInstance()->getAllServiceNodes());
    }
}
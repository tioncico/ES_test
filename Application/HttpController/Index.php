<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/8/4
 * Time: 下午3:13
 */

namespace App\HttpController;


use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\MysqlPoolObj;
use App\Utility\TrackerManager;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use Kjcx\helloworld;

class Index extends Base
{

    function index()
    {
        var_dump($file);
// 在定时器中投递的例子
//        TaskManager::async(new \App\Task\Test('a'));
        $this->response()->write('123');
      /*  $kjcx = new helloworld();
        $kjcx->setId(1);
        $kjcx->setStr('test');
        $this->response()->write($kjcx->getStr() . $kjcx->getId());*/
    }

    function test()
    {
        TrackerManager::getInstance()->getTracker()->addAttribute('user','用户名1');
        $obj = PoolManager::getInstance()->getPool(MysqlPool::class)->getObj(0.1);
        if($obj instanceof MysqlPoolObj){
            try{
                $res = $obj->get('xsk_test');
                $this->writeJson(200,($res));
            }catch (\Throwable $throwable){
                    ($throwable->getMessage());
            }finally{
                PoolManager::getInstance()->getPool(MysqlPool::class)->recycleObj($obj);
            }
        }
    }
}
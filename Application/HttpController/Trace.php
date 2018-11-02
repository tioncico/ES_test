<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/8/15
 * Time: 上午12:01
 */

namespace App\HttpController;


use App\Utility\TrackerManager;

class Trace extends Base
{
    function index()
    {
        /*
         * 可以写进去model中
         */


        $tracker = TrackerManager::getInstance()->getTracker();

        $tracker->addAttribute('user','用户名1');
        $tracker->addAttribute('name','这是昵称');
        $trackerPoint = $tracker->setPoint('查询用户订单',[
        'sql'=>'sql statement one'
    ]);
        //模拟sql 执行
        usleep(1000000);
        $tracker->endPoint('查询用户订单',$trackerPoint::STATUS_FAIL,["查询失败"]);
        TrackerManager::getInstance()->closeTracker();

        $this->response()->write('call trace');

    }
}
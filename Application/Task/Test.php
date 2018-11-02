<?php
/**
 * Created by PhpStorm.
 * User: Apple
 * Date: 2018/10/24 0024
 * Time: 15:58
 */

namespace App\Task;


use EasySwoole\EasySwoole\Swoole\Task\AbstractAsyncTask;

class Test extends AbstractAsyncTask
{
    function run($taskData, $taskId, $fromWorkerId)
    {
        echo "异步任务执行\n";
        while(1){
            sleep(10);
        }
        // TODO: Implement run() method.
    }

    function finish($result, $task_id)
    {
        echo "异步任务完成\n";
        // TODO: Implement finish() method.
    }


}
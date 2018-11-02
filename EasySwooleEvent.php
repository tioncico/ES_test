<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/5/28
 * Time: 下午6:33
 */

namespace EasySwoole\EasySwoole;


use App\ExceptionHandler;
use App\HttpController\Test;
use App\Rpc\RpcServer;
use App\Rpc\RpcTwo;
use App\Spider\Consumer;
use App\Spider\Index;
use App\TcpController\Parser;
use App\Utility\Pool\MysqlPool;
use App\Utility\Pool\RedisPool;
use App\Utility\TrackerManager;
use EasySwoole\Component\Di;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\Process\Helper;
use EasySwoole\EasySwoole\Swoole\Task\TaskManager;
use EasySwoole\EasySwoole\Swoole\Time\Timer;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Rpc\Bean\ServiceNode;
use EasySwoole\Socket\Dispatcher;
use EasySwoole\Trace\Bean\Tracker;
use EasySwoole\Utility\File;
use EasySwoole\EasySwoole\Config;
use Swoole\Process;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        /*
           * ***************** 协程数据库连接池 ********************
         */
        self::loadConf();
        PoolManager::getInstance()->register(MysqlPool::class);
        PoolManager::getInstance()->register(RedisPool::class);

        //调用链追踪器设置Token获取值为协程id
        TrackerManager::getInstance()->setTokenGenerator(function () {
            return \Swoole\Coroutine::getuid();
        });
        //每个链结束的时候，都会执行的回调
        TrackerManager::getInstance()->setEndTrackerHook(function ($token, Tracker $tracker) {
//            var_dump((string)$token);
//            echo  $tracker;
            Logger::getInstance()->log((string)$tracker);
        });

        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER, [ExceptionHandler::class, 'handle']);
    }

    public static function loadConf()
    {
        $files = File::scanDirectory(EASYSWOOLE_ROOT . '/Application/Config');
        if (is_array($files)) {
            foreach ($files['files'] as $file) {
                $fileNameArr = explode('.', $file);
                $fileSuffix = end($fileNameArr);
                if ($fileSuffix == 'php') {
                    Config::getInstance()->loadFile($file);
                } elseif ($fileSuffix == 'env') {
                    Config::getInstance()->loadEnv($file);
                }
            }
        }
    }

    public static function mainServerCreate(EventRegister $register)
    {
//        ServerManager::getInstance()->getSwooleServer()->addProcess((new Index("test"))->getProcess());
//        $z = new \ReflectionClass(\Swoole\Coroutine\Redis::class);
//        echo $z->getName(); // X
//        var_dump(($z->getMethod('pconnect')));

        echo "请输入你要爬取的网站根目录:\n";
        //            $web_url = trim(fgets(STDIN));
        $web_url = "http://www.nowamagic.net/";
        ServerManager::getInstance()->getSwooleServer()->addProcess((new Index("Index",['web_url'=>$web_url]))->getProcess());
        for ($i=0;$i<1;$i++){
            ServerManager::getInstance()->getSwooleServer()->addProcess((new Consumer("Consumer",['web_url'=>$web_url]))->getProcess());
        }
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        //为每个请求做标记
        TrackerManager::getInstance()->getTracker()->addAttribute('workerId', ServerManager::getInstance()->getSwooleServer()->worker_id);
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
        //因为在onRequest 中部分代码有埋点，比如UserModelOne，会产生追踪链，因此需要清理。
        TrackerManager::getInstance()->closeTracker();
    }

    public static function onReceive(\swoole_server $server, int $fd, int $reactor_id, string $data): void
    {

    }
}
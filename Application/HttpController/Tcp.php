<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2018/10/17 0017
 * Time: 15:35
 */

namespace App\HttpController;


use EasySwoole\EasySwoole\ServerManager;
use EasySwoole\Http\AbstractInterface\Controller;

class Tcp extends Controller
{
    public function index()
    {
        // TODO: Implement index() method.
    }
    /*
       * 请调用Test=>who，获取到fd再进行http调用
       * http://ip:9501/tpc/push/index.html?fd=xxxx
       */
    public function push()
    {
        $fd = intval($this->request()->getRequestParam('fd'));
        $info = ServerManager::getInstance()->getSwooleServer()->connection_info($fd);
        if(is_array($info)){
            ServerManager::getInstance()->getSwooleServer()->send($fd,'push in http at '.time());
        }else{
            $this->response()->write("fd {$fd} not exist");
        }
    }
}
<?php

namespace App;

use duncan3dc\Laravel\BladeInstance;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\AbstractInterface\Controller;

/**
 * 视图控制器
 * Class ViewController
 * @author  : evalor <master@evalor.cn>
 * @package App
 */
abstract class ViewController extends Controller
{
    protected $view;

    public function onRequest(?string $action): ?bool
    {
        $tempPath = Config::getInstance()->getConf('TEMP_DIR');    # 临时文件目录
        $this->view = new BladeInstance(EASYSWOOLE_ROOT . '/Views', "{$tempPath}/templates_c");

        return parent::onRequest($action); // TODO: Change the autogenerated stub
    }

    public function afterAction(?string $actionName): void
    {
        $this->view = null;
        parent::afterAction($actionName); // TODO: Change the autogenerated stub
    }

    /**
     * 输出模板到页面
     * @param string $view
     * @param array $params
     * @author : evalor <master@evalor.cn>
     */
    public function render(string $view, array $params = [])
    {
        $content = $this->view->render($view, $params);
        $this->response()->write($content);
    }
}
<?php

/**
 * @author ryan<zer0131@vip.qq.com>
 * @desc 默认控制器
 */
namespace Controller;

use OneFox\ApiController as BaseController;
use SSO\Client;
use OneFox\Request;

class IndexController extends BaseController {

    protected function _init() {
        $ssoClient = new Client();
        $login = $ssoClient->checkLogin();
        if (!$login) {
            //处理ajax请求
            if (Request::isAjax()) {
                $this->json(self::CODE_FAIL, 'no login');
            }
            $currentUrl = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];//获取当前页面的地址
            $redirect = $ssoClient->getSsoCenterJumpUrl($currentUrl);
            header("location: " . $redirect);
            exit;
        }
        $ssoClient->refreshTicket();
    }

    /**
     * 模拟系统首页
     */
    public function indexAction() {
        header('Content-type:text/html;charset=utf-8');
        var_dump($_COOKIE);
        var_dump('B项目登录状态中');
        exit;
    }
}

<?php

/**
 * @author ryan
 * @desc 模拟ldap
 */

namespace Controller\SSO;

use OneFox\Controller;

use OneFox\Config;
use OneFox\Request;
use OneFox\Response;
use SSO\Code;
use SSO\Session;
use SSO\Ticket;


class LdapController extends Controller {

    //登录页[GET]
    public function indexAction() {
        $this->show();
    }

    public function doLoginAction()
    {
        $appId  = Request::post('app_id', 0, 'string');
        $jumpto = Request::post('jumpto');
        if (!$appId) {
            echo 'params error';
            return;
        }

        $cookieName = md5(Config::get('sso.cookie_name'));


        $sessionInfo = array(
            'uid' => '1111',
            'username' => '测试登录'
        );

        $sessObj = new Session();
        $sessionId = $sessObj->create($sessionInfo);

        $ticketObj = new Ticket();
        $ticket = $ticketObj->genSysTicket($sessionId);

        setcookie($cookieName, $ticket, time() + 31500000, Config::get('sso.cookie_path'), '');

        $codeObj = new Code();
        $code = $codeObj->generateCode($sessionId);

        //携带code重定向至子系统callback页面
        $apps = Config::get('sso.apps');
        $appInfo = $apps[$appId];
        $callback = rtrim($appInfo['callback_url'], '?') . '?jumpto=' . urlencode($jumpto) . '&code=' . $code;
        Response::redirect($callback);
    }


}

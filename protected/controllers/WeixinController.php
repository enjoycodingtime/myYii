<?php
/**
 * Created by PhpStorm.
 * User: kezhang
 * Date: 16/3/31
 * Time: 下午10:57
 */
define("TOKEN", "zhangke");
define("APPID", 'wx6a88e32fda180101');

class WeixinController extends Controller
{
    public function actions()
    {
        return array(
            'quote' => array(
                'class' => 'CWebServiceAction',
            ),
        );
    }

    /**
     * @param string the symbol of the stock
     * @return float the stock price
     * @soap
     */
    public function getPrice($symbol)
    {
        return array(
            'quote' => array(
                'class' => 'CWebServiceAction',
            ),
        );
    }

    public function actionIndex()
    {
//        if (isset($_GET["echostr"])) {
//            $echoStr = $_GET["echostr"];
//            if ($this->checkSignature()) {
//                echo $echoStr;
//                exit;
//            }
//        } else {
//            echo 'sorry';
//            exit;
//        }
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $msgType = $postObj->MsgType;

            $time = time();
            $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0<FuncFlag>
            </xml>";
            if($msgType == 'image') {
                $params = array(
                    'fromUsername' => $fromUsername,
                    'toUsername' => $toUsername,
                    'mediaId' => $postObj->MediaId,
                );
                $fileName = Util::saveImage($postObj->MediaId,APPID);
                $params['content'] = $fileName;
                Util::replyTextMessage($params);
//                Util::replyImageMessage($params);
            }
            if($msgType == 'voice') {
                $params = array(
                    'fromUsername' => $fromUsername,
                    'toUsername' => $toUsername,
                    'mediaId' => $postObj->MediaId,
                );
                Util::replyVoiceMessage($params);
            }
            if($msgType == 'text')
            {
                $keyword = trim($postObj->Content);
                $msgType = "text";
                $contentStr = $keyword;
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                $contentStr = '收到事件';
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
                echo $resultStr;
            }
        }else {
            echo '咋不说哈呢';
            exit;
        }

    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    public function actionCreateMenu()
    {
        $accessToken = Util::getAccessToken(APPID);
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $accessToken;
        $params = '{
    "button": [
        {
            "name": "扫码", 
            "sub_button": [
                {
                    "type": "scancode_waitmsg", 
                    "name": "扫码带提示", 
                    "key": "rselfmenu_0_0", 
                    "sub_button": [ ]
                }, 
                {
                    "type": "scancode_push", 
                    "name": "扫码推事件", 
                    "key": "rselfmenu_0_1", 
                    "sub_button": [ ]
                }
            ]
        }, 
        {
            "name": "发图", 
            "sub_button": [
                {
                    "type": "pic_sysphoto", 
                    "name": "系统拍照发图", 
                    "key": "rselfmenu_1_0", 
                   "sub_button": [ ]
                 }, 
                {
                    "type": "pic_photo_or_album", 
                    "name": "拍照或者相册发图", 
                    "key": "rselfmenu_1_1", 
                    "sub_button": [ ]
                }, 
                {
                    "type": "pic_weixin", 
                    "name": "微信相册发图", 
                    "key": "rselfmenu_1_2", 
                    "sub_button": [ ]
                }
            ]
        }, 
        {
            "name": "发送位置", 
            "type": "location_select", 
            "key": "rselfmenu_2_0"
        }
    ]
}';
        if (!(Util::curlPost($url, $params, '', true)['errcode'] == 0)) {
            echo 'wrong';
//            Util::renderJSON('-200', 'wrong');
        }else {
//            Util::renderJSON('200', 'ok');
            echo 'ok';
        }
    }

    public function actionTest()
    {
        $this->renderPartial('test');


    }

    public function actionZb()
    {
        $this->renderPartial('zb');
    }
    public function actionKobe()
    {
        $this->renderPartial('zb');
    }
    public function actionZbResult()
    {
        $url = 'http://zb.weixinmongo.com/kbsm/result';
        if (isset($_GET["name"])) {
            $name = $_GET["name"];
            $img = Util::getImage($url, $name);
            $this->renderPartial('zbResult',array(
                'img'=>$img
            ));
        }else {

        $this->renderPartial('zb');
        }
    }
}
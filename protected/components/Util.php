<?php
/**
 * Created by PhpStorm.
 * User: kezhang
 * Date: 16/4/12
 * Time: 下午2:39
 */
require_once(dirname(__FILE__) . "/../common/qiniusdk/autoload.php");
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

// 用于签名的公钥和私钥
const QINIUA = '3t2FdP54OwGcKvg2bZQTy';
const QINIUK = 'ssv10S7r_H4qKjxmQ9NUn';
const BUCKET_NAME = 'vbnm';

class Util
{
    /*********************************************************************
     * 通用的CURL GET 方法
     * @param $url 访问的URL
     * @param $params 参数
     * @param string $logLevel 日志级别
     * @param boolean $resultToJson 是否把结果转换为JSON
     * @return bool|mixed
     ********************************************************************/
    public static function curlGet($url, $params, $logLevel = '', $resultToJson = false)
    {
        if (strstr($url, '?') === false) {
            $url = $url . '?' . http_build_query($params);
        } else {
            $url = $url . http_build_query($params);
        }
        /*****************************
         * 记录日志
         ****************************/
        if ($logLevel) {
            Yii::log($url, $logLevel);
        }

        $curl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        $content = curl_exec($curl);
        $status = curl_getinfo($curl);
        curl_close($curl);


        /*****************************
         * 记录日志
         ****************************/
        if ($logLevel) {
            //Yii::log(json_encode($status), $logLevel);
            Yii::log($content, $logLevel);
        }

        if (intval($status["http_code"]) == 200) {
            if ($resultToJson) {
                return json_decode(Util::removeUtf8Bom($content), true);
            } else {
                return $content;
            }
        } else {
            return false;
        }
    }

    /*********************************************************************
     * 通用的CURL POST 方法
     * @param $url 访问的URL
     * @param $params 参数
     * @param string $logLevel 日志级别
     * @param boolean $resultToJson 是否把结果转换为JSON
     * @return bool|mixed
     ********************************************************************/
    public static function curlPost($url, $params, $logLevel = '', $resultToJson = false)
    {
        /*****************************
         * 记录日志
         ****************************/
        if ($logLevel) {
            Yii::log($url, $logLevel);
            Yii::log(json_encode($params), $logLevel);
        }

        $curl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        if (is_string($params)) {
            $strPOST = $params;
        } else {
            $strPOST = http_build_query($params);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $strPOST);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);

        $content = curl_exec($curl);
        $status = curl_getinfo($curl);
        curl_close($curl);

        /*****************************
         * 记录日志
         ****************************/
        if ($logLevel) {
            //Yii::log(json_encode($status), $logLevel);
            Yii::log($content, $logLevel);
        }

        if (intval($status["http_code"]) == 200) {
            if ($resultToJson) {
                return json_decode($content, true);
            } else {
                return $content;
            }
        } else {
            return false;
        }
    }


    public static function getAccessToken($appId)
    {
        $accessTokenList = AccessToken::model()->findAll();
        if ($accessTokenList) {
            foreach ($accessTokenList as $item) {
                $updateTime = $item->FuiUpdateTime;
                $expireTime = $item->FuiExpireTime;
                $now = time();
                if ($now >= $expireTime) {
                    $tokenResult = Util::getAccessTokenByAppId($item->FstrAppid, $item->FstrAppkey);
                    if (!(is_array($tokenResult) && $tokenResult['access_token'])) {
                        Yii::log('get accessToken failed');
                    }

                    $now = time();
                    $item->FstrAccessToken = $tokenResult['access_token'];
                    $item->FuiExpireTime = $now + $tokenResult['expires_in'];
                    $item->FuiUpdateTime = $now;
                    if (!$item->update()) {
                        Yii::log('update accessToken failed');
                    }
                }
                if ($appId == $item->FstrAppid) {
                    return $item->FstrAccessToken;
                }
            }
            return false;
        }
        return false;
    }

    /**
     * 获取access token
     * @param $appId
     * @param $appSecret
     * @return bool|mixed
     */
    public static function getAccessTokenByAppId($appId, $appSecret)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $params = array(
            'grant_type' => 'client_credential',
            'appid' => $appId,
            'secret' => $appSecret,
        );
        return Util::curlGet($url, $params, '', true);
    }

    /**
     * 将json 数据输出到前端
     * @param $ret
     * @param string $message
     * @param null $data
     * @param bool $allowJsonP 是否支持使用JSONP
     * @param bool $mustSameDomain 是否输出domain信息
     */
    public static function renderJSON($ret, $message = '', $data = null, $allowJsonP = true, $mustSameDomain = true)
    {
        $result = array(
            'ret' => $ret,
            'msg' => $message,
        );
        if ($data !== null) {
            $result['data'] = $data;
        }
        $json = CJavaScript::jsonEncode($result);

        /***********************************************
         * 如果允许用JSONP返回数据，且回调函数合法
         * 则用JSONP返回数据
         **********************************************/
        $callback = Util::param('_cb_') ?: Util::param('callback');
        if ($allowJsonP && preg_match('/^[a-z_]\w*$/', $callback)) {
            if (!headers_sent()) {
                header("Content-type: application/javascript");
            }

            $jsonP = "{$callback}({$json});";
            if ($mustSameDomain) {
                $jsonP = "document.domain='" . Util::config('domain') . "';" . $jsonP;
            }
            echo $jsonP;
        } else {
            if (!headers_sent()) {
                header("Content-type: application/json");
            }
            echo $json;
        }
        Yii::app()->end();
    }

    /**
     * 删除UTF8 BOM头
     * @param $content
     * @return bool
     */
    public static function removeUtf8Bom($content)
    {
        $length = strlen($content);
        if ($length > 3 && ord($content[0]) == 239 && ord($content[1]) == 187 && ord($content[2]) == 191) {
            $newContent = substr($content, 3);
        } else {
            $newContent = $content;
        }
        return $newContent;
    }

    public static function replyTextMessage($parmes)
    {
        $fromUsername = $parmes['fromUsername'];
        $toUsername = $parmes['toUsername'];
        $content = $parmes['content'];
        $time = time();
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>0<FuncFlag>
            </xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $content);
        echo $resultStr;
    }

    public static function replyImageMessage($parmes)
    {
        $fromUsername = $parmes['fromUsername'];
        $toUsername = $parmes['toUsername'];
        $mediaId = $parmes['mediaId'];
        $time = time();
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Image>
                <MediaId><![CDATA[%s]]></MediaId>
            </Image>
            <FuncFlag>0<FuncFlag>
            </xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'image', $mediaId);
        echo $resultStr;
    }

    public static function replyVoiceMessage($parmes)
    {
        $fromUsername = $parmes['fromUsername'];
        $toUsername = $parmes['toUsername'];
        $mediaId = $parmes['mediaId'];
        $time = time();
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Voice>
                <MediaId><![CDATA[%s]]></MediaId>
            </Voice>
            <FuncFlag>0<FuncFlag>
            </xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'voice', $mediaId);
        echo $resultStr;
    }

    public static function replyVideoMessage($parmes)
    {
        $fromUsername = $parmes['fromUsername'];
        $toUsername = $parmes['toUsername'];
        $mediaId = $parmes['mediaId'];
        $title = $parmes['title'] || '标题';
        $desc = $parmes['desc'] || '';
        $time = time();
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[%s]]></MsgType>
            <Video>
                <MediaId><![CDATA[%s]]></MediaId>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
            </Video>
            <FuncFlag>0<FuncFlag>
            </xml>";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'video', $mediaId, $title, $desc);
        echo $resultStr;
    }

    public static function saveImage($mediaId, $appid)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=" . Util::getAccessToken($appid) . "&media_id=" . $mediaId;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);//只取body头
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//curl_exec执行成功后返回执行的结果；不设置的话，curl_exec执行成功则返回true
        $imageData = curl_exec($ch);
        curl_close($ch);
        if ($imageData == false) {
            Yii::log('get image failed', 'error');
            return;
        }
        $filename = 'temp/' . date("Ymdhis") . ".jpg";

        $tp = @fopen($filename, "a");
        if ($tp === false) {
            Yii::log('open file failed', 'error');
            return;
        }
        if (false !== fwrite($tp, $imageData)) {
            fclose($tp);
            Util::uploadFile($filename);
            return Yii::app()->params['basePath'] . $filename;
        }
    }

    public static function uploadFile($filePath)
    {
        $auth = new Auth(QINIUA, QINIUK);
        $bucket = BUCKET_NAME;
        // 要上传文件的本地路径
        $filePath = $filePath;
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 上传到七牛后保存的文件名
        $key = date("Ymdhis") . '.png';

        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            echo 'error;' . json_encode($err);
            echo 'ret:;' . json_encode($ret);

        } else {
//            echo $ret;
            echo json_encode($ret);

        }
    }

    public static function getImage($url, $name)
    {
        if ($name) {
            $url = $url . '?name=' . $name;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $resultData = curl_exec($ch);
        curl_close($ch);
        $flagImg = '/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i';
        preg_match_all($flagImg, $resultData, $imgArr);
        return $imgArr[2][0];
    }
}
<?php
///**
// * Created by PhpStorm.
// * User: kezhang
// * Date: 16/4/12
// * Time: 下午2:39
// */
//class Util
//{
//    /*********************************************************************
//     * 通用的CURL GET 方法
//     * @param $url 访问的URL
//     * @param $params 参数
//     * @param string $logLevel 日志级别
//     * @param boolean $resultToJson 是否把结果转换为JSON
//     * @return bool|mixed
//     ********************************************************************/
//    public static function curlGet($url, $params, $logLevel = '', $resultToJson = false) {
//        if (strstr($url, '?') === false) {
//            $url = $url . '?' . http_build_query($params);
//        } else {
//            $url = $url . http_build_query($params);
//        }
//
//        /*****************************
//         * 记录日志
//         ****************************/
//        if ($logLevel) {
//            Yii::log($url, $logLevel);
//        }
//
//        $curl = curl_init();
//        if (stripos($url, "https://") !== FALSE) {
//            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
//        }
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
//
//        $content = curl_exec($curl);
//        $status = curl_getinfo($curl);
//        curl_close($curl);
//
//
//        /*****************************
//         * 记录日志
//         ****************************/
//        if ($logLevel) {
//            //Yii::log(json_encode($status), $logLevel);
//            Yii::log($content, $logLevel);
//        }
//
//        if (intval($status["http_code"]) == 200) {
//            if ($resultToJson) {
//                return json_decode(Util::removeUtf8Bom($content), true);
//            } else {
//                return $content;
//            }
//        } else {
//            return false;
//        }
//    }
//
//    /*********************************************************************
//     * 通用的CURL POST 方法
//     * @param $url 访问的URL
//     * @param $params 参数
//     * @param string $logLevel 日志级别
//     * @param boolean $resultToJson 是否把结果转换为JSON
//     * @return bool|mixed
//     ********************************************************************/
//    public static function curlPost($url, $params, $logLevel = '', $resultToJson = false) {
//        /*****************************
//         * 记录日志
//         ****************************/
//        if ($logLevel) {
//            Yii::log($url, $logLevel);
//            Yii::log(json_encode($params), $logLevel);
//        }
//
//        $curl = curl_init();
//        if (stripos($url, "https://") !== FALSE) {
//            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//        }
//
//        if (is_string($params)) {
//            $strPOST = $params;
//        } else {
//            $strPOST = http_build_query($params);
//        }
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_POST, true);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $strPOST);
//        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
//
//        $content = curl_exec($curl);
//        $status = curl_getinfo($curl);
//        curl_close($curl);
//
//        /*****************************
//         * 记录日志
//         ****************************/
//        if ($logLevel) {
//            //Yii::log(json_encode($status), $logLevel);
//            Yii::log($content, $logLevel);
//        }
//
//        if (intval($status["http_code"]) == 200) {
//            if ($resultToJson) {
//                return json_decode($content, true);
//            } else {
//                return $content;
//            }
//        } else {
//            return false;
//        }
//    }
//
//
//
//    public static function getAccessToken($appId)
//    {
//        var_dump('asdfasdfa');
//        $accessTokenList = AccessToken::model()->findAll();
//        if ($accessTokenList) {
//            foreach ($accessTokenList as $item) {
//                $updateTime = $item->FuiUpdateTime;
//                $expireTime = $item->FuiExpireTime;
//                $now = time();
//                if ($now - $updateTime >= $expireTime) {
//                    $tokenResult = Util::getAccessTokenByAppId($item->FstrAppid,$item->FstrAppkey);
//                    if(!(is_array($tokenResult) && $tokenResult['access_token'])){
//                        Yii::log('get accessToken failed');
//                    }
//
//                    $now = time();
//                    $item->FstrAccessToken = $tokenResult['access_token'];
//                    $item->FuiExpireTime = $now + $tokenResult['expires_in'];
//                    $item->FuiUpdateTime = $now;
//                    if(!$item->update()){
//                        Yii::log('update accessToken failed');
//                    }
//                }
//                if($appId == $item->FstrAppid) {
//                    return $item->FstrAccessToken;
//                }
//            }
//            return false;
//        }
//        return false;
//    }
//    /**
//     * 获取access token
//     * @param $appId
//     * @param $appSecret
//     * @return bool|mixed
//     */
//    public static function getAccessTokenByAppId($appId, $appSecret){
//        $url = 'https://api.weixin.qq.com/cgi-bin/token';
//        $params = array(
//            'grant_type' => 'client_credential',
//            'appid' => $appId,
//            'secret' => $appSecret,
//        );
//        return Util::curlGet($url, $params, '', true);
//    }
//    /**
//     * 将json 数据输出到前端
//     * @param $ret
//     * @param string $message
//     * @param null $data
//     * @param bool $allowJsonP 是否支持使用JSONP
//     * @param bool $mustSameDomain 是否输出domain信息
//     */
//    public static function  renderJSON($ret, $message = '', $data = null, $allowJsonP = true, $mustSameDomain = true) {
//        $result = array(
//            'ret' => $ret,
//            'msg' => $message,
//        );
//        if ($data !== null) {
//            $result['data'] = $data;
//        }
//        $json = CJavaScript::jsonEncode($result);
//
//        /***********************************************
//         * 如果允许用JSONP返回数据，且回调函数合法
//         * 则用JSONP返回数据
//         **********************************************/
//        $callback = Util::param('_cb_') ?: Util::param('callback');
//        if ($allowJsonP && preg_match('/^[a-z_]\w*$/', $callback)) {
//            if (!headers_sent()) {
//                header("Content-type: application/javascript");
//            }
//
//            $jsonP = "{$callback}({$json});";
//            if($mustSameDomain){
//                $jsonP = "document.domain='" . Util::config('domain') . "';" . $jsonP;
//            }
//            echo $jsonP;
//        } else {
//            if (!headers_sent()) {
//                header("Content-type: application/json");
//            }
//            echo $json;
//        }
//        Yii::app()->end();
//    }
//}
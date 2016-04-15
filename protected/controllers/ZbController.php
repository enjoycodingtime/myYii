<?php
/**
 * Created by PhpStorm.
 * User: kezhang
 * Date: 16/4/11
 * Time: 下午10:53
 */
class ZbController extends Controller
{
    public function actionIndex()
    {
        $this->renderPartial('index');
    }

    public function actionHuaiyun()
    {
        $name = $_GET['name'];
        $im = imagecreatetruecolor(640, 578);
        $bg = imagecreatefromjpeg('images/beijing.jpg');
        imagecopy($im,$bg,0,0,0,0,640,578);
        imagedestroy($bg);
        $black = imagecolorallocate($im, 42, 14, 35);
        $text = $name;
        $font = 'images/simhei.ttf';
        imagettftext($im, 10, 0, 105, 264, $black, $font, $text);
        imagettftext($im, 10, 0, 305, 527, $black, $font, $text);
        imagettftext($im, 10, 0, 120, 548, $black, $font,date('y-m-d h:i',time()));
        imagettftext($im, 10, 0, 315, 548, $black, $font,date('y-m-d h:i',time()-2*3300));
        imagettftext($im, 10, 0, 510, 548, $black, $font,date('y-m-d h:i',time()));
        imagejpeg($im, 'images/simpletext.jpg');
        imagedestroy($im);
        $this->renderPartial('huaiyun');
    }
    public function actionHuaiyunle()
    {
        $this->renderPartial('share');
    }
}
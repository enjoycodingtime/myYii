<?php
/**
 * Created by PhpStorm.
 * User: kezhang
 * Date: 16/4/14
 * Time: 下午8:33
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no">
    <title>我爱科比</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        header {
            background-color: #18b4ed;
        }

        .header h1 {
            text-align: center;
            font-size: 18px;
            color: #fff;
            line-height: 36px;
        }

        .container {
            text-align: center;
        }

        .container img {
            width: 100%;
            margin: 0px auto;
        }

        .container form .input {
            width: 100%;
            border-top: 1px solid ghostwhite;
            border-bottom: 1px solid ghostwhite;
            font-size: 20px;
            display: inline-block;
            height: 50px;
            line-height: 50px;
            padding-right: 20px;
        }

        .container form .input input {
            border: none;
            display: inline-block;
            height: 50px;
            line-height: 50px;
            font-size: 20px;

        }

        .button {
            width: 100%;
            border-bottom: 1px solid ghostwhite;
            font-size: 20px;
            height: 50px;
            line-height: 50px;
            text-align: center;
        }

        .buttonA {
            margin: 0 auto;
            display: block;
            width: 80%;
            background-color: #18b4ed;
            border-radius: 10px;
            border: none;
            height: 50px;
            font-size: 20px;
            color: white;
            font-weight: bolder;
            text-decoration: none;
        }
        .neican {
            text-align: center;
        }
        .neican img{
            width: 50%;
        }
        .text {
            font-size: 12px;
            margin: 5px auto;
            color: grey;
        }
    </style>
</head>
<body>
<header class="header">
    <h1>再见科比</h1>
</header>
<div class="container">
    <div>
        <img src="<?= $img ?>" alt="">
    </div>
    <div class="text">
        长按上方图片点选保存图片
    </div>
</div>
<div class="neican">
    <img src="/images/neican.jpg" alt="">
    
</div>
<div class="button">
    <a href="http://1.404nf.cn/weixin/zb" class="buttonA">重新绘画</a>
</div>
</body>
</html>
<?php
/**
 * Created by PhpStorm.
 * User: kezhang
 * Date: 16/4/14
 * Time: 下午8:09
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
            width: 60%;
            margin: 50px auto;
        }

        .container form .input{

            width: 100%;
            border-top: 1px solid ghostwhite;
            border-bottom: 1px solid ghostwhite;
            font-size: 20px;
            display: inline-block;
            height: 50px;
            line-height: 50px;
        }
        .container form .input input {
            overflow: hidden;
            border: none;
            display: inline-block;
            height: 50px;
            line-height: 50px;
            font-size: 20px;

        }
        .container form .button {
            width: 100%;
            border-bottom: 1px solid ghostwhite;
            font-size: 20px;
            height: 50px;
            line-height: 50px;
        }
        button{
            width: 80%;
            background-color: #18b4ed;
            border-radius: 10px;
            border: none;
            height: 50px;
            font-size: 20px;
            color: white;
            font-weight: bolder;
        }
        .nameLable {
            width: 90px;
            text-align:left;
            display: inline-block;
            padding-left: 10px;
        }
    </style>
</head>
<body>
<header class="header">
    <h1>我给科比画素描</h1>
</header>
<div class="container">
    <div>
        <img src="/images/icon.jpg" alt="">
    </div>
    <form class="form" action="/weixin/zbResult" method="get">
        <div class="input">
            <label class="nameLable">
                姓名:
            </label>
            <input type="text" placeholder="请输入你的姓名" name="name">
        </div>
        <div class="button">
            <button type="submit">绘画</button>
        </div>
    </form>
</div>
</body>
</html>

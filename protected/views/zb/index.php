<?php
/**
 * Created by PhpStorm.
 * User: kezhang
 * Date: 16/4/11
 * Time: 下午10:55
 */

?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
	<title>怀孕单生成</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<style type="text/css">

.s{position: absolute;top: 180px;left: 250px; }
 #round {

 -webkit-user-select: none;
   padding:10px; width:100%; height:50px;
   border: 5px solid #f43d30;
-moz-border-radius: 15px;
   -webkit-border-radius: 15px;
   border-radius:15px;
   margin-top: 5px;
   }
   .xm{color: #a6a6a6;
    padding-top: 45px;
       font-size: 16px;
       line-height: 44px;

   }
  .in{width: 90%;height: 30px;text-align: center;margin-left: 10px;}

	</style>



</head>
<body>
<section>
	<img src="/images/beijing.jpg" width="100%">
</section>

<section>
		<form action="/zb/huaiyun" method="GET">
			<input class="in" type="text" placeholder="输入姓名" name="name" ><br>
			<input id="round" type="submit" value="确定"  >
		</form>
	</section>


</body>
</html>

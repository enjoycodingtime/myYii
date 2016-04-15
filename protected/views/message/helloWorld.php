<?php
$this->breadcrumbs=array(
	'Message'=>array('message/index'),
	'HelloWorld',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p> hello world <h1><?=CHtml::link('Good Bay',array('user/index'))?></h1><tt><?php echo __FILE__; ?></tt>.</p>

<h2><?php
echo $time;
?></h2>

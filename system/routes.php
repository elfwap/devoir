<?php

return [
	'c:=%;q:=[/*/{0}/{1}{2}{3}];' => [
		'controller' => 'articles',
		'action' => '$0',
		'params' => '$1$2$3'
	],
	'c:=web;a:=index;p:={22}{1};' => [
		'controller' => 'articles',
		'action' => '$22',
		'params' => '$1'
	],
	'c:={33};a:={0};p:={1};' => [
		'controller' => 'articles',
		'action' => '$0',
		'params' => '$1'
	],
	'c:={33};a:={2};p:={1};' => [
		'controller' => 'articles',
		'action' => '$0',
		'params' => '$1'
	],
	/* '/!/!/!;?#1devoir:/{0}/{1};' => [
		'controller' => 'articles',
		'action' => '$0',
		'params' => '$1'
	] */
];

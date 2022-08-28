<?php

return [
	'c:=resources;a:=images;p:={0}' => [
		'controller' => 'resources',
		'action' => 'img',
		'params' => '$0'
	],
	'c:=res;a:=images;p:={10}' => [
		'controller' => 'resources',
		'action' => 'img',
		'params' => '$10'
	],
	'c:=res;a:={12};p:={13}' => [
		'controller' => 'resources',
		'action' => '$12',
		'params' => '$13'
	],
	'c:=da-content;a:={20};p:={21}' => [
		'controller' => 'resources',
		'action' => '$20',
		'params' => '$21'
	],
	'c:=devoir-app-content;a:={22};p:={23}' => [
		'controller' => 'resources',
		'action' => '$22',
		'params' => '$23'
	],
	'c:=devoir-app-content;a:=images;p:={24}' => [
		'controller' => 'resources',
		'action' => 'img',
		'params' => '$24'
	],
];

<?php

return [
	'is_debug' => IS_DEBUG,
	'app' => [
		'namespace' => APPLICATION_NAMESPACE,
		'model' => [
			'namespace' => MODELS_NAMESPACE,
			'path' => MODELS_PATH,
		],
		'view' => [
			'namespace' => VIEWS_NAMESPACE,
			'path' => VIEWS_PATH,
		],
		'controller' => [
			'namespace' => CONTROLLERS_NAMESPACE,
			'path' => CONTROLLERS_PATH,
		],
		'default_controller' => DEFAULT_CONTROLLER,
		'default_action' => DEFAULT_ACTION,
		'root_path' => ROOT_PATH,
		'base_path' => BASE_PATH,
		'base_source_path' => BASE_SOURCE_PATH,
		'path' => [
			'root' => ROOT_PATH,
			'base' => BASE_PATH,
			'base_source' => BASE_SOURCE_PATH,
			'controller' => CONTROLLERS_PATH,
			'model' => MODELS_PATH,
			'view' => VIEWS_PATH
		]
	],
	'db' => [
		'default' => [
			'username' => '',
			'userpass' => '',
			'database' => '',
		]
	],
];
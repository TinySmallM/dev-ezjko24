<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
	'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl'=>'/master',
            'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-admin', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'ezjko_admin',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            	
            	''=>'page/all',
            	'page'=>'page/all',
            	'product'=>'product/all',
            	'news'=>'news/all',

                'review'=>'review/all',


                'member'=>'member/all',

                'platform/page'=>'platform/page',
            	
            	'order'=>'order/all',
            	'order/<id:\d+>'=>'order/index',
            	
            	'report/<action>'=>'report/<action>',
            	
            	'coupon'=>'coupon/index',
            	'coupon/<action>'=>'coupon/<action>',
            	
            	'storage>'=>'storage/index',
            	'storage/<action>'=>'storage/<action>',
            	
            	'<action>'=>'site/<action>'
            ],
        ],
    ],
    'params' => $params,
];

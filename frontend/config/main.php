<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'language' => 'ru-RU',
    'bootstrap' => ['log',/*'assetsAutoCompress'*/],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
    	
        'assetsAutoCompress' => [
            'class' => '\skeeks\yii2\assetsAuto\AssetsAutoCompressComponent',
            'jsFileCompileByGroups'         => false,
            'jsFileCompile'                 => false,
            'jsCompress'                => false, 
            'htmlFormatter' => [
                'class'         => 'skeeks\yii2\assetsAuto\formatters\html\TylerHtmlCompressor',
                'extra'         => true,
                'noComments'    => true,  
            ],
            'cssCompress' => true,
            'cssFileCompress'       => true, 
            'cssFileCompile'        => true,
        ],
        
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl'=>'',
            'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
        ],
        'user' => [
            'identityClass' => 'common\models\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-shelfFront', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'shelf_front',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer'
            ],
            'showScriptName' => false,
            //'suffix' => '',
            'rules' => [


                'lk'=>'lk/index',

                'lk/<action>'=>'lk/<action>',
                'lk/bind-vk-link/<token_hash:(.*)+>'=>'lk/bind-vk-link',

                
                'file/api' => 'file/api',

                'video' => 'page/video',
                

                'dolyame/<action>'=>'dolyame/<action>',
            	
                
            	'form/<action>'=>'form/<action>',
            	
            	//'news'=>'news/all',
                //'news/<id:\d+>' => 'news/index',

                'platform/theme/<url:(.*)+>'=>'platform/theme',
                'platform'=>'platform/subjects',
                

                'page/api'=>'page/api',

                'search'=>'page/search',

                'storage/<action>'=>'storage/<action>',
                
                'fingerprint/<action>'=>'fingerprint/<action>',
                
                'nashi-rabotyi'=>'page/works',
                'otzyvy'=>'page/review',
                'card'=>'page/card',
            	
                'cart/process'=>'cart/process',
                'cart/result'=>'cart/result',
            	'cart'=>'cart/index',
                
            	'cart/<action>'=>'cart/<action>',
            	
            	'mail/verify'=>'mail/verify',
            	'mail/<action>'=>'mail/index',
            	
            	//'payment/callback'=>'payment/callback',
            	//'payment/<action>/<hash:[/0-9a-zA-Z_-]+>'=>'payment/<action>',
            	
                ['pattern'=>'products2.xml', 'route' => 'page/products', 'suffix'=>false],
            	['pattern'=>'sitemap.xml', 'route' => 'page/sitemap', 'suffix'=>false],
            	['pattern'=>'robots.txt', 'route' => 'page/robots', 'suffix'=>false],
            	
            	'<url:(.*)+>'=>'page/index',
            ],
        ],
    ],
    'params' => $params,
    'on beforeRequest' => function () {
        $pathInfo = Yii::$app->request->pathInfo;
        $query = Yii::$app->request->queryString;
        if (!empty($pathInfo) && substr($pathInfo, -1) === '/') {
            $url = '/' . substr($pathInfo, 0, -1);
            if ($query) {
                $url .= '?' . $query;
            }
            Yii::$app->response->redirect($url, 301);
            Yii::$app->end();
        }
    },
];
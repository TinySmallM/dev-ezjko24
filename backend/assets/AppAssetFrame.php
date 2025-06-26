<?php

namespace backend\assets;

use yii\web\AssetBundle;

class AppAssetFrame extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css',
        'https://use.fontawesome.com/releases/v5.8.2/css/all.css',
        'https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,900&display=swap&subset=cyrillic,cyrillic-ext,latin-ext',
        'css/site.css',
        
    ];
    public $js = [
    	'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js',
    	'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js',
    	'https://cdn.jsdelivr.net/npm/sweetalert2@9.6.1',
    	'/cdn/axios-master/dist/axios.min.js',
    	'/cdn/jquery.maskedinput.min.js',
    	'js/script.js',
    
    	
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $jsOptions = [
	];
}
<?php

namespace backend\assets;

use yii\web\AssetBundle;

class AppAssetAce extends AssetBundle{
    public $basePath = '@webroot/js/ace/src-min';
    public $baseUrl = '@web/js/ace/src-min';
    public $css = [];
    public $js = [
		'ace.js',
		'https://cloud9ide.github.io/emmet-core/emmet.js',
		'ext-emmet.js'
	];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
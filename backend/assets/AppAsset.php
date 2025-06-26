<?php

namespace backend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css',
        'https://use.fontawesome.com/releases/v5.8.2/css/all.css',
        'https://fonts.googleapis.com/css?family=Quicksand:500,700&display=swap&subset=cyrillic,cyrillic-ext,latin-ext',
        'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap&subset=cyrillic,cyrillic-ext,latin-ext',
        'cdn/select2/select2.min.css',
        '//unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css',
        'adminlte/adminlte.min.css',
        'css/site.css?v2',
        
    ];
    public $js = [
    	'js/vue.js',
    	'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js',
    	'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js',
    	'https://unpkg.com/@popperjs/core@2',
    	'https://cdn.jsdelivr.net/npm/sweetalert2@9.6.1',
    	'/cdn/axios-master/dist/axios.min.js',
    	'/cdn/jquery.maskedinput.min.js',
    	'adminlte/adminlte.min.js',
    	'js/ckeditor-ckfinder-integration-master/ckeditor/ckeditor.js',
        'js/ckeditor-ckfinder-integration-master/ckfinder/ckfinder.js',
        '//cdn.jsdelivr.net/npm/sortablejs@1.8.4/Sortable.min.js',
        '//cdnjs.cloudflare.com/ajax/libs/Vue.Draggable/2.20.0/vuedraggable.umd.min.js',
        '//unpkg.com/vue-multiselect@2.1.0',
    	'cdn/select2/select2.min.js',
    	'js/script.js?v3',
    
    	
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
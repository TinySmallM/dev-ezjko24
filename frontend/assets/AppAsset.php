<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
		//'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&amp;family=Raleway:wght@700&amp;display=swap',
		//'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css',
        //'css/style.css?v2fs',
        //'css/new_styles.css?v9vffffffffffffff33f1',
        'css/flex.css?v7',

        '/cdn/mmenu-light.css',

        '/nt/css/normalize.css',
        '/nt/css/slick.css',
        '/cdn/fancybox-master/dist/jquery.fancybox.min.css',
        '/nt/css/tooltipster.css',
        '/nt/css/tooltipster.css',
        '/nt/css/formstyler.css',
        '/nt/css/range-slider.css',
        '/nt/css/style.css',

        //'/cdn/tingle.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.css',
        //'/cdn/fancybox-master/dist/jquery.fancybox.min.css',
        //'css/responsive.css',
        
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.min.js',
		'https://code.jquery.com/jquery-3.5.1.slim.min.js',
		'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js',
		//'https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.all.min.js',
		//'https://unpkg.com/aos@next/dist/aos.js',
    	//'//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
        
    	'/cdn/jquery.maskedinput.min.js',
        //'/cdn/fancybox-master/dist/jquery.fancybox.min.js',
	//	'js/script.js',
		//'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js',
		'/cdn/axios.min.js',
		'/cdn/sweetalert2@9.js',
		'/cdn/tingle.min.js',
        '/cdn/mmenu-light.js',
    	'js/script-new.js?v3fffffffffdfdffаfff3f2',
    	'js/fpa.js?v7fff',
        '/nt/js/slick.js',
        '/nt/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset'
    ];
    public $jsOptions = [
	];
}
<?

use yii\helpers\Url;

echo '<?xml version="1.0" encoding="UTF-8"?>'?>
 
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?foreach($page as $d){ ?>
        <url>
            <loc><?= str_replace('/index/','/',Url::home(true).$d['url'].'/'); ?></loc>
            <lastmod><?= date_create_from_format('U',strtotime($d['updatedon']))->format('Y-m-d') ?>T00:00:00+03:00</lastmod>
        </url>
    <?}?>
    <?foreach($product as $d){ ?>
        <url>
            <loc><?= Url::home(true) . $d['url']; ?></loc>
            <lastmod><?= date_create_from_format('U',strtotime($d['updatedon']))->format('Y-m-d') ?></lastmod>
        </url>
    <?}?>
</urlset>
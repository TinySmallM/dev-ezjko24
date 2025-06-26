<?

use yii\helpers\Url;
?>

User-agent: *
Allow: /
Disallow: /lk
Disallow: /cart
Disallow: /index/
Disallow: /index
Disallow: /*?
Disallow: /tproduct/
Disallow: /service/

User-agent: GoogleBot
Disallow: /*?*
Allow: *.css*
Allow: *.js*

Sitemap: <?=Url::home(true)?>sitemap.xml
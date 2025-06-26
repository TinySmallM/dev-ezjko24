<? echo '<?xml version="1.0" encoding="UTF-8"?>'?>
<?

use yii\Helpers\ArrayHelper;

$skipCategory = [];

$categoryOk = [];

?>

<yml_catalog>
    <shop>
        
        <categories>
            <?foreach($page as $d){
                if( in_array($d['id'],$skipCategory) ) continue;
                if(!$d['product']) continue;

                $categoryOk[] = $d['id'];
                ?>
            <category id="<?=$d['id']?>"><?=$d['menuname']?></category>
            <?}?>
        </categories>
        <offers>
            <?
            $productAll = [];
            foreach($page as $d){
                if( in_array($d['id'],$skipCategory) ) continue;
                if( !in_array($d['id'],$categoryOk) ) continue;
                ?>
                <?foreach($d['product'] as $p){
                    if( in_array($p['id'],$productAll) ) continue;

                    $categoryId = $d['id'];

                    $productAll[] = $p['id'];

                    //$p['characts'] = ArrayHelper::index($p['characts'],'name');

                    $price = ($p['price1_sum']&&$p['price2_sum']) && $p['price2_sum'] < $p['price1_sum'] ? $p['price2_sum'] : $p['price1_sum'];
                    if($price == 0) continue;

                    $p['content'] = preg_replace("/&#?[a-z0-9]+;/i","",$p['content']); 

                    ?>
                    <offer id="<?=$p['id']?>">
                        <name><?=$p['menuname']?></name>
                        <price><?= $price ?></price>
                        <currencyId>RUR</currencyId>
                        <categoryId><?=$categoryId?></categoryId>
                        <enable_auto_discounts>true</enable_auto_discounts>
                        <url>https://rus-electronika.ru/<?=$p['url']?></url>
                        <?if($p['image']){?>
                            <picture>https://rus-electronika.ru/upload/<?=$p['image']?></picture>
                        <?}?>

                        <description><?=strip_tags( $p['content'])?></description>
                    </offer>
                <?}?>
            <?}?>

        </offers>
    </shop>
</yml_catalog>

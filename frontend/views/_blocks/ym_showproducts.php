<?php

$impressions = [];
foreach($product as $i=>$d){
    
    $newImp = [
        'id'=>$d['id'],
        'name'=>$d['menuname'],
        'price'=>$d['price1_sum'],
        'list'=>$h1,
        'position'=>$i+1,
        'variant' => $d['price1_name'],
        'brand'=>'Атего36'
    ];

    $impressions[] = $newImp;
}
?>
<script>

window.dataLayer.push({
    "ecommerce": {
        "currencyCode": "RUB",
        "impressions": <?=json_encode($impressions)?>
    }
});
</script>
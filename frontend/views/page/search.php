<?
use common\models\Helpers;
use yii\helpers\Url;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:image', 'content' => Url::home(true).'upload/'.$data['image'] ]);
$this->title = Helpers::phReplace($data['title']);

?>

<!-- (wrapper) -->
<div class="wrapper">
  <div class="wrapper__content">
    
    <?=$this->render('../_blocks/nt_header')?> 



        <?if(!$items){?>
            <div class=" cover container">
                <p class="text-center" style="color: #222;font-size:1rem;">Ничего не найдено.</p>
            </div>
        <?}?>

        <?if($items){?>
        <div class="cover ">
            <?=$this->render('../_blocks/old2/atego_product_filter',[
                'items'=>$items
            ])?>
        </div>

        <?}?>

    <?=$this->render('../_blocks/nt_footer')?> 

    </div>
</div>

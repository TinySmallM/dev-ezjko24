<?
use common\models\Helpers;
use yii\helpers\Url;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:image', 'content' => Url::home(true).'upload/'.$data['image'] ]);
$this->title = Helpers::phReplace($data['title']);

?>


<?/*Banner */?>
<?=$this->render('../_blocks/banner',['data'=>[
    'h1'=>Helpers::phReplace($data['h1'])
]])?>

<?/*Плитка товаров */?>
<section class="shop one-item" data-aos="fade-up"
data-aos-easing="linear"
data-aos-duration="200">
    <div class="container">
        <div class="list">
            <div class="item">
                <a href="javascript:void(0)" class="image">
                    <img src="/img/preload.gif" data-src="/upload/thumb_<?=$data['gallery'][0]['image'];?>" alt="<?=$this->title?>"
                    title="<?=$this->title?>">
                </a>
                <?/*
                <a href="javascript:void(0)" class="heading">
                   <?=Helpers::phReplace($data['h1'])?>
                </a>
                */?>
                <div class="params">
                    <span class="green bold">Варианты доставки: </span>
                    <div class="scroll-container">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Контейнер для ваших ростков пшеницы </td>
                                    <td>300 руб.</td>
                                </tr>
                                <tr>
                                    <td>До пункта выдачи СДЭК</td>
                                    <td>300 руб.</td>
                                </tr>
                                <tr>
                                    <td>До двери</td>
                                    <td>600 руб.</td>
                                </tr>
                                <tr>
                                    <td>Банка 300 ростков пшеницы</td>
                                    <td>2000 руб.</td>
                                </tr>
                                <tr>
                                    <td>Банка 500 ростков пшеницы</td>
                                    <td>2000 руб.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="price">
                        Цена от <?= number_format($data['price2_sum'], 0, ',', ' ')?>&nbsp;руб.
                    </div>
                <button data-id="<?=Helpers::phReplace($data['h1'])?>" class="btn btn_ex_card" data-toggle="modal" data-target="#order-modal">
                    Заказать
                </button>
                
            </div>
        </div>
        
        <?if($data['content']){?>
        <div class="content mt-4">
        	<?php eval('?>'.Helpers::phReplace($data['content']) )?>
        </div>
        <?}?>
    </div>
</section>

<?/*Контент блок 1 */?>
<?=$this->render('../_blocks/content_block1',['data'=>[
    'image'=>$data['chunk']['content_block1_image']['content'],
    'content'=>Helpers::phReplace($data['chunk']['content_block1_text']['content'])
]])?>


<?/*=$this->render('../_blocks/ex_category_tile',['data'=>[
    'product'=>$data['child'],
    'h2'=>'',
    'text1'=>Helpers::phReplace($data['chunk']['text1']['content']),
    'text2'=>Helpers::phReplace($data['chunk']['text2']['content']),
    'nameTpl'=>Helpers::phReplace($data['chunk']['exTile_nameTpl']['content'])
]])*/?>


<!-- Контент блок 1 -->
<?php /*eval('?>'.Helpers::phReplace($data['chunk']['content_block1']['content']) )*/?>
<?
$script = <<< JS
let btn = document.querySelector('.btn_ex_card');
let hiddenInput = document.querySelector('input[name="product"]');
    btn.addEventListener('click', function(e){
       let id = btn.getAttribute('data-id'); 
       hiddenInput.value = id;
    });

JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
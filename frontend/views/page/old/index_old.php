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
<?/*=$this->render('../_blocks/tile_product',['data'=>[
    'product'=>$data['product'],
    'h2'=>Helpers::phReplace($data['chunk']['exTileh2_1']['content']),
    'text1'=>Helpers::phReplace($data['chunk']['text1']['content']),
    'text2'=>Helpers::phReplace($data['chunk']['text2']['content']),
    'nameTpl'=>Helpers::phReplace($data['chunk']['exTile_nameTpl']['content'])
]])*/?>

<?=$this->render('../_blocks/tile_category',['data'=>[
    'product'=>$data['child'],
    'h2'=>Helpers::phReplace($data['chunk']['exTileh2_1']['content']),
    'text1'=>Helpers::phReplace($data['chunk']['text1']['content']),
    'text2'=>Helpers::phReplace($data['chunk']['text2']['content']),
    'nameTpl'=>Helpers::phReplace($data['chunk']['exTile_nameTpl']['content'])
]])?>

<?/*Form */?>
<?=$this->render('../_blocks/form1')?>

<?/*Рекомендуемый товар */?>
<?=$this->render('../_blocks/recommend_product',['data'=>[
    'id'=>$data['chunk']['recommend_product']['content']
]])?>

<?/*Преимущества*/?>
<section class="advantages" data-aos="fade-right"
data-aos-easing="linear"
data-aos-duration="200">
    <div class="container">
        <h2>
            Наши преимущества 
        </h2>
    </div>
    <div class="inner">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="item">
                        <svg class="icon">
                            <use xlink:href="/icon/sprite.svg#thumb"></use>
                        </svg>
                        <span class="heading">
                            Одобрено ведущими специалистами
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item">
                        <svg class="icon">
                            <use xlink:href="/icon/sprite.svg#eco"></use>
                        </svg>
                        <span class="heading">
                            Только натуральные ингредиенты
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="item">
                        <svg class="icon">
                            <use xlink:href="/icon/sprite.svg#future"></use>
                        </svg>
                        <span class="heading">
                            Новейшие технологии производства
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <span class="slogan">
            Натуральный "СуперФуд" для здоровой и активной жизни
        </span>
    </div>
</section>

<?/*Form */?>
<?=$this->render('../_blocks/form1')?>

<?/*Контент блок 1 */?>
<?=$this->render('../_blocks/content_block1',['data'=>[
    'image'=>$data['chunk']['content_block1_image']['content'],
    'content'=>Helpers::phReplace($data['chunk']['content_block1_text']['content'])
]])?>

<?/* О ростках, о нас, доставка */?>
<section class="text-block" data-aos="fade-left"
data-aos-easing="linear"
data-aos-duration="200">
    <div class="container">
        <h2>О ростках пшеницы</h2>
        <div class="row">
            <div class="col-md-6 d-none d-md-block">
                <img src="/img/preload.gif" data-src="/img/crop2.jpg" alt="О ростках пшеницы" title="О ростках пшеницы">
            </div>
            <div class="col-md-6">
                <p>
                    Большое количество БАДов или различных витаминов производятся преимущественно из порошка. 
                    А при перемалывании в порошок продукт теряет свои полезные свойства за счет нагревания и трения, 
                    которые приводят к разрушению многих полезных элементов для человека. По этой причине многие люди 
                    разочаровываются в разрекламированных витаминах и таблетках, переставая их принимать.
                </p>
                <img class="d-md-none" style="margin-bottom: 10px;" src="/img/preload.gif" data-src="/img/crop2.jpg" alt="О ростках пшеницы" title="О ростках пшеницы">
                <p>
                    Компания «Живи 200» выращивает зеленые ростки пшеницы с применением новейших технологий, которые 
                    не используют нагревание и трение сырья. Благодаря этому в нашем продукте сохраняется максимально 
                    полезных элементов для организма человека. К тому же наша продукция изготавливается на территории 
                    Российской Федерации, а поэтому вы всегда получаете свежие ростки пшеницы, произведенные в удобную 
                    для приема форму.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="text-block pt-0" data-aos="fade-right"
data-aos-easing="linear"
data-aos-duration="200">
    <div class="container">
        <h2>Как мы работаем</h2>
        <div class="row">
            <div class="col-12">
                <p>
                    Мы выращиваем ростки пшеницы совершенно не применяя химические добавки и различные удобрения .
                </p>
                <p>
                    Ростки растут на подносах в полностью стерильных помещениях с особенными температурными условиями 
                    и микроклиматом. Благодаря нашим технологиям ростки пшеницы выращиваются за 10 дней. Благодаря свету, 
                    воде и кислороду активизируются все энергетические процессы роста зерна.
                </p>
                <p>
                    Сушка продукции производится на специальном оборудовании при постоянном температурном режиме в 27 
                    градусов и полным исключением солнечного света при сушке. Такой метод позволяет максимально 
                    сохранить полезные свойства ростков.
                </p>
                <p>
                	Для удобного принятия продукта сухие ростки прессуются в таблетки с формой яйца и массой 0,3 гр. 
                	На изготовление одной таблетки уходит 22 грамма свежих зеленых ростков пшеницы.
                </p>
            </div>
        </div>
    </div>
</section>

<!--Блок ДОСТАВКА-->
<section class="text-block pt-0 mb-30" data-aos="fade-up"
data-aos-easing="linear"
data-aos-duration="200">
    <div class="container">
        <div class="delivery">
            <h2>Оплата и доставка</h2>
            <div class="image d-none d-lg-block">
                <img src="/img/preload.gif" data-src="/img/delivery.jpg" alt="Оплата и доставка" title="Оплата и доставка">
            </div>
            <div class="row d-lg-none">
                <div class="col-sm-6">
                    <img src="/img/preload.gif" data-src="\img\step1.jpg" alt="Выбирайте понравившийся курс" title="Выбирайте понравившийся курс">
                    <span class="title">
                        Выбирайте <br />понравившийся курс
                    </span>
                </div>
                <div class="col-sm-6">
                    <img src="/img/preload.gif" data-src="\img\step2.jpg" alt="Доставка до двери 600 руб." title="Доставка до двери 600 руб.">
                    <span class="title">
                        Доставка до <br />двери 600 руб.
                    </span>
                </div>
                <div class="col-sm-6">
                    <img src="/img/preload.gif" data-src="\img\step3.jpg" alt="До пункта выдачи СДЭК 300 руб." title="До пункта выдачи СДЭК 300 руб.">
                    <span class="title">
                        До пункта <br />выдачи СДЭК <br />300 руб.
                    </span>
                </div>
                <div class="col-sm-6">
                    <img src="/img/preload.gif" data-src="\img\step4.jpg" alt="Начинайте новую жизнь" title="Начинайте новую жизнь">
                    <span class="title">
                        Начинайте <br />новую жизнь
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<?/* Видео */?>
<!--Блок с видео-->
<section class="text-block pt-0" data-aos="fade-left"
data-aos-easing="linear"
data-aos-duration="200">
    <div class="container">
        <!--<h2>О нас в программе “Жить Здорово”</h2>-->
        <h2>О пользе ростков пшеницы</h2>
        <div class="img-ebox ar-custom">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/Vw4YDWo6K9U" 
            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; 
            gyroscope; picture-in-picture" allowfullscreen>
                
            </iframe>
        </div>
    </div>
</section>

<?/*=$this->render('../_blocks/ex_category_tile',['data'=>[
    'product'=>$data['child'],
    'h2'=>'',
    'text1'=>Helpers::phReplace($data['chunk']['text1']['content']),
    'text2'=>Helpers::phReplace($data['chunk']['text2']['content']),
    'nameTpl'=>Helpers::phReplace($data['chunk']['exTile_nameTpl']['content'])
]])*/?>


<!-- Плитка товаров -->
<!--?=$this->render('../_blocks/ex_card_tile',['data'=>[
    'product'=>$data['product'],
    'h2'=>Helpers::phReplace($data['chunk']['exTileh2_1']['content']),
    'text1'=>'',
    'text2'=>'',
    'nameTpl'=>Helpers::phReplace($data['chunk']['exTile_nameTpl']['content'])
]])?-->


<!-- Контент блок 1 -->
<?php /*eval('?>'.Helpers::phReplace($data['chunk']['content_block1']['content']) )*/?>

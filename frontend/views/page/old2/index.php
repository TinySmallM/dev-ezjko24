<?
use common\models\Helpers;
use common\models\Product;
use common\models\PageProduct;
use yii\helpers\Url;


$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description']) ]);
$this->registerMetaTag(['property' => 'og:image', 'content' => Url::home(true).'upload/'.$data['image'] ]);
$this->title = Helpers::phReplace($data['title']);



$this->registerCss('
      @media (max-width:801px) {
        .embed-responsive-1by1::before {
        padding-top: 250px !important;
      }
      .main__img, .main__img_rhombus-1 {

      }
      .main-index .main__summary::before {
        left: 150px;
      }
      .main-index::before {
        display: none;
      }
      #first {
        display: none;
      }
      #second {
        order: 1;
      }
      }
      
      .main {
        padding-bottom: 80px !important;
      }
      .main__img::before, .main__img::after {
       width: 200px !important;
       height: 240px !important;
       right: 120px !important;
       position: absolute;

      }
      @media (min-width:801px) {
        #three {
          display: none;
        }
        .videofix {
          margin-top: 50px;
        }
        .padfix {
          padding-left: 80px;
        }
        .main-index .main__summary::before {
          bottom: -40px !important;
          margin-left: 170px !important;
        }
        .main-index::before {
          top: 60% !important;
        }
        #videocontainer {
            position: relative;
        }
        

        /*
            covers the whole container
            the video itself will actually stretch
            the container to the desired size
        */
        #videocover {
            position: absolute;
            z-index: 1;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
      }
');
?>

<?=$this->render('../_blocks/header',[
	'class'=>$data['chunk']['header_class']['content'],
	'type'=>'index',
])?>

<main>

<?=$this->render('../_blocks/static_banner',[
  'h1'=>$data['h1']
])?>

<?=$this->render('../_blocks/atego_category_tiles',[
  'templateId'=>2,
  'where'=>['AND',['!=','id',39]]
])?>

<?=$this->render('../_blocks/static_banner',[
  'h2'=>'Популярные товары'
])?>


<?=$this->render('../_blocks/atego_product_filter',[
  'where'=>['id'=>39]
])?>



<div style="margin-top: 50px;"></div>

<?/*=$this->render('../_blocks/reviews',[
	'items'=>$data['chunk']['review']['content'],
])?>

<?=$this->render('../_blocks/index_teachers',[
	'teachers'=>$data['chunk']['team']['content'],
])?>

<?=$this->render('../_blocks/index_advantages',[
	'advantages'=>$data['chunk']['advantages']['content'],
])?>

<?=$this->render('../_blocks/index_faq',[
	'items'=>$data['chunk']['faq']['content'],
])*/?>


</main>


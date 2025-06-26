<?
use common\models\Helpers;
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
	'class'=>'navbar_light',
	'type'=>'index',
])?>

<main class="bg_texture_white">

<?=$this->render('../_blocks/static_banner',[
  'h1'=>$data['h1'],
  'text'=>$data['chunk']['text1']['content']
])?>

<?if($data['chunk']['gallery']['content']){?>
  <?=$this->render('../_blocks/subject_themes',[
    'h2'=>$data['chunk']['gallery_h2']['content'],
    'items'=>$data['chunk']['gallery']['content']
  ])?>
<?}?>

<?if($data['chunk']['faq']['content']){?>
  <?=$this->render('../_blocks/qa_spoilers',[
    'h2'=>$data['chunk']['faq_h2']['content'],
    'rows'=>$data['chunk']['faq']['content']
  ])?>
<?}?>

<div class="container page_text" style="margin-top: 40px;">
		<?=$data['chunk']['text2']['content']?>
</div>


<div style="margin-top: 50px;"></div>



</main>


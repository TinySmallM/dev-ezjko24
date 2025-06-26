<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use common\models\ServPredicate;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    
    <script>
    	var payMethod = <?=json_encode(Yii::$app->params['payment'])?>;
    	var orderStatus = <?=json_encode(Yii::$app->params['orderStatus'])?>;
    	var deliveryType = <?=json_encode(Yii::$app->params['delivery'])?>;
    	var orgType = <?=json_encode(Yii::$app->params['orgType'])?>;
    	var pageTpl = <?=json_encode(Yii::$app->params['template'])?>;
      var servPredicate = <?=json_encode(ServPredicate::find()->asArray()->all())?>;
      var productCharacts = <?=json_encode(Yii::$app->params['productCharacts'])?>;
    </script>
    
</head>
<body class="hold-transition sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
  	
  	
    <!-- Left navbar links -->
    
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!--
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
      -->
    </ul>


    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
    	
      <!-- Notifications Dropdown Menu -->
      <!--
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      -->
      
      <li class="nav-item">
        <a class="nav-link" href="/master/logout">
          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li>
      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
    	<!--
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
           -->
      <span class="brand-text font-weight-light" style="font-size: 16px;">SMEDIA CRM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <!--
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div>
      -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
         
          
          <?if( Yii::$app->user->can('page') ){?>

          <li class="nav-header">Контент</li>
          
          <li class="nav-item">
            <a href="/master/page" class="nav-link">
              <ion-icon name="document"></ion-icon>
              <p>Страницы</p>
            </a>
          </li>
          <?}?>
          
          <?if( Yii::$app->user->can('product') ){?>
          <li class="nav-item">
            <a href="/master/product" class="nav-link">
              <ion-icon name="apps"></ion-icon>
              <p>Товары</p>
            </a>
          </li>
          <?}?>
          
          <?/*if( Yii::$app->user->can('page') ){?>
          <li class="nav-item">
            <a href="/master/page?parent=1083" class="nav-link">
              <ion-icon name="flash-outline"></ion-icon>
              <p>Интеграции</p>
            </a>
          </li>
          <?}*/?>

          <?if( Yii::$app->user->can('review') ){?>
          <li class="nav-item">
            <a href="/master/review" class="nav-link">
              <ion-icon name="chatbox-ellipses-outline"></ion-icon>
              <p>Отзывы</p>
            </a>
          </li>
          <?}?>
          
          
          <?/*if( Yii::$app->user->can('news') ){?>
          <li class="nav-item">
            <a href="/master/news" class="nav-link">
              <i class="nav-icon fas fa-newspaper"></i>
              <p>Новости</p>
            </a>
          </li>
          <?}*/?>
          
          <?/*if( Yii::$app->user->can('review') ){?>
          <li class="nav-item">
            <a href="/master/review/all" class="nav-link">
              <i class="nav-icon fas fa-comment-dots"></i>
              <p>Отзывы</p>
            </a>
          </li>
          <?}*/?>

          
          <?/*if( Yii::$app->user->can('member') ){?>
          <li class="nav-header">Платформа</li>
          <li class="nav-item">
            <a href="/master/member" class="nav-link">
              <ion-icon name="people-outline"></ion-icon>
              <p>Пользователи</p>
            </a>
          </li>
          <?}?>
          <?if( Yii::$app->user->can('page') ){?>
          <li class="nav-item">
            <a href="/master/platform/page" class="nav-link">
              <ion-icon name="document"></ion-icon>
              <p>Темы</p>
            </a>
          </li>
          <?}*/?>
          
            
          <?/*
          <li class="nav-header">Магазин</li>
          <?if( Yii::$app->user->can('order') ){?>
          <li class="nav-item">
            <a href="/master/order" class="nav-link">
              <ion-icon name="bag"></ion-icon>
              <p>Заказы</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/master/report/order" class="nav-link">
              <ion-icon name="pie-chart"></ion-icon>
              <p>Отчет по заказам</p>
            </a>
          </li>
          <?}?>
          
          <?if( Yii::$app->user->can('coupon') ){?>
            <li class="nav-item">
            <a href="/master/coupon" class="nav-link">
              <ion-icon name="happy"></ion-icon>
              <p>Промокоды</p>
            </a>
          </li>
          <?}?>
          */?>
          
          
          

          <li class="nav-header">Система</li>
          <li class="nav-item">
            <a href="/master/storage/" class="nav-link">
              <ion-icon name="cloud-upload"></ion-icon>
              <p>Загрузка картинок</p>
            </a>
          </li> 
          
          <?/*if( Yii::$app->user->can('template') ){?>
          <li class="nav-item">
            <a href="/master/template/all" class="nav-link">
              <i class="nav-icon fa-solid fa-sparkles"></i>
              <p>Шаблоны</p>
            </a>
          </li> 
          <?}*/?>
          
          <?/*if( Yii::$app->user->can('region') ){?>
          <li class="nav-item">
            <a href="/master/region" class="nav-link">
              <i class="nav-icon fas fa-globe"></i>
              <p>Регионы</p>
            </a>
          </li> 
          <?}*/?>
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Main content -->
    <div class="content">
    	<div class="container-fluid pt-3 pb-5">
    		<?= $content ?>
    	</div>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2019 <a href="#">SM PANEL</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.3
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

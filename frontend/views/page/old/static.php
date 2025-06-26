<?

use common\models\Helpers;

$this->registerMetaTag(['name' => 'description', 'content' => Helpers::phReplace($data['description'])]);
$this->registerMetaTag(['property' => 'og:description', 'content' => Helpers::phReplace($data['description'])]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => Helpers::phReplace($data['description'])]);
$this->title = Helpers::phReplace($data['title']);


?>

<section id="pageheader">
	<div class="container">
		<h1><?=$this->title;?></h1>
	</div>
</section>
<div class="container">
  <div class="row">
    <div class="col-12">
      <?php eval('?>'.Helpers::phReplace($data['content']));?>
    </div>
  </div>
</div>

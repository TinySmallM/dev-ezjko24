<?
$this->title = 'Страницы';

use backend\assets\AppAssetAce;
AppAssetAce::register($this);

$this->registerCss('
ul.tree-menu, ul.tree-menu ul.child{
	list-style-type: none;
    padding: 0;
}
ul.tree-menu ul.child{
	padding-left: 21px;
}
.tree-menu i{
	cursor: pointer;
}
.tree-menu i.folder{
	color: #f7ce53;
}
.tree-menu a{
	color: #333;
}
.tree-menu a:hover, .tree-menu a.active{
	color: #16a3b8;
}
label{
	margin: 0;
}
.cardTabs{
	border: none;
    display: inline-block;
}
.cardTabs .nav-item{
	display: inline-block;
	margin-bottom: -10px;
}
.return_editor {
    position: absolute;
    width: 30px;
    height: 30px;
    z-index: 9999;
    right: 30px;
    color: #fff;
}
.plusClick{
	float: right;
    margin-top: 3px;
    cursor: pointer;
}
');
?>

<div class="pageApploader" id="pageApp" style="display: none"  @keydown.ctrl.83.prevent.stop="save">
	
		<div class="row">
			<div class="col-12">
				<h1 class="title">Загрузка картинок</h1>
			</div>
			
			<!-- Tree view -->
			<div class="col-12">
				
				<image-upload :watermark="0" :src="image" v-on:update-src="image = $event"></image-upload>
				
				<label class="mt-3">Основное</label>
				<input type="text" :value="image">
				
				<label class="mt-2">Уменьшенное</label>
				<input type="text" :value="'thumb_'+image">

			</div>
		</div>
		
		
		
	</div>

<script>

let app = new Vue({
	el: '#pageApp',
	data: {
		image: null,
	},
	created: function(){
		$('.pageApploader').show();
	},
	methods: {
	}
});

</script>
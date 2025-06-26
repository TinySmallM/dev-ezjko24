<?
$this->title = 'Новости';

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
	font-size: 14px;
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
.tplChunkAll{
	margin-left: 50px;
    border-left: 1px solid #ced4d9;
    padding-left: 10px;
}
');
?>

<div class="pageApploader" id="pageApp" style="display: none"  @keydown.ctrl.83.prevent.stop="save">
	
		<div class="row">
			<div class="col-12">
				<h1 class="title">Шаблоны</h1>
			</div>
			
			<!-- Tree view -->
			<div class="col-12 col-md-3">
				<div class="card">
					<div class="card-header">
				    	<h3 class="card-title">Выберите нужный</h3>
				    	<i class="fas fa-plus plusClick" @click="itemAdd"></i>
					</div>
					<div class="card-body">
						<ul class="tree-menu">
							<li v-for="(page, i) in pages">
								
								<template>
									<i class="fas fa-file"></i>
									<a href="javascript:void(0)" @click="editor(page.id);" :class="treeActive(page.id)">{{page.name}}</a>
								</template>
								
							</li>
							
						</ul>					    				
					</div>
				</div>				

			</div>
			
			<!-- Editor -->
			<div class="col-12 col-md-9">
				<template v-if="Object.keys(item).length">
					
					<div class="card">
						<div class="card-header">
							
							<ul class="nav nav-tabs cardTabs"role="tablist">
			                  <li class="nav-item">
			                    <a class="nav-link active" data-toggle="pill" href="#editorTab-MAIN" role="tab" aria-selected="true">Основное</a>
			                  </li>
			                </ul>
					    	
					    	<div class="btns float-right">
					    		<a class="btn btn-sm btn-info" :href="'/news/'+item.id+'/'" target="_blank" title="Открыть страницу" v-if="item.id != 0">
					    			<i class="fas fa-external-link-alt"></i>
					    		</a>
					    		
					    		<button class="btn btn-sm btn-secondary" @click="save()" title="Сохранить (Ctrl+S)">
					    			<i class="far fa-save"></i>
					    		</button>
					    	</div>
						</div>
						<div class="card-body">
							
							<div class="tab-content" id="editorTab">
								
								<!-- Main -->
		                		<div class="tab-pane fade active show" id="editorTab-MAIN" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
                					
                					<h5 style="font-weight:600">Шаблон</h5>
									<div style="margin-left: 50px;">
					                	<div>
											<label>Название</label>
											<input class="form-control" v-model="item.name">
										</div>	
										<div class="mt-2">
											<label>Файл: /frontend/views/page/{name}.php</label>
											<input class="form-control" v-model="item.file">
										</div>
										<div class="mt-2">
											<label>Описание</label>
											<textarea v-model="item.description" class="form-control" stle="width:100%;max-width:100%;"></textarea>
										</div>
									</div>
									
									<h5 style="font-weight:600;margin-top: 30px;">Блоки шаблона</h5>
									<div class="mt-2" v-for="tplBlock in item.block">
										<label style="border-bottom: 1px solid #ced4d8;">{{tplBlock.name}}</label>
										<div style="margin-left: 50px;" class="tplChunkAll">
											<label>– HTML код</label>
											<textarea v-model="tplBlock.content" class="form-control" stle="width:100%;max-width:100%;"></textarea>
											
											<label>– Чанки</label>
											<textarea v-model="tplBlock.chunk" class="form-control" stle="width:100%;max-width:100%;"></textarea>
										</div>
									</div>

		                		</div>
		                	</div>
			
						</div>
					</div>					

				</template>
			</div>
			
		</div>
		
		
		
	</div>

<script>

let app = new Vue({
	el: '#pageApp',
	data: {
		item: {},
		pages: {},
	},
	created: function(){
		this.getItems(true);
		$('.pageApploader').show();
	},
	methods: {
		getItems: function(first=false){
			let app = this;
			
			axios.post('/master/template/api/?type=getAll', {})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.items){
					app.pages = res.items;
					
					//Если первый запуск
					if(first){
						app.editor(res.items[0].id);
						$('ul.tree-menu li a:first-child').addClass('active');
					}
			    }
			    else {
			    	Toast.fire({
					  icon: 'error',
					  title: 'Что-то не так'
					})
			    }
			    
			})
			
		},
		editor: function(id){
			let app = this;
			
			axios.post('/master/template/api/?type=get', {id: id})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.item){
			    	res.item.dateCreated = moment.unix(res.item.dateCreated).format('YYYY-MM-DD');
					app.item = res.item;
			    }
			    else {
			    	Toast.fire({
					  icon: 'error',
					  title: 'Что-то не так'
					})
			    }
			    
			})
			
		},
		save: function(){
			let app = this;
			
			let data = {};
			for (let key of Object.keys(app.item)) {
			  data[key] = app.item[key];
			}
			data.dateCreated = moment(data.dateCreated).unix();
			
			axios.post('/master/template/api/?type=save', data)
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(!res.success){
					Toast.fire({
					  icon: 'error',
					  title: 'Что-то не так'
					})
			    }
			    else{
			    	Toast.fire({
					  icon: 'success',
					  title: 'Сохранено'
					})
					app.getItems();
			    }
			   
			})
			
			
		},
		treeActive: function(id){
			let app = this;
			if(app.item && app.item.id == id) return 'active';
		},
		itemAdd: function(){
			app.item = {
				"id":"0",
				"name":null,
				"file":null,
				"description":null
			}
		}
	}
});

</script>
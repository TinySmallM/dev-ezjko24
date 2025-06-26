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
				<h1 class="title">Отзывы</h1>
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
									<a href="javascript:void(0)" @click="editor(page.id);" :class="treeActive(page.id)">{{treeName(page)}}</a>
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
									<!-- Data -->
									<div class="row">
										<div class="col-md-8">
											<div>
												<label>ФИО</label>
												<input class="form-control" v-model="item.fio">
											</div>	
											<div class="mt-2">
												<label>Договор</label>
												<input class="form-control" v-model="item.doc">
											</div>
											<div class="mt-2">
												<label>Отзыв</label>
												<textarea v-model="item.text" class="form-control" stle="width:100%;max-width:100%;"></textarea>
											</div>

											<div class="mt-2">
												<image-gallery :items="item.image" v-on:update-gallery="item.image = $event"></image-gallery>	
											</div>
										</div>
										<div class="col-md-4">
											<div class="mt-2">
												<label for="isPublCheck">Дата</label>
												<input type="date" v-model="item.dateCreated" class="form-control">
											</div>
											<div>
												<label>Оценка</label>
												<select v-model="item.stars" class="form-control">
													<option v-for="i in 5" :value="i">Оценка – {{ i }}</option>
												</select>
											</div>
											<div class="mt-2">
												<label>Регион</label>
												<select v-model="item.regionId" class="form-control">
													<option :value="null">Не выбран</option>
													<option :value="1">Москва</option>
													<option :value="2">Волгоград</option>
												</select>
											</div>
											<div class="mt-2">
												<input type="checkbox" v-model="item.isPublished" true-value="1" false-value="0" id="isPublCheck">
												<label for="isPublCheck">Опубликован</label>
											</div>
										</div>

										<!--
										<div class="col-12 mt-3">
											<label>Галерея</label>
											<image-gallery :items="item.image" v-on:update-gallery="item.image = $event"></image-gallery>
										</div>	
										-->
										<?/*
										<div class="col-12 col-md-4">
												<div class="mt-2">
													<input type="checkbox" v-model="item.isPublished">
													<label>Опубликована</label>
												</div>
												<div class="mt-2">
													
								    				<span v-if="item.id != 0">ID новости: #{{item.id}}</span>
												</div>
											
										</div>
										
										
										<!-- Image -->
										<div class="col-12 mt-3">
											<div class="row">
												<div class="col-12 col-md-2">
													<label>Картинка</label>
													<image-upload :src="item.image" v-on:update-src="item.image = $event"></image-upload>
												</div>
											</div>											
										</div>										
										
										<!-- content -->
										<div class="col-12 mt-3">
											<hr>
											<label>Контент</label>
											<!--<i class="fas zoom" :class="zoomClass ? 'fa-search-minus' : 'fa-search-plus'" @click="zoom" style="cursor:pointer"></i>-->
											<div id="aceEditor" :style="zoomClass"></div>
										</div>
										*/?>
										
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
			
			axios.post('/master/review/api/?type=getAll', {})
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
			
			axios.post('/master/review/api/?type=get', {id: id})
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
			
			axios.post('/master/review/api/?type=save', data)
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
		treeName: function(item){
			let name = '';
			if(item.fio != '') name += item.fio;
			if(item.doc) name += ' ('+item.doc+')';
			return name;
		},
		itemAdd: function(){
			app.item = {
				"id":"0",
				"stars":5,
				"dateCreated":null,
				"isPublished":null,
				"regionId":null,
				"image":[],
				"doc":null,
				"text":null,
				"fio":null,
				"productId":null,
				
			}
		}
	}
});

</script>
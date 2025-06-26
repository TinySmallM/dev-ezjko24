<?
$this->title = 'Товары';

use backend\assets\AppAssetAce;
AppAssetAce::register($this);

$this->registerCss('
ul.tree-menu{
	max-height: 600px;
	overflow-y: scroll;
}
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
				<h1 class="title">Товары</h1>
			</div>
			
			<!-- Tree view -->
			<div class="col-12 col-md-3">
				<div class="card">
					<div class="card-header">
				    	<h3 class="card-title">Выберите нужный</h3>
				    	<i class="fas fa-plus plusClick" @click="createNew()"></i>
					</div>
					<div class="card-body">
						<div class="input-group input-group-sm mb-3">
					        <input type="text" v-model="query" class="form-control">
					    </div>

						
						<ul class="tree-menu" v-if="!query">
							<li v-for="(page, i) in pages">
								
								<template v-if="Object.keys(page.product).length">
									<i class="folder fa fa-folder" onclick="$(this).siblings('.child').toggle()"></i>
									<a href="javascript:void(0)" @click="editor(page.id);" :class="treeActive(page.id)">{{treeName(page)}}</a>
								</template>
								<template v-else>
									<i class="fas fa-file"></i>
									<a href="javascript:void(0)" @click="editor(page.id);" :class="treeActive(page.id)">{{treeName(page)}}</a>
								</template>

								<ul class="child" style="display: none" v-if="page.product">
									<draggable v-model="page.product" :group="'page_product_'+page.id" @start="drag=true" @end="drag=false;saveSort(page.id,page.product)">
										<li v-for="(child, b) in page.product">
											<i class="fas fa-file"></i>
											<a href="javascript:void(0)" @click="editor(child.id,this)" :class="treeActive(child.id)">{{treeName(child)}}</a>
										</li>
									</draggable>
								</ul>

							</li>
							
						</ul>	

						<ul class="tree-menu" v-else>
							<li v-for="(child, b) in search_items">
								<i class="fas fa-file"></i>
								<a href="javascript:void(0)" @click="editor(child.id,this)" :class="treeActive(child.id)">{{treeName(child)}}</a>
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
			                  <li class="nav-item">
			                    <a class="nav-link" data-toggle="pill" href="#editorTab-TV" role="tab" aria-selected="false">TV поля</a>
			                  </li>
			                  <li class="nav-item">
			                    <a class="nav-link" data-toggle="pill" href="#editorTab-category" role="tab" aria-selected="false">Категории</a>
			                  </li>
							  <li class="nav-item">
			                    <a class="nav-link" data-toggle="pill" href="#editorTab-similar" role="tab" aria-selected="false">Сопутствующие</a>
			                  </li>
			                </ul>
					    	
					    	<div class="btns float-right">
					    		<a class="btn btn-sm btn-info" :href="'/'+item.url+'/'" target="_blank" title="Открыть страницу" v-if="item.url">
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
										<div class="col-12 col-md-8">
											
											<!-- Информация -->
											<div>
												<label>Title</label>
												<input class="form-control" v-model="item.title">
											</div>
											
											<div class="mt-2">
												<label>H1</label>
												<input class="form-control" v-model="item.h1">
											</div>
											
											<div class="mt-2">
												<label>Description</label>
												<textarea v-model="item.description" class="form-control" rows="3" style="width:100%;max-width:100%;"></textarea>
											</div>
											
											<!-- Цены -->
											<div class="row mt-3">
												<div class="col-12 col-md-3">
													<label>Цена 1 (сумма)</label>
													<input class="form-control" v-model="item.price1_sum" required>
												</div>
												<div class="col-12 col-md-3">
													<label>Цена 1 (ед)</label>
													<input class="form-control" v-model="item.price1_name" required>
												</div>
												<div class="col-12 col-md-3">
													<label>Цена 2 (сумма)</label>
													<input class="form-control" v-model="item.price2_sum">
												</div>
												<div class="col-12 col-md-3">
													<label>Цена 2 (ед)</label>
													<input class="form-control" v-model="item.price2_name">
												</div>
											</div>											
											
										</div>
										<div class="col-12 col-md-4">
												<div>
													<label>URL <span @click="newUrl()" style="cursor:pointer">(обновить)</span></label>
													<input class="form-control" v-model="item.url">
												</div>

												<div>
													<label>Menuname</label>
													<input class="form-control" v-model="item.menuname">
												</div>
												
												<?if(Yii::$app->user->can('admin')){?>
												<div class="mt-2">
													<label>Шаблон</label>
													<select-template :id="item.template" v-on:update-template="item.template = $event"></select-template>	
												</div>
												<?}?>
												
												<div class="mt-2">
													<input type="checkbox" v-model="item.menushow" true-value="1"  false-value="0">
													<label>Показывать в меню</label>
												</div>
												<div class="mt-2">
													<input type="checkbox" v-model="item.published" true-value="1"  false-value="0">
													<label>Опубликована</label>
												</div>
												<hr>
												
												
												<div>
													<label>Артикул 1</label>
													<input class="form-control" v-model="item.artikul1">
												</div>

												<div>
													<label>Артикул 2</label>
													<input class="form-control" v-model="item.artikul2">
												</div>
												<!--
												<div v-if="item.artikul"><span>Артикул: {{item.artikul}}</span></div>
												
												
												
												
												<div v-if="item.artikul"><span>Артикул: {{item.artikul}}</span></div>
												<div v-if="item.nds"><span>Ставка НДС: {{item.nds}}%</span></div>
												-->

												<div class="mt-2" v-if="item.id != 0"><span>ID товара: #{{item.id}}</span></div>
											
										</div>	

										<!-- Image -->
										<div class="col-12 mt-3" v-if="pageTpl[item.template].mainImageHandUpload">
											<div class="row">
												<div class="col-12 col-md-2">
													<label>Картинка</label>
													<image-upload :watermark="0" :src="item.image" v-on:update-src="item.image = $event"></image-upload>
												</div>
											</div>											
										</div>	

										<div class="col-12 mt-3">
											<label>Галерея</label>
											<image-gallery :items="item.gallery" v-on:update-gallery="item.gallery = $event"></image-gallery>
										</div>								
										
										<!-- content -->
										<div class="col-12 mt-3">
											<hr>
											<label>Контент</label>
											<!--<i class="fas zoom" :class="zoomClass ? 'fa-search-minus' : 'fa-search-plus'" @click="zoom" style="cursor:pointer"></i>-->
											<div id="aceEditor" :style="zoomClass"></div>
										</div>
									</div>			                      
		                		</div>
		                		
		                		<!-- TV -->
		                		<div class="tab-pane fade" id="editorTab-TV" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
									
									
									<?/*
									<!-- Data -->
									<div class="row">
										
										<div class="col-12 mb-3" v-for="(ch,i) in pageTpl[item.template].chunk">
											<label>{{ch.name}}</label>
											
											<div class="chunk_content" :class="ch.type" :id="'chunk_'+i" v-if="ch.type == 'ace'"></div>
											<textarea class="form-control" :id="'chunk_'+i" v-if="ch.type == 'tinymce'" rows="5" style="width: 100%;max-width:100%;"></textarea>
											<tv-services v-if="ch.type=='services'" :content="item.chunk[i].content" :predicate="servPredicate" v-on:update-chunk="item.chunk[i].content = $event" v-on:update-predicate="servPredicate = $event"></tv-services>

											<!-- Картинка -->
											<div class="row" v-if="ch.type == 'image'">
												<div class="col-12 col-md-2">
													<image-upload :watermark="0" :src="item.chunk[i].content" v-on:update-src="item.chunk[i].content = $event"></image-upload>
												</div>
											</div>

											<!-- Текстовое поле -->
											<input type="text" class="form-control" v-model="item.chunk[i].content" v-if="ch.type == 'text'">

											<!-- Дата -->
											<input type="date" class="form-control" v-model="item.chunk[i].content" v-if="ch.type == 'date'">

											<!-- Галерея -->
											<image-gallery :items="item.chunk[i].content" v-on:update-gallery="item.chunk[i].content = $event" v-if="ch.type == 'gallery'"></image-gallery>	

											<!-- Похожие товары -->
											<similar-product :items="item.chunk[i].content" v-on:update-similar="item.chunk[i].content = $event" v-if="ch.type == 'similar_product'"></similar-product>	
											
											<!-- Таблица -->
						                    <tv-table 
						                      :content="item.chunk[i].content" 
						                      :chunk="ch"
						                      v-on:update-content="item.chunk[i].content = $event"
						                      v-if="ch.type == 'table'"
						                    ></tv-table>
										</div>
										
									</div>	
									*/?>
									
										<div v-for="(block,block_name) in pageTpl[item.template].block" class="tv-block">
											<h5 class="tv-block-header">{{block.name}}</h5>
											<template v-for="(ch,i) in pageTpl[item.template].chunk">
												<div class="col-12 mb-3" v-if="ch.block == block_name" style="padding-left: 50px">
												<label>{{ch.name}}</label>
												
												<div class="chunk_content" :class="ch.type" :id="'chunk_'+i" v-if="ch.type == 'ace'"></div>
												<textarea class="form-control" :id="'chunk_'+i" v-if="ch.type == 'tinymce'" rows="5" style="width: 100%;max-width:100%;"></textarea>
												
												<!-- Картинка -->
												<div class="row" v-if="ch.type == 'image'">
													<div class="col-12 col-md-2">
														<image-upload :watermark="0" :src="item.chunk[i].content" v-on:update-src="item.chunk[i].content = $event"></image-upload>
													</div>
												</div>
	
												<!-- Текстовое поле -->
												<input type="text" class="form-control" v-model="item.chunk[i].content" v-if="ch.type == 'text'">
	
												<!-- Текстовая область -->
												<textarea class="form-control" v-if="ch.type == 'textarea'" rows="3" style="width: 100%;max-width:100%;" v-model="item.chunk[i].content"></textarea>

												<!-- Дата -->
												<input type="date" class="form-control" v-model="item.chunk[i].content" v-if="ch.type == 'date'">
												
												<image-gallery :items="item.chunk[i].content" v-on:update-gallery="item.chunk[i].content = $event" v-if="ch.type == 'gallery'"></image-gallery>	
												
												<!-- Таблица -->
							                    <tv-table 
							                      :content="item.chunk[i].content" 
							                      :chunk="ch"
							                      v-on:update-content="item.chunk[i].content = $event"
							                      v-if="ch.type == 'table'"
							                    ></tv-table>
											</div>
											</template>
										</div>
		                		</div>
		                		
		                		<!-- Category -->
		                		<div class="tab-pane fade" id="editorTab-category" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
									<template v-for="(cat,i) in pages">
										<div v-if="cat.id != 0">
										<input type="checkbox" v-model="item.page" :value="cat.id" :id="'cat_'+cat.id">
										<label :for="'cat_'+cat.id">{{cat.menuname}}</label> <a :href="'/'+cat.url+'/'" target="_blank" style="color: #333;">(открыть)</a>
										</div>
									</template>		                      
		                		</div>

								<!-- Similar -->
		                		<div class="tab-pane fade" id="editorTab-similar" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
									<similar-product :items="item.chunk['similar_product'].content" v-on:update-similar="item.chunk['similar_product'].content = $event" v-if="item.chunk['similar_product']"></similar-product>	                  
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
		codeEditor: {},
		zoomClass: null,
		fileUrl: null,
		pageTpl: pageTpl,
		servPredicate: servPredicate,
		query: null,
		search_items: null
	},
	created: function(){
		this.getItems(true);
		$('.pageApploader').show();
	},
	updated: function(){
		//this.updateCodeEditor();
	},
	watch: {
		item: {
			handler(val){
				if(this.item.gallery.length && !this.pageTpl[this.item.template].mainImageHandUpload){
					this.item.image = this.item.gallery[0].image; //Если не false, то первая фотка из галереи будет основной, а поле для загрузки не будет отображаться
				} 
			},
			deep: true
		},
		query: function(val){
	        this.getSearch(val);
	    }
	},
	methods: {
		saveSort: function(pageId,items){
			let ids = [];
			for(i in items){
				ids.push(items[i].id);
			}

			axios.post('/master/product/api/?type=saveSort', {'pageId':pageId,ids: ids})
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
					  title: 'Сортировка сохранена'
					})
			    }
			   
			})

		},
		getItems: function(first=false,query=null){
			let app = this;

			let data = {};
			
			axios.post('/master/product/api/?type=getAll', data)
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.items){

					app.pages = res.items;
					
					//Если первый запуск
					if(first){
						app.editor(res.items[0]['product'][0].id);
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
		getSearch: function(query=null){
			let app = this;

			let data = {};
			if(query) data.query = query;
			
			axios.post('/master/product/api/?type=getSearch', data)
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.items){

					app.search_items = res.items;
					
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
			
			axios.post('/master/product/api/?type=get', {id: id})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.item){
					app.item = res.item;
					
					setTimeout(function(){
						app.updateCodeEditor();
					}, 300)
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
			
			app.item.content = app.codeEditor.content.getData();
			
			
			for(key in app.item.chunk){
				
				
				
				if(app.item.chunk[key].type == 'tinymce' && app.codeEditor[key]){
					app.item.chunk[key].content = app.codeEditor[key].getData();
				}
				
				if(app.item.chunk[key].type == 'ace' && app.codeEditor[key]){
					app.item.chunk[key].content = app.codeEditor[key].getValue();
				}
				
			}
			
			axios.post('/master/product/api/?type=save', app.item)
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
					app.editor(app.item.id)
			    }
			   
			})
			
			
		},
		treeActive: function(id){
			let app = this;
			if(app.item && app.item.id == id) return 'active';
		},
		treeName: function(item){
			if(item.menuname != '') return item.menuname;
			return item.h1;
		},
		updateCodeEditor: function(){
			let app = this;
			
			for(name in CKEDITOR.instances)
			{
			    CKEDITOR.instances[name].destroy(true);
			}
			
			if( Object.keys(app.item).length ){
				
				//Редактор контента
				app.codeEditor.content = CKEDITOR.replace('aceEditor',CkOptions);
    			CKFinder.setupCKEditor(app.codeEditor.content,'../');
    			app.codeEditor.content.setData(app.item.content);
				
				//Редактор TV
				let chunks = app.item.chunk;
				
				for(key in this.item.chunk){
					
			    	let chunk = this.item.chunk[key];
			    	
			    	if(chunk.type == 'ace'){
			    		if(app.codeEditor[key]) app.codeEditor[key].setValue(chunk.content ? chunk.content : '');
						else app.codeEditor[key] = ace.edit('chunk_'+key, {
					        theme: "ace/theme/monokai",
					        mode: "ace/mode/php",
					        minLines: 10,
					        maxLines: 50,
					        wrap: true,
					        autoScrollEditorIntoView: true,
					        tabSize: 4,
					        showPrintMargin: false,
					        useSoftTabs: true,
					        fontSize: '15px',
					        enableEmmet: true,
					        value: chunk.content ? chunk.content : ''
					    });	    		
			    	}
			    	
			    	if(chunk.type == 'tinymce'){

		    			app.codeEditor[key] = null;
		    			app.codeEditor[key] = CKEDITOR.replace('chunk_'+key,CkOptions);
		    			CKFinder.setupCKEditor(app.codeEditor[key],'../');
		    			app.codeEditor[key].setData(chunk.content);
				    	
			    	}
			    }
			}			
		},
		newUrl: function(){
			let app = this;
			axios.post('/master/product/api/?type=getUrl', {id: app.item.id,title:app.item.title})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(!res.url){
					Toast.fire({
					  icon: 'error',
					  title: 'Что-то не так'
					})
			    }
			    else{
					app.item.url = res.url;
			    }
			   
			})			
		},
		createNew: function(){
			let app = this;
			axios.post('/master/product/api/?type=createNew', {})
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
					  title: 'Создано'
					})
					app.getItems();
					app.editor(res.id)
			    }
			   
			})
		},
	}
});

</script>
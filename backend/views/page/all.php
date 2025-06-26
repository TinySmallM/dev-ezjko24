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
.characts_fields_add_form{
	max-width: 300px;
    padding: 15px;
    border: 1px solid rgb(206 212 218);
    border-radius: 10px;
}
.characts_fields_add_form .f-input{
	margin-bottom: 10px;
}
');
?>

<div class="pageApploader" id="pageApp" style="display: none"  @keydown.ctrl.83.prevent.stop="save">
	
		<div class="row">
			<div class="col-12">
				<h1 class="title">Страницы</h1>
			</div>
			
			<!-- Tree view -->
			<div class="col-12 col-md-4">
				<div class="card" style="max-height: 600px;overflow-y: scroll;">
					<div class="card-header">
				    	<h3 class="card-title">Выберите нужную</h3>
						<i class="fas fa-plus plusClick" @click="createNew()"></i>
					</div>
					<div class="card-body">
						<ul class="tree-menu">
							<li v-for="(page, i) in pages">

								<a href="javascript:void(0)" @click="editor(page.id);" :class="treeActive(page.id)">
									<i class="fas fa-file"></i>
									{{treeName(page)}}
								</a>

	
								<div v-for="(page_2, b) in page.child" style="padding-left: 20px;">
									<a href="javascript:void(0)" @click="editor(page_2.id);" :class="treeActive(page_2.id)">
										<i class="fas fa-file"></i>
										{{treeName(page_2)}}
									</a>
									
									<div v-for="(page_3, c) in page_2.child" style="padding-left: 40px;">

										<a href="javascript:void(0)" @click="editor(page_3.id);" :class="treeActive(page_3.id)">
											<i class="fas fa-file"></i>
											{{treeName(page_3)}}
										</a>
										

									</div>
									
								</div>
	
								<!--<template v-if="Object.keys(page.child).length">
									<i class="folder fa fa-folder" onclick="$(this).siblings('.child').toggle()"></i>
									<a href="javascript:void(0)" @click="editor(page.id);" :class="treeActive(page.id)">{{treeName(page)}}</a>
								</template>
								<template v-else>
									<i class="fas fa-file"></i>
									<a href="javascript:void(0)" @click="editor(page.id);" :class="treeActive(page.id)">{{treeName(page)}}</a>
								</template>
								
								
								<ul class="child" style="display: none" v-if="page.child">
									<draggable v-model="page.child" :group="'page_child_'+page.id" @start="drag=true" @end="drag=false;saveSort(page.id,page.child)">
										<li v-for="(child, b) in page.child">
											<i class="fas fa-file"></i>
											<a href="javascript:void(0)" @click="editor(child.id,this)" :class="treeActive(child.id)">{{treeName(child)}}</a>
										</li>
									</draggable>
									
								</ul>
								-->
							</li>
							
						</ul>					    				
					</div>
				</div>				

			</div>
			
			<!-- Editor -->
			<div class="col-12 col-md-8">
				<template v-if="item">
					
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
			                    <a class="nav-link" data-toggle="pill" href="#editorTab-characts" role="tab" aria-selected="false">Характеристики</a>
			                  </li>
			                </ul>
					    	
					    	<div class="btns float-right">
					    		<a class="btn btn-sm btn-info" :href="'/'+item.url+'/'" target="_blank" title="Открыть страницу">
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
										</div>
										<div class="col-12 col-md-4">
												<?if(Yii::$app->user->can('admin')){?>
												<div>
													<label>URL <span @click="newUrl()">(обновить)</span></label>
													<input class="form-control" v-model="item.url">
												</div>
												<?}?>

												<div>
													<label>Menuname</label>
													<input class="form-control" v-model="item.menuname">
												</div>
												
												<?if(Yii::$app->user->can('page')){?>
												<div class="mt-2">
													<label>Шаблон</label>
													<select-template :id="item.template" v-on:update-template="item.template = $event"></select-template>
												</div>
												<?}?>
												
												<?if(Yii::$app->user->can('page')){?>
												<div class="mt-2">
													<label>Родитель</label>
													<!--
													<select2 :options="parentSelect" v-on:input="item.parent = $event" :value="item.parent" style="width:100%;margin-bottom:10px;"></select2>												
													-->
													<select-parent :id="item.parent" :pages="pages" v-on:update-parent="item.parent = $event"></select-parent>
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
												<div class="mt-2">
													
								    				<span>ID страницы: #{{item.id}}</span>
												</div>
											
										</div>
										
										
										<!-- Image -->
										<div class="col-12 mt-3">
											<div class="row">
												<div class="col-12 col-md-2">
													<label>Картинка</label>
													<image-upload :watermark="0" :src="item.image" v-on:update-src="item.image = $event"></image-upload>
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
									</div>			                      
		                		</div>
		                		
		                		<!-- TV -->
		                		<div class="tab-pane fade" id="editorTab-TV" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">

										
										<div v-for="(block,block_name) in pageTpl[item.template].block" class="tv-block">
											<h5 class="tv-block-header">{{block.name}}</h5>
											
											<template v-for="(ch,i) in pageTpl[item.template].chunk">
												<div class="col-12 mb-3" v-if="ch.block == block_name" style="padding-left: 50px">
													<label>{{ch.name}}</label>
												
													<button class="btn btn-sm" @click="fillFromApi(i)">Заполнить из API</button>
												
												
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
													<textarea class="form-control" v-model="item.chunk[i].content" v-if="ch.type == 'textarea'" rows="3" style="width: 100%;max-width:100%;"></textarea>
		
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

								<!-- Characts -->
		                		<div class="tab-pane fade" id="editorTab-characts" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">
									
									<div class="characts_fields_add_form">
										<b>Добавить поле</b>

										<div class="f-input">
											<label>Группа</label>
											<input type="text" class="form-control" v-model="characts_add__groupName">
										</div>
													
										<div class="f-input">
											<label>Название</label>
											<input type="text" class="form-control" v-model="characts_add__name">
										</div>

										<div class="f-input">
											<label>Тип</label>
											<select class="form-control" v-model="characts_add__type">
												<option :value="'text'">Текст</option>
												<option :value="'number'">Число</option>
												<option :value="'select'">Выбор из списка</option>
											</select>
										</div>

										<div class="f-input">
											<label>Значение по-умолчанию</label>
											<input type="text" class="form-control" v-model="characts_add__defaultVal">
										</div>

										<div class="f-input" v-if="characts_add__type == 'select'">
											<label>Варианты значений</label>
											<p>Каждое значение на новой строке.</p>
											<textarea class="form-control" rows="3" v-model="characts_add__options"></textarea>
										</div>

										<button class="btn btn-sm btn-secondary" @click="charactsAdd()">Добавить</button>
									</div>

									<div class="characts_fields_list" style="margin-top: 30px;">
										<b>Текущие поля</b>
										<p v-if="item.charactsfields.length == 0">Характеристики не добавлены.</p>
										<table class="table table-stripped table-bordered">
											<thead>
												<tr>
													<td>Группа</td>
													<td>Название</td>
													<td>Тип</td>
													<td>По-умолчанию</td>
													<td>Возм. значения</td>
													<td></td>
												</tr>
											</thead>
											<tbody>
												<tr v-for="(ch_item,ch_key) in item.charactsfields">
													<td>
														<input type="text" class="form-control" v-model="ch_item.groupName" placeholder="Не указано">
													</td>
													<td>
														<input type="text" class="form-control" v-model="ch_item.nameRu" placeholder="Не указано">
														<div><small>ID: {{ch_item.id}}, name: {{ch_item.name}}</small></div>
													</td>
													<td>
														<select class="form-control" v-model="ch_item.type">
															<option :value="'text'">Строка</option>
															<option :value="'number'">Число</option>
															<option :value="'select'">Выбор из списка</option>
														</select>
													</td>
													<td>
														<input type="text" class="form-control" v-model="ch_item.defaultVal" placeholder="Не указано">
													</td>
													<td>
														<textarea class="form-control" rows="3" v-model="ch_item.options" :disabled="ch_item.type!='select'?true:false"></textarea>
													</td>
													<td>
														<button class="btn btn-sm btn-secondary" @click="charactsSave(ch_item)">Save</button>
														<button class="btn btn-sm btn-danger" @click="charactsRemove(ch_item)">x</button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
												
		                		</div>
		                	</div>
			
						</div>
						<div class="card-footer">
							<div class="btns float-right">
					    		<a class="btn btn-sm btn-info" :href="'/'+item.url+'/'" target="_blank" title="Открыть страницу">
					    			<i class="fas fa-external-link-alt"></i>
					    		</a>
					    		
					    		<button class="btn btn-sm btn-secondary" @click="save()" title="Сохранить (Ctrl+S)">
					    			<i class="far fa-save"></i>
					    		</button>
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
		item: null,
		pages: {},
		codeEditor: {},
		zoomClass: null,
		fileUrl: null,
		pageTpl: pageTpl,
		parentSelect: null,

		characts_add__name: null,
		characts_add__groupName: null,
		characts_add__type: null,
		characts_add__defaultVal: null,
		characts_add__options: null,
	},
	created: function(){
		this.getItems(true);
		$('.pageApploader').show();
	},
	updated: function(){
		//this.updateCodeEditor();
	},
	methods: {
		saveSort: function(pageId,items){
			let ids = [];
			for(i in items){
				ids.push(items[i].id);
			}

			axios.post('/master/page/api/?type=saveSort', {'parent':pageId,ids: ids})
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
		getItems: function(first=false){
			let app = this;

			let params = new Proxy(new URLSearchParams(window.location.search), {
				get: (searchParams, prop) => searchParams.get(prop),
			});

			let where = {};
			if(params.parent) where.parent = params.parent;
			
			axios.post('/master/page/api/?type=getAll', {where:where})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.items){
					app.pages = res.items;
					
					//Если первый запуск
					if(first){
						//app.editor(res.items[0].id);
						//$('ul.tree-menu li a:first-child').addClass('active');
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
			
			axios.post('/master/page/api/?type=get', {id: id})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.item){
					app.item = res.item;
					//app.getParentsSel();
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
		charactsSave: function(data){
			let app = this;
			
			axios.post('/master/page/api/?type=charactsSave', data)
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.success){
					Toast.fire({
					  icon: 'success',
					  title: 'Сохранено!'
					})

			    }
			    else {
			    	Toast.fire({
					  icon: 'error',
					  title: res.error
					})
			    }
			    
			})
		},
		charactsRemove: function(data){
			let app = this;

			if( !window.confirm('Вы уверены, что хотите удалить поле?') ) {
				return;
			}
			
			axios.post('/master/page/api/?type=charactsRemove', data)
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.success){
					app.editor(app.item.id);
			    }
			    else {
			    	Toast.fire({
					  icon: 'error',
					  title: res.error
					})
			    }
			    
			})
		},
		charactsAdd: function(){
			let app = this;

			let data = {
				'pageId': app.item.id,
				'nameRu': app.characts_add__name,
				'groupName': app.characts_add__groupName,
				'type': app.characts_add__type,
				'defaultVal': app.characts_add__defaultVal,
				'options': app.characts_add__options
			}
			
			axios.post('/master/page/api/?type=charactsAdd', data)
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.success){
					Toast.fire({
					  icon: 'success',
					  title: 'Поле добавлено!'
					})
					app.editor(app.item.id);
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
			
			app.item.content = app.codeEditor.content.getValue();
			
			for(key in app.item.chunk){
				
				console.log(key);
				
				if(app.item.chunk[key].type == 'tinymce'){
					app.item.chunk[key].content = app.codeEditor[key].getData();
				}
				
				if(app.item.chunk[key].type == 'ace'){
					app.item.chunk[key].content = app.codeEditor[key].getValue();
				}
				
			}
			
			axios.post('/master/page/api/?type=save', app.item)
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
				if(app.codeEditor.content) app.codeEditor.content.setValue(app.item.content ? app.item.content : '');
				else app.codeEditor.content = ace.edit('aceEditor', {
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
			        value: app.item.content ? app.item.content : ''
			    });
				
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
		createNew: function(){
			let app = this;
			axios.post('/master/page/api/?type=createNew', {})
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
		fillFromApi: function(chunk_name){
			let app = this;
			let url = 'https://wrapapi.com/use/splashins/stolet/medschool_program/0.0.1?wrapAPIKey=n1AQeGcZtB680AydxRprl5mItvJqVXaR&url='+app.item.url;
			
			axios.post(url, {})
			.then(function (res) {
				res = res.data;
			    
			    if(res.data.output){
			    	app.item.chunk[chunk_name].content = res.data.output;
			    	window.alert('Сделано!');
			    }
			    
			   
			})
			
		}
		
	}
});

</script>
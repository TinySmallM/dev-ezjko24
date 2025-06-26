/* swal toast */
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 2000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})

/* Serialize form to JSON */
$.fn.serializeFormJSON = function () {

    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function uploadStorage(data){ //FormData object
	return new Promise((resolve, reject) => {
		axios({
		    method: 'post',
		    url: '/master/storage/upload',
		    data: data,
		    headers: {'Content-Type': 'multipart/form-data' }
		})
	    .then(function (res) {
		  	let resBody = res.data;
		    console.log(resBody);
		    if(resBody.files){
		    	resolve(resBody.files);
		    }
		    else {
		    	reject(resBody.error)
		    }
	    })			
	})
}

Vue.component('vue-multiselect', window.VueMultiselect.default)


Vue.component('tv-services', {
	props: ['content','predicate'],
	data: function () {
		return {
			localPredicate: [],
			selService: {
				name: '',
				description: '',
				price: 0,
				image: '',
				predicateId: null
			},
			clearService: {
				name: '',
				description: '',
				price: 0,
				image: '',
				predicateId: null
			},
			services: [],
			sumAll: 0
		}
	},
	mounted: function(){
		this.init();
	},
	watch: {
		content: function(){
			this.init();
		},
		services: function(services){
			this.calcSum();
			this.$emit('update-chunk', services)
		},
		selService: function(val){
			if(this.selService != null && !this.selService.price){
				this.selService.price = 0;
			}
		}
	},
	methods: {
		init: function(){
			let app = this;
			app.services = [];

			for(i in app.predicate){
				app.predicate[i].price = 0;
			}
			if(typeof(this.content) == 'object') this.services = this.content;
		},
		add: function(){
			let item = this.selService;
			this.services.push({
				name: item.name,
				description: item.description,
				price: item.price,
				image: item.image,
				predicateId: item.predicateId
			});
		},
		rm: function(index){
			this.services.splice(index, 1);
		},
		selPredicate: function(index){
			console.log(index);
			if(index == 'clear') {
				this.selService = {
					name: '',
					description: '',
					price: 0,
					image: '',
					predicateId: null
				};
			}
			
			else {
				let item = this.predicate[index];
				console.log(item);
				this.selService = {
					id: item.id,
					name: item.name,
					description: item.description,
					price: item.priceFixed,
					image: item.image,
					predicateId: item.id
				};
			}
		},
		savePredicate: function(){
			let app = this;
			axios.post('/master/product/api/?type=savePredicate', app.selService)
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
					app.$emit('update-predicate', res.items)
			    }
			   
			})
		},
		calcSum: function(){
			let app = this;
			
			let sum = 0;
			for(i in app.services){
				sum = Number(sum) + Number(app.services[i].price);
			}

			app.sumAll = sum;
		},
		select2Predicate: function(items){
			let pred = [];
			
			pred.push({id:'clear',text:'Выберите шаблон услуги'})

			for(i in items){
				pred.push({id:i,text:items[i].name})
			}

			return pred;
		}

	},
	template: `
	<div>
			<div>
				<div style="width: 55%;display: inline-block;">
					<select2 :options="select2Predicate(predicate)" v-on:input="selPredicate($event)" style="width:100%;margin-bottom:10px;"></select2>
				</div>
				<button class="btn btn-secondary" @click="selPredicate('clear')">Очистить</button>
			</div>

			<div class="mt-5">
				<div>
					<div style="width: 85%;display: inline-block;">
						<div class="row">
							<div class="col-2">
							<image-upload :src="selService.image" v-on:update-src="selService.image = $event" :watermark="1"></image-upload>
							</div>
							<div class="col-10">
								<div class="row">
									<div class="col-10">
										<label>Название</label>
										<input type="text" class="form-control" v-model="selService.name">										
									</div>
									<div class="col-2">
										<label>Цена</label>
										<input type="number" class="form-control" v-model="selService.price">
									</div>
									<div class="col-12">
										<textarea v-model="selService.description" rows="2" style="width:100%;max-width:100%;" class="form-control mt-1"></textarea>
									</div>
								</div>

							</div>

						</div>

							
					</div>
					<div style="width: 10%;display: inline-block;">
					<button class="btn btn-sm btn-info" @click="savePredicate()" style="margin-bottom: -58px;"><i class="fas fa-save"></i></button>
						<button class="btn btn-sm btn-success" @click="add()" style="margin-bottom: -58px;"><i class="fa fa-fw fa-plus" /></button>
					</div>					
				</div>

			</div>

			<div v-if="services" class="mt-4">
				<table class="table">
					<tbody>
						<tr v-for="(item,index) in services">
							<td style="width:20%">
								<image-upload :src="services[index].image" v-on:update-src="services[index].image = $event" :watermark="1"></image-upload>
							</td>
							<td style="width:70%">
								<div style="width: 80%;display:inline-block;">
									<input type="text" class="form-control" v-model="services[index].name">
									<textarea v-model="services[index].description" rows="2" style="width:100%;max-width:100%;" class="form-control mt-1"></textarea>
								</div>
								<div style="width: 18%;display:inline-block;">
									<input type="numer" class="form-control" v-model="services[index].price" @change="calcSum()">
								</div>
							</td>
							<td style="width:10%">
								<button class="btn btn-sm btn-danger" @click="rm(index)"><i class="fa fa-fw fa-times" /></button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<h6>Сумма: {{sumAll}}₽</h6>
	</div>
	`
})

Vue.component('image-upload', {
	props: ['src','watermark'],
	data: function () {
		return {
			local_src: null
		}
	},
	mounted: function(){
		this.local_src = this.src;
	},
	watch: {
		local_src: function(val){
			this.$emit('update-src', val)
		},
		src: function(val){
			this.local_src = val;
		}
	},
	methods: {
		checkSrc: function(local_src){
			if(!local_src || local_src == '') return '/img/no-photo.jpg';
			return '/upload/thumb_'+local_src;
		},
		fileUpload: function(event){
			let app = this;
			let formData = new FormData();

			$.each(app.$refs.file.files, function(i, file) {
				formData.append('file[]', file);
			});
			app.$refs.file.value = '';
			formData.append('watermark', app.watermark);
			
			uploadStorage(formData).then(filesArr=>{
				for(i in filesArr){
					app.local_src = filesArr[i].file;
				}
			}, err => {
				Swal.fire({
				  icon: 'success',
				  title: 'Ошибка!',
				  text: 'Не удалось загрузить файлы на сервер',
				  timer: 1200
				})	
				return;
			})
			
		}		
	},
	template: `
		<div>
			<input class="fileUpload" type="file" ref="file" @change="fileUpload()" style="display: none">
			
			<template v-if="src">
			<img 
				:src="checkSrc(src)" 
				class="img-thumbnail" 
				onclick="$(this).siblings('.fileUpload').click()" 
				style="cursor: pointer" 
				title="Изменить"
			>
			</template>
			<template v-else>
				<div
					onclick="$(this).siblings('.fileUpload').click()" 
					style="cursor: pointer;font-size: 13px;" 
				><i class="nav-icon fas fa-upload" style="padding: 10px;border: 1px solid #ced4d8;border-radius: 2px;"></i></div>
			</template>
		</div>
	`
})

Vue.component('image-gallery', {
	props: ['items'],
	data: function () {
		return {
			gallery: [],
		}
	},
	mounted: function(){
		this.gallery = this.items;
	},
	watch: {
		gallery: function(val){
			this.$emit('update-gallery', val)
		},
		items: function(val){
			this.gallery = this.items;
		}
	},
	methods: {
		fileUpload: function(event){
			let app = this;
			let formData = new FormData();

			$.each(app.$refs.file.files, function(i, file) {
				formData.append('file['+i+']', file);
			});

			app.$refs.file.value = '';

			formData.append('watermark', 1);
			
			uploadStorage(formData).then(filesArr=>{
				for(i in filesArr){
					console.log(i)
					app.gallery.push({id:0,image:filesArr[i].file,description:null,orderId:null,productId:null});
				}
			}, err => {
				Swal.fire({
				  icon: 'success',
				  title: 'Ошибка!',
				  text: 'Не удалось загрузить файлы на сервер',
				  timer: 1200
				})	
				return;
			})
			
		},
		rm: function(index){
			this.gallery.splice(index, 1);
		},
	},
	template: `
		<div>
			<input class="fileUpload" type="file" ref="file" @change="fileUpload()" style="display: none" multiple>
			<button class="btn btn-sm btn-success" @click="$refs.file.click()"><i class="fa fa-fw fa-plus" /></button>
			<div class="mt-3 galleryBlock">
				<draggable v-model="gallery" group="gallery" @start="drag=true" @end="drag=false">
					<div class="gallery_item" :style="'background-image: url(/upload/thumb_'+item.image+')'" v-for="(item,index) in gallery" :key="index" :title="item.description">
						<div class="gallery_tools">
							<i class="fas fa-times" @click="rm(index)"></i>
							<i class="far fa-eye"></i>
						</div>
						<textarea :rows=2 v-model="item.description" class="form-control gallery_desc"></textarea>
					</div>
				</draggable>
			</div>
		</div>
	`
})

Vue.component('similar-product', {
	props: ['items'],
	data: function () {
		return {
			similar: [],
			options: [],
			allProduct: []
		}
	},
	mounted: function(){
		this.getItems();
		this.similar = this.items; //При инициализации
	},
	watch: {
		similar: function(val){ //Если обновилось внутреннее
			this.$emit('update-similar', val)
		},
		items: function(val){ //Если обновилось входящее
			this.similar = this.items;
		}
	},
	methods: {
		customLabel: function(id){
			result = this.allProduct.filter(item => item.id == id)[0];
			return result.name;
		},
		getItems: function(first=false){
			let app = this;
			
			axios.post('/master/product/api/?type=getSimilar', {})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.items){
					app.allProduct = res.items;
					app.options = [];
					for(i in res.items){
						app.options.push(res.items[i].id);
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
	},
	template: `
		<div>
			<vue-multiselect v-model="similar" :options="options" :multiple="true" :taggable="true" :custom-label="customLabel"></vue-multiselect>
		</div>
	`
})

Vue.component('select-template', {
	props: ['id'],
	data: function () {
		return {
			selected: null,
			options: [],
			allTpl: pageTpl
		}
	},
	mounted: function(){
		this.selected = this.id; //При инициализации
		this.fillTpl();
	},
	watch: {
		selected: function(val){ //Если обновилось внутреннее
			this.$emit('update-template', val)
		},
		id: function(val){ //Если обновилось входящее
			this.selected = val;
		}
	},
	methods: {
		customLabel: function(key){
			return this.allTpl[key].name;
		},
		fillTpl: function(){
			let app = this;
			
			for(i in app.allTpl){
				app.options.push(i);
			}
			
		},
	},
	template: `
		<div>
			<vue-multiselect v-model="selected" :options="options" :custom-label="customLabel"></vue-multiselect>
		</div>
	`
})

Vue.component('select-parent', {
	props: ['id','pages'],
	data: function () {
		return {
			selected: null,
			options: [],
			parentAll: []
		}
	},
	mounted: function(){
		this.selected = this.id; //При инициализации
		this.fillTpl();
	},
	watch: {
		selected: function(val){ //Если обновилось внутреннее
			this.$emit('update-parent', val)
		},
		id: function(val){ //Если обновилось входящее
			this.selected = val;
		},
		pages: function(){
			this.fillTpl();
		}
	},
	methods: {
		customLabel: function(id){
			
			
			result = this.parentAll.filter(item => item.id == id)[0];
			
			if(!result) return 'Не выбран';

			if(result.menuname) return result.menuname;
			else return result.h1;
		},
		fillTpl: function(){
			let app = this;

			app.parentAll = [];
			app.options = [];

			app.pages.forEach(function(item,i){
				app.parentAll.push(app.pages[i]);
				app.options.push(app.pages[i].id);
			})

			app.parentAll.push({
				'id': 0,
				'menuname': 'Не выбран'
			})
			
			
		},
	},
	template: `
		<div>
			<vue-multiselect v-model="selected" :options="options" :custom-label="customLabel"></vue-multiselect>
		</div>
	`
})

Vue.component("select2", {
	props: ["options", "value"],
	template: `
	<select>
		<slot></slot>
	</select>      
	`,
	mounted: function() {
	  var vm = this;
	  console.log(vm.value);
	  $(this.$el)
		// init select2
		.select2({ data: vm.options })
		.val(vm.value)
		.trigger("change")
		// emit event on change.
		.on("change", function() {
		  vm.$emit("input", this.value);
		});
	},
	watch: {
	  value: function(value) {
		  console.log(value)
		// update value
		$(this.$el)
		  .val(value)
		  .trigger("change");
	  },
	  options: function(options) {
		// update options
		$(this.$el)
		  .empty()
		  .select2({ data: options });
	  }
	},
	destroyed: function() {
	  $(this.$el)
		.off()
		.select2("destroy");
	}
});

Vue.component('tv-table', {
	props: ['content','chunk'],
	data: function () {
		return {
			data: [],
		}
	},
	mounted: function(){
		this.data = this.content;
	},
	watch: {
		data: function(val){
			this.$emit('update-data', val)
		},
		content: function(val){
			this.data = this.content;
		}
	},
	methods: {
		rm: function(index){ //удалить элемент
			if (window.confirm(`Будет удалена строка ${index+1}. Вы уверены?`)) {
				this.data.splice(index, 1);
			}
		},
		add: function(){
			
			let newpush = {};

			Object.keys(this.chunk.columns).forEach(function(val){
				console.log(val);
				newpush[val] = null;
			})

			this.data.push(newpush);
		}
	},
	template: `
		<div classs="tv-table-body">
			<!-- Header -->
			<div class="tv-table-row tv-table-head">
				<div v-for="(col,col_i) in chunk.columns" class="tv-table-col">{{col.name}}</div>
				<div class="tv-table-col-options" v-if="chunk.showControls">
					<button class="btn btn-sm btn-secondary" v-on:click="add()">+</button>
				</div>
			</div>
			<!-- Body -->
			<div class="tv-table-row" v-for="(row,row_i) in data">
				<div v-for="(col,col_i) in chunk.columns" class="tv-table-col">
					<input type="text" class="form-control" v-model="row[col_i]" v-if="col.type == 'text'">
					<div v-if="col.type == 'image'">
						<image-upload :watermark="0" :src="row[col_i]" v-on:update-src="row[col_i] = $event"></image-upload>
					</div>
					<input type="date" class="form-control" v-model="row[col_i]" v-if="col.type == 'date'">
					<textarea v-model="row[col_i]" class="form-control" v-if="col.type == 'textarea'" rows="5" style="width: 100%;max-width:100%;"></textarea>
				</div>
				<div class="tv-table-col-options" v-if="chunk.showControls">
					<button class="btn btn-sm btn-secondary" v-on:click="rm(row_i)">-</button>
					<button class="btn btn-sm btn-secondary" v-on:click="add()">+</button>
				</div>
			</div>
		</div>
	`
})

Vue.component('tags-select', {
	props: ['items','itemtype','canaddnew','itemid','placeholder','multiple','taggable'],
	data: function () {
		return {
			selected: [],
			options: [],
			allItems: []
		}
	},
	mounted: function(){
		this.getItems();
		this.selected = this.items; //При инициализации
	},
	watch: {
		selected: function(val){ //Если обновилось внутреннее
			this.$emit('update-selected', val);
			if(this.selected != this.items) this.saveForItem()
		},
		items: function(val){ //Если обновилось входящее
			this.selected = this.items;
		}
	},
	methods: {
		addTag (newTag) {
			let app = this;

			if(!app.canaddnew) {
				Toast.fire({
					icon: 'error',
					title: 'Создать новый тег здесь нельзя.'
				})
				return;
			}
			
			axios.post('/master/tags/api?type=presetsAdd', {itemType: app.itemtype,tag:newTag})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.item){
					app.selected.push(newTag);
					app.options.push(newTag);
					Toast.fire({
						icon: 'success',
						title: 'Создан новый тег'
					})
				} 
			    else {
			    	Toast.fire({
					  icon: 'error',
					  title: 'Что-то не так'
					})
			    }
			    
			})
		},
		getItems: function(first=false){
			let app = this;
			
			axios.post('/master/tags/api?type=presetsGetAll', {itemType: app.itemtype})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.items){
					app.allItems = res.items;
					app.options = [];
					for(i in res.items){
						app.options.push(res.items[i].tag);
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
		saveForItem: function(){
			let app = this;

			if(app.itemid){
				axios.post('/master/tags/api?type=saveForItem', {itemType: app.itemtype,tags:app.selected,itemId:app.itemid})
				.then(function (res) {
					res = res.data;
					console.log(res);
					if(res.success){
						/*Toast.fire({
							icon: 'success',
							title: 'Теги сохранены'
						})
						*/
					} 
					else {
						/*Toast.fire({
							icon: 'error',
							title: 'Не удалось соъранить теги'
						})*/
					}
					
				})
			}		
		}
	},
	template: `
		<div class="multiselect-tag-width100">
			<vue-multiselect v-model="selected" :options="options" :multiple="multiple" :taggable="taggable" :hideSelected="true" openDirection="'above'" @tag="addTag" :tagPlaceholder="'Нажмите Enter, чтобы создать'" :selectLabel="'Нажмите Enter, чтобы выбрать'" :selectedLabel="'Выбран'" :deselectLabel="'Нажмите Enter, чтобы удалить'" :placeholder="placeholder?placeholder:'Выберите из списка'"></vue-multiselect>
		</div>
	`
})


var CkOptions = {
	filebrowserBrowseUrl : '/master/js/ckeditor-ckfinder-integration-master/ckfinder/ckfinder.html',
	filebrowserImageBrowseUrl : '/master/js/ckeditor-ckfinder-integration-master/ckfinder/ckfinder.html?type=Images',
	filebrowserFlashBrowseUrl : '/master/js/ckeditor-ckfinder-integration-master/ckfinder/ckfinder.html?type=Flash',
	filebrowserUploadUrl : '/master/js/ckeditor-ckfinder-integration-master/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	filebrowserImageUploadUrl : '/master/js/ckeditor-ckfinder-integration-master/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	filebrowserFlashUploadUrl : '/master/js/ckeditor-ckfinder-integration-master/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
	//contentsCss: ['/css/style.css'],
	customConfig: '/master/js/ckeditor_config.js?v4',
    extended_valid_elements:"span,style,link[href|rel]",
    custom_elements:"style,link,~link",
	allowedContent: true,
	/*
	toolbar: [
		[ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
	    [ 'FontSize', 'TextColor', 'BGColor' ]	
	]
	*/
	/*allowedContent: {
		$1: {
			elements: CKEDITOR.dtd,
			attributes: true,
			styles: true,
			classes: true
		}
	},*/
	
}

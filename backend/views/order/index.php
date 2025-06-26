<?
$this->title = 'Заказ #'.$item['id'];
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.min.js');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js');
?>

<div class="cartApploader" id="cartApp" style="display: none">
	
		<div class="row">
			<div class="col-12 col-md-6">
				<h1 class="title">Заказ #{{order.id}}</h1>
			</div>
			<div class="col-12 col-md-2 offset-md-4">
				<select v-model="order.status" @change="save()" class="form-control" style="width:100%">
					<template v-for="b in orderStatus[order.status].next">
						<option :value="b">{{orderStatus[b].name}}</button>
					</template>
					<option :value="order.status">{{orderStatus[order.status].name}}</option>
				</select>
				
			</div>
		</div>
		
		<div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
              	<div class="col-12 col-md-6">
              		<small>Hash: {{order.hash}}</small>
              	</div>
                <div class="col-12 col-md-6">
                	
                	<small class="float-right">Дата: <?=date_create_from_format('Y-m-d H:i:s',$item['created'])->format('d.m.Y H:i')?></small>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
              
              

              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                	
					<table class="table table-striped">
						<thead>
							<tr class="bg-ezjko">
								<th>Артикул</th>
								<th>Наименование</th>
								<th>Количество</th>
								<th>Стоимость</th>
							</tr>
						</thead>
						<tbody>
							<template v-for="(pr,i) in order.items">
								<tr v-if="pr.quantity > 0">
									<td>{{pr.artikul}}</td>
									<td>{{pr.name}} [{{pr.priceName}}]</td>
									<td>
										<input type="number" step="1" v-model="pr.quantity" min="0" max="1000" @focus="$(this).blur()" @change="calcCost(i)" v-if="order.status == 0">
										<template v-else>{{pr.quantity}}</template>
									</td>
									<td>
										<input type="number" v-model="pr.cost" @change="calcSum" step=".01" v-if="order.status == 0">
										<template v-else>{{pr.cost}}</template>
									</td>
								</tr>							
							</template>
						</tbody>
					</table>
					
					<div class="row">
						<div class="col-12 col-md-6">
							<span class="font-weight-bold">В корзине <span style="color: #c51d4f">{{countAll}} товар(ов)</span> на сумму <span style="color: #c51d4f">{{sumAll}}₽</span></span>
						</div>
					</div>    
                </div>
              </div>


              <div class="row no-print">
                <div class="col-12">
				
                  <button type="button" class="btn btn-secondary float-right" @click="save()">Сохранить
                  </button>

				  <button type="button" class="btn btn-secondary float-right" style="margin-right: 20px;" @click="couponPersonal()">Купон за покупку</button>

				  <button type="button" class="btn btn-secondary float-right" style="margin-right: 20px;" @click="resendEmailComplete()">Отправить Email с кодом</button>
                </div>
              </div>
            </div>
		
		<!-- Данные клиента -->
		<div class="row mt-5">
			<div class="col-12">
				<hr>
				<h3 class="text-center">Данные клиента</h3>
			</div>
			<div class="col-12 col-md-8 offset-md-2">
				<div class="row">
					<!-- Статус -->
					<div class="col-12 col-md-6">Статус:</div>
					<div class="col-12 col-md-6">
						<select class="form-control" required v-model="order.orgType">
							<option value="1" >Физ. лицо</option>
							<option value="2">Юр.лицо</option>
							<option value="3">ИП</option>
						</select>
					</div>
					
					<!-- Название организации -->
					<div class="col-12 col-md-6 mt-3">Ф.И.О или наименование организации:</div>
					<div class="col-12 col-md-6 mt-md-3">
						<input type="text" name="name" class="form-control" required v-model="order.name">
					</div>
					
					<!-- Email -->
					<div class="col-12 col-md-6 mt-3">E-mail:</div>
					<div class="col-12 col-md-6 mt-md-3">
						<input type="text" name="email" placeholder="pochta@mail.ru" class="form-control" required v-model="order.email">
					</div>
					
					<!-- Телефон -->
					<div class="col-12 col-md-6 mt-3">Телефон:</div>
					<div class="col-12 col-md-6 mt-md-3">
						<input type="text" name="phone" placeholder="+7 (999) 999 99 99" class="form-control" required v-model="order.phone">
					</div>
					
					
					
					<div class="col-12"><hr></div>
					
					<!-- Способ доставки -->
					<div class="col-12 col-md-6 mt-2">Способ доставки:</div>
					<div class="col-12 col-md-6 mt-md-2">
						<select class="form-control" required v-model="order.delivery">
							<option value="1">Самовывоз</option>
							<option value="2" selected>Доставка по Волгоградской обл.</option>
							<option value="3">Почта россии</option>
							<option value="4">Транспортная компания</option>
						</select>
					</div>
					
					<!-- Способ оплаты -->
					<div class="col-12 col-md-6 mt-3">Способ оплаты:</div>
					<div class="col-12 col-md-6 mt-md-3">
						<select class="form-control" required v-model="order.payMethod">
							<option value="1">Наличными при получении</option>
							<option value="2" selected>Картой онлайн</option>
							<option value="3">Расчетный счет</option>
						</select>
					</div>
					
					<!-- Комментарий -->
					<div class="col-12 col-md-6 mt-3">Комментарий для менеджера:</div>
					<div class="col-12 col-md-6 mt-md-3">
						<textarea class="form-control" style="width: 100%max-width:100%;" rows="3" v-model="order.comment"></textarea>
					</div>
					
					<div class="col-12 mt-3">
						<button class="btn btn-secondary m-0 float-right" v-if="countAll > 0" @click="save">Сохранить</button>
					</div>

				</div>
			</div>
		</div>
		
		<!-- Информация о платеже -->
		<div class="row mt-5" v-if="order.extPayStatus">
			<div class="col-12">
				<hr>
				<h3 class="text-center">Информация о платеже</h3>
			</div>
			<div class="col-12 col-md-8 offset-md-2">
				<div class="row">
					
					<!-- Ext ID -->
					<div class="col-12 col-md-6">Внешний ID:</div>
					<div class="col-12 col-md-6">{{order.extPayId}}</div>
					
					<!-- Ext Pay Status -->
					<div class="col-12 col-md-6">Внешний статус:</div>
					<div class="col-12 col-md-6">{{order.extPayStatus}}</div>
					
					<!-- Ext Pay Date Completed -->
					<div class="col-12 col-md-6">Время завершения платежа:</div>
					<div class="col-12 col-md-6">{{order.extPayDateCompleted}}</div>

				</div>
			</div>
		</div>
		
		<div class="fp" v-if="order.fingerprint" style="margin-top: 40px;">
			<h3>Отпечаток пользователя</h3>
			<table class="table">
				<tr>
					<td>
						<b>ID:</b><br>
						{{order.fingerprint.id}}
					</td>
					<td>
						<b>Ya Metrika:</b><br>
						{{order.fingerprint.ya_client_id_frontend}}
					</td>
					<td>
						<b>OS:</b><br>
						{{order.fingerprint.os_frontend}}
					</td>
					<td>
						<b>Browser:</b><br>
						{{order.fingerprint.browser_frontend}}
					</td>
					<td>
						<b>Display:</b><br>
						{{order.fingerprint.display_frontend}}
					</td>
					<td>
						<b>IP:</b><br>
						{{order.fingerprint.ip_backend}}
					</td>
					<td>
						<b>Device:</b><br>
						{{order.fingerprint.device_type_backend}}
					</td>
				</tr>
			</table>
			
			<h3 style="margin-top:40px">История посещений</h3>
			<table class="table" style="table-layout: fixed;">
				<tr v-for="fpa in order.fingerprint.action" :style="fpa.id==fpa.firstActionId?'background: #92ffe4':null">
					<td style="width: 10%">
						<div :style="fpa.id != fpa.firstActionId?'margin-left: 40px;font-size: 13px;':null">
						{{fpa.firstActionId}}
						</div>
						
					</td>
					<td style="width: 10%">
						{{fpa.type}}
					</td>
					<td style="width: 70%;word-wrap: break-word;">
						<div v-if="fpa.id==fpa.firstActionId">
							{{fpa.referer}} ->
						</div>
						{{fpa.url}}{{fpa.params?'?'+fpa.params:null}}
					</td>
					<td style="width: 20%">
						{{ moment.unix(fpa.dateCreated).format('HH:mm:ss DD.MM.YYYY') }}
					</td>
				</tr>
			</table>
		</div>
		
	</div>

<script>
	let orderId = <?=$item['id']?>;
</script>

<?
$script = <<< JS

let app = new Vue({
	el: '#cartApp',
	data: {
		order: {},
		sumAll: 0,
		countAll: 0,
	},
	created: function(){
		this.getItems();
		$('.cartApploader').show();
	},
	methods: {

		resendEmailComplete: function(){
			let app = this;
			
			axios.post('/master/order/api/?type=sendEmailComplete', {id: orderId})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.success){
					Toast.fire({
					  icon: 'success',
					  title: 'Email отправлен!'
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
		getItems: function(){
			let app = this;
			
			axios.post('/master/order/api/?type=get', {id: orderId})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.item){
					app.order = res.item;
					app.calcSum();
			    }
			    else {
			    	Toast.fire({
					  icon: 'error',
					  title: 'Что-то не так'
					})
			    }
			    
			})
			
		},
		calcSum: function(){
			let app = this;
			
			app.sumAll = 0;
			app.countAll = 0;
			for(i in app.order.items){
				if(app.order.items[i].quantity == 0) continue;
				
				app.countAll++;
				app.sumAll += Number(app.order.items[i]['cost']);
			}
			app.sumAll = app.sumAll.toFixed(2);
		},
		calcCost: function(){
			let app = this;
			app.order.items[i]['cost'] = (app.order.items[i]['quantity'] * app.order.items[i]['priceSum']).toFixed(2)
			app.calcSum();
		},
		save: function(){
			let app = this;
			
			axios.post('/master/order/api/?type=save', {id:orderId, order: app.order})
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
					  title: 'Заказ отредактирован'
					})
			    }
			   
			})
			
			
		},
		couponPersonal: function(){
			let app = this;
			
			axios.post('/master/coupon/api/?type=addPersonal', {email:app.order.email,amount:app.order.amount})
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
					  title: 'Купон отправлен'
					})
			    }
			   
			})
			
			
		},
	}
});


JS;
$this->registerJs($script, yii\web\View::POS_READY);
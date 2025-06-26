<?

$this->title = 'Корзина';
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.min.js');

$this->registerCss('

.cart__index .form-control:focus{
	box-shadow: none;
	outline: none;
	border-color: #21332f;
}

.cart__index{
	padding-bottom: 100px;
}
.cart__index_table{
	border: 2px solid 2ab9cb;
	border-radius: 5px;
    overflow: hidden;
}
tr.bg_ezjko{
	font-weight: 600;
    color: #21332f;
	background: #fdfdfd;
}
.cart__index_table table{
	margin: 0;
	width: 100%;
}
.cart__index_table table td{
	border-top: 1px solid 2ab9cb;
	font-size: 1.1rem;
	padding: 5px;
}
.cart__index_table table thead{
	background: #29a5d7;
}
.cart__index_table table thead td{
	font-weight: 600;
}
.cart__index_content{
	max-width: 960px;
	width: 100%;
}
.cart__index_table tbody tr{
	background: #f3f3f3;
}
.static__banner_h1{

}
.cartSubmit{
	 max-width: 430px;
	 width: 100%;
}
.cartProcess{
	display: flex;
	justify-content: flex-end;
	margin-top: 40px;
}
.inputLabel{
    display: flex;
    justify-content: flex-start;
    align-items: center;
    color: #333;
    font-size: 1rem;
    margin: 0;
	font-weight: 600;
}
.ig{
	margin-bottom: 15px;
}
.ig input{
	padding: 7px 10px;
    width: 100%;
    background: #88817c00;
	border: 2px solid #d3d3d3;
    font-size: 1rem;
    color: #383330;
	outline: none;
	background: #fff;
    border-radius: 5px;
	font-weight: 600;
}
.ig input:focus,
.ig input.filled{
	border: 2px solid #29a5d7;
}
.ig input::placeholder{
	color: #bfbfbf;
}
.ig p{
	font-weight: 600;
	color: #58514b;
}
.cart_image_preview{
	width: 60px;
    height: auto;
    border-radius: 5px;
}
.surprise_box{
	color: rgb(33, 51, 47);font-weight: 600;font-size: 1rem;background: #cdcece;padding: 10px;margin-bottom: 20px;
}
.surprise_icon{
	position: absolute;
    margin-top: -23px;
    margin-left: -8px;
    width: 60px;
}

.payment_type h2{
	font-size: 1.5rem;
	margin-top: 30px;
}

.payment_form{
	max-width: 690px;
    margin: 0 auto;
}

.payment_types_items{
	max-width: 690px;
    margin: 0 auto;
}
.payment_type_item{
	display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid #fff;
    padding: 10px 14px;
    background: #fdfdfd;
	border-bottom: 1px solid #e1e1e1;
	padding-left: 55px;
	cursor: pointer;
	border-radius: 5px;
    overflow: hidden;
	margin-bottom: 5px;
	border: 2px solid #fdfdfd;
}
.payment_type_item:hover{
	background: #f3f3f3;
	border: 2px solid #867f7a;
}
.payment_type_item:before{
    content: "";
    background: #fff;
    width: 15px;
    height: 15px;
    border-radius: 11px;
    position: absolute;
    margin-left: -34px;
    border: 1px solid #20342f;
}
.payment_type_item.selected{
	background: #f3f3f3;
	border: 2px solid #867f7a;
}
.payment_type_item.selected:before{
	background: #1e322d;
    border: 1px solid #1e322d;
}
.payment_type_item p{
	font-weight: 600;
    color: #333;
    font-size: 1.1rem;
    margin: 0;
}
.payment_type_item img{
	width: 130px;
}
.payment_type_dolyame{
	padding: 18px 14px;
	padding-left: 55px;
}

.fade-leave-active{
	transition: opacity 0s;
}
.fade-enter-active {
  transition: opacity 0.2s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
  opacity: 0;
}

.promocode_field{
	margin-top: 30px;
	max-width: 400px;
}
.promocode_field__form{
	display: flex;
	column-gap: 5px;
}
.promocode_field__form button{
	background: #b1aeac;
	outline: none;
	box-shadow: none;
}

.cart-buttons-stages{

}

.cart-buttons-stages .btn{
	display: inline-block;
}


@media (max-width: 495px) {
	.cart__index_table table tbody td{
		line-height: 1.4rem;
		font-size: 1rem;
	}
	.surprise_icon{
		margin-top: 0;
	}
}

');
?>


<!-- (wrapper) -->
<div class="wrapper">
  <div class="wrapper__content">
    
		<?=$this->render('../_blocks/nt_header')?> 
		<div class="cover cart__index  d-flex flex-hor-center">
			
			<div class="cart__index_content">
			
				
				
				<div class="container mt-5">
					
					<div class="cartApploader" id="cartApp" style="display: none">

						<transition name="fade">
							<div v-if="stage=='cart'">
								<h1 class="static__banner_h1 text-center" style="padding-top: 150px;">Ваша корзина</h1>	

								<div v-if="Object.keys(cart).length">
									<div class="cart__index_table">
										<table class="table">
										<thead>
											<tr class="bg-ezjko">
												<td>Наименование</td>
												<!--<td>Количество</td>-->
												<td style="text-align: right;">Стоимость</td>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(pr,i) in cart">
												<td>
													<div style="display: flex;justify-content: flex-start;align-items: center;column-gap:10px;">
														<img :src="'/upload/thumb_'+pr.image" v-if="pr.image" class="cart_image_preview">
														<div>{{pr.h1}} [{{pr.priceName}}]</div>
													</div>	
												</td>
												<!--
												<td>
													<input type="number" step="1" v-model="pr.count" min="0" max="1000" @focus="$(this).blur()" @change="save(i)">
												</td>
												-->
												<td style="text-align: right;position: relative;">
													
													<div>
														<template v-if="pr.coupon_priceSum">
															<span style="text-decoration: line-through;margin-right: 20px;">{{pr.priceSum}}</span>
															<span>{{pr.coupon_priceSum}} ₽</span>
														</template>

														<template v-else>
															{{pr.priceSum*pr.count}} ₽
														</template>
														<div @click="removeOne(i);" style="display: inline-block;display: inline-block;color: #820000;cursor: pointer;">X</div>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
									</div>
									<div class="row" style="margin-top: 10px;">
										<div class="col-12 col-md-6" v-if="!coupon">
											<span class="font-weight-bold" style="color: #21332f;font-size: 1rem;">
											Общая сумма к оплате <span style="color: #29a5d7;">{{sumAll}}₽</span></span>
										</div>
										<div class="col-12 col-md-6" v-else>
											<span class="font-weight-bold" style="color: #21332f;font-size: 1rem;">
											Скидка <span style="color: #936e45">{{sumDiscount}}₽</span> по промокоду {{coupon.text}}
											<br>Общая сумма к оплате <span style="color: #936e45">{{sumAll}}₽</span></span>
											<div>
											<a href="javascript:void(0)" @click="clear('promo')" style="color: #333;text-decoration: underline;">Удалить промокод</a>
											</div>
										</div>
										<div class="col-12 col-md-6">
											<a class="btn-sm" style="color: #21332f;font-size: 1rem;" v-if="Object.keys(cart).length > 0" @click="clear" href="javascript:void(null)">Очистить</a>
										</div>
									</div>
									<div class="promocode_field mob-width-100" >
										<!--<div class="ig" v-if="!coupon">
											<label class="inputLabel">Промокод:</label>
											<div class="promocode_field__form">
												<input type="text" placeholder="Промокод..." v-model="promocode">
												<button class="btn" @click="setPromocode()">Применить</button>
											</div>
										</div>
										
										<div class="ig">
											<a href="javascript:void(null)" @click="link()" style="color: rgb(51, 51, 51);text-decoration: underline;">Получить ссылку на корзину</a>
										</div>
										-->
									</div>
								</div>

								<div v-else style="font-weight: 600;text-align: center;font-size: 1.2rem;color: #000;">
									<p>Ваша корзина пуста. Самое время это исправить!</p>
								</div>
								
								<div class="d-flex flex-hor-center" style="margin-top: 30px;" v-if="Object.keys(cart).length">
									<button class="btn btn-sm bg-ezjko m-0 mt-3 btn-christ" type="button" @click="stage='payment_data'">Оформить заказ</button>
								</div>
								
							</div>
						</transition>


						<transition name="fade">
						<div v-if="stage=='payment_data'" class="payment_form" >
							<h1 class="static__banner_h1 text-center" style="padding-top: 150px;">Заполните данные</h1>	
							
								<div class="ig">
									<label class="inputLabel">Ваше имя:</label>
									<input type="text" name="name" placeholder="Меня зовут..." v-model="paymentData.name">
								</div>

								<div class="ig" v-if="paymentType=='dolyame'">
									<label class="inputLabel">Ваша фамилия:</label>
									<input type="text" name="lastname" placeholder="Моя фамилия..." v-model="paymentData.lastname">
								</div>
								
								<!-- Email -->
								<div class="ig">
									<label class="inputLabel">Email:</label>
									<input type="text" name="email" placeholder="pochta@mail.ru" v-model="paymentData.email">
									<!--<p>Мы создадим личный кабинет (если его нет) и откроем доступ к курсам.</p>-->
								</div>
								
								<!-- Телефон -->
								<div class="ig">
									<label class="inputLabel">Телефон:</label>
									<input type="text" name="phone" v-mask="'+7##########'" placeholder="+7 (999) 999 99 99" v-model="paymentData.phone" v-if="paymentType=='dolyame'">
									<input type="text" name="phone" placeholder="+7 (999) 999 99 99" v-model="paymentData.phone" v-else>
								</div>

								

								<div class="d-flex flex-hor-center cart-buttons-stages" style="margin-top: 30px;column-gap: 10px;">
									<button class="btn btn-sm bg-ezjko m-0 mt-3 btn-christ" type="button" @click="stage='cart'">Назад</button>
									<button class="btn btn-sm bg-ezjko m-0 mt-3 btn-christ" type="button" @click="paymentProcess()">Далее</button>
								</div>
						</div>
						</transition>
					</div>

				</div>
				
			</div>
		</div>

		<?=$this->render('../_blocks/nt_footer')?>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script>

<script>
	<?
		$pd = [
			"name" => isset($_SESSION['member_firstname'])?$_SESSION['member_firstname']:null,
			"lastname" => isset($_SESSION['member_lastname'])?$_SESSION['member_lastname']:null,
			"email" => isset($_SESSION['member_email'])?$_SESSION['member_email']:null,
			"phone" => isset($_SESSION['member_phone'])?$_SESSION['member_phone']:null
		];
	?>
	var paymentData = <?=json_encode($pd)?>;
</script>

<?
$script = <<< JS

let urlParams = new URLSearchParams(window.location.search);
if(urlParams.get('cart_hash')){
	urlParams.delete('cart_hash')
	window.history.pushState({}, "Корзина", '/cart');
}


Vue.use(VueMask.VueMaskPlugin);
/*
$("input[name='phone']").mask("+7 (999)-999 99 99");
*/

$('.ig input').change(function(){
	if( $(this).val() ) $(this).addClass('filled');
	else $(this).removeClass('filled');
})

window.app = new Vue({
	el: '#cartApp',
	data: {
		stage: 'cart',
		cart: {},
		coupon: null,
		sumAll: 0,
		sumDiscount: 0,
		countAll: 0,
		paymentType: 'none',
		promocode: null,

		paymentData: paymentData
	},
	created: function(){
		this.getItems();
		$('.cartApploader').show();
	},
	watch: {
		cart: function(cart){
	    	if( Object.keys(cart).length > 0 ) $('.cartProcess').show();
	    	else $('.cartProcess').hide();
	    },
	},
	methods: {
		getItems: function(){
			let app = this;
			
			axios.post('/cart/api?type=get', {})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(res.items){
					app.cart = res.items;
					app.coupon = res.coupon;
					app.calcSum();
			    }
			    else {
			    	saToast.fire({
					  icon: 'error',
					  title: 'Что-то не так'
					})
			    }
			    
			})
			
		},
		calcSum: function(){
			let app = this;
			
			app.sumAll = 0;
			app.sumDiscount = 0;

			let sumAll = 0;
			let sumDiscount = 0;
			for(i in app.cart){
				sumAll += app.cart[i]['count'] * parseFloat(app.cart[i]['coupon_priceSum']?app.cart[i]['coupon_priceSum']:app.cart[i]['priceSum']);

				if( app.cart[i]['coupon_priceSum'] ) sumDiscount += ( parseFloat(app.cart[i]['priceSum']) - parseFloat(app.cart[i]['coupon_priceSum']) ) * app.cart[i]['count'];
			}

			if(sumAll < 0) sumAll = 0;
			app.sumAll = sumAll;
			app.sumDiscount = Math.round(sumDiscount);
		},
		save: function(data){
			let app = this;
			
			axios.post('/cart/api?type=update&set=1', data)
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(!res.success){
					saToast.fire({
					  icon: 'error',
					  title: 'Что-то не так'
					})
			    }
				
				
				if(data.count == 0){
					window.dataLayer.push({
						"ecommerce": {
							"currencyCode": "RUB",    
							"remove": {
								"products": [
									{
										"id": res.item.id,
										"name": res.item.h1,
										"quantity": 1,
									}
								]
							}
						}
					});
				}
				
			    
			    if(res.items){
			    	app.cart = res.items;
			    }
			    
			    app.calcSum();
			})
			
			
		},
		clear: function(type='all'){
			let app = this;

			let dialogTitle = 'Все товары будут удалены. Все верно?';

			if(type == 'promo') dialogTitle = 'Промокод будет удален. Все верно?';

			Swal.fire({
			  title: dialogTitle,
			  icon: 'error',
			  showDenyButton: true,
			  showCancelButton: true,
			  confirmButtonText: 'Да',
			  cancelButtonText: "Нет, оставьте",
			  confirmButtonColor: '#aaa',
			  cancelButtonColor: '#29a5d7',
			}).then((result) => {
			  if (result.isConfirmed) {
					app.processClear(type);
			  }
			})

		},
		setPromocode: function(){
			let app = this;

			if(!app.promocode){
				Swal.fire({
					title: 'Ошибка',
					icon: 'error',
					text: "Промокод не указан",
					showDenyButton: false,
					showCancelButton: false,
					confirmButtonColor: '#aaa',
					cancelButtonColor: '#263c36',
				})
			}

			axios.post('/cart/api?type=promoAdd', {text:app.promocode})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(!res.success){
					Swal.fire({
						title: 'Ошибка',
						icon: 'error',
						text: res.error,
						showDenyButton: false,
						showCancelButton: false,
						confirmButtonColor: '#aaa',
						cancelButtonColor: '#263c36',
					})
			    }
			    
			    if(res.items){
			    	app.cart = res.items;
					app.coupon = res.coupon;
			    }
			    app.calcSum();
			})
		},
		processClear(type){
			axios.post('/cart/api?type=clear', {type:type})
			.then(function (res) {
				res = res.data;
			    console.log(res);
			    if(!res.success){
					saToast.fire({
					  icon: 'error',
					  title: 'Что-то не так'
					})
			    }
			    
			    if(res.items){
			    	app.cart = res.items;
					app.coupon = res.coupon;
			    }
			    app.calcSum();
			})
		},
		removeOne(itemKey){
			
			let app = this;
			
			Swal.fire({
			  title: "Товар будет удален. Все верно?",
			  icon: 'error',
			  showDenyButton: true,
			  showCancelButton: true,
			  confirmButtonText: 'Да',
			  cancelButtonText: "Нет, оставьте",
			  confirmButtonColor: '#263c36',
			  denyButtonColor: '#29a5d7',
			}).then((result) => {
			  if (result.isConfirmed) {
					app.save({id:itemKey,count:0});
			  }
			})

		},
		paymentProcess: function(){
			let app = this;

			let data = JSON.parse( JSON.stringify(app.paymentData) )

			if(app.paymentType == 'none'){
				//ym(95552711, 'reachGoal', 'cart-send');
				axios.post('/cart/api?type=process', data)
				.then(function (res) {
					
					res = res.data;
					console.log(res);
					
					if(res.success == true){
						Swal.fire({
							text: 'Заказ успешно создан!',
							icon: 'success',
							showConfirmButton: false,
							showDenyButton: false,
							showCancelButton: false,
							cancelButtonText: "Вернуться к заказу",
						})
						setTimeout(function(){
							window.location.reload();
						}, 5000)
					}
					else {
						Swal.fire({
							text: 'Ошибка! '+res.msg,
							icon: 'error',
							showConfirmButton: false,
							showDenyButton: true,
							showCancelButton: true,
							cancelButtonText: "Вернуться к заказу",
						})
					}
					
				})
			}
			

		}
	}
});


JS;
$this->registerJs($script, yii\web\View::POS_READY);
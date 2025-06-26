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
	border: 2px solid #867f7a;
}
tr.bg_ezjko{
	font-weight: 600;
    color: #21332f;
}
.cart__index_table table td{
	border-top: 1px solid #9f9790;
	font-size: 1.1rem;
}
.cart__index_table table thead{
	background: #b1aeac;
}
.cart__index_table table thead td{
	font-weight: 600;
}
.cart__index_content{
	max-width: 960px;
	width: 100%;
}
.static__banner_h1{
	text-transform: uppercase;
    font-weight: 600;
    font-size: 2.8em;
    margin-bottom: 20px;
    color: #21332f;
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
	padding: 5px 10px;
    width: 100%;
    background: #88817c00;
    border: 2px solid #867f7a;
    font-size: 1rem;
    color: #333;
	outline: none;
}
.ig input:focus,
.ig input.filled{
	border: 2px solid #43494a;
}
.ig input::placeholder{
	color: #333;
}

.success-msg b{
    font-size: 1rem;
    margin-top: 5px;
    display: block;
}
.success-msg b span{
	color: #fff;
    background: #155824;
    padding: 4px 6px;
}
');

session_start();
?>

<?=$this->render('../_blocks/header',[
	'class'=>'navbar_green show_bg_default',
	'type'=>'index',
])?>



<div class="cart__index bg_texture_white d-flex flex-hor-center">
	
	<div class="cart__index_content">
	
		
		
		<div class="container mt-5">
			
			<h1 class="static__banner_h1 text-center" style="padding-top: 150px;">Оплата заказа</h1>	

            <?if($state == 'error'){?>
                <div class="alert alert-danger">
                    <b>Ошибка. Платеж не прошел.</b><br>
                    Нажмите "назад" и попробуйте еще раз.
                </div>
				<script>
					ym(70515631,'reachGoal','cart-order-fail')
				</script>
            <?}?>

            <?if($state == 'success' && $order){?>
                <div class="alert alert-success success-msg">
                    <b>Проверяем ваш платеж...</b><br>

                </div>
				<script>
					var order_hash = '<?=$order['hash']?>';
				</script>
            <?}else if($state == 'success'){?>
				<div class="alert alert-success success-msg">
                    <b>Ваш заказ успешно оплачен.</b><br>
					<b style="font-size: 0.84rem">Как получить доступ к материалам:</b>
					1. Войдите в ваш личный кабинет по ссылке: <a style="color:#333;font-weight: 600;" href="https://christmedschool.com/lk">christmedschool.com/lk</a><br><br>
					[ ! ] Если учетной записи ранее не было, она создается автоматически после оплаты заказа.<br>
					[ ! ] В качестве логина используйте почту, указанную при оплате.<br><br>
					
					2. Перейдите в раздел "Мой курс"<br><br>
					3. Выбериет интересующий вас предмет<br><br>
					4. Выберите в меню слева (на пк) или снизу (на телефоне) тему. У купленных тем не будет иконки замочка.
                </div>
			<?}?>
		</div>
		
	</div>
</div>

<?
$script = <<< JS

function pushYmOrder(id,items){
	window.dataLayer.push({
	"ecommerce": {
		"currencyCode": "RUB",
		"purchase": {
			"actionField": {
				"id" : id
			},
			"products": items
		}
	}
});
}

$(function(){



	function checkPayment(){
		axios.post('/cart/api?type=getOrderByHash', {hash:order_hash})
		.then(function (res) {
			let data = res.data;

			if (data.status == 3) {
				let text = '<b>Ваш заказ успешно оплачен и обработан.</b>';
				//if(data.platformCode) text += '<b>Код активации: <span>'+data.platformCode+'</span></b>';
				
				text = '<b>Ваш заказ успешно оплачен.</b><br>'
				text = '<b style="font-size: 0.84rem">Как получить доступ к материалам:</b>'
				text = '1. Войдите в ваш личный кабинет по ссылке: <a style="color:#333;font-weight: 600;" href="https://christmedschool.com/lk">christmedschool.com/lk</a><br><br>'
				text = '[ ! ] Если учетной записи ранее не было, она создается автоматически после оплаты заказа.<br>'
				text = '[ ! ] В качестве логина используйте почту, указанную при оплате.<br><br>'
					
				text = '2. Перейдите в раздел "Мой курс"<br><br>'
				text = '3. Выбериет интересующий вас предмет<br><br>'
				text = '4. Выберите в меню слева (на пк) или снизу (на телефоне) тему. У купленных тем не будет иконки замочка.'

				$('.alert-success').html(text);

				
				ym(70515631,'reachGoal','cart-order-complete');
				pushYmOrder(data.id,data.ya_cart)

				
			}
			else {
				setTimeout(function(){
					checkPayment();
				}, 5000)
			}
			
		})
	}


	if(order_hash){
		checkPayment();		
	}
})
JS;
$this->registerJs($script, yii\web\View::POS_READY);
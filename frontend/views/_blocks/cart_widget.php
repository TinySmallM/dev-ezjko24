<?

use yii\helpers\Url;

$this->registerCss('

@keyframes bounce {
	0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
	40% {transform: translateY(-30px);}
	60% {transform: translateY(-15px);}
}

.cart__widget_circle{
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 105px;
    border: 2px solid #763b3b;
    animation: pop 4.2s infinite;
}

.cart__widget{
    box-shadow: 0px 0px 0px 0px #ffffff94;
    position: fixed;
    width: 80px;
    height: 80px;
    background: #2ab9cb;
    background-size: 35px;
    z-index: 10;
    border-radius: 105px;
    right: 50px;
    bottom: 50px;
    border: 2px solid #2491a0;
    animation: bounce 2.2s infinite;
    cursor: pointer;
}
.cart__widget_icon{
	 background: url("/cdn/cartwidget/cart.svg") no-repeat;
	 background-size: cover;
	 width: 100%;
	 margin: 0 auto;
     background-position: center;
     height: 100%;
     background-size: 40px;
     background-position-y: 20px;
     
}
.cart__widget_num{
    background: #2491a0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 30px;
    font-size: 1rem;
    font-weight: 600;
    position: absolute;
    bottom: -10px;
    color: #fff;
}

.cart__widget.hidden{
	display: none;
}

@media (max-width: 767.98px) {
    .cart__widget{
        right: 20px;
        bottom: 20px;
    }
}

');
?>

<?
$script = <<< JS

function showCartFrame(){
	window.location.href = '/cart';
	/*
	$.fancybox.open({
		src  : '/cart',
		type : 'iframe',
		toolbar  : false,
		smallBtn : true,
		iframe : {
			preload : false,
			css: {
				width: '80%',
			}
		},/*
		beforeShow: function () {
	        $("body > .wrapper").addClass("blur");
	    },
	    afterClose: function () {
	        $("body > .wrapper").removeClass("blur");
	    }
	    
	});
	*/
}
  
JS;
$this->registerJs($script, yii\web\View::POS_END);
?>

<div onclick="showCartFrame()" class="cart__widget <?=strpos($_SERVER['REQUEST_URI'],'/cart')>-1||empty($_SESSION['cart'])||count($_SESSION['cart'])==0?'hidden':null?>">
	<div class="cart__widget_num"><?=is_array($_SESSION['cart'])?count($_SESSION['cart']):null?></div>
	<div class="cart__widget_icon"></div>
</div>
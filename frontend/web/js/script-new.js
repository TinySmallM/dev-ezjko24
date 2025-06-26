
const menu = new MmenuLight(
	document.querySelector( "#mobile-nav" ),

);

const navigator_main = menu.navigation({
	title: 'Меню',
});
const drawer = menu.offcanvas();
//}



document.querySelector( ".openMenu" )
.addEventListener( "click", ( evnt ) => {
	evnt.preventDefault();
	drawer.open();
});
//}

$('.openSubmenuHref').click(function(e){
	e.preventDefault();

	if( $(this).siblings('ul').length ){
		$(this).parent().click();
	}
	else window.location.href = $(this).attr('href');

	//drawer.close();
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



var modal_dynamic = new tingle.modal({
	footer: false,
	stickyFooter: false,
	closeMethods: ['overlay', 'button', 'escape'],
	closeLabel: "Закрыть",
	cssClass: ['form-tingle-default','form-tingle-maxwidth'],
	onOpen: function(){
		let players = Plyr.setup('video', { plyr_controls });
	},
	onClose: function() {
        modal_dynamic.setContent('');
    },
});


function pageInModal(pageId){
	axios.get('/page/api?type=page_in_modal&id='+pageId, {})
	.then(function (res) {
		let data = res.data;

		if (!data.error) {
			modal_dynamic.setContent(data.data);
			modal_dynamic.open();
		} else {
			Swal.fire({
				title: 'Ошибка',
				text: data['msg'],
				icon: 'error',
			});
		}
		
	})
}


$(function(){

	$('.promoActivate').click(function(){
		Swal.fire({
			title: 'Введите промокод',
			input: 'text',
			inputLabel: 'Промокод',
			confirmButtonText: '<div stylle="color: #fff">Применить</div>',
			showCancelButton: true,
			confirmButtonColor: '#263c36',
			cancelButtonText: "Отмена",
			inputValidator: (value) => {
				if (!value) {
					return 'Поле не может быть пустым!'
				}
			}
		}).then((data) => {
			if (data.value) {
				processPromocode(data.value)
			}
		});
	})

	function processPromocode(text){
		axios.post('/cart/api?type=promoAdd', {text:text})
		.then(function (res) {
			res = res.data;
		    console.log(res);
		    if(res.success == true){
				ym(95552711, 'reachGoal', 'cart-promoadd');
				Swal.fire({
                    text: 'Промокод добавлен',
                    icon: 'success',
                });
				if(window.app) {
					window.app.getItems();
					window.app.calcSum();

				}
				else {
					setTimeout(function(){
						window.location.reload()	    	
					})
				}
				
		    }
		    else {
				Swal.fire({
                    text: res.error,
                    icon: 'error',
                });
		    }
		    
		})
	}
	
	function processCartAdd(id,count=1,pricetype){
		axios.post('/cart/api?type=update', {id:id,count:count,priceType:pricetype})
		.then(function (res) {
			res = res.data;
		    console.log(res);
		    if(res.success == true){
		    	$('.cart__widget').removeClass('hidden');
		    	$('.cart__widget_num').text(res.count);		    
				
				if(count != 0) {
					ym(95552711, 'reachGoal', 'cart-add');

					
					window.dataLayer.push({
						"ecommerce": {
							"currencyCode": "RUB",    
							"add": {
								"products": [
									{
										"id": res.item.id,
										"name": res.item.h1,
										"price": res.item.priceSum,
										"brand": "ChristMedSchool",
										"quantity": res.item.count
									}
								]
							}
						}
					});
					
				}
		    	
				Swal.fire({
					showDenyButton: true,
					showCancelButton: true,
                    title: 'Успешно!',
                    text: 'Добавлено в корзину',
                    icon: 'success',
                    confirmButtonText: '<div stylle="color: #fff">Перейти к оплате</div>',
					confirmButtonColor: '#263c36',
					cancelButtonColor: '#263c36',
                    cancelButtonText: "Выбрать ещё",
                }).then((result) => {
				  if (result.isConfirmed) {
				    window.location.href = '/cart';
				  }
				});
				
		    }
		    else {
				Swal.fire({
                    text: 'Что-то не так',
                    icon: 'error',
                });
		    }
		    
		})
	}
	
	$('form.cartAdd').submit(function(){
	
		let form = $(this);
		let data = form.serializeFormJSON();
		
		processCartAdd(data.id,data.count,data.priceType);
		
		return false;
	})
	$('[data-buyid]').click(function(){
		
		if(!$(this).data('price') || $(this).data('price') != 0){
			processCartAdd($(this).data('buyid'),1,$(this).data('buypricetype'));
			return false;
		} 
		
	})
})


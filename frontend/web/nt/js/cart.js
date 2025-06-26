// Добавление товара в корзину
let newItem = [];


function addCart(id, price, quantity, name='', picture,callback=function(){}){
    $.ajax({
        url:'/system/cart/',
        data: 'id='+id+"&price="+price+"&quantity="+quantity+"&name="+name+"&picture="+picture,
        success: function(data){
          console.log(data);
            if (callback){
               callback(data);
               $('.basket-info .basket-info__value').text(data.total_count +  ' шт');
               $('.basket-info .basket-info__value_2').html( `${data.total} <span>₽</span>`);
               $('.basket .basket__title').text( `Корзина (${data.total_count}) `);
               $('.basket-info .basket-info__itog-value').html( `${data.total} <span>₽</span>`);
               $('.cart_count').html(`${data.total_count}`);
                
            }
        }
      });
}


$("body").on('click', ".addCart", function(){
    var data = $(this).data();


    const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: true,
      showCancelButton: true,
      timer: 5000, // Таймаут 5 секунд
      timerProgressBar: true,
      customClass: {
          actions: 'swal2-actions-custom' // Добавляем класс для кастомизации кнопок
      },
      didOpen: (toast) => {
          toast.querySelector('.swal2-actions').style.display = 'flex';
          toast.querySelector('.swal2-actions').style.justifyContent = 'space-between';
      }
  });

    // const Toast = Swal.mixin({
    //     toast: true,
    //     position: 'top-end',
    //     showConfirmButton: false,
    //     timer: 1000,
    //     timerProgressBar: true,
    //     didOpen: (toast) => {
    //       toast.addEventListener('mouseenter', Swal.stopTimer)
    //       toast.addEventListener('mouseleave', Swal.resumeTimer)
    //     }
    //   })

    $(this).text(function(i, text) {
        if (text == 'Купить'){
            addCart(data.id, data.price, data.quantity, data.name, data.picture);

            Toast.fire({
              icon: 'success',
              title: '<i class="fa fa-check-circle"></i> Товар добавлен в корзину',
              confirmButtonText: 'Перейти в корзину',
              cancelButtonText: 'Вернуться к покупкам'
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location.href = '/cart';
              } else if (result.dismiss === Swal.DismissReason.cancel) {
                  // Just close the toast
              }
          });

        }
        else{
            addCart(data.id, data.price, -1);
            Toast.fire({
                icon: 'error',
                title: 'Товар удален'
              });
        }
        return text === "Купить" ? "Удалить" : "Купить";
      })
      $(this).toggleClass("btn-activ");
    
    
     

        console.log(data)
    })


    $("body").on('recalc', ".jq-number__spin.plus", function(){
      var input = $(this).parents('.counter').find('input');
      var data = input.data();
      var qty = input.val();
      var total = $(this).parents('.basket__product-info-right').find('.basket__product-sum-number')
      total.html(`${data.price * qty} <span class="basket__product-sum-currency">₽</span>`);
      var total_count = $(this).parents('.basket-info__list').find('.basket-info__value')
       addCart(data.id, data.price, qty, data.name, data.picture)             
    });   

    $("body").on('click', ".jq-number__spin.plus", function(){
        var input = $(this).parents('.counter').find('input');
        var data = input.data();
        var qty = input.val();
        var total = $(this).parents('.basket__product-info-right').find('.basket__product-sum-number')
        total.html(`${data.price * qty} <span class="basket__product-sum-currency">₽</span>`);
        var total_count = $(this).parents('.basket-info__list').find('.basket-info__value')
         addCart(data.id, data.price, qty, data.name, data.picture)             
         


    })
    $("body").on('click', ".jq-number__spin.minus", function(){
        var input = $(this).parents('.counter').find('input');
        var data = input.data();
        var qty = input.val();
        var total = $(this).parents('.basket__product-info-right').find('.basket__product-sum-number')
        total.html(`${data.price * qty} <span class="basket__product-sum-currency">₽</span>`);
         addCart(data.id, data.price, qty, data.name, data.picture);
         if (qty < 1){
            addCart(data.id, data.price, -1);
            $('.basket__product_'+data.id).toggle('slow');
         }

    })
    $("body").on('click', ".basket__product-delete", function(){
        var id = $(this).data("id");
        addCart(id, 0, 0);
        $('.basket__product_'+id).toggle('slow');
    });
    $("body").on('click', ".card__favorites-input", function(){
      var id = $(this).data("id");
      console.log(id)
      $('.card__favorites-input'+id).toggle('slow');
  });



    
    $("body").on('submit', ".order__submit", function(){ 
        
    });

    
    
    


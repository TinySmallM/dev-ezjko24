
var is_press_h = '';
var map;
var datas_new = {};
function getValue(el){
  var name = $(el).attr("name");
  var value = $(el).val();
  //console.log($(el).attr('type'));
  if ($(el).is('[type=radio]')){
    value = $('[name=' + name + ' ]:checked').val();
  }
  setData(name, value);
 // console.log(setData(name,value));
  //if (tmpl_cart){
    //tmpl_cart[0].data[name] = value;
    //$("[name="+name+"]").focus();
  //}
}


$().ready(function(){
  $("body").on("keypress",".b-store", function(){
    var el = this;
    if (is_press_h){
      window.clearTimeout(is_press_h);
    }
    is_press_h = window.setTimeout(function(){
      getValue(el);
    },200);
  });
  $("body").on("blur",".b-store,.b-store-after", function(){
	  
  });
  
  
  $("body").on("change",".b-store,.b-store-after", function(){
    var el = this;
    if (is_press_h){
      window.clearTimeout(is_press_h);
    }
    is_press_h = window.setTimeout(function(){
      getValue(el);
    },200);
  });  
});


function getData(key){
  var str = localStorage.getItem(key) || '';
  if (str.match(/[\}\]]/)){
    str = JSON.parse(str);
  }
  return str;
}

function setData(key, value){

  //console.log(key, value, typeof(value));
  var data;
  if (typeof(value) != 'string'){
    value = JSON.stringify(value);
  }
  if (typeof(key) != 'string'){
    value = JSON.stringify(key);
    key = "raw";
    data = { 'key': key, 'value': value};
  }
  else {
	  data = JSON.stringify({ 'key': key, 'value': value});
  }

  var args = arguments;
  localStorage.setItem(key, value);
  $.ajax({
    url:'/store/userdata/',
    data: data,
    method: 'POST',
    dataType: 'json',
    success: function(msg){ 
      $.each(msg, function(key,value){
        if (typeof(value) != 'string'){
          value = JSON.stringify(value);
        }
        localStorage.setItem(key, value);
      });
      if (args[2]){
        args[2](value, msg);
      }
    }
  })
}
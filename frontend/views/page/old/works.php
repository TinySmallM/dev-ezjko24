<?
$this->registerMetaTag(['name' => 'description', 'content' => $data['description']]);
$this->registerMetaTag(['property' => 'og:description', 'content' => $data['description']]);
$this->registerMetaTag(['property' => 'twitter:description', 'content' => $data['description']]);
$this->title = $data['title'];

$this->registerJsFile('https://cdn.jsdelivr.net/npm/vue/dist/vue.js');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js');

$this->registerCss('
.workItem div{
    height: 250px;
    display: block;
    background-size: cover;
    background-position: center;
}
.workItem div:hover{
    border: 3px solid #fa7d09;
}
');
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
			<h1 class="title" style="margin-top:0px">Примеры фото работ с реальных объектов</h1>
			<p>Ознакомьтесь с нашим <b>портфолио</b>. Это лишь небольшая часть фотографий наших кухонь, <b>сделанных на заказ</b>. Здесь Вы можете увидеть, как в реальности могут сочетаться стили наших кухонь с интерьером квартир. <b>Фотографии</b> отображают результат совместной работы наших дизайнеров и мастеров - команды компании «Кухни Века».</p>
		</div>
    </div>
    <div class="row" id="worksApp">
        <div class="col-6 col-md-3 mb-4 workItem" v-for="item in items">
            <a :href="'/upload/'+item.image" data-fancybox="gallery" :data-caption="item.description" >
                <div :style="'background-image: url(/upload/thumb_'+item.image+')'"></div>
            </a>
        </div>
    </div>
</div>

<?
$script = <<< JS
let app = new Vue({
	el: '#worksApp',
	data: {
        items: [],
        offset: 0,
        csrf: {param: csrfParam, token: csrfToken},
        preloadBlocked: false
	},
	mounted: function(){
		this.getItems();
		this.loadMore();
	},
	methods: {
		loadMore() {
			let app = this;
		    window.onscroll = () => {
		      let bottomOfWindow = $(window).scrollTop()+$(window).height()>=$(document).height()-1000;
		      if (bottomOfWindow && !app.preloadBlocked) {
		      	app.preloadBlocked = true;
		      	this.getItems();
		      }
		    };
		},
		getItems: function(){
			let app = this;
			
			axios.get('?type=api&offset='+app.offset)
			.then(function (res) {
				res = res.data;
			    if(res.items){
					for(i in res.items){
                        app.items.push(res.items[i]);
                        app.offset += 1;
                    }
			    }
			    else window.alert('Не могу загрузить');
			    
			    app.preloadBlocked = false;
			    
			})
			
        },
    }
});
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>
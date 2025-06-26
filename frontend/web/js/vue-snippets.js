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

function uploadStorageCrm(data){ //FormData object
    data.append('token_hash',localStorage.getItem('token_hash'));
    return new Promise((resolve, reject) => {
        axios({
            method: 'post',
            url: 'https://christmedschool.com/storage/upload',
            data: data,
            headers: {
                'Content-Type': 'multipart/form-data',
            }
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

Vue.component('select-date', {
    props: ['val'],
    data: function () {
        return {
            local_val: null
        }
    },
    mounted: function(){
        this.setLocalVal();
    },
    watch: {
        local_val: function(val){
            this.$emit('update-val', val)
        },
        val: function(val){
            this.setLocalVal();
        }
    },
    methods: {
        setLocalVal: function(){
            if(this.val){
                this.local_val = moment(this.val).format('Y-MM-DD');
            }
        },
    },
    template: `
		<div class="vue-select-date">
			<input type="date" v-model="local_val">
		</div>
	`
})

Vue.component('input-select', {
    props: ['options','val'],
    data: function () {
        return {
            local_val: null
        }
    },
    mounted: function(){
        this.setLocalVal();
    },
    watch: {
        local_val: function(val){
            this.$emit('update-val', val)
        },
        val: function(val){
            this.setLocalVal();
        }
    },
    methods: {
        setLocalVal: function(){
            this.local_val = this.val;
        }
    },
    template: `
		<div class="vue-input-select">
			<select v-model="local_val">
                <option v-for="(name,val) in options" :value="val" >{{name}}</option>
            </select>
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
            if(!local_src || local_src == '') return 'https://christmedschool.com/cdn/no-image.png';
            return 'https://christmedschool.com/upload/thumb_'+local_src;
        },
        fileUpload: function(event){
            let app = this;
            let formData = new FormData();

            $.each(app.$refs.file.files, function(i, file) {
                formData.append('file[]', file);
            });
            app.$refs.file.value = '';
            formData.append('watermark', app.watermark);

            uploadStorageCrm(formData).then(filesArr=>{
                for(i in filesArr){
                    app.local_src = filesArr[i].file;
                }
            }, err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Ошибка!',
                    text: 'Не удалось загрузить файлы на сервер',
                    timer: 1200
                })
                return;
            })

        }
    },
    template: `
		<div class="vue-image-upload">
			<input class="fileUpload" type="file" ref="file" @change="fileUpload()" style="display: none">
			
			<template v-if="src">
			<img 
				:src="checkSrc(src)" 
				class="img-thumbnail" 
				onclick="$(this).siblings('.fileUpload').click()" 
				style="cursor: pointer;width: 100%;border-radius: 5px;border: 2px solid #ffffff;box-shadow: 0 0 0px 1px #dddddd;" 
				title="Изменить"
			>
			</template>
			<template v-else>
				<div
					onclick="$(this).siblings('.fileUpload').click()" 
					style="cursor: pointer;font-size: 13px;" 
				>
                  <img :src="'https://christmedschool.com/cdn/no-image.png'" class="img-thumbnail" title="Изменить">
                
                </div>
			</template>
		</div>
	`
})
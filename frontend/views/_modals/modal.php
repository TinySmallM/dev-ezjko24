<?

$this->registerCss('
#order-modal .close{
	position: absolute;
	right: 10px;
	top: 6px;
	color: #fff;
	opacity: 100;
	outline: none;
}

.modal-open .modal {
	padding-left: 0px !important;
	padding-right: 0px !important;
	overflow-y: scroll;
  }
');

$this->registerCss('
body.modal-open {
    overflow: unset;
	width: 100%;
}
')

?>

<!-- Modal -->
<div class="modal fade" id="order-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="true" style="height: 100vh;">
<div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 100%;overflow-y: unset;z-index:10;">
  <div class="modal-content" style="background-color: rgba(0,0,0,0);">
  <?/*
	<div class="modal-body">
		<div class="form">
			<h2 class="section-title">Отправьте заявку и получите дизайн-проект <b>бесплатно</b></h2>
			<span class="small-descr">* - поля обязательные для заполнения</span>
			<form>
			    <!--<label style="color:#fff; font-size:24px" for="date">Дата и время звонка</label>-->
       <!--         <input class="form-control black-control"  type="datetime-local" name="date">-->
				<input type="text" name="name" class="form-control black-control mt-3 mb-3 mt-md-5" placeholder="* Имя" required>
				<input type="text" name="phone" class="form-control black-control phone-input mb-5" placeholder="* Телефон" required>
				<input type="hidden" name="formId" value="1">
				<input type=hidden name="product" value="">
				<!--
				<span class="attachment-label mb-2 mb-md-3">Прикрепить проект</span>
				<label class="input-file">
					<input name="attachment" type="file" class="d-none" multiple>
					<span class="btn btn-outline-primary">Выбрать файл</span>
					<span class="description">Файл не выбран</span>
				</label>
				-->
				<input type="submit" value="Отправить" class="btn btn-primary btn-xl mt-3 mt-md-4 mb-3">
				<span class="small-descr">Отправляя данные вы даете согласие на обработку персональных 
					данных в соответствии с политикой конфиденциальности</span>
			</form>
		</div>
		
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	*/?>

	<?=$this->render('../_blocks/project-form')?>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
  </div>
</div>
</div>
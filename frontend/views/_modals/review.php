<?
 use yii\helpers\Html;
?>
<div class="modal fade modal-feedback-form2 " tabindex="-1" role="dialog" aria-modal="true" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form class="sendFeedback">
            <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold">Оставить отзыв</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body mx-3">
          	
          	<div class="md-form mb-4">
              <i class="fas fa-user prefix grey-text"></i>
              <input name="phone" type="text"  class="form-control validate" placeholder="*Ваш телефон" required>
            </div>
    
            <div class="md-form mb-4">
              <i class="fas fa-user prefix grey-text"></i>
              <input name="name" type="text"  class="form-control validate" placeholder="*Ваше имя" required>
            </div>
            <div class="md-form mb-4">
              <i class="fas fa-pencil-alt prefix grey-text active"></i>
              <textarea name="message"  class="form-control validate pl-2" placeholder="*Введите текст" required></textarea>
            </div>
    
          </div>
          <div class="modal-footer d-flex justify-content-center">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Отправить</button>
          </div>
          
          <p class="px-4"><small>This site is protected by reCAPTCHA and the Google
                <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                <a href="https://policies.google.com/terms">Terms of Service</a> apply.
          </small></p>
        </form>
    </div>
  </div>
</div>
<?

use common\models\Helpers;
use yii\helpers\Url;

?>
<?php eval('?>'.Helpers::phReplace($data['content']) )?>
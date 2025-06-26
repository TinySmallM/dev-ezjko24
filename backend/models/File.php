<?php

namespace backend\models;

use Yii;
use common\models\Helpers;

class File
{
    
    
    public function resize($image, $image_thumb, $w_o = false, $h_o = false) {
		if (($w_o < 0) || ($h_o < 0)) {
		  echo "Некорректные входные параметры";
		  return false;
		}
		list($w_i, $h_i, $type) = getimagesize($image); // Получаем размеры и тип изображения (число)
		$types = array("", "gif", "jpeg", "png"); // Массив с типами изображений
		$ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа
		if ($ext) {
		  $func = 'imagecreatefrom'.$ext; // Получаем название функции, соответствующую типу, для создания изображения
		  $img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
		} else {
		  echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый
		  return false;
		}
		/* Если указать только 1 параметр, то второй подстроится пропорционально */
		if($w_i > $h_i){
			$h_o = $w_o / ($w_i / $h_i);
		}else{
			$w_o = $h_o / ($h_i / $w_i);
		}
		$img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения
		imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его
		$func = 'image'.$ext; // Получаем функция для сохранения результата
		return $func($img_o, $image_thumb); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
	}
}

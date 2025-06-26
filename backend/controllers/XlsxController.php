<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\models\Product;
use common\models\Helpers;

use moonland\phpexcel\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XlsxController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['all','api'],
                        'roles' => ['page'],
                        'allow' => true
                    ],
                ],
            ],
        ];
    }

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    public function beforeAction($action){
		$this->enableCsrfValidation = false;
		//Helpers::appGlobals();
		return parent::beforeAction($action);
	}

    public function actionExport(){
		
    	return $this->render('export');
        
    }
    
    public function actionApi(){
    	$reqBody = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	if (Yii::$app->request->get('type') == 'export.product'){

            $items = Product::find()->asArray()->all();

            $res = $this->arrToExcel($items);

            if( $res['dir'] ) {
                Yii::$app->response->sendFile($res['dir'], $res['name']);
            }
    		

    	}
    	
    }

    private function excelToJson(){
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $fileDir = $_FILES['file']['tmp_name'];
        
        
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($fileDir);
		
		$data = $spreadsheet->getActiveSheet()
			->toArray();

		$dOut = [];
		
		$keys = [];
		foreach($data[0] as $key_key=>$key_name){
	        $keys[$key_key] = $key_name;
	    }
		
		
		//create keys for each row
		foreach($data as $i=>$d){
		    if($i == 0) continue;
		    
		    //check row is empty
		    $rowIsEmpty = true;
		    foreach($d as $key=>$val){
		        if($val) $rowIsEmpty = false;
		    }
		    if($rowIsEmpty) continue;
		    
		    $rowNew = [];
		    foreach($keys as $key=>$val){
		       $rowNew[$val] = $d[$key];
		    }
		    
		    $dOut[] = $rowNew;
		    
		}
		
		return ['items'=>$dOut];
		
		
    }
    
    private function arrToExcel($data){
        $rb = Yii::$app->request->bodyParams;
    	Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	
    	$alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
    	
    	$keys = array_keys($data[0]);
    	
    	$head = [];

		foreach($keys as $key_i=>$key_name){
			$head[$key_name] = ['name'=>$key_name,'xPos'=>$alphabet[$key_i]];
		}
    	
    	$spreadsheet = new Spreadsheet();
    	$sheet = $spreadsheet->getActiveSheet();
    	
    	foreach($head as $k=>$d){
    		$sheet->getColumnDimension($d['xPos'])->setAutoSize(false);
			$sheet->setCellValue($d['xPos'].'1',$d['name']);
		}
		
		/* Рисуем тело таблицы */
		$numY=2;$numX=1;
		foreach($data as $item_key=>$item){
	
			foreach($keys as $key_i=>$key_name){
				$sheet->setCellValue($head[$key_name]['xPos'].($numY), !empty($item[$key_name])?$item[$key_name]:'');
			}
		    
			$numY = $numY+1;
		}
		
    	
    	/* Сохраняем и возвращаем ссылку */
		file_put_contents(Yii::$app->basePath.'/../frontend/web/file_export.xlsx','');
    	$writer = new Xlsx($spreadsheet);
		$writer->save(Yii::$app->basePath.'/../frontend/web/file_export.xlsx');	
		
		return ['name'=>'file_export.xlsx','dir'=>Yii::$app->basePath.'/../frontend/web/file_export.xlsx'];
    }

}

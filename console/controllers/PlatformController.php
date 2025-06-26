<?php
	namespace console\controllers;
	
	use Yii;
	use yii\console\Controller;
	use yii\httpclient\Client;
	use yii\Helpers\ArrayHelper;

	use common\models\PlatformPage;
    use common\models\Product;
    use common\models\ProductBuyAction;
    use common\models\Order;
    use common\models\Member;
    use common\models\PlatformAccess;
    use common\models\OrderItem;

	
	class PlatformController extends Controller {
		public function actionPage(){
            $dir = '/var/www/site_main/data/www/christmedschool.com/platform';

            $files = $this->getDirContents($dir);
            
            foreach($files as $f){
                if(!strpos($f,'.html')) continue;
                echo($f.PHP_EOL);

                $f_d = file_get_contents($f);

                $f_d = preg_replace('/{%.*%}/','',$f_d);
                $f_d = str_replace('<h1>Контент доступен только участникам школы</h1>','',$f_d);

                preg_match('/<h1[^>]*?>(.*?)<\/h1>/si', $f_d, $h1_matches);

                $h1 = '';
                if(isset($h1_matches[1])) $h1 = $h1_matches[1];

                $f_d = str_replace('https://christmedschool.com/host1/chr','',$f_d);
                $f_d = str_replace('href="','href="https://cdn.christmedschool.com',$f_d);
                $f_d = str_replace('src="','src="https://cdn.christmedschool.com',$f_d);

                $pp = new PlatformPage;
                $pp->h1 = $h1;
                $pp->content = trim($f_d);
                $pp->file = str_replace('/var/www/site_main/data/www/christmedschool.com/platform/','',$f);
                
                if(!$pp->save()) return print_r($pp->getErrors());


            }



        }

        function getDirContents($dir, &$results = array()) {
            $files = scandir($dir);
            foreach ($files as $key => $value) {
                $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
                if (!is_dir($path)) {
                    $results[] = $path;
                } else if ($value != "." && $value != "..") {
                    $this->getDirContents($path, $results);
                    $results[] = $path;
                }
            }
            return $results;
        }

        public function actionProduct(){
            $arr = [
                [
                    "id"=> "1",
                    "product_id"=> "1",
                    "theme_id"=> "43",
                    "scu"=> "700",
                    "theme_url"=> "intpns1"
                ],
                [
                    "id"=> "2",
                    "product_id"=> "1",
                    "theme_id"=> "44",
                    "scu"=> "700",
                    "theme_url"=> "intpns2"
                ],
                [
                    "id"=> "3",
                    "product_id"=> "1",
                    "theme_id"=> "45",
                    "scu"=> "700",
                    "theme_url"=> "intpns3"
                ],
                [
                    "id"=> "4",
                    "product_id"=> "1",
                    "theme_id"=> "46",
                    "scu"=> "700",
                    "theme_url"=> "intpns4"
                ],
                [
                    "id"=> "5",
                    "product_id"=> "1",
                    "theme_id"=> "47",
                    "scu"=> "700",
                    "theme_url"=> "intpns5"
                ],
                [
                    "id"=> "55",
                    "product_id"=> "4",
                    "theme_id"=> "32",
                    "scu"=> "702",
                    "theme_url"=> "biochem4"
                ],
                [
                    "id"=> "56",
                    "product_id"=> "2",
                    "theme_id"=> "39",
                    "scu"=> "701",
                    "theme_url"=> "intenseinf1"
                ],
                [
                    "id"=> "57",
                    "product_id"=> "2",
                    "theme_id"=> "40",
                    "scu"=> "701",
                    "theme_url"=> "intenseinf2"
                ],
                [
                    "id"=> "58",
                    "product_id"=> "2",
                    "theme_id"=> "41",
                    "scu"=> "701",
                    "theme_url"=> "intenseinf3"
                ],
                [
                    "id"=> "59",
                    "product_id"=> "2",
                    "theme_id"=> "42",
                    "scu"=> "701",
                    "theme_url"=> "intenseinf4"
                ],
                [
                    "id"=> "60",
                    "product_id"=> "5",
                    "theme_id"=> "32",
                    "scu"=> "703",
                    "theme_url"=> "biochem4"
                ],
                [
                    "id"=> "61",
                    "product_id"=> "6",
                    "theme_id"=> "23",
                    "scu"=> "704",
                    "theme_url"=> "biochem3"
                ],
                [
                    "id"=> "62",
                    "product_id"=> "7",
                    "theme_id"=> "23",
                    "scu"=> "705",
                    "theme_url"=> "biochem3"
                ],
                [
                    "id"=> "63",
                    "product_id"=> "8",
                    "theme_id"=> "15",
                    "scu"=> "706",
                    "theme_url"=> "biochem2"
                ],
                [
                    "id"=> "64",
                    "product_id"=> "9",
                    "theme_id"=> "15",
                    "scu"=> "707",
                    "theme_url"=> "biochem2"
                ],
                [
                    "id"=> "65",
                    "product_id"=> "10",
                    "theme_id"=> "14",
                    "scu"=> "708",
                    "theme_url"=> "biochem1"
                ],
                [
                    "id"=> "66",
                    "product_id"=> "11",
                    "theme_id"=> "14",
                    "scu"=> "709",
                    "theme_url"=> "biochem1"
                ],
                [
                    "id"=> "67",
                    "product_id"=> "12",
                    "theme_id"=> "14",
                    "scu"=> "710",
                    "theme_url"=> "biochem1"
                ],
                [
                    "id"=> "68",
                    "product_id"=> "12",
                    "theme_id"=> "15",
                    "scu"=> "710",
                    "theme_url"=> "biochem2"
                ],
                [
                    "id"=> "69",
                    "product_id"=> "12",
                    "theme_id"=> "23",
                    "scu"=> "710",
                    "theme_url"=> "biochem3"
                ],
                [
                    "id"=> "70",
                    "product_id"=> "12",
                    "theme_id"=> "32",
                    "scu"=> "710",
                    "theme_url"=> "biochem4"
                ],
                [
                    "id"=> "71",
                    "product_id"=> "13",
                    "theme_id"=> "14",
                    "scu"=> "711",
                    "theme_url"=> "biochem1"
                ],
                [
                    "id"=> "72",
                    "product_id"=> "13",
                    "theme_id"=> "15",
                    "scu"=> "711",
                    "theme_url"=> "biochem2"
                ],
                [
                    "id"=> "73",
                    "product_id"=> "13",
                    "theme_id"=> "23",
                    "scu"=> "711",
                    "theme_url"=> "biochem3"
                ],
                [
                    "id"=> "74",
                    "product_id"=> "13",
                    "theme_id"=> "32",
                    "scu"=> "711",
                    "theme_url"=> "biochem4"
                ],
                [
                    "id"=> "75",
                    "product_id"=> "14",
                    "theme_id"=> "38",
                    "scu"=> "712",
                    "theme_url"=> "myology4"
                ],
                [
                    "id"=> "76",
                    "product_id"=> "15",
                    "theme_id"=> "38",
                    "scu"=> "713",
                    "theme_url"=> "myology4"
                ],
                [
                    "id"=> "77",
                    "product_id"=> "16",
                    "theme_id"=> "37",
                    "scu"=> "714",
                    "theme_url"=> "myology3"
                ],
                [
                    "id"=> "78",
                    "product_id"=> "17",
                    "theme_id"=> "37",
                    "scu"=> "715",
                    "theme_url"=> "myology3"
                ],
                [
                    "id"=> "79",
                    "product_id"=> "18",
                    "theme_id"=> "31",
                    "scu"=> "716",
                    "theme_url"=> "myology2"
                ],
                [
                    "id"=> "80",
                    "product_id"=> "19",
                    "theme_id"=> "31",
                    "scu"=> "717",
                    "theme_url"=> "myology2"
                ],
                [
                    "id"=> "81",
                    "product_id"=> "20",
                    "theme_id"=> "30",
                    "scu"=> "718",
                    "theme_url"=> "myology1"
                ],
                [
                    "id"=> "82",
                    "product_id"=> "21",
                    "theme_id"=> "30",
                    "scu"=> "719",
                    "theme_url"=> "myology1"
                ],
                [
                    "id"=> "83",
                    "product_id"=> "22",
                    "theme_id"=> "30",
                    "scu"=> "720",
                    "theme_url"=> "myology1"
                ],
                [
                    "id"=> "84",
                    "product_id"=> "22",
                    "theme_id"=> "31",
                    "scu"=> "720",
                    "theme_url"=> "myology2"
                ],
                [
                    "id"=> "85",
                    "product_id"=> "22",
                    "theme_id"=> "37",
                    "scu"=> "720",
                    "theme_url"=> "myology3"
                ],
                [
                    "id"=> "86",
                    "product_id"=> "22",
                    "theme_id"=> "38",
                    "scu"=> "720",
                    "theme_url"=> "myology4"
                ],
                [
                    "id"=> "87",
                    "product_id"=> "23",
                    "theme_id"=> "30",
                    "scu"=> "721",
                    "theme_url"=> "myology1"
                ],
                [
                    "id"=> "88",
                    "product_id"=> "23",
                    "theme_id"=> "31",
                    "scu"=> "721",
                    "theme_url"=> "myology2"
                ],
                [
                    "id"=> "89",
                    "product_id"=> "23",
                    "theme_id"=> "37",
                    "scu"=> "721",
                    "theme_url"=> "myology3"
                ],
                [
                    "id"=> "90",
                    "product_id"=> "23",
                    "theme_id"=> "38",
                    "scu"=> "721",
                    "theme_url"=> "myology4"
                ],
                [
                    "id"=> "91",
                    "product_id"=> "24",
                    "theme_id"=> "24",
                    "scu"=> "722",
                    "theme_url"=> "intensecenter2"
                ],
                [
                    "id"=> "92",
                    "product_id"=> "24",
                    "theme_id"=> "25",
                    "scu"=> "722",
                    "theme_url"=> "intensecenter1"
                ],
                [
                    "id"=> "93",
                    "product_id"=> "24",
                    "theme_id"=> "26",
                    "scu"=> "722",
                    "theme_url"=> "intensecenter3"
                ],
                [
                    "id"=> "94",
                    "product_id"=> "24",
                    "theme_id"=> "27",
                    "scu"=> "722",
                    "theme_url"=> "intensecenter4"
                ],
                [
                    "id"=> "95",
                    "product_id"=> "24",
                    "theme_id"=> "28",
                    "scu"=> "722",
                    "theme_url"=> "intensecenter5"
                ],
                [
                    "id"=> "96",
                    "product_id"=> "24",
                    "theme_id"=> "29",
                    "scu"=> "722",
                    "theme_url"=> "intensecenter6"
                ],
                [
                    "id"=> "97",
                    "product_id"=> "25",
                    "theme_id"=> "22",
                    "scu"=> "723",
                    "theme_url"=> "microbiology3"
                ],
                [
                    "id"=> "98",
                    "product_id"=> "26",
                    "theme_id"=> "22",
                    "scu"=> "724",
                    "theme_url"=> "microbiology3"
                ],
                [
                    "id"=> "99",
                    "product_id"=> "27",
                    "theme_id"=> "21",
                    "scu"=> "725",
                    "theme_url"=> "microbiology2"
                ],
                [
                    "id"=> "100",
                    "product_id"=> "28",
                    "theme_id"=> "21",
                    "scu"=> "726",
                    "theme_url"=> "microbiology2"
                ],
                [
                    "id"=> "101",
                    "product_id"=> "29",
                    "theme_id"=> "20",
                    "scu"=> "727",
                    "theme_url"=> "microbiology1"
                ],
                [
                    "id"=> "102",
                    "product_id"=> "30",
                    "theme_id"=> "20",
                    "scu"=> "728",
                    "theme_url"=> "microbiology1"
                ],
                [
                    "id"=> "103",
                    "product_id"=> "31",
                    "theme_id"=> "20",
                    "scu"=> "729",
                    "theme_url"=> "microbiology1"
                ],
                [
                    "id"=> "104",
                    "product_id"=> "31",
                    "theme_id"=> "21",
                    "scu"=> "729",
                    "theme_url"=> "microbiology2"
                ],
                [
                    "id"=> "105",
                    "product_id"=> "31",
                    "theme_id"=> "22",
                    "scu"=> "729",
                    "theme_url"=> "microbiology3"
                ],
                [
                    "id"=> "106",
                    "product_id"=> "32",
                    "theme_id"=> "20",
                    "scu"=> "730",
                    "theme_url"=> "microbiology1"
                ],
                [
                    "id"=> "107",
                    "product_id"=> "32",
                    "theme_id"=> "21",
                    "scu"=> "730",
                    "theme_url"=> "microbiology2"
                ],
                [
                    "id"=> "108",
                    "product_id"=> "32",
                    "theme_id"=> "22",
                    "scu"=> "730",
                    "theme_url"=> "microbiology3"
                ],
                [
                    "id"=> "109",
                    "product_id"=> "33",
                    "theme_id"=> "19",
                    "scu"=> "731",
                    "theme_url"=> "arthrology3"
                ],
                [
                    "id"=> "110",
                    "product_id"=> "34",
                    "theme_id"=> "19",
                    "scu"=> "732",
                    "theme_url"=> "arthrology3"
                ],
                [
                    "id"=> "111",
                    "product_id"=> "35",
                    "theme_id"=> "18",
                    "scu"=> "733",
                    "theme_url"=> "arthrology2"
                ],
                [
                    "id"=> "112",
                    "product_id"=> "36",
                    "theme_id"=> "18",
                    "scu"=> "734",
                    "theme_url"=> "arthrology2"
                ],
                [
                    "id"=> "113",
                    "product_id"=> "37",
                    "theme_id"=> "17",
                    "scu"=> "735",
                    "theme_url"=> "arthrology1"
                ],
                [
                    "id"=> "114",
                    "product_id"=> "38",
                    "theme_id"=> "17",
                    "scu"=> "736",
                    "theme_url"=> "arthrology1"
                ],
                [
                    "id"=> "115",
                    "product_id"=> "39",
                    "theme_id"=> "17",
                    "scu"=> "737",
                    "theme_url"=> "arthrology1"
                ],
                [
                    "id"=> "116",
                    "product_id"=> "39",
                    "theme_id"=> "18",
                    "scu"=> "737",
                    "theme_url"=> "arthrology2"
                ],
                [
                    "id"=> "117",
                    "product_id"=> "39",
                    "theme_id"=> "19",
                    "scu"=> "737",
                    "theme_url"=> "arthrology3"
                ],
                [
                    "id"=> "118",
                    "product_id"=> "40",
                    "theme_id"=> "17",
                    "scu"=> "738",
                    "theme_url"=> "arthrology1"
                ],
                [
                    "id"=> "119",
                    "product_id"=> "40",
                    "theme_id"=> "18",
                    "scu"=> "738",
                    "theme_url"=> "arthrology2"
                ],
                [
                    "id"=> "120",
                    "product_id"=> "40",
                    "theme_id"=> "19",
                    "scu"=> "738",
                    "theme_url"=> "arthrology3"
                ],
                [
                    "id"=> "121",
                    "product_id"=> "41",
                    "theme_id"=> "9",
                    "scu"=> "739",
                    "theme_url"=> "webinar1"
                ],
                [
                    "id"=> "122",
                    "product_id"=> "41",
                    "theme_id"=> "10",
                    "scu"=> "739",
                    "theme_url"=> "webinar2"
                ],
                [
                    "id"=> "123",
                    "product_id"=> "41",
                    "theme_id"=> "11",
                    "scu"=> "739",
                    "theme_url"=> "webinar3"
                ],
                [
                    "id"=> "124",
                    "product_id"=> "41",
                    "theme_id"=> "12",
                    "scu"=> "739",
                    "theme_url"=> "webinar4"
                ],
                [
                    "id"=> "125",
                    "product_id"=> "41",
                    "theme_id"=> "13",
                    "scu"=> "739",
                    "theme_url"=> "webinar5"
                ],
                [
                    "id"=> "126",
                    "product_id"=> "42",
                    "theme_id"=> "6",
                    "scu"=> "740",
                    "theme_url"=> "urok6"
                ],
                [
                    "id"=> "127",
                    "product_id"=> "43",
                    "theme_id"=> "6",
                    "scu"=> "741",
                    "theme_url"=> "urok6"
                ],
                [
                    "id"=> "128",
                    "product_id"=> "44",
                    "theme_id"=> "5",
                    "scu"=> "742",
                    "theme_url"=> "urok5"
                ],
                [
                    "id"=> "129",
                    "product_id"=> "45",
                    "theme_id"=> "5",
                    "scu"=> "743",
                    "theme_url"=> "urok5"
                ],
                [
                    "id"=> "130",
                    "product_id"=> "46",
                    "theme_id"=> "4",
                    "scu"=> "744",
                    "theme_url"=> "urok4"
                ],
                [
                    "id"=> "131",
                    "product_id"=> "47",
                    "theme_id"=> "4",
                    "scu"=> "745",
                    "theme_url"=> "urok4"
                ],
                [
                    "id"=> "132",
                    "product_id"=> "48",
                    "theme_id"=> "3",
                    "scu"=> "746",
                    "theme_url"=> "urok3"
                ],
                [
                    "id"=> "133",
                    "product_id"=> "49",
                    "theme_id"=> "3",
                    "scu"=> "747",
                    "theme_url"=> "urok3"
                ],
                [
                    "id"=> "134",
                    "product_id"=> "50",
                    "theme_id"=> "2",
                    "scu"=> "748",
                    "theme_url"=> "urok2"
                ],
                [
                    "id"=> "135",
                    "product_id"=> "51",
                    "theme_id"=> "2",
                    "scu"=> "749",
                    "theme_url"=> "urok2"
                ],
                [
                    "id"=> "136",
                    "product_id"=> "52",
                    "theme_id"=> "1",
                    "scu"=> "750",
                    "theme_url"=> "urok1"
                ],
                [
                    "id"=> "137",
                    "product_id"=> "53",
                    "theme_id"=> "1",
                    "scu"=> "751",
                    "theme_url"=> "urok1"
                ],
                [
                    "id"=> "138",
                    "product_id"=> "53",
                    "theme_id"=> "2",
                    "scu"=> "751",
                    "theme_url"=> "urok2"
                ],
                [
                    "id"=> "139",
                    "product_id"=> "53",
                    "theme_id"=> "3",
                    "scu"=> "751",
                    "theme_url"=> "urok3"
                ],
                [
                    "id"=> "140",
                    "product_id"=> "53",
                    "theme_id"=> "4",
                    "scu"=> "751",
                    "theme_url"=> "urok4"
                ],
                [
                    "id"=> "141",
                    "product_id"=> "53",
                    "theme_id"=> "5",
                    "scu"=> "751",
                    "theme_url"=> "urok5"
                ],
                [
                    "id"=> "142",
                    "product_id"=> "53",
                    "theme_id"=> "6",
                    "scu"=> "751",
                    "theme_url"=> "urok6"
                ],
                [
                    "id"=> "143",
                    "product_id"=> "54",
                    "theme_id"=> "1",
                    "scu"=> "752",
                    "theme_url"=> "urok1"
                ],
                [
                    "id"=> "144",
                    "product_id"=> "54",
                    "theme_id"=> "2",
                    "scu"=> "752",
                    "theme_url"=> "urok2"
                ],
                [
                    "id"=> "145",
                    "product_id"=> "54",
                    "theme_id"=> "3",
                    "scu"=> "752",
                    "theme_url"=> "urok3"
                ],
                [
                    "id"=> "146",
                    "product_id"=> "54",
                    "theme_id"=> "4",
                    "scu"=> "752",
                    "theme_url"=> "urok4"
                ],
                [
                    "id"=> "147",
                    "product_id"=> "54",
                    "theme_id"=> "5",
                    "scu"=> "752",
                    "theme_url"=> "urok5"
                ],
                [
                    "id"=> "148",
                    "product_id"=> "54",
                    "theme_id"=> "6",
                    "scu"=> "752",
                    "theme_url"=> "urok6"
                ],
                [
                    "id"=> "149",
                    "product_id"=> "55",
                    "theme_id"=> "33",
                    "scu"=> "753",
                    "theme_url"=> "intu4"
                ],
                [
                    "id"=> "150",
                    "product_id"=> "55",
                    "theme_id"=> "34",
                    "scu"=> "753",
                    "theme_url"=> "intu5"
                ],
                [
                    "id"=> "151",
                    "product_id"=> "55",
                    "theme_id"=> "48",
                    "scu"=> "753",
                    "theme_url"=> "intu1"
                ],
                [
                    "id"=> "152",
                    "product_id"=> "55",
                    "theme_id"=> "49",
                    "scu"=> "753",
                    "theme_url"=> "intu2"
                ],
                [
                    "id"=> "153",
                    "product_id"=> "55",
                    "theme_id"=> "50",
                    "scu"=> "753",
                    "theme_url"=> "intu3"
                ],
                [
                    "id"=> "154",
                    "product_id"=> "56",
                    "theme_id"=> "52",
                    "scu"=> "754",
                    "theme_url"=> "microbiology4"
                ],
                [
                    "id"=> "155",
                    "product_id"=> "57",
                    "theme_id"=> "52",
                    "scu"=> "755",
                    "theme_url"=> "microbiology4"
                ],
                [
                    "id"=> "156",
                    "product_id"=> "31",
                    "theme_id"=> "52",
                    "scu"=> "729",
                    "theme_url"=> "microbiology4"
                ],
                [
                    "id"=> "157",
                    "product_id"=> "32",
                    "theme_id"=> "52",
                    "scu"=> "730",
                    "theme_url"=> "microbiology4"
                ],
                [
                    "id"=> "160",
                    "product_id"=> "58",
                    "theme_id"=> "56",
                    "scu"=> "756",
                    "theme_url"=> "biochemz8"
                ],
                [
                    "id"=> "161",
                    "product_id"=> "59",
                    "theme_id"=> "56",
                    "scu"=> "757",
                    "theme_url"=> "biochemz8"
                ],
                [
                    "id"=> "162",
                    "product_id"=> "58",
                    "theme_id"=> "55",
                    "scu"=> "756",
                    "theme_url"=> "biochemz7"
                ],
                [
                    "id"=> "163",
                    "product_id"=> "59",
                    "theme_id"=> "55",
                    "scu"=> "757",
                    "theme_url"=> "biochemz7"
                ],
                [
                    "id"=> "165",
                    "product_id"=> "60",
                    "theme_id"=> "55",
                    "scu"=> "758",
                    "theme_url"=> "biochemz7"
                ],
                [
                    "id"=> "166",
                    "product_id"=> "61",
                    "theme_id"=> "55",
                    "scu"=> "759",
                    "theme_url"=> "biochemz7"
                ],
                [
                    "id"=> "167",
                    "product_id"=> "62",
                    "theme_id"=> "56",
                    "scu"=> "760",
                    "theme_url"=> "biochemz8"
                ],
                [
                    "id"=> "168",
                    "product_id"=> "63",
                    "theme_id"=> "56",
                    "scu"=> "761",
                    "theme_url"=> "biochemz8"
                ],
                [
                    "id"=> "171",
                    "product_id"=> "66",
                    "theme_id"=> "61",
                    "scu"=> "764",
                    "theme_url"=> "gist3"
                ],
                [
                    "id"=> "172",
                    "product_id"=> "66",
                    "theme_id"=> "59",
                    "scu"=> "764",
                    "theme_url"=> "gist1"
                ],
                [
                    "id"=> "173",
                    "product_id"=> "66",
                    "theme_id"=> "60",
                    "scu"=> "764",
                    "theme_url"=> "gist2"
                ],
                [
                    "id"=> "174",
                    "product_id"=> "66",
                    "theme_id"=> "62",
                    "scu"=> "764",
                    "theme_url"=> "gist4"
                ],
                [
                    "id"=> "175",
                    "product_id"=> "66",
                    "theme_id"=> "58",
                    "scu"=> "764",
                    "theme_url"=> "gist0"
                ],
                [
                    "id"=> "176",
                    "product_id"=> "66",
                    "theme_id"=> "63",
                    "scu"=> "764",
                    "theme_url"=> "gist5"
                ],
                [
                    "id"=> "177",
                    "product_id"=> "67",
                    "theme_id"=> "61",
                    "scu"=> "765",
                    "theme_url"=> "gist3"
                ],
                [
                    "id"=> "178",
                    "product_id"=> "67",
                    "theme_id"=> "59",
                    "scu"=> "765",
                    "theme_url"=> "gist1"
                ],
                [
                    "id"=> "179",
                    "product_id"=> "67",
                    "theme_id"=> "60",
                    "scu"=> "765",
                    "theme_url"=> "gist2"
                ],
                [
                    "id"=> "180",
                    "product_id"=> "67",
                    "theme_id"=> "62",
                    "scu"=> "765",
                    "theme_url"=> "gist4"
                ],
                [
                    "id"=> "181",
                    "product_id"=> "67",
                    "theme_id"=> "58",
                    "scu"=> "765",
                    "theme_url"=> "gist0"
                ],
                [
                    "id"=> "182",
                    "product_id"=> "67",
                    "theme_id"=> "63",
                    "scu"=> "765",
                    "theme_url"=> "gist5"
                ],
                [
                    "id"=> "183",
                    "product_id"=> "68",
                    "theme_id"=> "58",
                    "scu"=> "766",
                    "theme_url"=> "gist0"
                ],
                [
                    "id"=> "184",
                    "product_id"=> "69",
                    "theme_id"=> "59",
                    "scu"=> "767",
                    "theme_url"=> "gist1"
                ],
                [
                    "id"=> "185",
                    "product_id"=> "70",
                    "theme_id"=> "59",
                    "scu"=> "768",
                    "theme_url"=> "gist1"
                ],
                [
                    "id"=> "186",
                    "product_id"=> "71",
                    "theme_id"=> "60",
                    "scu"=> "769",
                    "theme_url"=> "gist2"
                ],
                [
                    "id"=> "187",
                    "product_id"=> "72",
                    "theme_id"=> "60",
                    "scu"=> "770",
                    "theme_url"=> "gist2"
                ],
                [
                    "id"=> "188",
                    "product_id"=> "73",
                    "theme_id"=> "61",
                    "scu"=> "771",
                    "theme_url"=> "gist3"
                ],
                [
                    "id"=> "189",
                    "product_id"=> "74",
                    "theme_id"=> "61",
                    "scu"=> "772",
                    "theme_url"=> "gist3"
                ],
                [
                    "id"=> "190",
                    "product_id"=> "75",
                    "theme_id"=> "62",
                    "scu"=> "773",
                    "theme_url"=> "gist4"
                ],
                [
                    "id"=> "191",
                    "product_id"=> "76",
                    "theme_id"=> "62",
                    "scu"=> "774",
                    "theme_url"=> "gist4"
                ],
                [
                    "id"=> "192",
                    "product_id"=> "77",
                    "theme_id"=> "63",
                    "scu"=> "775",
                    "theme_url"=> "gist5"
                ],
                [
                    "id"=> "193",
                    "product_id"=> "78",
                    "theme_id"=> "63",
                    "scu"=> "776",
                    "theme_url"=> "gist5"
                ],
                [
                    "id"=> "194",
                    "product_id"=> "79",
                    "theme_id"=> "66",
                    "scu"=> "777",
                    "theme_url"=> "farm2"
                ],
                [
                    "id"=> "195",
                    "product_id"=> "79",
                    "theme_id"=> "67",
                    "scu"=> "777",
                    "theme_url"=> "farm3"
                ],
                [
                    "id"=> "196",
                    "product_id"=> "79",
                    "theme_id"=> "64",
                    "scu"=> "777",
                    "theme_url"=> "farm0"
                ],
                [
                    "id"=> "197",
                    "product_id"=> "79",
                    "theme_id"=> "65",
                    "scu"=> "777",
                    "theme_url"=> "farm1"
                ],
                [
                    "id"=> "198",
                    "product_id"=> "79",
                    "theme_id"=> "68",
                    "scu"=> "777",
                    "theme_url"=> "farm4"
                ],
                [
                    "id"=> "199",
                    "product_id"=> "79",
                    "theme_id"=> "69",
                    "scu"=> "777",
                    "theme_url"=> "farm5"
                ],
                [
                    "id"=> "200",
                    "product_id"=> "80",
                    "theme_id"=> "66",
                    "scu"=> "778",
                    "theme_url"=> "farm2"
                ],
                [
                    "id"=> "201",
                    "product_id"=> "80",
                    "theme_id"=> "67",
                    "scu"=> "778",
                    "theme_url"=> "farm3"
                ],
                [
                    "id"=> "202",
                    "product_id"=> "80",
                    "theme_id"=> "64",
                    "scu"=> "778",
                    "theme_url"=> "farm0"
                ],
                [
                    "id"=> "203",
                    "product_id"=> "80",
                    "theme_id"=> "65",
                    "scu"=> "778",
                    "theme_url"=> "farm1"
                ],
                [
                    "id"=> "204",
                    "product_id"=> "80",
                    "theme_id"=> "68",
                    "scu"=> "778",
                    "theme_url"=> "farm4"
                ],
                [
                    "id"=> "205",
                    "product_id"=> "80",
                    "theme_id"=> "69",
                    "scu"=> "778",
                    "theme_url"=> "farm5"
                ],
                [
                    "id"=> "206",
                    "product_id"=> "81",
                    "theme_id"=> "64",
                    "scu"=> "779",
                    "theme_url"=> "farm0"
                ],
                [
                    "id"=> "207",
                    "product_id"=> "82",
                    "theme_id"=> "65",
                    "scu"=> "780",
                    "theme_url"=> "farm1"
                ],
                [
                    "id"=> "208",
                    "product_id"=> "83",
                    "theme_id"=> "65",
                    "scu"=> "781",
                    "theme_url"=> "farm1"
                ],
                [
                    "id"=> "209",
                    "product_id"=> "84",
                    "theme_id"=> "66",
                    "scu"=> "782",
                    "theme_url"=> "farm2"
                ],
                [
                    "id"=> "210",
                    "product_id"=> "85",
                    "theme_id"=> "66",
                    "scu"=> "783",
                    "theme_url"=> "farm2"
                ],
                [
                    "id"=> "211",
                    "product_id"=> "86",
                    "theme_id"=> "67",
                    "scu"=> "784",
                    "theme_url"=> "farm3"
                ],
                [
                    "id"=> "212",
                    "product_id"=> "87",
                    "theme_id"=> "67",
                    "scu"=> "785",
                    "theme_url"=> "farm3"
                ],
                [
                    "id"=> "213",
                    "product_id"=> "88",
                    "theme_id"=> "68",
                    "scu"=> "786",
                    "theme_url"=> "farm4"
                ],
                [
                    "id"=> "214",
                    "product_id"=> "89",
                    "theme_id"=> "68",
                    "scu"=> "787",
                    "theme_url"=> "farm4"
                ],
                [
                    "id"=> "215",
                    "product_id"=> "90",
                    "theme_id"=> "69",
                    "scu"=> "788",
                    "theme_url"=> "farm5"
                ],
                [
                    "id"=> "216",
                    "product_id"=> "91",
                    "theme_id"=> "69",
                    "scu"=> "789",
                    "theme_url"=> "farm5"
                ],
                [
                    "id"=> "217",
                    "product_id"=> "92",
                    "theme_id"=> "70",
                    "scu"=> "790",
                    "theme_url"=> "psystem0"
                ],
                [
                    "id"=> "218",
                    "product_id"=> "92",
                    "theme_id"=> "71",
                    "scu"=> "790",
                    "theme_url"=> "psystem1"
                ],
                [
                    "id"=> "219",
                    "product_id"=> "92",
                    "theme_id"=> "72",
                    "scu"=> "790",
                    "theme_url"=> "psystem2"
                ],
                [
                    "id"=> "220",
                    "product_id"=> "92",
                    "theme_id"=> "73",
                    "scu"=> "790",
                    "theme_url"=> "psystem3"
                ],
                [
                    "id"=> "221",
                    "product_id"=> "93",
                    "theme_id"=> "70",
                    "scu"=> "791",
                    "theme_url"=> "psystem0"
                ],
                [
                    "id"=> "222",
                    "product_id"=> "93",
                    "theme_id"=> "71",
                    "scu"=> "791",
                    "theme_url"=> "psystem1"
                ],
                [
                    "id"=> "223",
                    "product_id"=> "93",
                    "theme_id"=> "72",
                    "scu"=> "791",
                    "theme_url"=> "psystem2"
                ],
                [
                    "id"=> "224",
                    "product_id"=> "93",
                    "theme_id"=> "73",
                    "scu"=> "791",
                    "theme_url"=> "psystem3"
                ],
                [
                    "id"=> "225",
                    "product_id"=> "94",
                    "theme_id"=> "71",
                    "scu"=> "792",
                    "theme_url"=> "psystem1"
                ],
                [
                    "id"=> "226",
                    "product_id"=> "95",
                    "theme_id"=> "72",
                    "scu"=> "793",
                    "theme_url"=> "psystem2"
                ],
                [
                    "id"=> "227",
                    "product_id"=> "96",
                    "theme_id"=> "72",
                    "scu"=> "794",
                    "theme_url"=> "psystem2"
                ],
                [
                    "id"=> "228",
                    "product_id"=> "97",
                    "theme_id"=> "73",
                    "scu"=> "795",
                    "theme_url"=> "psystem3"
                ],
                [
                    "id"=> "229",
                    "product_id"=> "98",
                    "theme_id"=> "73",
                    "scu"=> "796",
                    "theme_url"=> "psystem3"
                ],
                [
                    "id"=> "230",
                    "product_id"=> "99",
                    "theme_id"=> "71",
                    "scu"=> "797",
                    "theme_url"=> "psystem1"
                ],
                [
                    "id"=> "231",
                    "product_id"=> "100",
                    "theme_id"=> "74",
                    "scu"=> "798",
                    "theme_url"=> "krv1"
                ],
                [
                    "id"=> "232",
                    "product_id"=> "100",
                    "theme_id"=> "75",
                    "scu"=> "798",
                    "theme_url"=> "krv2"
                ],
                [
                    "id"=> "233",
                    "product_id"=> "100",
                    "theme_id"=> "76",
                    "scu"=> "798",
                    "theme_url"=> "krv3"
                ],
                [
                    "id"=> "234",
                    "product_id"=> "100",
                    "theme_id"=> "77",
                    "scu"=> "798",
                    "theme_url"=> "krv4"
                ],
                [
                    "id"=> "235",
                    "product_id"=> "101",
                    "theme_id"=> "79",
                    "scu"=> "799",
                    "theme_url"=> "lipobmen1"
                ],
                [
                    "id"=> "236",
                    "product_id"=> "101",
                    "theme_id"=> "80",
                    "scu"=> "799",
                    "theme_url"=> "lipobmen2"
                ],
                [
                    "id"=> "237",
                    "product_id"=> "101",
                    "theme_id"=> "81",
                    "scu"=> "799",
                    "theme_url"=> "lipobmen3"
                ],
                [
                    "id"=> "238",
                    "product_id"=> "101",
                    "theme_id"=> "82",
                    "scu"=> "799",
                    "theme_url"=> "lipobmen4"
                ],
                [
                    "id"=> "239",
                    "product_id"=> "101",
                    "theme_id"=> "78",
                    "scu"=> "799",
                    "theme_url"=> "lipobmen0"
                ],
                [
                    "id"=> "240",
                    "product_id"=> "101",
                    "theme_id"=> "83",
                    "scu"=> "799",
                    "theme_url"=> "lipobmen5"
                ],
                [
                    "id"=> "241",
                    "product_id"=> "102",
                    "theme_id"=> "79",
                    "scu"=> "800",
                    "theme_url"=> "lipobmen1"
                ],
                [
                    "id"=> "242",
                    "product_id"=> "102",
                    "theme_id"=> "80",
                    "scu"=> "800",
                    "theme_url"=> "lipobmen2"
                ],
                [
                    "id"=> "243",
                    "product_id"=> "102",
                    "theme_id"=> "81",
                    "scu"=> "800",
                    "theme_url"=> "lipobmen3"
                ],
                [
                    "id"=> "244",
                    "product_id"=> "102",
                    "theme_id"=> "82",
                    "scu"=> "800",
                    "theme_url"=> "lipobmen4"
                ],
                [
                    "id"=> "245",
                    "product_id"=> "102",
                    "theme_id"=> "78",
                    "scu"=> "800",
                    "theme_url"=> "lipobmen0"
                ],
                [
                    "id"=> "246",
                    "product_id"=> "102",
                    "theme_id"=> "83",
                    "scu"=> "800",
                    "theme_url"=> "lipobmen5"
                ],
                [
                    "id"=> "247",
                    "product_id"=> "103",
                    "theme_id"=> "79",
                    "scu"=> "801",
                    "theme_url"=> "lipobmen1"
                ],
                [
                    "id"=> "248",
                    "product_id"=> "104",
                    "theme_id"=> "79",
                    "scu"=> "802",
                    "theme_url"=> "lipobmen1"
                ],
                [
                    "id"=> "249",
                    "product_id"=> "105",
                    "theme_id"=> "78",
                    "scu"=> "803",
                    "theme_url"=> "lipobmen0"
                ],
                [
                    "id"=> "250",
                    "product_id"=> "106",
                    "theme_id"=> "80",
                    "scu"=> "804",
                    "theme_url"=> "lipobmen2"
                ],
                [
                    "id"=> "251",
                    "product_id"=> "107",
                    "theme_id"=> "80",
                    "scu"=> "805",
                    "theme_url"=> "lipobmen2"
                ],
                [
                    "id"=> "252",
                    "product_id"=> "108",
                    "theme_id"=> "81",
                    "scu"=> "806",
                    "theme_url"=> "lipobmen3"
                ],
                [
                    "id"=> "253",
                    "product_id"=> "109",
                    "theme_id"=> "81",
                    "scu"=> "807",
                    "theme_url"=> "lipobmen3"
                ],
                [
                    "id"=> "254",
                    "product_id"=> "110",
                    "theme_id"=> "83",
                    "scu"=> "808",
                    "theme_url"=> "lipobmen5"
                ],
                [
                    "id"=> "255",
                    "product_id"=> "111",
                    "theme_id"=> "83",
                    "scu"=> "809",
                    "theme_url"=> "lipobmen5"
                ],
                [
                    "id"=> "256",
                    "product_id"=> "112",
                    "theme_id"=> "82",
                    "scu"=> "810",
                    "theme_url"=> "lipobmen4"
                ],
                [
                    "id"=> "257",
                    "product_id"=> "113",
                    "theme_id"=> "82",
                    "scu"=> "811",
                    "theme_url"=> "lipobmen4"
                ],
                [
                    "id"=> "258",
                    "product_id"=> "114",
                    "theme_id"=> "84",
                    "scu"=> "812",
                    "theme_url"=> "intmos1"
                ],
                [
                    "id"=> "259",
                    "product_id"=> "114",
                    "theme_id"=> "85",
                    "scu"=> "812",
                    "theme_url"=> "intmos2"
                ],
                [
                    "id"=> "260",
                    "product_id"=> "114",
                    "theme_id"=> "86",
                    "scu"=> "812",
                    "theme_url"=> "intmos3"
                ],
                [
                    "id"=> "261",
                    "product_id"=> "114",
                    "theme_id"=> "87",
                    "scu"=> "812",
                    "theme_url"=> "intmos4"
                ],
                [
                    "id"=> "262",
                    "product_id"=> "114",
                    "theme_id"=> "88",
                    "scu"=> "812",
                    "theme_url"=> "intmos5"
                ],
                [
                    "id"=> "263",
                    "product_id"=> "115",
                    "theme_id"=> "89",
                    "scu"=> "813",
                    "theme_url"=> "fizvot0"
                ],
                [
                    "id"=> "264",
                    "product_id"=> "115",
                    "theme_id"=> "90",
                    "scu"=> "813",
                    "theme_url"=> "fizvot1"
                ],
                [
                    "id"=> "265",
                    "product_id"=> "115",
                    "theme_id"=> "91",
                    "scu"=> "813",
                    "theme_url"=> "fizvot2"
                ],
                [
                    "id"=> "266",
                    "product_id"=> "115",
                    "theme_id"=> "92",
                    "scu"=> "813",
                    "theme_url"=> "fizvot3"
                ],
                [
                    "id"=> "267",
                    "product_id"=> "116",
                    "theme_id"=> "89",
                    "scu"=> "814",
                    "theme_url"=> "fizvot0"
                ],
                [
                    "id"=> "268",
                    "product_id"=> "116",
                    "theme_id"=> "90",
                    "scu"=> "814",
                    "theme_url"=> "fizvot1"
                ],
                [
                    "id"=> "269",
                    "product_id"=> "116",
                    "theme_id"=> "91",
                    "scu"=> "814",
                    "theme_url"=> "fizvot2"
                ],
                [
                    "id"=> "270",
                    "product_id"=> "116",
                    "theme_id"=> "92",
                    "scu"=> "814",
                    "theme_url"=> "fizvot3"
                ],
                [
                    "id"=> "271",
                    "product_id"=> "117",
                    "theme_id"=> "90",
                    "scu"=> "815",
                    "theme_url"=> "fizvot1"
                ],
                [
                    "id"=> "272",
                    "product_id"=> "118",
                    "theme_id"=> "90",
                    "scu"=> "816",
                    "theme_url"=> "fizvot1"
                ],
                [
                    "id"=> "273",
                    "product_id"=> "119",
                    "theme_id"=> "91",
                    "scu"=> "817",
                    "theme_url"=> "fizvot2"
                ],
                [
                    "id"=> "274",
                    "product_id"=> "120",
                    "theme_id"=> "91",
                    "scu"=> "818",
                    "theme_url"=> "fizvot2"
                ],
                [
                    "id"=> "275",
                    "product_id"=> "121",
                    "theme_id"=> "92",
                    "scu"=> "819",
                    "theme_url"=> "fizvot3"
                ],
                [
                    "id"=> "276",
                    "product_id"=> "122",
                    "theme_id"=> "92",
                    "scu"=> "820",
                    "theme_url"=> "fizvot3"
                ],
                [
                    "id"=> "277",
                    "product_id"=> "123",
                    "theme_id"=> "96",
                    "scu"=> "821",
                    "theme_url"=> "intpheart4"
                ],
                [
                    "id"=> "278",
                    "product_id"=> "123",
                    "theme_id"=> "97",
                    "scu"=> "821",
                    "theme_url"=> "intpheart5"
                ],
                [
                    "id"=> "279",
                    "product_id"=> "123",
                    "theme_id"=> "93",
                    "scu"=> "821",
                    "theme_url"=> "intpheart1"
                ],
                [
                    "id"=> "280",
                    "product_id"=> "123",
                    "theme_id"=> "94",
                    "scu"=> "821",
                    "theme_url"=> "intpheart2"
                ],
                [
                    "id"=> "281",
                    "product_id"=> "123",
                    "theme_id"=> "95",
                    "scu"=> "821",
                    "theme_url"=> "intpheart3"
                ],
                [
                    "id"=> "282",
                    "product_id"=> "124",
                    "theme_id"=> "98",
                    "scu"=> "822",
                    "theme_url"=> "intazob1"
                ],
                [
                    "id"=> "283",
                    "product_id"=> "124",
                    "theme_id"=> "99",
                    "scu"=> "822",
                    "theme_url"=> "intazob2"
                ],
                [
                    "id"=> "284",
                    "product_id"=> "124",
                    "theme_id"=> "100",
                    "scu"=> "822",
                    "theme_url"=> "intazob3"
                ],
                [
                    "id"=> "285",
                    "product_id"=> "124",
                    "theme_id"=> "101",
                    "scu"=> "822",
                    "theme_url"=> "intazob4"
                ],
                [
                    "id"=> "286",
                    "product_id"=> "124",
                    "theme_id"=> "102",
                    "scu"=> "822",
                    "theme_url"=> "intazob5"
                ],
                [
                    "id"=> "287",
                    "product_id"=> "125",
                    "theme_id"=> "104",
                    "scu"=> "823",
                    "theme_url"=> "cellpatology1"
                ],
                [
                    "id"=> "288",
                    "product_id"=> "125",
                    "theme_id"=> "105",
                    "scu"=> "823",
                    "theme_url"=> "cellpatology2"
                ],
                [
                    "id"=> "289",
                    "product_id"=> "125",
                    "theme_id"=> "106",
                    "scu"=> "823",
                    "theme_url"=> "cellpatology3"
                ],
                [
                    "id"=> "290",
                    "product_id"=> "125",
                    "theme_id"=> "107",
                    "scu"=> "823",
                    "theme_url"=> "cellpatology4"
                ],
                [
                    "id"=> "291",
                    "product_id"=> "126",
                    "theme_id"=> "104",
                    "scu"=> "824",
                    "theme_url"=> "cellpatology1"
                ],
                [
                    "id"=> "292",
                    "product_id"=> "126",
                    "theme_id"=> "105",
                    "scu"=> "824",
                    "theme_url"=> "cellpatology2"
                ],
                [
                    "id"=> "293",
                    "product_id"=> "126",
                    "theme_id"=> "106",
                    "scu"=> "824",
                    "theme_url"=> "cellpatology3"
                ],
                [
                    "id"=> "294",
                    "product_id"=> "126",
                    "theme_id"=> "107",
                    "scu"=> "824",
                    "theme_url"=> "cellpatology4"
                ],
                [
                    "id"=> "295",
                    "product_id"=> "127",
                    "theme_id"=> "104",
                    "scu"=> "825",
                    "theme_url"=> "cellpatology1"
                ],
                [
                    "id"=> "296",
                    "product_id"=> "128",
                    "theme_id"=> "104",
                    "scu"=> "826",
                    "theme_url"=> "cellpatology1"
                ],
                [
                    "id"=> "297",
                    "product_id"=> "129",
                    "theme_id"=> "105",
                    "scu"=> "827",
                    "theme_url"=> "cellpatology2"
                ],
                [
                    "id"=> "298",
                    "product_id"=> "130",
                    "theme_id"=> "105",
                    "scu"=> "828",
                    "theme_url"=> "cellpatology2"
                ],
                [
                    "id"=> "299",
                    "product_id"=> "131",
                    "theme_id"=> "106",
                    "scu"=> "829",
                    "theme_url"=> "cellpatology3"
                ],
                [
                    "id"=> "300",
                    "product_id"=> "132",
                    "theme_id"=> "106",
                    "scu"=> "830",
                    "theme_url"=> "cellpatology3"
                ],
                [
                    "id"=> "301",
                    "product_id"=> "133",
                    "theme_id"=> "107",
                    "scu"=> "831",
                    "theme_url"=> "cellpatology4"
                ],
                [
                    "id"=> "302",
                    "product_id"=> "134",
                    "theme_id"=> "107",
                    "scu"=> "832",
                    "theme_url"=> "cellpatology4"
                ],
                [
                    "id"=> "303",
                    "product_id"=> "135",
                    "theme_id"=> "109",
                    "scu"=> "833",
                    "theme_url"=> "appendix1"
                ],
                [
                    "id"=> "304",
                    "product_id"=> "136",
                    "theme_id"=> "111",
                    "scu"=> "834",
                    "theme_url"=> "nutriomika1"
                ],
                [
                    "id"=> "305",
                    "product_id"=> "136",
                    "theme_id"=> "112",
                    "scu"=> "834",
                    "theme_url"=> "nutriomika2"
                ],
                [
                    "id"=> "306",
                    "product_id"=> "136",
                    "theme_id"=> "113",
                    "scu"=> "834",
                    "theme_url"=> "nutriomika3"
                ],
                [
                    "id"=> "307",
                    "product_id"=> "136",
                    "theme_id"=> "110",
                    "scu"=> "834",
                    "theme_url"=> "nutriomika0"
                ],
                [
                    "id"=> "308",
                    "product_id"=> "137",
                    "theme_id"=> "111",
                    "scu"=> "835",
                    "theme_url"=> "nutriomika1"
                ],
                [
                    "id"=> "309",
                    "product_id"=> "137",
                    "theme_id"=> "112",
                    "scu"=> "835",
                    "theme_url"=> "nutriomika2"
                ],
                [
                    "id"=> "310",
                    "product_id"=> "137",
                    "theme_id"=> "113",
                    "scu"=> "835",
                    "theme_url"=> "nutriomika3"
                ],
                [
                    "id"=> "311",
                    "product_id"=> "137",
                    "theme_id"=> "110",
                    "scu"=> "835",
                    "theme_url"=> "nutriomika0"
                ],
                [
                    "id"=> "312",
                    "product_id"=> "138",
                    "theme_id"=> "111",
                    "scu"=> "836",
                    "theme_url"=> "nutriomika1"
                ],
                [
                    "id"=> "313",
                    "product_id"=> "139",
                    "theme_id"=> "111",
                    "scu"=> "837",
                    "theme_url"=> "nutriomika1"
                ],
                [
                    "id"=> "314",
                    "product_id"=> "140",
                    "theme_id"=> "112",
                    "scu"=> "838",
                    "theme_url"=> "nutriomika2"
                ],
                [
                    "id"=> "315",
                    "product_id"=> "141",
                    "theme_id"=> "112",
                    "scu"=> "839",
                    "theme_url"=> "nutriomika2"
                ],
                [
                    "id"=> "316",
                    "product_id"=> "142",
                    "theme_id"=> "113",
                    "scu"=> "840",
                    "theme_url"=> "nutriomika3"
                ],
                [
                    "id"=> "317",
                    "product_id"=> "143",
                    "theme_id"=> "113",
                    "scu"=> "841",
                    "theme_url"=> "nutriomika3"
                ],
                [
                    "id"=> "318",
                    "product_id"=> "144",
                    "theme_id"=> "114",
                    "scu"=> "842",
                    "theme_url"=> "intnerves1"
                ],
                [
                    "id"=> "319",
                    "product_id"=> "144",
                    "theme_id"=> "115",
                    "scu"=> "842",
                    "theme_url"=> "intnerves2"
                ],
                [
                    "id"=> "320",
                    "product_id"=> "144",
                    "theme_id"=> "116",
                    "scu"=> "842",
                    "theme_url"=> "intnerves3"
                ],
                [
                    "id"=> "321",
                    "product_id"=> "144",
                    "theme_id"=> "117",
                    "scu"=> "842",
                    "theme_url"=> "intnerves4"
                ],
                [
                    "id"=> "322",
                    "product_id"=> "144",
                    "theme_id"=> "118",
                    "scu"=> "842",
                    "theme_url"=> "intnerves5"
                ],
                [
                    "id"=> "323",
                    "product_id"=> "144",
                    "theme_id"=> "119",
                    "scu"=> "842",
                    "theme_url"=> "intnerves6"
                ],
                [
                    "id"=> "324",
                    "product_id"=> "144",
                    "theme_id"=> "120",
                    "scu"=> "842",
                    "theme_url"=> "intnerves7"
                ],
                [
                    "id"=> "325",
                    "product_id"=> "144",
                    "theme_id"=> "121",
                    "scu"=> "842",
                    "theme_url"=> "intnerves8"
                ],
                [
                    "id"=> "326",
                    "product_id"=> "145",
                    "theme_id"=> "35",
                    "scu"=> "843",
                    "theme_url"=> "myology0"
                ],
                [
                    "id"=> "327",
                    "product_id"=> "145",
                    "theme_id"=> "30",
                    "scu"=> "843",
                    "theme_url"=> "myology1"
                ],
                [
                    "id"=> "328",
                    "product_id"=> "145",
                    "theme_id"=> "31",
                    "scu"=> "843",
                    "theme_url"=> "myology2"
                ],
                [
                    "id"=> "329",
                    "product_id"=> "145",
                    "theme_id"=> "37",
                    "scu"=> "843",
                    "theme_url"=> "myology3"
                ],
                [
                    "id"=> "330",
                    "product_id"=> "145",
                    "theme_id"=> "38",
                    "scu"=> "843",
                    "theme_url"=> "myology4"
                ],
                [
                    "id"=> "331",
                    "product_id"=> "145",
                    "theme_id"=> "18",
                    "scu"=> "843",
                    "theme_url"=> "arthrology2"
                ],
                [
                    "id"=> "332",
                    "product_id"=> "145",
                    "theme_id"=> "16",
                    "scu"=> "843",
                    "theme_url"=> "arthrology_check_list"
                ],
                [
                    "id"=> "333",
                    "product_id"=> "145",
                    "theme_id"=> "17",
                    "scu"=> "843",
                    "theme_url"=> "arthrology1"
                ],
                [
                    "id"=> "334",
                    "product_id"=> "145",
                    "theme_id"=> "19",
                    "scu"=> "843",
                    "theme_url"=> "arthrology3"
                ],
                [
                    "id"=> "335",
                    "product_id"=> "145",
                    "theme_id"=> "1",
                    "scu"=> "843",
                    "theme_url"=> "urok1"
                ],
                [
                    "id"=> "336",
                    "product_id"=> "145",
                    "theme_id"=> "2",
                    "scu"=> "843",
                    "theme_url"=> "urok2"
                ],
                [
                    "id"=> "337",
                    "product_id"=> "145",
                    "theme_id"=> "6",
                    "scu"=> "843",
                    "theme_url"=> "urok6"
                ],
                [
                    "id"=> "338",
                    "product_id"=> "145",
                    "theme_id"=> "7",
                    "scu"=> "843",
                    "theme_url"=> "urok11"
                ],
                [
                    "id"=> "339",
                    "product_id"=> "145",
                    "theme_id"=> "3",
                    "scu"=> "843",
                    "theme_url"=> "urok3"
                ],
                [
                    "id"=> "340",
                    "product_id"=> "145",
                    "theme_id"=> "4",
                    "scu"=> "843",
                    "theme_url"=> "urok4"
                ],
                [
                    "id"=> "341",
                    "product_id"=> "145",
                    "theme_id"=> "5",
                    "scu"=> "843",
                    "theme_url"=> "urok5"
                ],
                [
                    "id"=> "342",
                    "product_id"=> "146",
                    "theme_id"=> "26",
                    "scu"=> "844",
                    "theme_url"=> "intensecenter3"
                ],
                [
                    "id"=> "343",
                    "product_id"=> "146",
                    "theme_id"=> "25",
                    "scu"=> "844",
                    "theme_url"=> "intensecenter1"
                ],
                [
                    "id"=> "344",
                    "product_id"=> "146",
                    "theme_id"=> "24",
                    "scu"=> "844",
                    "theme_url"=> "intensecenter2"
                ],
                [
                    "id"=> "345",
                    "product_id"=> "146",
                    "theme_id"=> "29",
                    "scu"=> "844",
                    "theme_url"=> "intensecenter6"
                ],
                [
                    "id"=> "346",
                    "product_id"=> "146",
                    "theme_id"=> "28",
                    "scu"=> "844",
                    "theme_url"=> "intensecenter5"
                ],
                [
                    "id"=> "347",
                    "product_id"=> "146",
                    "theme_id"=> "27",
                    "scu"=> "844",
                    "theme_url"=> "intensecenter4"
                ],
                [
                    "id"=> "348",
                    "product_id"=> "146",
                    "theme_id"=> "43",
                    "scu"=> "844",
                    "theme_url"=> "intpns1"
                ],
                [
                    "id"=> "349",
                    "product_id"=> "146",
                    "theme_id"=> "44",
                    "scu"=> "844",
                    "theme_url"=> "intpns2"
                ],
                [
                    "id"=> "350",
                    "product_id"=> "146",
                    "theme_id"=> "46",
                    "scu"=> "844",
                    "theme_url"=> "intpns4"
                ],
                [
                    "id"=> "351",
                    "product_id"=> "146",
                    "theme_id"=> "47",
                    "scu"=> "844",
                    "theme_url"=> "intpns5"
                ],
                [
                    "id"=> "352",
                    "product_id"=> "146",
                    "theme_id"=> "45",
                    "scu"=> "844",
                    "theme_url"=> "intpns3"
                ],
                [
                    "id"=> "353",
                    "product_id"=> "147",
                    "theme_id"=> "26",
                    "scu"=> "845",
                    "theme_url"=> "intensecenter3"
                ],
                [
                    "id"=> "354",
                    "product_id"=> "147",
                    "theme_id"=> "43",
                    "scu"=> "845",
                    "theme_url"=> "intpns1"
                ],
                [
                    "id"=> "355",
                    "product_id"=> "147",
                    "theme_id"=> "44",
                    "scu"=> "845",
                    "theme_url"=> "intpns2"
                ],
                [
                    "id"=> "356",
                    "product_id"=> "147",
                    "theme_id"=> "46",
                    "scu"=> "845",
                    "theme_url"=> "intpns4"
                ],
                [
                    "id"=> "357",
                    "product_id"=> "147",
                    "theme_id"=> "25",
                    "scu"=> "845",
                    "theme_url"=> "intensecenter1"
                ],
                [
                    "id"=> "358",
                    "product_id"=> "147",
                    "theme_id"=> "47",
                    "scu"=> "845",
                    "theme_url"=> "intpns5"
                ],
                [
                    "id"=> "359",
                    "product_id"=> "147",
                    "theme_id"=> "24",
                    "scu"=> "845",
                    "theme_url"=> "intensecenter2"
                ],
                [
                    "id"=> "360",
                    "product_id"=> "147",
                    "theme_id"=> "29",
                    "scu"=> "845",
                    "theme_url"=> "intensecenter6"
                ],
                [
                    "id"=> "361",
                    "product_id"=> "147",
                    "theme_id"=> "28",
                    "scu"=> "845",
                    "theme_url"=> "intensecenter5"
                ],
                [
                    "id"=> "362",
                    "product_id"=> "147",
                    "theme_id"=> "45",
                    "scu"=> "845",
                    "theme_url"=> "intpns3"
                ],
                [
                    "id"=> "363",
                    "product_id"=> "147",
                    "theme_id"=> "27",
                    "scu"=> "845",
                    "theme_url"=> "intensecenter4"
                ],
                [
                    "id"=> "364",
                    "product_id"=> "147",
                    "theme_id"=> "120",
                    "scu"=> "845",
                    "theme_url"=> "intnerves7"
                ],
                [
                    "id"=> "365",
                    "product_id"=> "147",
                    "theme_id"=> "118",
                    "scu"=> "845",
                    "theme_url"=> "intnerves5"
                ],
                [
                    "id"=> "366",
                    "product_id"=> "147",
                    "theme_id"=> "117",
                    "scu"=> "845",
                    "theme_url"=> "intnerves4"
                ],
                [
                    "id"=> "367",
                    "product_id"=> "147",
                    "theme_id"=> "116",
                    "scu"=> "845",
                    "theme_url"=> "intnerves3"
                ],
                [
                    "id"=> "368",
                    "product_id"=> "147",
                    "theme_id"=> "115",
                    "scu"=> "845",
                    "theme_url"=> "intnerves2"
                ],
                [
                    "id"=> "369",
                    "product_id"=> "147",
                    "theme_id"=> "119",
                    "scu"=> "845",
                    "theme_url"=> "intnerves6"
                ],
                [
                    "id"=> "370",
                    "product_id"=> "147",
                    "theme_id"=> "121",
                    "scu"=> "845",
                    "theme_url"=> "intnerves8"
                ],
                [
                    "id"=> "371",
                    "product_id"=> "148",
                    "theme_id"=> "51",
                    "scu"=> "846",
                    "theme_url"=> "biochem0"
                ],
                [
                    "id"=> "372",
                    "product_id"=> "148",
                    "theme_id"=> "23",
                    "scu"=> "846",
                    "theme_url"=> "biochem3"
                ],
                [
                    "id"=> "373",
                    "product_id"=> "148",
                    "theme_id"=> "14",
                    "scu"=> "846",
                    "theme_url"=> "biochem1"
                ],
                [
                    "id"=> "374",
                    "product_id"=> "148",
                    "theme_id"=> "32",
                    "scu"=> "846",
                    "theme_url"=> "biochem4"
                ],
                [
                    "id"=> "375",
                    "product_id"=> "148",
                    "theme_id"=> "15",
                    "scu"=> "846",
                    "theme_url"=> "biochem2"
                ],
                [
                    "id"=> "376",
                    "product_id"=> "148",
                    "theme_id"=> "99",
                    "scu"=> "846",
                    "theme_url"=> "intazob2"
                ],
                [
                    "id"=> "377",
                    "product_id"=> "148",
                    "theme_id"=> "98",
                    "scu"=> "846",
                    "theme_url"=> "intazob1"
                ],
                [
                    "id"=> "378",
                    "product_id"=> "148",
                    "theme_id"=> "113",
                    "scu"=> "846",
                    "theme_url"=> "nutriomika3"
                ],
                [
                    "id"=> "379",
                    "product_id"=> "148",
                    "theme_id"=> "112",
                    "scu"=> "846",
                    "theme_url"=> "nutriomika2"
                ],
                [
                    "id"=> "380",
                    "product_id"=> "148",
                    "theme_id"=> "111",
                    "scu"=> "846",
                    "theme_url"=> "nutriomika1"
                ],
                [
                    "id"=> "381",
                    "product_id"=> "148",
                    "theme_id"=> "110",
                    "scu"=> "846",
                    "theme_url"=> "nutriomika0"
                ],
                [
                    "id"=> "382",
                    "product_id"=> "148",
                    "theme_id"=> "100",
                    "scu"=> "846",
                    "theme_url"=> "intazob3"
                ],
                [
                    "id"=> "383",
                    "product_id"=> "148",
                    "theme_id"=> "101",
                    "scu"=> "846",
                    "theme_url"=> "intazob4"
                ],
                [
                    "id"=> "384",
                    "product_id"=> "148",
                    "theme_id"=> "102",
                    "scu"=> "846",
                    "theme_url"=> "intazob5"
                ],
                [
                    "id"=> "385",
                    "product_id"=> "149",
                    "theme_id"=> "33",
                    "scu"=> "847",
                    "theme_url"=> "intu4"
                ],
                [
                    "id"=> "386",
                    "product_id"=> "149",
                    "theme_id"=> "34",
                    "scu"=> "847",
                    "theme_url"=> "intu5"
                ],
                [
                    "id"=> "387",
                    "product_id"=> "149",
                    "theme_id"=> "48",
                    "scu"=> "847",
                    "theme_url"=> "intu1"
                ],
                [
                    "id"=> "388",
                    "product_id"=> "149",
                    "theme_id"=> "50",
                    "scu"=> "847",
                    "theme_url"=> "intu3"
                ],
                [
                    "id"=> "389",
                    "product_id"=> "149",
                    "theme_id"=> "49",
                    "scu"=> "847",
                    "theme_url"=> "intu2"
                ],
                [
                    "id"=> "390",
                    "product_id"=> "149",
                    "theme_id"=> "99",
                    "scu"=> "847",
                    "theme_url"=> "intazob2"
                ],
                [
                    "id"=> "391",
                    "product_id"=> "149",
                    "theme_id"=> "98",
                    "scu"=> "847",
                    "theme_url"=> "intazob1"
                ],
                [
                    "id"=> "392",
                    "product_id"=> "149",
                    "theme_id"=> "78",
                    "scu"=> "847",
                    "theme_url"=> "lipobmen0"
                ],
                [
                    "id"=> "393",
                    "product_id"=> "149",
                    "theme_id"=> "79",
                    "scu"=> "847",
                    "theme_url"=> "lipobmen1"
                ],
                [
                    "id"=> "394",
                    "product_id"=> "149",
                    "theme_id"=> "80",
                    "scu"=> "847",
                    "theme_url"=> "lipobmen2"
                ],
                [
                    "id"=> "395",
                    "product_id"=> "149",
                    "theme_id"=> "81",
                    "scu"=> "847",
                    "theme_url"=> "lipobmen3"
                ],
                [
                    "id"=> "396",
                    "product_id"=> "149",
                    "theme_id"=> "82",
                    "scu"=> "847",
                    "theme_url"=> "lipobmen4"
                ],
                [
                    "id"=> "397",
                    "product_id"=> "149",
                    "theme_id"=> "83",
                    "scu"=> "847",
                    "theme_url"=> "lipobmen5"
                ],
                [
                    "id"=> "398",
                    "product_id"=> "149",
                    "theme_id"=> "100",
                    "scu"=> "847",
                    "theme_url"=> "intazob3"
                ],
                [
                    "id"=> "399",
                    "product_id"=> "149",
                    "theme_id"=> "101",
                    "scu"=> "847",
                    "theme_url"=> "intazob4"
                ],
                [
                    "id"=> "400",
                    "product_id"=> "149",
                    "theme_id"=> "102",
                    "scu"=> "847",
                    "theme_url"=> "intazob5"
                ],
                [
                    "id"=> "401",
                    "product_id"=> "150",
                    "theme_id"=> "128",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns6"
                ],
                [
                    "id"=> "402",
                    "product_id"=> "150",
                    "theme_id"=> "123",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns1"
                ],
                [
                    "id"=> "403",
                    "product_id"=> "150",
                    "theme_id"=> "124",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns2"
                ],
                [
                    "id"=> "404",
                    "product_id"=> "150",
                    "theme_id"=> "125",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns3"
                ],
                [
                    "id"=> "405",
                    "product_id"=> "150",
                    "theme_id"=> "129",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns7"
                ],
                [
                    "id"=> "406",
                    "product_id"=> "150",
                    "theme_id"=> "130",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns8"
                ],
                [
                    "id"=> "407",
                    "product_id"=> "150",
                    "theme_id"=> "126",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns4"
                ],
                [
                    "id"=> "408",
                    "product_id"=> "150",
                    "theme_id"=> "122",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns0"
                ],
                [
                    "id"=> "409",
                    "product_id"=> "150",
                    "theme_id"=> "127",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns5"
                ],
                [
                    "id"=> "410",
                    "product_id"=> "150",
                    "theme_id"=> "131",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns9"
                ],
                [
                    "id"=> "411",
                    "product_id"=> "150",
                    "theme_id"=> "132",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns10"
                ],
                [
                    "id"=> "412",
                    "product_id"=> "150",
                    "theme_id"=> "133",
                    "scu"=> "848",
                    "theme_url"=> "prepvcns11"
                ],
                [
                    "id"=> "413",
                    "product_id"=> "151",
                    "theme_id"=> "128",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns6"
                ],
                [
                    "id"=> "414",
                    "product_id"=> "151",
                    "theme_id"=> "123",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns1"
                ],
                [
                    "id"=> "415",
                    "product_id"=> "151",
                    "theme_id"=> "124",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns2"
                ],
                [
                    "id"=> "416",
                    "product_id"=> "151",
                    "theme_id"=> "125",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns3"
                ],
                [
                    "id"=> "417",
                    "product_id"=> "151",
                    "theme_id"=> "129",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns7"
                ],
                [
                    "id"=> "418",
                    "product_id"=> "151",
                    "theme_id"=> "130",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns8"
                ],
                [
                    "id"=> "419",
                    "product_id"=> "151",
                    "theme_id"=> "126",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns4"
                ],
                [
                    "id"=> "420",
                    "product_id"=> "151",
                    "theme_id"=> "122",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns0"
                ],
                [
                    "id"=> "421",
                    "product_id"=> "151",
                    "theme_id"=> "127",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns5"
                ],
                [
                    "id"=> "422",
                    "product_id"=> "151",
                    "theme_id"=> "131",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns9"
                ],
                [
                    "id"=> "423",
                    "product_id"=> "151",
                    "theme_id"=> "132",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns10"
                ],
                [
                    "id"=> "424",
                    "product_id"=> "151",
                    "theme_id"=> "133",
                    "scu"=> "849",
                    "theme_url"=> "prepvcns11"
                ],
                [
                    "id"=> "425",
                    "product_id"=> "152",
                    "theme_id"=> "123",
                    "scu"=> "850",
                    "theme_url"=> "prepvcns1"
                ],
                [
                    "id"=> "426",
                    "product_id"=> "152",
                    "theme_id"=> "124",
                    "scu"=> "850",
                    "theme_url"=> "prepvcns2"
                ],
                [
                    "id"=> "427",
                    "product_id"=> "152",
                    "theme_id"=> "125",
                    "scu"=> "850",
                    "theme_url"=> "prepvcns3"
                ],
                [
                    "id"=> "428",
                    "product_id"=> "152",
                    "theme_id"=> "126",
                    "scu"=> "850",
                    "theme_url"=> "prepvcns4"
                ],
                [
                    "id"=> "429",
                    "product_id"=> "153",
                    "theme_id"=> "123",
                    "scu"=> "851",
                    "theme_url"=> "prepvcns1"
                ],
                [
                    "id"=> "430",
                    "product_id"=> "153",
                    "theme_id"=> "124",
                    "scu"=> "851",
                    "theme_url"=> "prepvcns2"
                ],
                [
                    "id"=> "431",
                    "product_id"=> "153",
                    "theme_id"=> "125",
                    "scu"=> "851",
                    "theme_url"=> "prepvcns3"
                ],
                [
                    "id"=> "432",
                    "product_id"=> "153",
                    "theme_id"=> "126",
                    "scu"=> "851",
                    "theme_url"=> "prepvcns4"
                ],
                [
                    "id"=> "433",
                    "product_id"=> "154",
                    "theme_id"=> "128",
                    "scu"=> "852",
                    "theme_url"=> "prepvcns6"
                ],
                [
                    "id"=> "434",
                    "product_id"=> "154",
                    "theme_id"=> "123",
                    "scu"=> "852",
                    "theme_url"=> "prepvcns1"
                ],
                [
                    "id"=> "435",
                    "product_id"=> "154",
                    "theme_id"=> "122",
                    "scu"=> "852",
                    "theme_url"=> "prepvcns0"
                ],
                [
                    "id"=> "436",
                    "product_id"=> "154",
                    "theme_id"=> "127",
                    "scu"=> "852",
                    "theme_url"=> "prepvcns5"
                ],
                [
                    "id"=> "437",
                    "product_id"=> "154",
                    "theme_id"=> "129",
                    "scu"=> "852",
                    "theme_url"=> "prepvcns7"
                ],
                [
                    "id"=> "438",
                    "product_id"=> "155",
                    "theme_id"=> "128",
                    "scu"=> "853",
                    "theme_url"=> "prepvcns6"
                ],
                [
                    "id"=> "439",
                    "product_id"=> "155",
                    "theme_id"=> "123",
                    "scu"=> "853",
                    "theme_url"=> "prepvcns1"
                ],
                [
                    "id"=> "440",
                    "product_id"=> "155",
                    "theme_id"=> "129",
                    "scu"=> "853",
                    "theme_url"=> "prepvcns7"
                ],
                [
                    "id"=> "441",
                    "product_id"=> "155",
                    "theme_id"=> "122",
                    "scu"=> "853",
                    "theme_url"=> "prepvcns0"
                ],
                [
                    "id"=> "442",
                    "product_id"=> "155",
                    "theme_id"=> "127",
                    "scu"=> "853",
                    "theme_url"=> "prepvcns5"
                ],
                [
                    "id"=> "443",
                    "product_id"=> "156",
                    "theme_id"=> "123",
                    "scu"=> "854",
                    "theme_url"=> "prepvcns1"
                ],
                [
                    "id"=> "444",
                    "product_id"=> "156",
                    "theme_id"=> "130",
                    "scu"=> "854",
                    "theme_url"=> "prepvcns8"
                ],
                [
                    "id"=> "445",
                    "product_id"=> "156",
                    "theme_id"=> "131",
                    "scu"=> "854",
                    "theme_url"=> "prepvcns9"
                ],
                [
                    "id"=> "446",
                    "product_id"=> "156",
                    "theme_id"=> "132",
                    "scu"=> "854",
                    "theme_url"=> "prepvcns10"
                ],
                [
                    "id"=> "447",
                    "product_id"=> "156",
                    "theme_id"=> "133",
                    "scu"=> "854",
                    "theme_url"=> "prepvcns11"
                ],
                [
                    "id"=> "448",
                    "product_id"=> "157",
                    "theme_id"=> "123",
                    "scu"=> "855",
                    "theme_url"=> "prepvcns1"
                ],
                [
                    "id"=> "449",
                    "product_id"=> "157",
                    "theme_id"=> "130",
                    "scu"=> "855",
                    "theme_url"=> "prepvcns8"
                ],
                [
                    "id"=> "450",
                    "product_id"=> "157",
                    "theme_id"=> "131",
                    "scu"=> "855",
                    "theme_url"=> "prepvcns9"
                ],
                [
                    "id"=> "451",
                    "product_id"=> "157",
                    "theme_id"=> "132",
                    "scu"=> "855",
                    "theme_url"=> "prepvcns10"
                ],
                [
                    "id"=> "452",
                    "product_id"=> "157",
                    "theme_id"=> "133",
                    "scu"=> "855",
                    "theme_url"=> "prepvcns11"
                ],
                [
                    "id"=> "453",
                    "product_id"=> "158",
                    "theme_id"=> "134",
                    "scu"=> "856",
                    "theme_url"=> "appendix2"
                ],
                [
                    "id"=> "454",
                    "product_id"=> "159",
                    "theme_id"=> "136",
                    "scu"=> "857",
                    "theme_url"=> "onmk_i_pnmk2"
                ],
                [
                    "id"=> "455",
                    "product_id"=> "159",
                    "theme_id"=> "135",
                    "scu"=> "857",
                    "theme_url"=> "onmk_i_pnmk1"
                ],
                [
                    "id"=> "456",
                    "product_id"=> "159",
                    "theme_id"=> "138",
                    "scu"=> "857",
                    "theme_url"=> "onmk_i_pnmk4"
                ],
                [
                    "id"=> "457",
                    "product_id"=> "159",
                    "theme_id"=> "137",
                    "scu"=> "857",
                    "theme_url"=> "onmk_i_pnmk3"
                ],
                [
                    "id"=> "458",
                    "product_id"=> "160",
                    "theme_id"=> "136",
                    "scu"=> "858",
                    "theme_url"=> "onmk_i_pnmk2"
                ],
                [
                    "id"=> "459",
                    "product_id"=> "161",
                    "theme_id"=> "137",
                    "scu"=> "859",
                    "theme_url"=> "onmk_i_pnmk3"
                ],
                [
                    "id"=> "460",
                    "product_id"=> "162",
                    "theme_id"=> "138",
                    "scu"=> "860",
                    "theme_url"=> "onmk_i_pnmk4"
                ],
                [
                    "id"=> "461",
                    "product_id"=> "163",
                    "theme_id"=> "141",
                    "scu"=> "861",
                    "theme_url"=> "gemorragicheskie_zabolevaniya"
                ],
                [
                    "id"=> "462",
                    "product_id"=> "163",
                    "theme_id"=> "140",
                    "scu"=> "861",
                    "theme_url"=> "anemii"
                ],
                [
                    "id"=> "463",
                    "product_id"=> "163",
                    "theme_id"=> "139",
                    "scu"=> "861",
                    "theme_url"=> "limfomy_free"
                ],
                [
                    "id"=> "464",
                    "product_id"=> "163",
                    "theme_id"=> "142",
                    "scu"=> "861",
                    "theme_url"=> "bolezn_lejkocitov"
                ],
                [
                    "id"=> "465",
                    "product_id"=> "164",
                    "theme_id"=> "141",
                    "scu"=> "862",
                    "theme_url"=> "gemorragicheskie_zabolevaniya"
                ],
                [
                    "id"=> "466",
                    "product_id"=> "164",
                    "theme_id"=> "140",
                    "scu"=> "862",
                    "theme_url"=> "anemii"
                ],
                [
                    "id"=> "467",
                    "product_id"=> "164",
                    "theme_id"=> "139",
                    "scu"=> "862",
                    "theme_url"=> "limfomy_free"
                ],
                [
                    "id"=> "468",
                    "product_id"=> "164",
                    "theme_id"=> "142",
                    "scu"=> "862",
                    "theme_url"=> "bolezn_lejkocitov"
                ],
                [
                    "id"=> "469",
                    "product_id"=> "165",
                    "theme_id"=> "142",
                    "scu"=> "863",
                    "theme_url"=> "bolezn_lejkocitov"
                ],
                [
                    "id"=> "470",
                    "product_id"=> "166",
                    "theme_id"=> "142",
                    "scu"=> "864",
                    "theme_url"=> "bolezn_lejkocitov"
                ],
                [
                    "id"=> "471",
                    "product_id"=> "167",
                    "theme_id"=> "141",
                    "scu"=> "865",
                    "theme_url"=> "gemorragicheskie_zabolevaniya"
                ],
                [
                    "id"=> "472",
                    "product_id"=> "168",
                    "theme_id"=> "141",
                    "scu"=> "866",
                    "theme_url"=> "gemorragicheskie_zabolevaniya"
                ],
                [
                    "id"=> "473",
                    "product_id"=> "169",
                    "theme_id"=> "140",
                    "scu"=> "867",
                    "theme_url"=> "anemii"
                ],
                [
                    "id"=> "474",
                    "product_id"=> "170",
                    "theme_id"=> "140",
                    "scu"=> "868",
                    "theme_url"=> "anemii"
                ],
                [
                    "id"=> "475",
                    "product_id"=> "181",
                    "theme_id"=> "147",
                    "scu"=> "879",
                    "theme_url"=> "biohimicheskie_osnovy_kancerogeneza"
                ],
                [
                    "id"=> "476",
                    "product_id"=> "180",
                    "theme_id"=> "147",
                    "scu"=> "878",
                    "theme_url"=> "biohimicheskie_osnovy_kancerogeneza"
                ],
                [
                    "id"=> "477",
                    "product_id"=> "179",
                    "theme_id"=> "146",
                    "scu"=> "877",
                    "theme_url"=> "biohimiya_soedinitelnoy_tkani"
                ],
                [
                    "id"=> "478",
                    "product_id"=> "178",
                    "theme_id"=> "146",
                    "scu"=> "876",
                    "theme_url"=> "biohimiya_soedinitelnoy_tkani"
                ],
                [
                    "id"=> "479",
                    "product_id"=> "177",
                    "theme_id"=> "145",
                    "scu"=> "875",
                    "theme_url"=> "biohimiya_krovi"
                ],
                [
                    "id"=> "480",
                    "product_id"=> "176",
                    "theme_id"=> "145",
                    "scu"=> "874",
                    "theme_url"=> "biohimiya_krovi"
                ],
                [
                    "id"=> "481",
                    "product_id"=> "175",
                    "theme_id"=> "144",
                    "scu"=> "873",
                    "theme_url"=> "metabolizm_gema_i_obmen_zhelezazheleza"
                ],
                [
                    "id"=> "482",
                    "product_id"=> "174",
                    "theme_id"=> "144",
                    "scu"=> "872",
                    "theme_url"=> "metabolizm_gema_i_obmen_zhelezazheleza"
                ],
                [
                    "id"=> "483",
                    "product_id"=> "173",
                    "theme_id"=> "143",
                    "scu"=> "871",
                    "theme_url"=> "obezvrezhivanie_toksicheskih_veshchestv_v_organizme"
                ],
                [
                    "id"=> "484",
                    "product_id"=> "172",
                    "theme_id"=> "143",
                    "scu"=> "870",
                    "theme_url"=> "obezvrezhivanie_toksicheskih_veshchestv_v_organizme"
                ],
                [
                    "id"=> "485",
                    "product_id"=> "172",
                    "theme_id"=> "144",
                    "scu"=> "870",
                    "theme_url"=> "metabolizm_gema_i_obmen_zhelezazheleza"
                ],
                [
                    "id"=> "486",
                    "product_id"=> "172",
                    "theme_id"=> "145",
                    "scu"=> "870",
                    "theme_url"=> "biohimiya_krovi"
                ],
                [
                    "id"=> "487",
                    "product_id"=> "172",
                    "theme_id"=> "146",
                    "scu"=> "870",
                    "theme_url"=> "biohimiya_soedinitelnoy_tkani"
                ],
                [
                    "id"=> "488",
                    "product_id"=> "172",
                    "theme_id"=> "147",
                    "scu"=> "870",
                    "theme_url"=> "biohimicheskie_osnovy_kancerogeneza"
                ],
                [
                    "id"=> "489",
                    "product_id"=> "171",
                    "theme_id"=> "143",
                    "scu"=> "869",
                    "theme_url"=> "obezvrezhivanie_toksicheskih_veshchestv_v_organizme"
                ],
                [
                    "id"=> "490",
                    "product_id"=> "171",
                    "theme_id"=> "144",
                    "scu"=> "869",
                    "theme_url"=> "metabolizm_gema_i_obmen_zhelezazheleza"
                ],
                [
                    "id"=> "491",
                    "product_id"=> "171",
                    "theme_id"=> "145",
                    "scu"=> "869",
                    "theme_url"=> "biohimiya_krovi"
                ],
                [
                    "id"=> "492",
                    "product_id"=> "171",
                    "theme_id"=> "146",
                    "scu"=> "869",
                    "theme_url"=> "biohimiya_soedinitelnoy_tkani"
                ],
                [
                    "id"=> "493",
                    "product_id"=> "171",
                    "theme_id"=> "147",
                    "scu"=> "869",
                    "theme_url"=> "biohimicheskie_osnovy_kancerogeneza"
                ],
                [
                    "id"=> "494",
                    "product_id"=> "182",
                    "theme_id"=> "51",
                    "scu"=> "880",
                    "theme_url"=> "biochem0"
                ],
                [
                    "id"=> "495",
                    "product_id"=> "182",
                    "theme_id"=> "33",
                    "scu"=> "880",
                    "theme_url"=> "intu4"
                ],
                [
                    "id"=> "496",
                    "product_id"=> "182",
                    "theme_id"=> "23",
                    "scu"=> "880",
                    "theme_url"=> "biochem3"
                ],
                [
                    "id"=> "497",
                    "product_id"=> "182",
                    "theme_id"=> "14",
                    "scu"=> "880",
                    "theme_url"=> "biochem1"
                ],
                [
                    "id"=> "498",
                    "product_id"=> "182",
                    "theme_id"=> "32",
                    "scu"=> "880",
                    "theme_url"=> "biochem4"
                ],
                [
                    "id"=> "499",
                    "product_id"=> "182",
                    "theme_id"=> "34",
                    "scu"=> "880",
                    "theme_url"=> "intu5"
                ],
                [
                    "id"=> "500",
                    "product_id"=> "182",
                    "theme_id"=> "48",
                    "scu"=> "880",
                    "theme_url"=> "intu1"
                ],
                [
                    "id"=> "501",
                    "product_id"=> "182",
                    "theme_id"=> "50",
                    "scu"=> "880",
                    "theme_url"=> "intu3"
                ],
                [
                    "id"=> "502",
                    "product_id"=> "182",
                    "theme_id"=> "49",
                    "scu"=> "880",
                    "theme_url"=> "intu2"
                ],
                [
                    "id"=> "503",
                    "product_id"=> "182",
                    "theme_id"=> "15",
                    "scu"=> "880",
                    "theme_url"=> "biochem2"
                ],
                [
                    "id"=> "504",
                    "product_id"=> "182",
                    "theme_id"=> "55",
                    "scu"=> "880",
                    "theme_url"=> "biochemz7"
                ],
                [
                    "id"=> "505",
                    "product_id"=> "182",
                    "theme_id"=> "56",
                    "scu"=> "880",
                    "theme_url"=> "biochemz8"
                ],
                [
                    "id"=> "506",
                    "product_id"=> "182",
                    "theme_id"=> "54",
                    "scu"=> "880",
                    "theme_url"=> "biochemz6"
                ],
                [
                    "id"=> "507",
                    "product_id"=> "182",
                    "theme_id"=> "80",
                    "scu"=> "880",
                    "theme_url"=> "lipobmen2"
                ],
                [
                    "id"=> "508",
                    "product_id"=> "182",
                    "theme_id"=> "101",
                    "scu"=> "880",
                    "theme_url"=> "intazob4"
                ],
                [
                    "id"=> "509",
                    "product_id"=> "182",
                    "theme_id"=> "100",
                    "scu"=> "880",
                    "theme_url"=> "intazob3"
                ],
                [
                    "id"=> "510",
                    "product_id"=> "182",
                    "theme_id"=> "99",
                    "scu"=> "880",
                    "theme_url"=> "intazob2"
                ],
                [
                    "id"=> "511",
                    "product_id"=> "182",
                    "theme_id"=> "79",
                    "scu"=> "880",
                    "theme_url"=> "lipobmen1"
                ],
                [
                    "id"=> "512",
                    "product_id"=> "182",
                    "theme_id"=> "98",
                    "scu"=> "880",
                    "theme_url"=> "intazob1"
                ],
                [
                    "id"=> "513",
                    "product_id"=> "182",
                    "theme_id"=> "110",
                    "scu"=> "880",
                    "theme_url"=> "nutriomika0"
                ],
                [
                    "id"=> "514",
                    "product_id"=> "182",
                    "theme_id"=> "78",
                    "scu"=> "880",
                    "theme_url"=> "lipobmen0"
                ],
                [
                    "id"=> "515",
                    "product_id"=> "182",
                    "theme_id"=> "82",
                    "scu"=> "880",
                    "theme_url"=> "lipobmen4"
                ],
                [
                    "id"=> "516",
                    "product_id"=> "182",
                    "theme_id"=> "83",
                    "scu"=> "880",
                    "theme_url"=> "lipobmen5"
                ],
                [
                    "id"=> "517",
                    "product_id"=> "182",
                    "theme_id"=> "113",
                    "scu"=> "880",
                    "theme_url"=> "nutriomika3"
                ],
                [
                    "id"=> "518",
                    "product_id"=> "182",
                    "theme_id"=> "112",
                    "scu"=> "880",
                    "theme_url"=> "nutriomika2"
                ],
                [
                    "id"=> "519",
                    "product_id"=> "182",
                    "theme_id"=> "81",
                    "scu"=> "880",
                    "theme_url"=> "lipobmen3"
                ],
                [
                    "id"=> "520",
                    "product_id"=> "182",
                    "theme_id"=> "111",
                    "scu"=> "880",
                    "theme_url"=> "nutriomika1"
                ],
                [
                    "id"=> "521",
                    "product_id"=> "182",
                    "theme_id"=> "102",
                    "scu"=> "880",
                    "theme_url"=> "intazob5"
                ],
                [
                    "id"=> "522",
                    "product_id"=> "182",
                    "theme_id"=> "143",
                    "scu"=> "880",
                    "theme_url"=> "obezvrezhivanie_toksicheskih_veshchestv_v_organizme"
                ],
                [
                    "id"=> "523",
                    "product_id"=> "182",
                    "theme_id"=> "147",
                    "scu"=> "880",
                    "theme_url"=> "biohimicheskie_osnovy_kancerogeneza"
                ],
                [
                    "id"=> "524",
                    "product_id"=> "182",
                    "theme_id"=> "146",
                    "scu"=> "880",
                    "theme_url"=> "biohimiya_soedinitelnoy_tkani"
                ],
                [
                    "id"=> "525",
                    "product_id"=> "182",
                    "theme_id"=> "145",
                    "scu"=> "880",
                    "theme_url"=> "biohimiya_krovi"
                ],
                [
                    "id"=> "526",
                    "product_id"=> "182",
                    "theme_id"=> "144",
                    "scu"=> "880",
                    "theme_url"=> "metabolizm_gema_i_obmen_zhelezazheleza"
                ],
                [
                    "id"=> "527",
                    "product_id"=> "183",
                    "theme_id"=> "150",
                    "scu"=> "881",
                    "theme_url"=> "uglevodnye_distrofii"
                ],
                [
                    "id"=> "528",
                    "product_id"=> "183",
                    "theme_id"=> "149",
                    "scu"=> "881",
                    "theme_url"=> "zhirovye_distrofii"
                ],
                [
                    "id"=> "529",
                    "product_id"=> "183",
                    "theme_id"=> "148",
                    "scu"=> "881",
                    "theme_url"=> "distrofii_obshchie_ponyatiya"
                ],
                [
                    "id"=> "530",
                    "product_id"=> "183",
                    "theme_id"=> "153",
                    "scu"=> "881",
                    "theme_url"=> "mineralnye_distrofii_obrazovanie_kamnej"
                ],
                [
                    "id"=> "531",
                    "product_id"=> "183",
                    "theme_id"=> "152",
                    "scu"=> "881",
                    "theme_url"=> "smeshannye_distrofii_pigmentnye_distrofii"
                ],
                [
                    "id"=> "532",
                    "product_id"=> "183",
                    "theme_id"=> "151",
                    "scu"=> "881",
                    "theme_url"=> "belkovye_distrofii"
                ],
                [
                    "id"=> "533",
                    "product_id"=> "184",
                    "theme_id"=> "156",
                    "scu"=> "882",
                    "theme_url"=> "obshchie_ponyatiya_propedevtiki_3"
                ],
                [
                    "id"=> "534",
                    "product_id"=> "184",
                    "theme_id"=> "155",
                    "scu"=> "882",
                    "theme_url"=> "obshchie_ponyatiya_propedevtiki_2"
                ],
                [
                    "id"=> "535",
                    "product_id"=> "184",
                    "theme_id"=> "154",
                    "scu"=> "882",
                    "theme_url"=> "obshchie_ponyatiya_propedevtiki_1"
                ],
                [
                    "id"=> "536",
                    "product_id"=> "184",
                    "theme_id"=> "157",
                    "scu"=> "882",
                    "theme_url"=> "obshchie_ponyatiya_propedevtiki_4"
                ],
                [
                    "id"=> "537",
                    "product_id"=> "185",
                    "theme_id"=> "158",
                    "scu"=> "883",
                    "theme_url"=> "int_narusheniya_tepl_obmena_1"
                ],
                [
                    "id"=> "538",
                    "product_id"=> "185",
                    "theme_id"=> "159",
                    "scu"=> "883",
                    "theme_url"=> "int_narusheniya_tepl_obmena_2"
                ],
                [
                    "id"=> "539",
                    "product_id"=> "185",
                    "theme_id"=> "160",
                    "scu"=> "883",
                    "theme_url"=> "int_narusheniya_tepl_obmena_3"
                ],
                [
                    "id"=> "540",
                    "product_id"=> "185",
                    "theme_id"=> "161",
                    "scu"=> "883",
                    "theme_url"=> "int_narusheniya_tepl_obmena_4"
                ],
                [
                    "id"=> "541",
                    "product_id"=> "186",
                    "theme_id"=> "162",
                    "scu"=> "884",
                    "theme_url"=> "nerv_system_i_organy_chyvstv_1"
                ],
                [
                    "id"=> "542",
                    "product_id"=> "186",
                    "theme_id"=> "165",
                    "scu"=> "884",
                    "theme_url"=> "nerv_system_i_organy_chyvstv_4"
                ],
                [
                    "id"=> "543",
                    "product_id"=> "186",
                    "theme_id"=> "164",
                    "scu"=> "884",
                    "theme_url"=> "nerv_system_i_organy_chyvstv_3"
                ],
                [
                    "id"=> "544",
                    "product_id"=> "186",
                    "theme_id"=> "163",
                    "scu"=> "884",
                    "theme_url"=> "nerv_system_i_organy_chyvstv_2"
                ],
                [
                    "id"=> "545",
                    "product_id"=> "189",
                    "theme_id"=> "165",
                    "scu"=> "887",
                    "theme_url"=> "nerv_system_i_organy_chyvstv_4"
                ],
                [
                    "id"=> "546",
                    "product_id"=> "188",
                    "theme_id"=> "164",
                    "scu"=> "886",
                    "theme_url"=> "nerv_system_i_organy_chyvstv_3"
                ],
                [
                    "id"=> "547",
                    "product_id"=> "187",
                    "theme_id"=> "163",
                    "scu"=> "885",
                    "theme_url"=> "nerv_system_i_organy_chyvstv_2"
                ],
                [
                    "id"=> "548",
                    "product_id"=> "190",
                    "theme_id"=> "166",
                    "scu"=> "888",
                    "theme_url"=> "ekg_1"
                ],
                [
                    "id"=> "549",
                    "product_id"=> "190",
                    "theme_id"=> "167",
                    "scu"=> "888",
                    "theme_url"=> "ekg_2"
                ],
                [
                    "id"=> "550",
                    "product_id"=> "190",
                    "theme_id"=> "168",
                    "scu"=> "888",
                    "theme_url"=> "ekg_3"
                ],
                [
                    "id"=> "551",
                    "product_id"=> "190",
                    "theme_id"=> "169",
                    "scu"=> "888",
                    "theme_url"=> "ekg_4"
                ],
                [
                    "id"=> "552",
                    "product_id"=> "190",
                    "theme_id"=> "170",
                    "scu"=> "888",
                    "theme_url"=> "ekg_5"
                ],
                [
                    "id"=> "553",
                    "product_id"=> "191",
                    "theme_id"=> "179",
                    "scu"=> "898",
                    "theme_url"=> "immunology_9"
                ],
                [
                    "id"=> "554",
                    "product_id"=> "192",
                    "theme_id"=> "178",
                    "scu"=> "897",
                    "theme_url"=> "immunology_8"
                ],
                [
                    "id"=> "555",
                    "product_id"=> "193",
                    "theme_id"=> "177",
                    "scu"=> "896",
                    "theme_url"=> "immunology_7"
                ],
                [
                    "id"=> "556",
                    "product_id"=> "194",
                    "theme_id"=> "176",
                    "scu"=> "895",
                    "theme_url"=> "immunology_6"
                ],
                [
                    "id"=> "557",
                    "product_id"=> "195",
                    "theme_id"=> "175",
                    "scu"=> "894",
                    "theme_url"=> "immunology_5"
                ],
                [
                    "id"=> "558",
                    "product_id"=> "196",
                    "theme_id"=> "174",
                    "scu"=> "893",
                    "theme_url"=> "immunology_4"
                ],
                [
                    "id"=> "559",
                    "product_id"=> "197",
                    "theme_id"=> "173",
                    "scu"=> "892",
                    "theme_url"=> "immunology_3"
                ],
                [
                    "id"=> "560",
                    "product_id"=> "198",
                    "theme_id"=> "172",
                    "scu"=> "891",
                    "theme_url"=> "immunology_2"
                ],
                [
                    "id"=> "561",
                    "product_id"=> "199",
                    "theme_id"=> "171",
                    "scu"=> "890",
                    "theme_url"=> "immunology_1"
                ],
                [
                    "id"=> "562",
                    "product_id"=> "200",
                    "theme_id"=> "171",
                    "scu"=> "889",
                    "theme_url"=> "immunology_1"
                ],
                [
                    "id"=> "563",
                    "product_id"=> "200",
                    "theme_id"=> "172",
                    "scu"=> "889",
                    "theme_url"=> "immunology_2"
                ],
                [
                    "id"=> "564",
                    "product_id"=> "200",
                    "theme_id"=> "173",
                    "scu"=> "889",
                    "theme_url"=> "immunology_3"
                ],
                [
                    "id"=> "565",
                    "product_id"=> "200",
                    "theme_id"=> "174",
                    "scu"=> "889",
                    "theme_url"=> "immunology_4"
                ],
                [
                    "id"=> "566",
                    "product_id"=> "200",
                    "theme_id"=> "175",
                    "scu"=> "889",
                    "theme_url"=> "immunology_5"
                ],
                [
                    "id"=> "567",
                    "product_id"=> "200",
                    "theme_id"=> "176",
                    "scu"=> "889",
                    "theme_url"=> "immunology_6"
                ],
                [
                    "id"=> "568",
                    "product_id"=> "200",
                    "theme_id"=> "177",
                    "scu"=> "889",
                    "theme_url"=> "immunology_7"
                ],
                [
                    "id"=> "569",
                    "product_id"=> "200",
                    "theme_id"=> "178",
                    "scu"=> "889",
                    "theme_url"=> "immunology_8"
                ],
                [
                    "id"=> "570",
                    "product_id"=> "200",
                    "theme_id"=> "179",
                    "scu"=> "889",
                    "theme_url"=> "immunology_9"
                ],
                [
                    "id"=> "571",
                    "product_id"=> "201",
                    "theme_id"=> "180",
                    "scu"=> "899",
                    "theme_url"=> "prep_endokr_zabol_1"
                ],
                [
                    "id"=> "572",
                    "product_id"=> "201",
                    "theme_id"=> "181",
                    "scu"=> "899",
                    "theme_url"=> "prep_endokr_zabol_2"
                ],
                [
                    "id"=> "573",
                    "product_id"=> "201",
                    "theme_id"=> "182",
                    "scu"=> "899",
                    "theme_url"=> "prep_endokr_zabol_3"
                ],
                [
                    "id"=> "574",
                    "product_id"=> "201",
                    "theme_id"=> "183",
                    "scu"=> "899",
                    "theme_url"=> "prep_endokr_zabol_4"
                ],
                [
                    "id"=> "575",
                    "product_id"=> "201",
                    "theme_id"=> "184",
                    "scu"=> "899",
                    "theme_url"=> "prep_endokr_zabol_5"
                ],
                [
                    "id"=> "576",
                    "product_id"=> "201",
                    "theme_id"=> "185",
                    "scu"=> "899",
                    "theme_url"=> "prep_endokr_zabol_6"
                ],
                [
                    "id"=> "577",
                    "product_id"=> "202",
                    "theme_id"=> "180",
                    "scu"=> "900",
                    "theme_url"=> "prep_endokr_zabol_1"
                ],
                [
                    "id"=> "578",
                    "product_id"=> "203",
                    "theme_id"=> "181",
                    "scu"=> "901",
                    "theme_url"=> "prep_endokr_zabol_2"
                ],
                [
                    "id"=> "579",
                    "product_id"=> "204",
                    "theme_id"=> "182",
                    "scu"=> "902",
                    "theme_url"=> "prep_endokr_zabol_3"
                ],
                [
                    "id"=> "580",
                    "product_id"=> "205",
                    "theme_id"=> "183",
                    "scu"=> "903",
                    "theme_url"=> "prep_endokr_zabol_4"
                ],
                [
                    "id"=> "581",
                    "product_id"=> "206",
                    "theme_id"=> "184",
                    "scu"=> "904",
                    "theme_url"=> "prep_endokr_zabol_5"
                ],
                [
                    "id"=> "582",
                    "product_id"=> "207",
                    "theme_id"=> "185",
                    "scu"=> "905",
                    "theme_url"=> "prep_endokr_zabol_6"
                ],
                [
                    "id"=> "583",
                    "product_id"=> "208",
                    "theme_id"=> "187",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_2"
                ],
                [
                    "id"=> "584",
                    "product_id"=> "208",
                    "theme_id"=> "188",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_3"
                ],
                [
                    "id"=> "585",
                    "product_id"=> "208",
                    "theme_id"=> "189",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_4"
                ],
                [
                    "id"=> "586",
                    "product_id"=> "208",
                    "theme_id"=> "190",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_5"
                ],
                [
                    "id"=> "587",
                    "product_id"=> "208",
                    "theme_id"=> "191",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_6"
                ],
                [
                    "id"=> "588",
                    "product_id"=> "208",
                    "theme_id"=> "192",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_7"
                ],
                [
                    "id"=> "589",
                    "product_id"=> "208",
                    "theme_id"=> "186",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_1"
                ],
                [
                    "id"=> "590",
                    "product_id"=> "208",
                    "theme_id"=> "193",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_8"
                ],
                [
                    "id"=> "591",
                    "product_id"=> "208",
                    "theme_id"=> "194",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_9"
                ],
                [
                    "id"=> "592",
                    "product_id"=> "208",
                    "theme_id"=> "195",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_10"
                ],
                [
                    "id"=> "593",
                    "product_id"=> "208",
                    "theme_id"=> "196",
                    "scu"=> "906",
                    "theme_url"=> "bioorgan_him_11"
                ],
                [
                    "id"=> "594",
                    "product_id"=> "209",
                    "theme_id"=> "186",
                    "scu"=> "907",
                    "theme_url"=> "bioorgan_him_1"
                ],
                [
                    "id"=> "595",
                    "product_id"=> "210",
                    "theme_id"=> "187",
                    "scu"=> "908",
                    "theme_url"=> "bioorgan_him_2"
                ],
                [
                    "id"=> "596",
                    "product_id"=> "211",
                    "theme_id"=> "188",
                    "scu"=> "909",
                    "theme_url"=> "bioorgan_him_3"
                ],
                [
                    "id"=> "597",
                    "product_id"=> "212",
                    "theme_id"=> "189",
                    "scu"=> "910",
                    "theme_url"=> "bioorgan_him_4"
                ],
                [
                    "id"=> "598",
                    "product_id"=> "213",
                    "theme_id"=> "190",
                    "scu"=> "911",
                    "theme_url"=> "bioorgan_him_5"
                ],
                [
                    "id"=> "599",
                    "product_id"=> "214",
                    "theme_id"=> "191",
                    "scu"=> "912",
                    "theme_url"=> "bioorgan_him_6"
                ],
                [
                    "id"=> "600",
                    "product_id"=> "215",
                    "theme_id"=> "192",
                    "scu"=> "913",
                    "theme_url"=> "bioorgan_him_7"
                ],
                [
                    "id"=> "601",
                    "product_id"=> "216",
                    "theme_id"=> "193",
                    "scu"=> "914",
                    "theme_url"=> "bioorgan_him_8"
                ],
                [
                    "id"=> "602",
                    "product_id"=> "217",
                    "theme_id"=> "194",
                    "scu"=> "915",
                    "theme_url"=> "bioorgan_him_9"
                ],
                [
                    "id"=> "603",
                    "product_id"=> "218",
                    "theme_id"=> "195",
                    "scu"=> "916",
                    "theme_url"=> "bioorgan_him_10"
                ],
                [
                    "id"=> "604",
                    "product_id"=> "219",
                    "theme_id"=> "196",
                    "scu"=> "917",
                    "theme_url"=> "bioorgan_him_11"
                ],
                [
                    "id"=> "605",
                    "product_id"=> "220",
                    "theme_id"=> "197",
                    "scu"=> "918",
                    "theme_url"=> "int_prep_vl_na_immun_1"
                ],
                [
                    "id"=> "606",
                    "product_id"=> "220",
                    "theme_id"=> "198",
                    "scu"=> "918",
                    "theme_url"=> "int_prep_vl_na_immun_2"
                ],
                [
                    "id"=> "607",
                    "product_id"=> "220",
                    "theme_id"=> "199",
                    "scu"=> "918",
                    "theme_url"=> "int_prep_vl_na_immun_3"
                ],
                [
                    "id"=> "608",
                    "product_id"=> "220",
                    "theme_id"=> "200",
                    "scu"=> "918",
                    "theme_url"=> "int_prep_vl_na_immun_4"
                ],
                [
                    "id"=> "609",
                    "product_id"=> "220",
                    "theme_id"=> "201",
                    "scu"=> "918",
                    "theme_url"=> "int_prep_vl_na_immun_5"
                ],
                [
                    "id"=> "610",
                    "product_id"=> "220",
                    "theme_id"=> "202",
                    "scu"=> "918",
                    "theme_url"=> "int_prep_vl_na_immun_6"
                ],
                [
                    "id"=> "611",
                    "product_id"=> "220",
                    "theme_id"=> "203",
                    "scu"=> "918",
                    "theme_url"=> "int_prep_vl_na_immun_7"
                ],
                [
                    "id"=> "612",
                    "product_id"=> "220",
                    "theme_id"=> "204",
                    "scu"=> "918",
                    "theme_url"=> "int_prep_vl_na_immun_8"
                ],
                [
                    "id"=> "613",
                    "product_id"=> "220",
                    "theme_id"=> "205",
                    "scu"=> "918",
                    "theme_url"=> "int_prep_vl_na_immun_9"
                ],
                [
                    "id"=> "614",
                    "product_id"=> "221",
                    "theme_id"=> "1",
                    "scu"=> "919",
                    "theme_url"=> "urok1"
                ],
                [
                    "id"=> "615",
                    "product_id"=> "221",
                    "theme_id"=> "6",
                    "scu"=> "919",
                    "theme_url"=> "urok6"
                ],
                [
                    "id"=> "616",
                    "product_id"=> "221",
                    "theme_id"=> "62",
                    "scu"=> "919",
                    "theme_url"=> "gist4"
                ],
                [
                    "id"=> "617",
                    "product_id"=> "221",
                    "theme_id"=> "61",
                    "scu"=> "919",
                    "theme_url"=> "gist3"
                ],
                [
                    "id"=> "618",
                    "product_id"=> "221",
                    "theme_id"=> "63",
                    "scu"=> "919",
                    "theme_url"=> "gist5"
                ],
                [
                    "id"=> "619",
                    "product_id"=> "221",
                    "theme_id"=> "58",
                    "scu"=> "919",
                    "theme_url"=> "gist0"
                ],
                [
                    "id"=> "620",
                    "product_id"=> "221",
                    "theme_id"=> "2",
                    "scu"=> "919",
                    "theme_url"=> "urok2"
                ],
                [
                    "id"=> "621",
                    "product_id"=> "221",
                    "theme_id"=> "3",
                    "scu"=> "919",
                    "theme_url"=> "urok3"
                ],
                [
                    "id"=> "622",
                    "product_id"=> "221",
                    "theme_id"=> "60",
                    "scu"=> "919",
                    "theme_url"=> "gist2"
                ],
                [
                    "id"=> "623",
                    "product_id"=> "221",
                    "theme_id"=> "4",
                    "scu"=> "919",
                    "theme_url"=> "urok4"
                ],
                [
                    "id"=> "624",
                    "product_id"=> "221",
                    "theme_id"=> "5",
                    "scu"=> "919",
                    "theme_url"=> "urok5"
                ],
                [
                    "id"=> "625",
                    "product_id"=> "221",
                    "theme_id"=> "59",
                    "scu"=> "919",
                    "theme_url"=> "gist1"
                ],
                [
                    "id"=> "626",
                    "product_id"=> "221",
                    "theme_id"=> "186",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_1"
                ],
                [
                    "id"=> "627",
                    "product_id"=> "221",
                    "theme_id"=> "187",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_2"
                ],
                [
                    "id"=> "628",
                    "product_id"=> "221",
                    "theme_id"=> "188",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_3"
                ],
                [
                    "id"=> "629",
                    "product_id"=> "221",
                    "theme_id"=> "189",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_4"
                ],
                [
                    "id"=> "630",
                    "product_id"=> "221",
                    "theme_id"=> "190",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_5"
                ],
                [
                    "id"=> "631",
                    "product_id"=> "221",
                    "theme_id"=> "191",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_6"
                ],
                [
                    "id"=> "632",
                    "product_id"=> "221",
                    "theme_id"=> "192",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_7"
                ],
                [
                    "id"=> "633",
                    "product_id"=> "221",
                    "theme_id"=> "193",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_8"
                ],
                [
                    "id"=> "634",
                    "product_id"=> "221",
                    "theme_id"=> "194",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_9"
                ],
                [
                    "id"=> "635",
                    "product_id"=> "221",
                    "theme_id"=> "195",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_10"
                ],
                [
                    "id"=> "636",
                    "product_id"=> "221",
                    "theme_id"=> "196",
                    "scu"=> "919",
                    "theme_url"=> "bioorgan_him_11"
                ]
                ];

            foreach($arr as $d){
                $pr = Product::find()
                    ->where(['artikul1'=>$d['scu']])
                    ->orWhere(['artikul2'=>$d['scu']])
                    ->one();
                
                if(!$pr) {
                    echo(PHP_EOL.'Product by artikul '.$d['scu'].': ');
                    echo('pr not found');
                    print_r($d);
                    continue;
                }
                //else echo('found with id '.$pr->id.', theme by url: ');
                
                $pp = PlatformPage::find()
                    ->where(['LIKE','file',''.$d['theme_url'].'.html'])
                    ->orWhere(['LIKE','file_2',''.$d['theme_url'].'.html'])
                    ->one();

                if(!$pp) {
                    echo(PHP_EOL.'Product by artikul '.$d['scu'].': ');
                    echo('pp not found ('.$d['theme_url'].')');
                    continue;
                }
                //else echo('id '.$pp->id.', file '.$pp->file);

                $pba = new ProductBuyAction;
                $pba->productId = $pr->id;
                $pba->itemName = 'PlatformPage';
                $pba->itemId = $pp->id;
                $pba->itemAction = 'platform_page_access';
                
                if(!$pba->save()) print_r($pba->getErrors());

            }
        
        }

        public function actionOrderMember(){
            $orders = Order::find()->all();
            foreach($orders as $i=>$d){
                if($d->memberId) continue;
                echo(PHP_EOL.$d->id.', email '.$d->email);
                $m = Member::createOrGetByEmail($d->email,$d->name,$d->phone);
                if($m){
                    echo(', member id is '.$m->id);
                    $d->memberId = $m->id;
                    if($d->save() ) echo(', added memberId to Order');
                    else ', not saved order';
                }

            }
        }
        public function actionPlatformMember(){
            $members = file_get_contents('/var/www/site_main/data/www/christmedschool.com/platform_users.json');
            $members = json_decode($members, true);


            foreach($members as $i=>$d){

                echo(PHP_EOL.$d['id']);

                if( empty($d['email']) ) {
                    echo('WTF EMAIL NOT FOUND');
                    continue;
                }


                
                $m = Member::createOrGetByEmail($d['email'],isset($d['first_name'])?$d['first_name']:null,isset($d['phone'])?$d['phone']:null);
                if($m){
                    echo(PHP_EOL.'member get '.$m->id);

                    if(!$m->platform_id && $d['id']) $m->platform_id = $d['id'];
                    if(!$m->platform_password && isset($d['password']) ) $m->platform_password = $d['password'];
                    if(!$m->platform_joined && isset($d['date_joined']) ) $m->platform_joined = substr($d['date_joined'],0,19);
                    if(!$m->platform_lastlogin && isset($d['last_login']) ) $m->platform_lastlogin = substr($d['last_login'],0,19);
                    if(!$m->firstname && isset($d['first_name']) ) $m->firstname = $d['first_name'];
                    if($m->platform_id && isset($d['phone']) ) $m->phone = $d['phone'];
                    if(!$m->platform_username && isset($d['username']) ) $m->platform_username = trim(strip_tags($d['username']));
                    if(!$m->platform_profileId && isset($d['profile_id']) ) $m->platform_profileId = $d['profile_id'];

                    if(!$m->save() ) {
                        return $m->getErrors();
                    }
                    echo(', member update');
                }
            }
        }

        public function actionThemeByPlatform(){
            $access = file_get_contents('/var/www/site_main/data/www/christmedschool.com/platform_accesstheme.json');
            $access = json_decode($access, true);

            foreach($access as $d){
                $m = Member::createOrGetByEmail($d['user_email'],null,null);
                if($m){
                    echo(PHP_EOL.'member get '.$m->id);

                    if(!$m->platform_id && $d['id']) $m->platform_id = $d['user_id'];

                    if(!$m->save() ) {
                        return $m->getErrors();
                    }
                    echo(', member update'.PHP_EOL);
                }

                $item_to_access = PlatformPage::findOne($d['new_page_id']);
                            
                $paccess = PlatformAccess::find()->where(['memberId'=>$m->id,'pageId'=>$item_to_access->id])->one();
                if(!$paccess){
                    echo('access not found'.PHP_EOL);
                    $paccess = new PlatformAccess;
                    $paccess->memberId = $m->id;
                    $paccess->pageId = $item_to_access->id;
                    $paccess->subjectId = $item_to_access->subjectId;
                    $paccess->grantedBy = 'platform_access_'.$d['user_email'];
                    $paccess->grantedById = $d['id'];

                    if(!$paccess->save()){
                        print_r($paccess->getErrors());
                        return;
                    }
                    else {
                        echo('access granted'.PHP_EOL);
                    }
                }
                else {
                    echo('access exists'.PHP_EOL);
                }



            }


        }

        public function actionOrderOtherEmail(){
            $order = file_get_contents('/var/www/site_main/data/www/christmedschool.com/platform_otheremail.json');
            $order = json_decode($order, true);

            foreach($order as $d){
                $o = Order::findOne($d['id']);
                $o->platformOtherEmail = $d['platform_email'];
                if(!$o->save()) print_r( $o->getErrors() );

            }


        }

        public function actionAccessByOrder($id=null){

            $w = [];
            if($id) $w['id'] = $id;

            $od = Order::find()
                ->where(['status'=>3])
                //->andWhere(['is not', 'platformOtherEmail', new \yii\db\Expression('null')])
                //->andWhere(['is', 'wpImported', new \yii\db\Expression('null')])
                //->andWhere(['>', 'platformOtherEmail', new \yii\db\Expression('null')])
                ->andWhere($w)
                ->orderBy(['id'=>SORT_DESC])
                ->asArray()->all();
            
            foreach($od as $i=>$d){

                
                echo(PHP_EOL.($i+1).'/'.count($od).' Processing order '.$d['id'].PHP_EOL);

                $o_items = OrderItem::find()->where(['order_id'=>$d['id']])->all();
                
                foreach($o_items as $item){

                    echo('OrderItem '.$item->id.': '.PHP_EOL);

                    $pa_all = ProductBuyAction::find()->where(['productId'=>$item->product_id])->all();
                    if(!$pa_all){
                        echo('buy_action not found'.PHP_EOL);
                        continue;
                    }

                    foreach($pa_all as $pa){

                        if($pa->itemAction == 'platform_page_access'){
                            $item_to_access = PlatformPage::findOne($pa->itemId);
                            
                            $paccess = PlatformAccess::find()->where(['memberId'=>$d['memberId'],'pageId'=>$item_to_access->id])->one();
                            if(!$paccess){
                                $paccess = new PlatformAccess;
                                $paccess->memberId = $d['memberId'];
                                $paccess->pageId = $item_to_access->id;
                                $paccess->subjectId = $item_to_access->subjectId;
                                $paccess->grantedBy = 'order';
                                $paccess->grantedById = $d['id'];

                                if(!$paccess->save()){
                                    print_r($paccess->getErrors());
                                    return;
                                }
                                else {
                                    echo('access granted'.PHP_EOL);
                                }
                            }
                            else {
                                echo('access exists'.PHP_EOL);
                            }

                        }
                    }

                }
            }

        }

        public function actionOrderWp(){
            $items = file_get_contents('/var/www/site_main/data/www/christmedschool.com/order_wp.json');
            $items = json_decode($items, true);

            $orders = [];

            foreach($items as $i=>$d){
                echo(PHP_EOL.'Processing item '.$d['order_item_id'].' by order_id '.$d['order_id'].PHP_EOL );

                $o = Order::find()->where(['wpImported'=>$d['order_id']])->one();
                if(!$o){
                    echo('Create new one order'.PHP_EOL );
                    $o = new Order;
                    $o->id = $i+142;
                    $o->wpImported = Intval($d['order_id']);
                    $o->name = strip_tags( trim( $d['customer__name'] ));
                    $o->email = strip_tags( trim( $d['customer__email'] ));
                    $o->status = 3;
                    $o->created = gmdate("Y-m-d H:i:s", ($d['date_created'] - 25569) * 86400);
                    $o->amount = Intval($d['order__net_total']);
                    $o->payMethod = 1;
                    $o->firstname = strip_tags( trim( $d['customer__name'] ));
                    if(!$o->save()) print_r( $o->getErrors() );
                }
                else {
                    echo('Found order'.$o->id.PHP_EOL );
                }

                /*
                $oi = OrderItem::find()->where(['product_id'=>$d['product_new_site__id'],'order_id'=>$o->id])->one();
                if(!$oi){
                    echo('Create new one orderItem '.$d['product__sku'].PHP_EOL );
                    $pr = Product::findOne($d['product_new_site__id']);

                    $priceType = 1;
                    $priceName = $pr->price1_name;

                    if($pr->artikul1 == $d['product__sku']){
                        $priceType = 1;
                        $priceName = $pr->price1_name;
                    }

                    if($pr->artikul2 == $d['product__sku']){
                        $priceType = 2;
                        $priceName = $pr->price1_name;
                    }

                    $oi = new OrderItem;
                    $oi->order_id = $o->id;
                    $oi->product_id = $d['product_new_site__id'];
                    $oi->name = $pr->h1;
                    $oi->priceSum = $d['product_net_revenue'];
                    $oi->coupon_priceSum = $d['product_net_revenue'];
                    $oi->quantity = 1;
                    $oi->cost = $d['product_net_revenue'];
                    $oi->priceName = $priceName;
                    $oi->priceType = $priceType;
                    $oi->artikul = $d['product__sku'];
                    $oi->nds = null;
                    $oi->wpImported = $d['order_item_id'];
                    if( !$oi->save() ) print_r($oi->getErrors());
                }
                */


                // create order
                // create order_item
                //

                
            }            
        }

        public function actionMigrate($email_child,$email_main){

            $child = Member::find()->where(['email'=>$email_child])->one();
            $main = Member::find()->where(['email'=>$email_main])->one();

            $childId = $child->id;
            $mainId = $main->id;


            if(!$main->platform_id) $main->platform_id = $child->platform_id;
            if(!$main->platform_username) $main->platform_username = $child->platform_username;
            if(!$main->platform_password) $main->platform_password = $child->platform_password;
            if(!$main->platform_joined) $main->platform_joined = $child->platform_joined;
            if(!$main->platform_lastlogin) $main->platform_lastlogin = $child->platform_lastlogin;
            if(!$main->platform_profileId) $main->platform_profileId = $child->platform_profileId;
            if(!$main->platform_email) $main->platform_email = $child->email;
            if(!$main->firstname) $main->firstname = $child->firstname;

            $main->save();
            echo('Platform data: '.$main->id.PHP_EOL);

            $order = Order::find()->where(['memberId'=>$childId])->all();
            foreach($order as $d){
                echo('Order: '.$d->id.PHP_EOL);
                $d->memberId = $mainId;
                $d->email = $main->email;
                $d->save();
            }

            $orderMain = Order::find()->where(['memberId'=>$mainId,'platformOtherEmail'=>$child->email])->all();
            foreach($orderMain as $d){
                echo('Order platformOtherEmail removed '.$d->id.PHP_EOL);
                $d->platformOtherEmail = null;
                $d->save();
            }

            $access = PlatformAccess::find()->where(['memberId'=>$childId])->all();
            foreach($access as $d){
                echo('Access: '.$d->id.PHP_EOL);
                $d->memberId = $mainId;
                $d->save();
            }
            
            $child->delete();

            echo('Child deleted'.PHP_EOL);


            echo('SUCCESS!'.PHP_EOL);

        }

	
	}
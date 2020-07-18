<?php


namespace App\components;

use Illuminate\Support\Facades\DB;

class Helper
{
    public static function getResponseServer(string $url):bool
    {
        $header = '';
        $options = array(
            CURLOPT_URL => trim($url),
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        if (!curl_errno($ch)) {
            $header = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
        curl_close($ch);

        return $header > 0 && $header < 400;
    }

    public static function queryString(array $data):string
    {
        $string = '';
        foreach ($data as $key => $value){
            if(is_array($value)){
                foreach ($value as $item){
                    $string .= $key.'[]='.$item.'&';
                }
            }elseif (isset($value)){
                $string .= $key.'='.$value.'&';
            }

        }
        return substr($string, 0, -1);
    }
    public static function getDate(string $date):string
    {
        $publisherYear = date('Y',strtotime($date));
        $publisherMonth = date('m',strtotime($date));
        return $date ? static::monthArray()[$publisherMonth].' '.$publisherYear : '';
    }

    public static function getFullDate(string $date):string
    {
        $publisherYear = date('Y',strtotime($date));
        $publisherMonth = date('m',strtotime($date));
        return $date ? static::monthFullArray()[$publisherMonth].' '.$publisherYear : '';
    }

    public static function monthArray():array
    {
        return [
            '01' => 'Янв',
            '02' => 'Фев',
            '03' => 'Мар',
            '04' => 'Апр',
            '05' => 'Май',
            '06' => 'Июн',
            '07' => 'Июл',
            '08' => 'Авг',
            '09' => 'Сен',
            '10' => 'Окт',
            '11' => 'Ноя',
            '12' => 'Дек',
        ];

    }

    public static function monthFullArray():array
    {
        return [
            '01' => 'Январь',
            '02' => 'Февраль',
            '03' => 'Март',
            '04' => 'Апрель',
            '05' => 'Май',
            '06' => 'Июнь',
            '07' => 'Июль',
            '08' => 'Август',
            '09' => 'Сентябрь',
            '10' => 'Октябрь',
            '11' => 'Ноябрь',
            '12' => 'Декабрь',
        ];

    }

    public static function clearArray(array $array):array
    {
        $newArr = [];
        if(is_array($array)){
            foreach ($array as $key => $value){
                if(is_array($value)){
                    $newArr[$key] = static::clearArray($value);
                }else{
                    $newArr[$key] = !$value ? $value : static::clearData($value);
                }
            }
        }
        return $newArr;
    }

    public static function objectToArray($array)
    {
        if(is_object($array) || is_array($array)) {
            $ret = (array)$array;
            foreach($ret as &$item) {
                $item = static::objectToArray($item);
            }
            return $ret;
        }
        return $array;
    }

    public static function table(string $name):string
    {
        $array = ['{','}','%'];
        return str_replace($array,'',$name);
    }

    public static function getAlias(string $path):string
    {
//        $pathInfo = $_SERVER['REQUEST_URI'];
//        $path = explode('?',$pathInfo);
        $array = ['/','category','post'];
        return str_replace($array,'',$path);
    }

    public static function tableList():array
    {
        $array = [];
        foreach (DB::select('SHOW TABLES') as $tableName) {
            foreach ($tableName as $name){
                $model = static::table($name);
                if(strripos($model,'_has_')){
                    continue;
                }
                $array[$model] = $model;
            }
        }
        return $array;
    }

    public static function clearData(string $data):string
    {
        $string = preg_replace('/([^\pL\pN\pP\pS\pZ])|([\xC2\xA0])/u', ' ', $data);
        $string = preg_replace('/ {2,}/',' ',trim(strip_tags(html_entity_decode($string))));
        return $string;
    }

    public static function substr(string $text,int $start = 0,int $end = 300):string
    {
        $text = static::clearData($text);
        $text = substr($text, $start, $end);
        $text = rtrim($text, "!,.-");
        $text = substr($text, 0, strrpos($text, ' '));
        return $text." … ";
    }

    public static function clearPhone(string $phone):string
    {
        return preg_replace('/[\D]/', '', $phone);
    }
}

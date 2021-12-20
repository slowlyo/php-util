<?php

class Helper
{
    private static $instance;

    private $redis_connect = '127.0.0.1';

    private $redis_port = '6379';

    private $redis_password = '';

    public $redis_instance = null;

    private $pending_date = '';

    private function __construct() { }

    private function __clone() { }

    public static function make()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * 比 var_dump() 要好看的打印
     *
     * @param $data
     */
    public function dump($data)
    {
        highlight_string("<?php \n\n" . var_export($data, true) . ";\n\n?>");
    }

    public function dd($data)
    {
        $this->dump($data);
        die;
    }

    /**
     * 获取 IP 地址
     *
     * @return mixed|string
     */
    public function getIp()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } else if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = "";
        }

        return $cip;
    }

    public function arrayGet(array $array, $key, $default = null)
    {
        return $array[$key] ?? $default;
    }

    /**
     * 获取域名
     *
     * @return string
     */
    public function getDomainName()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

        return $http_type . $_SERVER['HTTP_HOST'];
    }

    /**
     * 获取首字母
     */
    public function getFirstChar($str)
    {
        if (empty($str)) {
            return '';
        }
        //取出参数字符串中的首个字符
        $temp_str = substr($str, 0, 1);
        if (ord($temp_str) > 127) {
            $str = substr($str, 0, 3);
        } else {
            $str   = $temp_str;
            $fchar = ord($str[0]);
            if ($fchar >= ord('A') && $fchar <= ord('z')) {
                return strtoupper($temp_str);
            } else {
                return null;
            }
        }
        $s1 = iconv('UTF-8', 'gb2312//IGNORE', $str);
        if (empty($s1)) {
            return null;
        }
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        if (empty($s2)) {
            return null;
        }
        $s   = $s2 == $str ? $s1 : $str;
        $asc = ord($s[0]) * 256 + ord($s[1]) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        if ($str == '哒') {
            return 'D';
        }

        return $this->rareWords($asc);
    }

    //百家姓中的生僻字
    public function rareWords($asc = '')
    {
        $rare_arr = [
            -3652  => ['word' => "窦", 'first_char' => 'D'],
            -8503  => ['word' => "奚", 'first_char' => 'X'],
            -9286  => ['word' => "酆", 'first_char' => 'F'],
            -7761  => ['word' => "岑", 'first_char' => 'C'],
            -5128  => ['word' => "滕", 'first_char' => 'T'],
            -9479  => ['word' => "邬", 'first_char' => 'W'],
            -5456  => ['word' => "臧", 'first_char' => 'Z'],
            -7223  => ['word' => "闵", 'first_char' => 'M'],
            -2877  => ['word' => "裘", 'first_char' => 'Q'],
            -6191  => ['word' => "缪", 'first_char' => 'M'],
            -5414  => ['word' => "贲", 'first_char' => 'B'],
            -4102  => ['word' => "嵇", 'first_char' => 'J'],
            -8969  => ['word' => "荀", 'first_char' => 'X'],
            -4938  => ['word' => "於", 'first_char' => 'Y'],
            -9017  => ['word' => "芮", 'first_char' => 'R'],
            -2848  => ['word' => "羿", 'first_char' => 'Y'],
            -9477  => ['word' => "邴", 'first_char' => 'B'],
            -9485  => ['word' => "隗", 'first_char' => 'K'],
            -6731  => ['word' => "宓", 'first_char' => 'M'],
            -9299  => ['word' => "郗", 'first_char' => 'X'],
            -5905  => ['word' => "栾", 'first_char' => 'L'],
            -4393  => ['word' => "钭", 'first_char' => 'T'],
            -9300  => ['word' => "郜", 'first_char' => 'G'],
            -8706  => ['word' => "蔺", 'first_char' => 'L'],
            -3613  => ['word' => "胥", 'first_char' => 'X'],
            -8777  => ['word' => "莘", 'first_char' => 'S'],
            -6708  => ['word' => "逄", 'first_char' => 'P'],
            -9302  => ['word' => "郦", 'first_char' => 'L'],
            -5965  => ['word' => "璩", 'first_char' => 'Q'],
            -6745  => ['word' => "濮", 'first_char' => 'P'],
            -4888  => ['word' => "扈", 'first_char' => 'H'],
            -9309  => ['word' => "郏", 'first_char' => 'J'],
            -5428  => ['word' => "晏", 'first_char' => 'Y'],
            -2849  => ['word' => "暨", 'first_char' => 'J'],
            -7206  => ['word' => "阙", 'first_char' => 'Q'],
            -4945  => ['word' => "殳", 'first_char' => 'S'],
            -9753  => ['word' => "夔", 'first_char' => 'K'],
            -10041 => ['word' => "厍", 'first_char' => 'S'],
            -5429  => ['word' => "晁", 'first_char' => 'C'],
            -2396  => ['word' => "訾", 'first_char' => 'Z'],
            -7205  => ['word' => "阚", 'first_char' => 'K'],
            -10049 => ['word' => "乜", 'first_char' => 'N'],
            -10015 => ['word' => "蒯", 'first_char' => 'K'],
            -3133  => ['word' => "竺", 'first_char' => 'Z'],
            -6698  => ['word' => "逯", 'first_char' => 'L'],
            -9799  => ['word' => "俟", 'first_char' => 'Q'],
            -6749  => ['word' => "澹", 'first_char' => 'T'],
            -7220  => ['word' => "闾", 'first_char' => 'L'],
            -10047 => ['word' => "亓", 'first_char' => 'Q'],
            -10005 => ['word' => "仉", 'first_char' => 'Z'],
            -3417  => ['word' => "颛", 'first_char' => 'Z'],
            -6431  => ['word' => "驷", 'first_char' => 'S'],
            -7226  => ['word' => "闫", 'first_char' => 'Y'],
            -9293  => ['word' => "鄢", 'first_char' => 'Y'],
            -6205  => ['word' => "缑", 'first_char' => 'G'],
            -9764  => ['word' => "佘", 'first_char' => 'S'],
            -9818  => ['word' => "佴", 'first_char' => 'N'],
            -9509  => ['word' => "谯", 'first_char' => 'Q'],
            -3122  => ['word' => "笪", 'first_char' => 'D'],
            -9823  => ['word' => "佟", 'first_char' => 'T'],
        ];
        if (array_key_exists($asc, $rare_arr) && $rare_arr[$asc]['first_char']) {
            return $rare_arr[$asc]['first_char'];
        } else {
            return null;
        }
    }

    /**
     * 加密字符串
     *
     * @param string $data 要加密的字符串
     *
     * @return string
     */
    public function strEncode(string $data)
    {
        return base64_encode(openssl_encrypt($data, 'DES-ECB', md5('encrypt_and_decode')));
    }

    /**
     * 解密字符串
     *
     * @param string $data 要解密的字符串
     *
     * @return false|string
     */
    public function strDecode(string $data)
    {
        $str = openssl_decrypt(base64_decode($data), 'DES-ECB', md5('encrypt_and_decode'));

        return $str ?: '';
    }

    /**
     * 字符替换
     *
     * @param null     $str     要替换的字符
     * @param string   $new_str 用来替换的字符
     * @param int      $start   开始位置
     * @param int|null $length  替换长度
     *
     * @return string|string[]
     */
    public function strReplace($str, string $new_str, int $start, int $length = null)
    {
        if ($str == '') {
            return '';
        }

        if (!$length) {
            $length = mb_strlen($str) - $start;
        }
        $new_str = sprintf("%'$new_str{$length}s", $new_str);

        return substr_replace($str, $new_str, $start, $length);
    }

    /**
     * 隐藏手机号
     *
     * @param null   $phone 手机号
     * @param string $str   用来替换的字符
     *
     * @return string|string[]
     */
    public function hidePhoneNumber($phone, string $str = '*')
    {
        return $this->strReplace($phone, $str, 3, 4);
    }

    /**
     * 隐藏身份证号
     *
     * @param null   $id_number 身份证号
     * @param string $str       用来替换的字符
     *
     * @return string|string[]
     */
    public function hideIdNumber($id_number, string $str = '*')
    {
        return $this->strReplace($id_number, $str, 6, 8);
    }

    /**
     * 计算两点间的距离
     *
     * @param $lng
     * @param $lat
     * @param $lng1
     * @param $lat1
     *
     * @return string
     */
    public function getDistance($lng, $lat, $lng1, $lat1)
    {
        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI           = 3.1415926;

        $radLat1 = $lat * $PI / 180.0;
        $radLat2 = $lat1 * $PI / 180.0;

        $radLng1 = $lng * $PI / 180.0;
        $radLng2 = $lng1 * $PI / 180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        $tmp = round($distance / 1000, 1);
        if ($tmp <= 0) {
            return round($distance) . 'm';
        }

        return (round($distance / 1000, 1) > 0 ? round($distance / 1000, 1) : 0) . 'km';
    }

    /**
     * 连接 redis
     *
     * @return $this
     */
    public function redisConnect()
    {
        $redis = new Redis();
        $redis->connect($this->redis_connect, $this->redis_port);
        $redis->auth($this->redis_password);
        $this->redis_instance = $redis;

        return $this;
    }

    /**
     * @param string $connect
     * @param string $port
     * @param string $password
     *
     * @return $this
     */
    public function redis(string $connect = '127.0.0.1', string $port = '6379', string $password = '')
    {
        $this->redis_connect  = $connect;
        $this->redis_port     = $port;
        $this->redis_password = $password;

        return $this->redisConnect();
    }

    /**
     * 测试连接
     */
    public function redisTestConnect()
    {
        $this->redis_instance->set('test_redis_connect', 'redis ok');

        $this->dd($this->redis_instance->get('test_redis_connect'));
    }

    /**
     * redis 锁
     *
     * @param        $key
     * @param int    $expire_date
     * @param int    $count
     *
     * @return bool
     */
    public function redisLock($key, int $expire_date = 5, int $count = 1)
    {
        $key = md5($key);

        $result = $this->redis_instance->incr($key);
        if ($result <= $count) {
            $this->redis_instance->expire($key, $expire_date);

            return true;
        }

        return false;
    }

    /**
     * redis 解锁
     *
     * @param $key
     *
     * @return mixed
     */
    public function redisUnlock($key)
    {
        $key = md5($key);

        return $this->redis_instance->delete($key);
    }

    /**
     * 检查手机号:等级1-3
     *
     * @param     $number
     * @param int $grade
     *
     * @return false|int|void
     */
    public function isPhoneNumber($number, int $grade = 2)
    {
        switch ($grade) {
            case 1:
                return preg_match('/^(?:(?:\+|00)86)?1\d{10}$/', $number);
            case 2:
                return preg_match('/^(?:(?:\+|00)86)?1[3-9]\d{9}$/', $number);
            case 3:
                return preg_match('/^(?:(?:\+|00)86)?1(?:(?:3[\d])|(?:4[5-7|9])|(?:5[0-3|5-9])|(?:6[5-7])|(?:7[0-8])|(?:8[\d])|(?:9[1|8|9]))\d{8}$/', $number);
        }
    }

    /**
     * 检查身份证号
     *
     * @param $id_number
     *
     * @return false|int
     */
    public function isIdNumber($id_number)
    {
        return preg_match('/(^\d{8}(0\d|10|11|12)([0-2]\d|30|31)\d{3}$)|(^\d{6}(18|19|20)\d{2}(0\d|10|11|12)([0-2]\d|30|31)\d{3}(\d|X|x)$)/', $id_number);
    }

    /**
     * 检查邮箱
     *
     * @param $email
     *
     * @return false|int
     */
    public function isEmail($email)
    {
        return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/', $email);
    }

    /**
     * 重定向
     *
     * @param $url
     */
    public function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    /**
     * 计算年龄
     *
     * @param $birthday
     *
     * @return false|int|string
     */
    public function getAge($birthday)
    {
        $birthday = strtotime($birthday);
        //格式化出生时间年月日
        $byear  = date('Y', $birthday);
        $bmonth = date('m', $birthday);
        $bday   = date('d', $birthday);

        //格式化当前时间年月日
        $tyear  = date('Y');
        $tmonth = date('m');
        $tday   = date('d');

        //开始计算年龄
        $age = $tyear - $byear;
        if ($bmonth > $tmonth || $bmonth == $tmonth && $bday > $tday) {
            $age--;
        }

        return $age;
    }

    /**
     * 获取生肖
     *
     * @param $birthday
     *
     * @return string
     */
    public function getZodiac($birthday)
    {
        $animals = ['子鼠', '丑牛', '寅虎', '卯兔', '辰龙', '巳蛇', '午马', '未羊', '申猴', '酉鸡', '戌狗', '亥猪'];
        $key     = (date('Y', strtotime($birthday)) - 1900) % 12;

        return $animals[$key];
    }

    /**
     * 获取星座
     *
     * @param $date
     *
     * @return string
     */
    public function getConstellation($date)
    {
        $month = date('m', strtotime($date));
        $day   = date('d', strtotime($date));

        if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
            $constellation = "水瓶座";
        } else if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) {
            $constellation = "双鱼座";
        } else if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
            $constellation = "白羊座";
        } else if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
            $constellation = "金牛座";
        } else if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) {
            $constellation = "双子座";
        } else if (($month == 6 && $day >= 22) || ($month == 7 && $day <= 22)) {
            $constellation = "巨蟹座";
        } else if (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
            $constellation = "狮子座";
        } else if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
            $constellation = "处女座";
        } else if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) {
            $constellation = "天秤座";
        } else if (($month == 10 && $day >= 24) || ($month == 11 && $day <= 22)) {
            $constellation = "天蝎座";
        } else if (($month == 11 && $day >= 23) || ($month == 12 && $day <= 21)) {
            $constellation = "射手座";
        } else if (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) {
            $constellation = "摩羯座";
        }

        return $constellation;
    }

    /**
     * 获取身份证信息
     *
     * @param $id_number
     *
     * @return array
     */
    public function getIdCardInfo($id_number)
    {
        // 性别
        $gender = $this->getIdcardGender($id_number);

        // 生日
        $birthday = $this->getIdCardBirthday($id_number);

        // 年龄
        $age = $this->getIdcardAge($id_number);

        // 生肖
        $zodiac = $this->getIdCardZodiac($id_number);

        // 星座
        $constellation = $this->getIdCardConstellation($id_number);

        // 地区
        $area = $this->getIdCardArea($id_number);

        // 籍贯
        $hometown = $this->getIdCardHometown($id_number);


        return compact('gender', 'birthday', 'age', 'zodiac', 'constellation', 'area', 'hometown');
    }

    /**
     * 获取身份证 性别
     *
     * @param $id_number
     *
     * @return string
     */
    public function getIdcardGender($id_number)
    {
        return (int)substr($id_number, 16, 1) % 2 === 0 ? '女' : '男';
    }

    /**
     * 获取身份证 生日
     *
     * @param $id_number
     *
     * @return false|string
     */
    public function getIdCardBirthday($id_number)
    {
        return date('Y-m-d', strtotime(substr($id_number, 6, 8)));
    }

    /**
     * 获取身份证 年龄
     *
     * @param $id_number
     *
     * @return false|int|string
     */
    public function getIdcardAge($id_number)
    {
        return $this->getAge($this->getIdCardBirthday($id_number));
    }

    /**
     * 获取身份证 生肖
     *
     * @param $id_number
     *
     * @return string
     */
    public function getIdCardZodiac($id_number)
    {
        return $this->getZodiac($this->getIdCardBirthday($id_number));
    }

    /**
     * 获取身份证 星座
     *
     * @param $id_number
     *
     * @return string
     */
    public function getIdCardConstellation($id_number)
    {
        return $this->getConstellation($this->getIdCardBirthday($id_number));
    }

    /**
     * 获取身份证 区域
     *
     * @param $id_number
     *
     * @return mixed|null
     */
    public function getIdCardArea($id_number)
    {
        $area = [
            11 => "北京",
            12 => "天津",
            13 => "河北",
            14 => "山西",
            15 => "内蒙古",
            21 => "辽宁",
            22 => "吉林",
            23 => "黑龙江",
            31 => "上海",
            32 => "江苏",
            33 => "浙江",
            34 => "安徽",
            35 => "福建",
            36 => "江西",
            37 => "山东",
            41 => "河南",
            42 => "湖北",
            43 => "湖南",
            44 => "广东",
            45 => "广西",
            46 => "海南",
            50 => "重庆",
            51 => "四川",
            52 => "贵州",
            53 => "云南",
            54 => "西藏",
            61 => "陕西",
            62 => "甘肃",
            63 => "青海",
            64 => "宁夏",
            65 => "新疆",
            71 => "台湾",
            81 => "香港",
            82 => "澳门",
            91 => "国外",
        ];

        return $this->arrayGet($area, substr($id_number, 0, 2), '');
    }

    /**
     * 获取身份证 籍贯
     *
     * @param $id_number
     *
     * @return mixed|null
     */
    public function getIdCardHometown($id_number)
    {
        if (!file_exists(__DIR__ . '/address-code.php')) {
            return '';
        }
        $code      = substr($id_number, 0, 6);
        $addresses = require 'address-code.php';

        return $this->arrayGet($addresses, $code);
    }

    /**
     * 一行行读取文件
     *
     * @param $path
     *
     * @return array|false
     */
    public function fileToLine($path)
    {
        return file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }

    /**
     * 生成日期数组
     *
     * @param string $start    开始时间
     * @param string $end      结束时间
     * @param string $format   生成的时间格式
     * @param int    $interval 生成的时间间隔(默认一天)
     *
     * @return array|false[]|string[]
     */
    public function dateArray(string $start, string $end, string $format = 'Y-m-d', int $interval = 86400)
    {
        $dates = range(strtotime($start), strtotime($end), $interval);

        return array_map(function ($_date) use ($format) {
            return date($format, $_date);
        }, $dates);
    }

    /**
     * 获取当前毫秒
     *
     * @return string
     */
    public function millisecond()
    {
        return sprintf('%.0f', microtime(true) * 1000);
    }

    /**
     * http 请求
     *
     * @param       $url
     * @param       $method
     * @param null  $postfields
     * @param array $headers
     *
     * @return array
     */
    public function httpRequest($url, $method, $postfields = null, array $headers = [])
    {
        $method = strtoupper($method);
        $ci     = curl_init();

        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);

        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }

        $ssl = preg_match('/^https:\/\//i', $url);
        curl_setopt($ci, CURLOPT_URL, $url);

        if ($ssl) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false); // https请求 不验证证书和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false); // 不从证书中检查SSL加密算法是否存在
        }

        //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);

        /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response     = curl_exec($ci);
        $request_info = curl_getinfo($ci);
        $http_code    = curl_getinfo($ci, CURLINFO_HTTP_CODE);

        curl_close($ci);

        return compact('response', 'request_info', 'http_code');
    }

    /**
     * 把正方形图片裁剪为圆形
     *
     * @param string $path      原图片路径或者二进制字符串
     * @param string $save_path 裁剪完图片保存路径，如果为空则返回图片的二进制流数据
     *
     * @return bool|false|string 如果返回值为false 则裁剪图片失败否则裁剪完成，$save_path为空则返回二进制数据，否则为true
     */
    public function imageRound(string $path, string $save_path = '')
    {
        if (@is_file($path)) {
            // 如果传入的是文件路径则打开文件再创建图片
            $src = imagecreatefromstring(file_get_contents($path));
        } else {
            // 通过二进制字符串创建图片对象
            $src = @imagecreatefromstring($path);
        }
        if (!$src) {
            return false;
        }
        $w = imagesx($src);
        $h = imagesy($src);
        // 新建一个图像
        $new_pic = imagecreatetruecolor($w, $h);
        // 关闭图像混色
        imagealphablending($new_pic, false);
        // 设置图片保存透明通道
        imagesavealpha($new_pic, true);
        // 设置图片透明底色
        $transparent = imagecolorallocatealpha($new_pic, 0, 0, 0, 127);
        // 获取圆形半径
        $r = $w / 2;
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                // 获取原图片指定坐标点的像素值
                $c  = imagecolorat($src, $x, $y);
                $_x = $x - $w / 2;
                $_y = $y - $h / 2;
                if ((($_x * $_x) + ($_y * $_y)) < ($r * $r)) {
                    // 设置像素颜色值为原图片像素的颜色值
                    imagesetpixel($new_pic, $x, $y, $c);
                } else {
                    // 设置像素颜色值为透明
                    imagesetpixel($new_pic, $x, $y, $transparent);
                }
            }
        }
        $data = false;
        if (!$save_path) {
            // 如果保存路径为空则创建临时文件
            $save_path = tempnam(sys_get_temp_dir(), 'image_round');
            if (imagepng($new_pic, $save_path)) {
                // 如果图片保存到临时文件成功则读取图片的二进制数据
                $data = file_get_contents($save_path);
                // 删除临时文件
                @unlink($save_path);
            }
        } else {
            // 保存图片到指定路径
            $data = imagepng($new_pic, $save_path);
        }
        // 销毁裁剪为的图片对象
        imagedestroy($new_pic);
        // 销毁原图片对象
        imagedestroy($src);

        return $data;
    }

    /**
     * 修改图片大小
     *
     * @param string $path      图片路径或者图片二进制数据
     * @param int    $w         图片宽度
     * @param int    $h         图片高度
     * @param string $save_path 保存路径，如果为空则返回图片二进制数据
     *
     * @return bool|false|string
     */
    public function imageChangeSize(string $path, int $w, int $h, string $save_path = '')
    {
        if (@is_file($path)) {
            // 如果传入的是文件路径则打开文件再创建图片
            $path = file_get_contents($path);
        }
        if (!$w || !$h) {
            return $path;
        }
        // 通过二进制字符串创建图片对象
        $image = @imagecreatefromstring($path);
        if (!$image) {
            return false;
        }
        $new_pic = imagecreatetruecolor($w, $h);
        $s_w     = imagesx($image);
        $s_h     = imagesy($image);
        // 关闭图像混色
        imagealphablending($new_pic, false);
        // 设置图片保存透明通道
        imagesavealpha($new_pic, true);
        imagecopyresampled($new_pic, $image, 0, 0, 0, 0, $w, $h, $s_w, $s_h);
        $data = false;
        if (!$save_path) {
            // 如果保存路径为空则创建临时文件
            $save_path = tempnam(sys_get_temp_dir(), 'image_change_size');
            if (imagepng($new_pic, $save_path)) {
                // 如果图片保存到临时文件成功则读取图片的二进制数据
                $data = file_get_contents($save_path);
                // 删除临时文件
                @unlink($save_path);
            }
        } else {
            // 保存图片到指定路径
            $data = imagepng($new_pic, $save_path);
        }
        imagedestroy($image);
        imagedestroy($new_pic);

        return $data;
    }

    /**
     * @param string   $dist_path    要添加水印的图片或路径
     * @param string   $water_path   水印图片或路径
     * @param int      $x            水印放置X轴位置
     * @param int      $y            水印放置Y轴位置
     * @param int|null $water_width  水印图片宽
     * @param int|null $water_height 水印图片高
     * @param string   $save_path    加我水印后保存路径如果为空返回图片二进制字符串
     *
     * @return bool|false|string
     */
    public function addImageWater(string $dist_path, string $water_path, int $x, int $y, int $water_width = null, int $water_height = null, string $save_path = '')
    {
        if (@is_file($dist_path)) {
            // 如果传入的是文件路径则打开文件再创建图片
            $dist_image = imagecreatefromstring(file_get_contents($dist_path));
        } else {
            // 通过二进制字符串创建图片对象
            $dist_image = @imagecreatefromstring($dist_path);
        }
        if (!$dist_image) {
            return false;
        }
        $water_image_str = $this->imageChangeSize($water_path, $water_width, $water_height);
        if (!$water_image_str) {
            return false;
        }
        $water_image = imagecreatefromstring($water_image_str);
        if (!$water_image) {
            return false;
        }
        $water_width  = imagesx($water_image);
        $water_height = imagesy($water_image);
        imagecopy($dist_image, $water_image, $x, $y, 0, 0, $water_width, $water_height);
        $data = false;
        if (!$save_path) {
            // 如果保存路径为空则创建临时文件
            $save_path = tempnam(sys_get_temp_dir(), 'image_change_size');
            if (imagepng($dist_image, $save_path)) {
                // 如果图片保存到临时文件成功则读取图片的二进制数据
                $data = file_get_contents($save_path);
                // 删除临时文件
                @unlink($save_path);
            }
        } else {
            // 保存图片到指定路径
            $data = imagepng($dist_image, $save_path);
        }
        imagedestroy($dist_image);
        imagedestroy($water_image);

        return $data;
    }

    /**
     * 修改小程序二维码的logo
     *
     * @param string $path      小程序码图片或者二进制数据
     * @param string $logo_path logo图片地址或者二进制数据
     * @param string $save_path 如果为空则返回图片二进制流字符串
     *
     * @return bool|false|string
     */
    public function changeMiniProgramLogo(string $path, string $logo_path, string $save_path = '')
    {
        if (@is_file($path)) {
            [$w, $h, $type] = getimagesize($path);
        } else {
            [$w, $h, $type] = getimagesizefromstring($path);
        }
        if (!$w) {
            return false;
        }
        // 计算logo占二维码的宽度和高度
        $logo_w = $w / 2.2;
        // 计算logo在图片上的位置
        $x    = ($w - $logo_w) / 2;
        $logo = $this->imageRound($logo_path);

        return $this->addImageWater($path, $logo, $x, $x, $logo_w, $logo_w, $save_path);
    }
}

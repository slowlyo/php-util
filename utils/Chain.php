<?php

/**
 * 此类的本意是链式调用PHP自带函数
 * 除了本类中自带的函数, 调用其它函数时需要该函数的第一个原始参数是待处理的数据($this->data)
 * 如果要在本类中重写PHP自带函数, 需要php7以上支持
 */
class Chain
{
    public $data = null;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public static function make($data)
    {
        return new self($data);
    }

    /*
     * 核心代码
     * 利用可变函数去隐式调用PHP自带函数
     * 可变函数不能用于例如 echo，print，unset()，isset()，empty()，include，require 以及类似的语言结构。
     * 需要时用自己定义的重名函数来将这些结构用作可变函数。
     * http://www.php.net/manual/zh/functions.variable-functions.php
     */
    public function __call($func, $args)
    {
        $this->data = $func($this->data, ...$args);
        return $this;
    }

    public function __destruct()
    {
        unset($this->data);
    }

    public function all()
    {
        return $this->data;
    }

    public function first()
    {
        return $this->get(0);
    }

    public function last()
    {
        return $this->get(count($this->data));
    }

    public function get($key, $default = '')
    {
        return $this->data[$key] ?? $default;
    }

    public function group($key)
    {
        $new = [];
        foreach ($this->data as $v) {
            $new[$v[$key]][] = $v;
        }
        $this->data = $new;
        return $this;
    }

    public function implode($char = '')
    {
        $this->data = implode($char, $this->data);
        return $this;
    }

    public function explode($char)
    {
        $this->data = explode($char, $this->data);
        return $this;
    }

    public function empty()
    {
        return empty($this->data);
    }

    public function array_map($callback)
    {
        $this->data = array_map($callback, $this->data);
        return $this;
    }

    public function str_replace($search, $replace = '')
    {
        $this->data = str_replace($search, $replace, $this->data);
        return $this;
    }

    public function echo()
    {
        echo $this->data;//参数为整型时回被当作状态码返回 e.g. 200/404/500.... 浏览器无可见输出
    }

    public function exit()
    {
        exit($this->data); //参数为整型时回被当作状态码返回 e.g. 200/404/500.... 浏览器无可见输出
    }
}

// eg:

//$arr = [
//	['id' => 1, 'name' =>'111'],
//	['id' => 2, 'name' =>'222'],
//	['id' => 3, 'name' =>'333'],
//	['id' => 4, 'name' =>'333'],
//	['id' => 5, 'name' =>''],
//];

// echo Data::ini($arr)->array_column('name', 'id')->array_filter()->array_unique(); // {"1":"111","2":"222","3":"333"} 调用__toString
// Data::ini($arr)->array_column('name', 'id')->array_filter()->array_unique()->count()->strval()->echo(); // 3
// Data::ini($arr)->array_column('name', 'id')->array_filter()->array_unique()->reset()->var_dump(); // string(3) "111"
// Data::ini($arr)->array_column('name', 'id')->array_filter()->array_unique()->end()->echo(); // 333
// var_dump(Data::ini($arr)->array_column('name', 'id')->array_filter()->array_unique()->empty()); //bool(false)
// echo Data::ini($arr)->array_column('name', 'id')->array_filter()->array_unique()->get(1); //111
// echo Data::ini($arr)->array_column('name', 'id')->array_filter()->array_unique()->get(5); //0
// Data::ini($arr)->array_column('name', 'id')->array_filter()->array_unique()->json_encode()->exit(); // {"1":"111","2":"222","3":"333"}
// Data::ini($arr)->array_column('name', 'id')->array_filter()->array_unique()->implode(',')->echo();
// Data::ini('aaa^111|bbb^222|ccc^333')->explode('|')->json_encode()->echo();

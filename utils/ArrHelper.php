<?php

class ArrHelper
{
    private $arr;

    public function __construct($arr)
    {
        $this->arr = $arr;
    }


    /**
     * 数组笛卡尔积
     * 数据格式: [[1,2,3], [1,2,3]]
     */
    public function crossJoin()
    {
        $results = [[]];

        foreach ($this->arr as $index => $array) {
            $append = [];

            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;

                    $append[] = $product;
                }
            }

            $results = $append;
        }

        $this->arr = $results;

        return $this;
    }


    /**
     * 展开数组
     */
    public function flatten($depth = INF, $array = [])
    {
        if (count($array) == 0) {
            $array = $this->arr;
        }
        $result = [];

        foreach ($array as $item) {
            if (!is_array($item)) {
                $result[] = $item;
            } else {
                $values = $depth === 1 ? array_values($item) : $this->flatten($depth - 1, $item);

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        $this->arr = $result;

        return $this;
    }

    public function get($key, $default = null)
    {
        return $this->arr[$key] ?? $default;
    }

    /**
     * 获取全部数据
     *
     * @return mixed
     */
    public function all()
    {
        return $this->arr;
    }

    public function first()
    {
        return $this->arr[0];
    }

    public function last()
    {
        return $this->arr[count($this->arr) - 1];
    }
}

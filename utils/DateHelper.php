<?php

class DateHelper
{
    private $date;

    public function __construct($date)
    {
        if (!$date) {
            $date = date('Y-m-d H:i:s');
        }
        if (is_int($date)) {
            $date = date('Y-m-d H:i:s', $date);
        }
        $this->date = $date;
    }


    /**
     * 生成日期数组
     *
     * @param string $end 目标日期
     * @param string $format 生成的时间格式
     * @param int $interval 生成的时间间隔(默认一天)
     *
     * @return array|false[]|string[]
     */
    public function range(string $end, string $format = 'Y-m-d', int $interval = 86400)
    {
        $dates = range(strtotime($this->date), strtotime($end), $interval);

        return array_map(function ($_date) use ($format) {
            return date($format, $_date);
        }, $dates);
    }


    /**
     * 获取当前毫秒
     *
     * @return int
     */
    public function millisecond()
    {
        return intval(microtime(true) * 1000);
    }

    /**
     * Y-m-d
     *
     * @return false|string
     */
    public function toDateString()
    {
        return date('Y-m-d', strtotime($this->date));
    }

    /**
     * Y-m-d H:i:s
     *
     * @return false|string
     */
    public function toDateTimeString()
    {
        return date('Y-m-d H:i:s', strtotime($this->date));
    }

    /**
     * @return false|int
     */
    public function timestamp()
    {
        return strtotime($this->date);
    }

    public function addDay()
    {
        $this->addDays();
        return $this;
    }

    public function addDays($day = 1)
    {
        $this->date = date('Y-m-d H:i:s', strtotime("+$day day", strtotime($this->date)));
        return $this;
    }

    public function subDay()
    {
        $this->subDays();
        return $this;
    }

    public function subDays($day = 1)
    {
        $this->date = date('Y-m-d H:i:s', strtotime("-$day day", strtotime($this->date)));
        return $this;
    }

    public function addWeek()
    {
        $this->addWeeks();
        return $this;
    }

    public function addWeeks($weeks = 1)
    {
        $this->date = date('Y-m-d H:i:s', strtotime("+$weeks week", strtotime($this->date)));
        return $this;
    }

    public function subWeek()
    {
        $this->subWeeks();
        return $this;
    }

    public function subWeeks($weeks = 1)
    {
        $this->date = date('Y-m-d H:i:s', strtotime("-$weeks week", strtotime($this->date)));
        return $this;
    }

    public function addMonth()
    {
        $this->addMonths();
        return $this;
    }

    public function addMonths($months = 1)
    {
        $this->date = date('Y-m-d H:i:s', strtotime("+$months month", strtotime($this->date)));
        return $this;
    }

    public function subMonth()
    {
        $this->subMonths();
        return $this;
    }

    public function subMonths($months = 1)
    {
        $this->date = date('Y-m-d H:i:s', strtotime("-$months month", strtotime($this->date)));
        return $this;
    }

    public function addYear()
    {
        $this->addYears();
        return $this;
    }

    public function addYears($years = 1)
    {
        $this->date = date('Y-m-d H:i:s', strtotime("+$years year", strtotime($this->date)));
        return $this;
    }

    public function subYear()
    {
        $this->subYears();
        return $this;
    }

    public function subYears($years = 1)
    {
        $this->date = date('Y-m-d H:i:s', strtotime("-$years year", strtotime($this->date)));
        return $this;
    }

    public function startOfDay()
    {
        $this->date = date('Y-m-d 00:00:00', strtotime($this->date));
        return $this;
    }

    public function startOfMonth()
    {
        $this->date = date('Y-m-01 H:i:s', strtotime($this->date));
        $this->startOfDay();
        return $this;
    }

    public function startOfYear()
    {
        $this->date = date('Y-01-01 H:i:s', strtotime($this->date));
        $this->startOfDay();
        return $this;
    }

    public function endOfDay()
    {
        $this->date = date('Y-m-d 23:59:59', strtotime($this->date));
        return $this;
    }

    public function endOfMonth()
    {
        $this->addMonth()->startOfMonth()->subDay()->endOfDay();
        return $this;
    }

    public function endOfYear()
    {
        $this->addYears()->startOfYear()->subDay()->endOfDay();
        return $this;
    }

    /**
     * 计算年龄
     *
     * @return false|int|string
     */
    public function getAge()
    {
        $birthday = strtotime($this->date);
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
     * @return string
     */
    public function getZodiac()
    {
        $animals = ['子鼠', '丑牛', '寅虎', '卯兔', '辰龙', '巳蛇', '午马', '未羊', '申猴', '酉鸡', '戌狗', '亥猪'];
        $key     = (date('Y', strtotime($this->date)) - 1900) % 12;

        return $animals[$key];
    }

    /**
     * 获取星座
     *
     *
     * @return string
     */
    public function getConstellation()
    {
        $month = date('m', strtotime($this->date));
        $day   = date('d', strtotime($this->date));

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
}

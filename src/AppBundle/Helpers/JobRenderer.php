<?php
namespace AppBundle\Helpers;

/**
 * Created by PhpStorm.
 * User: diwin
 * Date: 2017. 09. 11.
 * Time: 0:04
 */

class JobRenderer
{
    protected $resultSet;

    /**
     * JobRenderer constructor.
     * @param $resultSet
     */
    public function __construct($resultSet)
    {
        $this->resultSet = $resultSet;
    }

    public function render()
    {
        $arr = array();
        foreach ($this->resultSet as $key => $item) {
            $arr[$item['name']][$key] = $item;
        }
        ksort($arr, SORT_NUMERIC);
        return $arr;
    }


}
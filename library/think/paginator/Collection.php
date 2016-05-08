<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zhangyajun <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think\paginator;

use Exception;
use think\Paginator;

/**
 * Class Collection
 * @package think\paginator
 * @method integer total()
 * @method integer listRows()
 * @method integer currentPage()
 * @method string render()
 * @method Paginator fragment($fragment)
 * @method Paginator appends($key, $value)
 */
class Collection extends \think\Collection
{

    /** @var Paginator */
    protected $paginator;

    public function __construct($items = [], Paginator $paginator = null)
    {
        if (!$paginator instanceof Paginator) {
            throw new \RuntimeException('Paginator Required!');
        }
        $this->paginator = $paginator;
        parent::__construct($items);
    }

    public static function make($items = [], Paginator $paginator = null)
    {
        return new static($items, $paginator);
    }


    public function toArray()
    {
        try {
            $total = $this->total();
        } catch (Exception $e) {
            $total = null;
        }

        return [
            'total'        => $total,
            'per_page'     => $this->listRows(),
            'current_page' => $this->currentPage(),
            'data'         => parent::toArray()
        ];
    }

    public function __call($method, $args)
    {
        if (method_exists($this->paginator, $method)) {
            return call_user_func_array([$this->paginator, $method], $args);
        } else {
            throw new Exception(__CLASS__ . ':' . $method . ' method not exist');
        }
    }
}
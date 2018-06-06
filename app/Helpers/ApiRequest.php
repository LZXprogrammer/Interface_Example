<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use App\Facades\GeoIPFacade as GeoIP;
use Illuminate\Support\Facades\Config;

class ApiRequest
{
    /**
     * Illuminate\Http\Request 实例.
     *
     * @var Illuminate\Http\Request
     */
    protected $_request;

    /**
     * 构造函数.
     *
     * @param Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->_request = $request;
    }

    /**
     * 调用 Request 的方法.
     *
     * @param string $method
     * @param mixed $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        return call_user_func_array([$this->_request, $method], $params);
    }

    /**
     * 获得 Request 参数.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if($this->_request->has($key)) {
            return $this->_request->$key;
        }
        return NULL;
    }

    /**
     * 获取用户 IP.
     *
     * @return string
     */
    public function getClientIp()
    {
        // 如果有头信息的话
        if($this->_request->server('HTTP_X_FORWARDED_FOR') != NULL) {
            return $this->_request->server('HTTP_X_FORWARDED_FOR');
        }

        return $this->_request->server('REMOTE_ADDR');
    }

    /**
     * 获取用户国家.
     *
     * @return mixed
     */
    public function getClientCountry()
    {
        try {
            return GeoIP::getCountry($this->getClientIp());
        } catch (\Exception $e) {
            return NULL;
        }
    }

    /**
     * 是否是重要国家.
     *
     * @return mixed
     */
    public function isImpCountry()
    {
        $imp_ctries = array_column(Config::get('customer.IMP_CTRY_LIST'), 'iso_code');
        $country_code = $this->getClientCountry() ?
                      $this->getClientCountry()->isoCode : NULL;
        
        return in_array($country_code, $imp_ctries);
    }

    /**
     * 获取 OSS 前缀.
     *
     * @param string $country_code
     * @return mixed
     */
    public function getOSSPrefix($country_code)
    {
        $oss_prefix = Config::get('customer.oss_prefix');

        // 根据国家选择 OSS
        switch (strtolower($country_code)) {
          case 'us':
            $rst = $oss_prefix['us'];
            break;
          case 'cn':
            $rst = $oss_prefix['bj'];
            break;
          case 'tw':
            $rst = $oss_prefix['hk'];
            break;
          default:
            $rst = $oss_prefix['us'];
            break;
        }

        return $rst;
    }
}

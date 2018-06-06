<?php

namespace App\Facades;
use GeoIp2\Database\Reader;

class GeoIP
{
    /**
     * GeoIp2\Database\Reader 实例.
     *
     * @var GeoIp2\Database\Reader
     */
    protected $_reader;

    /**
     * 构造函数.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_reader = new Reader(storage_path('geoipdb/GeoLite2-Country.mmdb'));
    }

    /**
     * 构造函数.
     *
     * @param string $ip
     * @return GeoIp2\Record\Country
     */
    public function getCountry($ip)
    {
        $record = $this->_reader->country($ip);

        return $record->country;
    }

}

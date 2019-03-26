<?php
/**
 * Created by PhpStorm.
 * User: xuxiaodao
 * Date: 2017/12/14
 * Time: 上午11:55
 */

namespace App\Http\Service;


class Http
{
    /**
     * post请求
     *
     * @author yezi
     *
     * @param $url
     * @param $option
     * @param array $header
     * @return array
     */
    public function post($url, $option, $header = [])
    {
        return $this->request($type = 'POST', $url, $option, $header);
    }

    /**
     * get请求
     *
     * @param $url
     * @param $option
     * @param array $header
     * @return array
     */
    public function get($url, $option, $header = [])
    {
        return $this->request($type = 'GET', $url, $option, $header);
    }

    /**
     * 发起请求
     *
     * @author yezi
     *
     * @param string $type
     * @param $url
     * @param $option
     * @param array $header
     * @param int $setopt
     * @return array
     */
    public function request($type = 'POST', $url, $option, $header = [], $setopt = 10)
    {
        $curl = curl_init();
        if (!empty ($option)) {
            if ($type == "GET") {
                $url .= '?' . http_build_query($option);
            } else {
                $options = json_encode($option);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $options);
            }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)');
        curl_setopt($curl, CURLOPT_TIMEOUT, $setopt);
        if (empty($header)) {
            $header = [];
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
        $result = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return ['status_code' => $status, 'result' => json_decode($result, true)];
    }
}
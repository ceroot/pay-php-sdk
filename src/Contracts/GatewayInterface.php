<?php

// +----------------------------------------------------------------------
// | pay-php-sdk
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/pay-php-sdk
// +----------------------------------------------------------------------

namespace Pay\Contracts;

/**
 * 支付网关接口
 * Interface GatewayInterface
 * @package Pay\Contracts
 */
interface GatewayInterface
{
    /**
     * 发起支付
     * @param array $options
     * @return mixed
     */
    public function apply(array $options);

    /**
     * 订单退款
     * @param $options
     * @return mixed
     */
    public function refund($options);

    /**
     * 关闭订单
     * @param $options
     * @return mixed
     */
    public function close($options);

    /**
     * 查询订单
     * @param $out_trade_no
     * @return mixed
     */
    public function find($out_trade_no);

    /**
     * 通知验证
     * @param array $data
     * @param null $sign
     * @param bool $sync
     * @return mixed
     */
    public function verify($data, $sign = null, $sync = false);
}

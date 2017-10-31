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

namespace Pay\Gateways\Wechat;

use Pay\Exceptions\GatewayException;
use Pay\Exceptions\InvalidArgumentException;

/**
 * 微信企业打款网关
 * Class TransferGateway
 * @package Pay\Gateways\Wechat
 */
class TransferGateway extends Wechat
{
    /**
     * 接口地址
     * @var string
     */
    protected $gateway = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';

    /**
     * 当前操作类型
     * @return string
     */
    protected function getTradeType()
    {
        return '';
    }

    /**
     * 应用并返回数据
     * @param array $options
     * @return array
     * @throws GatewayException
     */
    public function apply(array $options = [])
    {
        if (is_null($this->userConfig->get('app_id'))) {
            throw new InvalidArgumentException('Missing Config -- [app_id]');
        }
        $options['mchid'] = $this->config['mch_id'];
        $options['mch_appid'] = $this->userConfig->get('app_id');
        unset($this->config['appid']);
        unset($this->config['mch_id']);
        unset($this->config['sign_type']);
        unset($this->config['trade_type']);
        unset($this->config['notify_url']);
        $this->config = array_merge($this->config, $options);
        $this->config['sign'] = $this->getSign($this->config);
        $data = $this->fromXml($this->post(
            $this->gateway,
            $this->toXml($this->config),
            ['ssl_cer' => $this->userConfig->get('ssl_cer', ''), 'ssl_key' => $this->userConfig->get('ssl_key', '')]
        ));
        if (!isset($data['return_code']) || $data['return_code'] !== 'SUCCESS' || $data['result_code'] !== 'SUCCESS') {
            $error = 'GetResultError:' . $data['return_msg'];
            $error .= isset($data['err_code_des']) ? ' - ' . $data['err_code_des'] : '';
        }
        if (isset($error)) {
            throw new GatewayException($error, 20000, $data);
        }
        return $data;
    }
}

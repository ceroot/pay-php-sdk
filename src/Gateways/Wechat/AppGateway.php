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

use Pay\Exceptions\InvalidArgumentException;

/**
 * Class AppGateway
 * @package Pay\Gateways\Wechat
 */
class AppGateway extends Wechat
{

    /**
     * @return string
     */
    protected function getTradeType()
    {
        return 'APP';
    }

    /**
     * @param array $options
     * @return array
     */
    public function apply(array $options = [])
    {
        if (is_null($this->userConfig->get('appid'))) {
            throw new InvalidArgumentException('Missing Config -- [appid]');
        }
        $this->config['appid'] = $this->userConfig->get('appid');
        $payRequest = [
            'appid'     => $this->userConfig->get('appid'),
            'partnerid' => $this->userConfig->get('mch_id'),
            'prepayid'  => $this->preOrder($options)['prepay_id'],
            'timestamp' => time(),
            'noncestr'  => $this->createNonceStr(),
            'package'   => 'Sign=WXPay',
        ];
        $payRequest['sign'] = $this->getSign($payRequest);
        return $payRequest;
    }

}

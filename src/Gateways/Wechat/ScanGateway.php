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
 * Class ScanGateway
 * @package Pay\Gateways\Wechat
 */
class ScanGateway extends Wechat
{

    /**
     * @return string
     */
    protected function getTradeType()
    {
        return 'NATIVE';
    }

    /**
     * @param array $options
     * @return mixed
     */
    public function apply(array $options = [])
    {
        if (is_null($this->userConfig->get('app_id'))) {
            throw new InvalidArgumentException('Missing Config -- [app_id]');
        }
        return $this->preOrder($options)['code_url'];
    }
}

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
 * Class WapGateway
 * @package Pay\Gateways\Wechat
 */
class WapGateway extends Wechat
{
    /**
     * @return string
     */
    protected function getTradeType()
    {
        return 'MWEB';
    }

    /**
     * @param array $options
     * @return string
     */
    public function apply(array $options = [])
    {
        if (is_null($this->userConfig->get('app_id'))) {
            throw new InvalidArgumentException('Missing Config -- [app_id]');
        }
        $data = $this->preOrder($options);
        return is_null($this->userConfig->get('return_url')) ? $data['mweb_url'] : $data['mweb_url'] . '&redirect_url=' . urlencode($this->userConfig->get('return_url'));
    }
}

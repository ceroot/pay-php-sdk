# pay-php-sdk
PHP聚合支付SDK（微信支付 + 支付宝支付）

<p>
<a href="https://packagist.org/packages/zoujingli/pay-php-sdk"><img src="https://poser.pugx.org/zoujingli/pay-php-sdk/v/stable" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/zoujingli/pay-php-sdk"><img src="https://poser.pugx.org/zoujingli/pay-php-sdk/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/zoujingli/pay-php-sdk"><img src="https://poser.pugx.org/zoujingli/pay-php-sdk/v/unstable" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/zoujingli/pay-php-sdk"><img src="https://poser.pugx.org/zoujingli/pay-php-sdk/license" alt="License"></a>
</p>

欢迎 Star，欢迎 Fork！

## 特点
- 代码简洁，无需加载多余组件，可应用与任何平台或框架
- 隐藏开发者不需要关注的细节，完全内部实现
- 根据支付宝、微信最新 API 开发集成
- 高度抽象的类，免去各种拼json与xml的痛苦
- 符合 PSR 标准，你可以各种方便的与你的框架集成
- 文件结构清晰易理解，可以随心所欲添加本项目中没有的支付网关
- 方法使用更优雅，不必再去研究那些奇怪的的方法名或者类名是做啥用的

## 声明
- 代码与框架部分参考于互联网开源项目
- SDK全部源码基于MIT协议开源，完全免费

## 运行环境
- PHP 5.3+
- composer


## 配置参数
```php
$config = [
    // 微信支付参数
    'wechat' => [
        'app_id'     => '', // 应用ID
        'mch_id'     => '', // 微信支付商户号
        'mch_key'    => '', // 微信支付密钥
        'ssl_cer'    => '', // 微信证书 cert 文件
        'ssl_key'    => '', // 微信证书 key 文件
        'notify_url' => '', // 支付通知URL
    ],
    // 支付宝支付参数
    'alipay' => [
        'app_id'      => '', // 应用ID
        'public_key'  => '', // 支付宝公钥(1行填写)
        'private_key' => '', // 支付宝私钥(1行填写)
        'notify_url'  => '', // 支付通知URL
    ]
];
```

## 支付网关

由于各支付网关参差不齐，所以我们抽象了两个方法 `driver()`，`gateway()`。

两个方法的作用如下：

`driver()` ： 确定支付平台，如 `alipay`,`wechat`;  
`gateway()`： 确定支付网关。如 `app`,`pos`,`scan`,`transfer`,`wap`,`...`

具体实现可以查看源代码。


### 1、支付宝

- 电脑支付
- 手机网站支付
- APP 支付
- 刷卡支付
- 扫码支付

SDK 中对应的 driver 和 gateway 如下表所示：  

| driver | gateway |   描述       |
| :----: | :-----: | :-------:   |
| alipay | web     | 电脑支付     |
| alipay | wap     | 手机网站支付  |
| alipay | app     | APP 支付  |
| alipay | pos     | 刷卡支付  |
| alipay | scan    | 扫码支付  |
| alipay | transfer    | 帐户转账（可用于平台用户提现）  |

### 2、微信

- 公众号支付
- 小程序支付
- H5 支付
- 扫码支付
- 刷卡支付
- APP 支付

SDK 中对应的 driver 和 gateway 如下表所示：

| driver | gateway |   描述     |
| :----: | :-----: | :-------: |
| wechat | mp      | 公众号支付  |
| wechat | miniapp | 小程序支付  |
| wechat | wap     | H5 支付    |
| wechat | scan    | 扫码支付    |
| wechat | pos     | 刷卡支付    |
| wechat | app     | APP 支付  |
| wechat | transfer     | 企业付款  |

## 通用操作方法

所有网关均支持以下方法

- pay(array $options)  
说明：支付接口  
参数：数组类型，订单业务配置项，包含 订单号，订单金额等  
返回：mixed 详情请看「支付网关配置说明与返回值」一节。 

- refund(array|string $options, $refund_amount = null)  
说明：退款接口  
参数：`$options` 为字符串类型仅对`支付宝支付`有效，此时代表订单号，第二个参数为退款金额。  
返回：mixed  退款成功，返回 服务器返回的数组；否则返回 false；  

- close(array|string $options)  
说明：关闭订单接口  
参数：`$options` 为字符串类型时代表订单号，如果为数组，则为关闭订单业务配置项，配置项内容请参考各个支付网关官方文档。  
返回：mixed  关闭订单成功，返回 服务器返回的数组；否则返回 false；  

- find(string $out_trade_no)  
说明：查找订单接口  
参数：`$out_trade_no` 为订单号。  
返回：mixed  查找订单成功，返回 服务器返回的数组；否则返回 false；  

- verify($data, $sign = null)  
说明：验证服务器返回消息是否合法  
参数：`$data` 为服务器接收到的原始内容，`$sign` 为签名信息，当其为空时，系统将自动转化 `$data` 为数组，然后取 `$data['sign']`。  
返回：mixed  验证成功，返回 服务器返回的数组；否则返回 false；  

## 平台及网关实现
```php
// 实例支付对象
$pay = new \Pay\Pay($config);

try {
    $options = $pay->driver('alipay')->gateway('app')->apply($payOrder);
    var_dump($options);
} catch (Exception $e) {
    echo "创建订单失败，" . $e->getMessage();
}
```

## 通知处理

#### 支付宝通知
```php
// 实例支付对象
$pay = new \Pay\Pay($config);

if ($pay->driver('alipay')->gateway()->verify($_POST)) {
    file_put_contents('notify.txt', "收到来自支付宝的异步通知\r\n", FILE_APPEND);
    file_put_contents('notify.txt', "订单号：{$_POST['out_trade_no']}\r\n", FILE_APPEND);
    file_put_contents('notify.txt', "订单金额：{$_POST['total_amount']}\r\n\r\n", FILE_APPEND);
} else {
    file_put_contents('notify.txt', "收到异步通知\r\n", FILE_APPEND);
}
```

#### 微信通知
```php
$pay = new \Pay\Pay($config);
$verify = $pay->driver('wechat')->gateway('mp')->verify(file_get_contents('php://input'));

if ($verify) {
    file_put_contents('notify.txt', "收到来自微信的异步通知\r\n", FILE_APPEND);
    file_put_contents('notify.txt', "订单号：{$verify['out_trade_no']}\r\n", FILE_APPEND);
    file_put_contents('notify.txt', "订单金额：{$verify['total_fee']}\r\n\r\n", FILE_APPEND);
} else {
    file_put_contents('notify.txt', "收到异步通知\r\n", FILE_APPEND);
}

echo "success";
```

## SDK安装
```shell
// 方法一、 使用composer安装
composer require zoujingli/pay-php-sdk

// 方法二、 直接加载支付SDK
include 'init.php'
```
# TRUSTOCEAN SSL WHMCS Module
这是为WHMCS用户开发的用于对接TRUSTOCEAN SSL API V2版本的模块，主要完成证书订单提交和基本的证书管理操作。

先决条件：
- WHMCS7以上
- php7.x 配备openssl支持
- CURL SSL支持
- TRUSTOCEAN API账户权限

申请Partner账户: https://www.trustocean.com
寻找API信息: https://console.trustocean.com/partner/api-setting

## 获取开发者API权限
注册 https://console.trustocean.com
添加QQ： 2852368244 获取开发者权限或代理商权限

## 关于证书信息同步
目前采用主动Push进行证书签发通知，您需要在开通API账户之后联系TRUSTOCEAN技术人员为您设置Push URL地址

## Notice
此版本可能存在一定安全隐患，需要代理商的技术人员评估代码或修改代码后进行使用。此版本不作为正式版本发放，仅供技术人员测试和试用。

## License
我们特别喜欢MIT-License, 毋庸置疑，此软件基于MIT协议进行授权。
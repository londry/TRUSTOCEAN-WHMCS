# TrustOcean SSL Reseller Module for WHMCS
此模块是由 TrustOcean Limited 专为希望通过WHMCS系统销售 SSL 证书的经销商用户开发。
您可以使用此模块实现下列这些目的:
- 集成销售来自 Sectigo(formerly Comodo CA) 的全线 SSL 证书产品
- 集成销售来自 TrustOcean Limited 的全线 SSL 证书产品
- 集成为您的 WHMCS 客户免费提供 SSL 证书服务, TrustOcean Encryption365 SSL
- 整个销售流程都采用您自己的 WHMCS 系统品牌，从证书购买、申请、下载 到 重签、管理，整个流程都将在您的 WHMCS 系统内完成，您的用户无需跳转至 TrustOcean进行操作。更好的帮助您白标化开展在线业务。

## 项目提供的模块/功能
- SSL 证书提供商(WHMCS server/provider)模块, 使得您可以为您的用户提供数十款全球信任的 SSL 产品
- SSL 管理(WHMCS Addon)模块, 您可以通过在您自己的 WHMCS 后台查看、搜索您所有客户的 SSL 证书订单
- SSL 管理页面(客户侧)，由 TrustOceanSSLAdmin 模块提供，用户可在前台页面集中化管理和搜索账户下的 SSL 证书订单，跟踪验证流程

## 管理员功能
- 同步订单信息
- 操作订单，向TrustOcean发出退款申请(当用户提出退款时)
- 操作订单，向TrustOcean发出重新验证域名的请求
- 操作订单，向TrustOcean发出重新发送OV SSL电话验证邮件的请求
- 操作订单，在订单首次提交到TrustOcean之前，将订单设置为续费订单，额外获得30-90天有效期叠加
- 操作订单，向TrustOcean发出吊销证书的通知(当用户提出或出现滥用、私钥泄露的情况)
- 查看订单的CSR信息
- 查看订单的证书内容、证书链信息
- 查看订单内的域名验证状态和列表
- 操作订单，移除无法通过验证的域名
- 查看订单退款处理状态
- 统一管理和搜索已经创建的SSL证书订单
- 管理和设置经销商 API 账户信息
- 查看模块系统版本和更新信息
- 查看最新的经销商通知和重要新闻
- 关闭或开启安全签章展示选项
- 选择 TrustOcean API的接入点，支持 阿里云北京 和 阿里云伦敦 接入点

## WHMCS用户功能
- 配置申请 SSL 证书
- 验证域名
- 切换域名验证方式
- 移除暂时无法通过验证的域名
- 重新发送验证邮件
- 重新执行域名验证请求
- 查询证书签发状态
- 查看 SSL 证书订单的基本信息
- 更换证书CSR/私钥
- 更换证书内的域名
- 自助购买和增加多域名证书的域名额度
- 查看 PEM 证书内容、CSR内容和证书链内容
- 下载证书和证书链
- 转换获取 适用于 Apache IIS Nginx 格式的证书压缩包
- 查看和获取网站安全签章代码

## 集成的 webhook 功能
使用 webhook 可以更加实时的通知和更新您的证书签发状态，避免因 WHMCS 轮询而导致的签发延迟、用户体验度下降。
- 通过 webhook 自动更新证书签发状态
- 自动更新退款状态(finished,reject)
- 自动同步证书状态更新(revoked,cancelled,issued)

## 如何开始使用
- 通过 TrustOcean 经销商计划免费获取经销商账户: [提交经销商申请](https://www.trustocean.com/partner-program)
- 前往 经销商 [API账户页面](https://console.trustocean.com/partner/api-setting) 配置 PUSH_URL 和 PUSH公钥，以及获取 API Username 和 API Token
- 下载此模块的最新版本, 上传至您的WHMCS安装目录，解压并前往 WHMCS 后台激活模块，并配置对应的 API 账户信息进行保存激活
- 参考已发布的 [《TRUSTOCEAN SSL WHMCS 模块配置/对接/手册》](https://www.trustocean.com/repository/TRUSTOCEAN-WHMCS-MODULE-USERGUID.pdf) 配置您的SSL产品和价格，特别注意多域名产品的价格配置方式。

## 获取使用和账户申请帮助
- 客服QQ: 2852368244
- 发帖至 [TrustOcean 开发者社区](https://developer.trustocean.com)
- TrustOceanSSL开发者交流QQ群: 941598653

## BUG 反馈和功能建议
我们期望您能够将使用中遇到的问题和一些功能建议及时的告诉我们，以便于我们及时跟进和处理。欢迎您发帖至 [TrustOcean 开发者社区](https://developer.trustocean.com)

## 授权方式 MIT License

[百度百科解释](https://baike.baidu.com/item/MIT%E8%AE%B8%E5%8F%AF%E8%AF%81)
[英文解释 MIT License](https://choosealicense.com/licenses/mit/#)

Copyright (c) 2019 TrustOcean Limited

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
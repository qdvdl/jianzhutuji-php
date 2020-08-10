<?php
//短信配置
// $config['accessKeyId']= 'LTAIVLShUM3EpdCA';//短信id $accessKeyId
// $config['accessKeySecret']= 'Odw8qglnv3t2pvRdD9dcnitHFNobxv';//密钥 $accessKeySecret
// $config['sign']= '天舜互动';//签名 $sign
// $config['code']= 'SMS_76400145';//发送短信code $sign
// 突击小程序
$config['accessKeyId']= 'LTAI4GFn9GmRDqAqevFrzEqC';//短信id $accessKeyId
$config['accessKeySecret']= 'DZQhJS2iBb25QZo5U0tLPIArQolhXV';//密钥 $accessKeySecret
$config['sign']= '建筑突击';//签名 $sign

// LTAI4GFn9GmRDqAqevFrzEqC
// DZQhJS2iBb25QZo5U0tLPIArQolhXV


/*Base Site URL*/
$config['base_url'] = '/';

//配置支付接口
//微信支付
// $config['wechat_config']=[
//     "appid"=>"wxa51a3bb8f01e35dd",                        //微信appid
//     "appSecret"=>"c995e562a3aadf04fa12e69de1365b9e",      //微信密钥
//     "mch_id"=>"1483129572",                               //微信商户号
//     "key"=>"cd0Sctshd1KFzxJsHdfbBD12HxHCsdTt"             //商户密钥    
// ];
//小程序支付
$config['wechat_config']=[
    "appid"=>"wx1e50f10351052738",                        //小程序id
    "appSecret"=>"a09ace5592caaf9b18626a296ede6d92",      //小程序密钥
    "mch_id"=>"1482899222",                               //微信支付分配的商户号
    "key"=>"cd0Sctshd1KFzxJsHdfbBD12HxHCsdTt"             //商户密钥    
];

//支付宝
$config['alipay_config']=[
    "appid"=>"2019051364478375",
    "pid"=>"2088131529223950",
    "appPublic"=>"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA751V7eYeWwdl6HRErNicar/4Hp0Yb8TZMJ8bgzCePchqSIp7haEveTZvB3mxmzXJw3j0T8bjjL37ZY7QqNirnyw/4BTRbmSHmsSdpkhBpeQi8/IZVRbeFLKyl1T+2vmUAd5UiN+3NOIYpx9VfMmmGGowvxqjCyfpCzdBUqWJvxhoARvGG61T59byLReidyngp+s9Ud3e+ifHEn7QyqKVa7yYf1miJywT0TiNJXzTK5Pka8kvtApBy1eKA3lGrIHNUCe6n786WiM+G8rA3j3m6qEIgCEyN+mIQ3oeI+69r1DdR9kHQk4Xm/rFZBPdOvGwfRZot3bOFB9Wmk+c8yBg5QIDAQAB",
    "appPrivate"=>"MIIEpQIBAAKCAQEA751V7eYeWwdl6HRErNicar/4Hp0Yb8TZMJ8bgzCePchqSIp7haEveTZvB3mxmzXJw3j0T8bjjL37ZY7QqNirnyw/4BTRbmSHmsSdpkhBpeQi8/IZVRbeFLKyl1T+2vmUAd5UiN+3NOIYpx9VfMmmGGowvxqjCyfpCzdBUqWJvxhoARvGG61T59byLReidyngp+s9Ud3e+ifHEn7QyqKVa7yYf1miJywT0TiNJXzTK5Pka8kvtApBy1eKA3lGrIHNUCe6n786WiM+G8rA3j3m6qEIgCEyN+mIQ3oeI+69r1DdR9kHQk4Xm/rFZBPdOvGwfRZot3bOFB9Wmk+c8yBg5QIDAQABAoIBAQDuPWg3nmqmJWfsxNWXadOzy+UeQyIN1xH/ZuMLAGcFlOQf9hi0l7vE8BtaumOdp9BRG00GmQCcajSwGFxc1PlmzK9B1FEbSrcNado5f3RORdN+60LGuHLc74PkNW0kOawwY2d3o7/bw8WxPNVZuoD7m3GWQRzFNcxfC3RWgSLBLM2BWPNn4XVIuvSnt3JwnsJ7UPZ1j8utCQr1fidd3Tk/qOHSExLalDU6ToIsruyswitDtz1J9jD2lNRlQDppEsPiHfQ4UV2fj1SBQu1hT2Kgeq9YSLkzUNHV2larbpcjZw/OoZ3lparnJ1Ub35TGd2PNh/K+6OqVQ17JjpjNvfrNAoGBAPutlKYjajq8PZNAas8AlIOw3emAC/CHzqkm4PJHldXkWjB2hYATPfEeJKiX8t/UfVB/LF2PLDQ8Sy0j+zSHOOuRmAMFJ667nECgABJejMMZgD6NdqaAtw3F1ugLGv6Q0JEtYi+UjB150hoW9M9i+7EiwAslI3DapN3/Oh+LmQVXAoGBAPO6uNUHp8h+OyyOOFy0jfD9z8jkyndF8ltmIzsBhWst8UHO4FkKyq/3f2GGaWlROSSIjsCH3Zb0ij1JJDTY0LC54APkEZd08R7iICcPDmF2kNE6XH/n271HzS0LzGbKHn2fTwPPI08o40po/C4cLcLEq1/Rwp+1sHemwjUMMsojAoGABSRfxCE3G2obK/YF+KK/Zg1oC0xFci2kKEqWD/0sb4lR2mmhgqNiAZonD5bDLJWP1eQzSNoTAiI1Ov/gMwuMOyvnWjBxxh0pLRzXw4hRAe90clK7mN1KYCqpoGYRsB/PUxgFSImVb0i85TGQ4OfZ0p2uJMwmdDjTY4HCc0CL6NMCgYEArDH5sBhuhAobCImabHTr652Glep+2PHEHqr0rRWQC38z+kiM3JvxQ41ao65w/wVxl8wa/w9+kM5IdLAeMnAUUSehi14wx47JZAZirPXINTetbQUMoRlQeFQoM3Eogl6+WAabrpdD3QOIQeQWdwVVl0uXwGTUWj3lZUOd5hiuMX0CgYEAlfywrSUyjxEiOva2RkWE57ou+hWQ5n8+ZZkhuzlhn0v9ojXAtfxtBsYXYQA23XCJucFQfQjVZJugucINIS9TLJ7Dr3m7XGepwp9qLj6X/QCBhXAXMkEJVlIrgBDLrhf5Mwwtu/YI9syeyvknnuLdeKChNhd4gV/NHBanPDBTU5g=",
    "payurl"=>"https://openapi.alipay.com/gateway.do"
];
//基础配置
$config['username']="webvdl";
$config['userpass']="webvdl.@1818";
$config['serverurl']="vue.jianzhu.com:8888";//http://localhost:8080/


<?php

/**
 * Class lanaguageLoader
 * 加载用户语言文件, 可以在这里根据用户的语言设置配置对应的语言文件
 * 对于中文用户，这里默认采用中文语言，并未提供自动切换功能
 * 获取开发支持: https://developers.trustocean.com
 */
class lanaguageLoader{

    private  $lang;

    function __construct($vars)
    {
        $TRUSTOCEAN_LANG = [];
        $langDirBase =  __DIR__.'/../lang/';

        if($vars['Language'] != "" && file_exists(__DIR__.'/../lang/'.strtolower($vars['Language']).'.php')){
            $file = strtolower($vars['Language']).'.php';
        }else{
            $file = 'english.php';
        }
        require_once $langDirBase.$file;
        $this->lang = $TRUSTOCEAN_LANG;
    }

    public function loading(){
        return $this->lang;
    }
}
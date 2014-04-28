<?php


use TripleI\Bootloader\Utility\Initializer\AbstractInitializer;

class Initializer extends AbstractInitializer
{

    /**
     * ファイル名
     *
     * @var String
     **/
    protected $file_name;


    /**
     * 解凍ディレクトリへのパス
     *
     * @var String
     **/
    protected $thawing_dir;


    /**
     * 日付ディレクトリ名
     *
     * @var String
     **/
    protected $datetime;


    /**
     * パラメータ
     *
     * @var Array
     **/
    protected $params;


    /**
     * ルートディレクトリへのパス
     *
     * @var String
     **/
    protected $root_path;



    /**
     * 初期化処理
     *
     * @return void
     **/
    public function init ()
    {
        try {
            $this->_validateParameters();

        } catch (\Exception $e) {
            throw $e;
        }
    }
}

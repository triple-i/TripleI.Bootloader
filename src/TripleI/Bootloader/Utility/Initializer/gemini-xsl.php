<?php

use TripleI\Bootloader\Utility\Initializer\AbstractInitializer;

class Geminixsl extends AbstractInitializer
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

            // 解凍物を指定ディレクトリに移動させる
            $this->_moveToData();

        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }



    /**
     * パラメータをバリデートする
     *
     * @return void
     **/
    protected function _validateParameters ()
    {
        parent::_validateParameters();

        if (! isset($this->params[1])) {
            throw new \Exception('インストール先のパスを指定してください');
        }

        // 引数のパスを整形する
        // チルダを分解したり、末尾にスラッシュを付与したり
        $this->params[1] = preg_replace('/^~/', $_SERVER['HOME'], $this->params[1]);
        if (preg_match('/\/$/', $this->params[1])) {
            $this->params[1] = preg_replace('/\/$/', '', $this->params[1]);
        }

        // インストール先が存在しない場合
        if (! is_dir($this->params[1])) {
            throw new \Exception('インストール先のパスが存在しません');
        }
    }



    /**
     * 指定パスへコンテンツを移動させる
     *
     * @return void
     **/
    private function _moveToData ()
    {
        $path = $this->params[1];
        $command = sprintf('cp -r %s %s', $this->root_path.'/*', $path.'/');
        passthru($command);
    }
}

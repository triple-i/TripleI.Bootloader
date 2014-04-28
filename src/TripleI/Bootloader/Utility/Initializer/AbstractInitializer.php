<?php


namespace TripleI\Bootloader\Utility\Initializer;

abstract class AbstractInitializer
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
     * 解凍ディレクトリへのパスを取得する
     *
     * @return String
     **/
    public function getThawingDir ()
    {
        return $this->thawing_dir;
    }



    /**
     * 解凍ディレクトリへのパスをセットする
     *
     * @param String $thawing_dir  解凍ディレクトリへのパス
     * @return void
     **/
    public function setThawingDir ($thawing_dir)
    {
        $this->thawing_dir = $thawing_dir;
    }



    /**
     * 日付ディレクトリを取得する
     *
     * @return String
     **/
    public function getDateTime ()
    {
        return $this->datetime;
    }



    /**
     * 日付ディレクトリ名をセットする
     *
     * @param String $datetime  日付ディレクトリ名
     * @return void
     **/
    public function setDateTime ($datetime)
    {
        $this->datetime = $datetime;
    }



    /**
     * パラメータを取得する
     *
     * @return Array
     **/
    public function getParameters ()
    {
        return $this->params;
    }



    /**
     * パラメータをセットする
     *
     * @params Array $params  パラメータ配列
     * @return void
     **/
    public function setParameters ($params)
    {
        $this->file_name = $params[0];
        $this->params    = $params;
    }



    /**
     * パラメータをバリデートする
     *
     * @return void
     **/
    protected function _validateParameters ()
    {
        // 解凍ディレクトリへのパス
        if (is_null($this->thawing_dir)) {
            throw new \Exception('解凍ディレクトリへのパスを指定してください');
        }

        // 日付ディレクトリ名
        if (is_null($this->datetime)) {
            throw new \Exception('日付ディレクトリ名を指定してください');
        }

        // パラメータ
        if (is_null($this->params)) {
            throw new \Exception('パラメータを指定してください');
        }


        // ルートディレクトリのセット
        $this->root_path = $this->thawing_dir.DS.$this->file_name.DS.$this->datetime;
    }



    /**
     * シェルにテキストを表示する
     *
     * @param String $msg  メッセージ
     * @param String $tilte  タイトル
     * @return void
     **/
    public function log ($msg, $title = null)
    {
        if (is_null($title)) {
            $txt = '  '.pack('c',0x1B).'[1m'.$msg.pack('c',0x1B).'[0m'.PHP_EOL;
        } else {
            $txt = '  '.pack('c',0x1B).'[1;32m'.$title.':'.pack('c',0x1B).'[0m  ';
            $txt .= pack('c',0x1B).'[1m'.$msg.pack('c',0x1B).'[0m'.PHP_EOL;
        }

        echo $txt;
    }



    /**
     * シェルにエラーテキストを表示する
     *
     * @param String $msg  メッセージ
     * @return void
     **/
    public function errorLog ($msg)
    {
        echo '  '.pack('c',0x1B).'[1;31m'.$msg.pack('c',0x1B).'[0m'.PHP_EOL;
    }



    /**
     * 初期化処理
     *
     * @return void
     **/
    abstract public function init ();
}


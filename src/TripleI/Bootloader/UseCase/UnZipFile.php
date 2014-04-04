<?php


namespace TripleI\Bootloader\UseCase;

class UnZipFile
{

    /**
     * 解凍したいファイル名
     * 拡張子は含まない
     *
     * @var String
     **/
    private $file_name;



    /**
     * ユーザ名
     *
     * @var String
     **/
    private $user;



    /**
     * zipファイルへのパス
     *
     * @var String
     **/
    private $zip_path;



    /**
     * 解凍用ディレクトリ
     *
     * @var String
     **/
    private $thawing_dir;



    /**
     * 日付値
     *
     * @var String
     **/
    private $datetime;



    /**
     * 解凍ファイル名をセットする
     *
     * @param String $file_name  ファイル名
     * @return void
     **/
    public function setFileName ($file_name)
    {
        $this->file_name = $file_name;
    }



    /**
     * ユーザ名をセットする
     *
     * @param String $user  ユーザ名
     * @return void
     **/
    public function setUser ($user)
    {
        $this->user = $user;
    }



    /**
     * 解凍処理
     *
     * @return boolean
     **/
    public function execute ()
    {
        try {
            // パラメータのバリデート
            $this->_validateParameters();

            // 解凍ディレクトリの生成
            $this->_makeThawingDirectory();
            
        
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }



    /**
     * パラメータのバリデート
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        if (is_null($this->file_name)) {
            throw new \Exception('ファイル名が指定されていません');
        }

        if (is_null($this->user)) {
            throw new \Exception('ユーザ名が指定されていません');
        }


        $this->zip_path = '/tmp/'.$this->file_name.'.zip';
        if (! file_exists($this->zip_path)) {
            throw new \Exception('指定したファイルが存在しません');
        }
    }



    /**
     * 解凍用ディレクトリなどを生成する
     *
     * @return void
     **/
    private function _makeThawingDirectory ()
    {
        // ユーザディレクトリの有無を確認
        $user_dir = '/home/'.$this->user;
        if (! is_dir($user_dir)) {
            var_dump(getcwd());
            exit();
            exec('cd && pwd', $re);
            var_dump(dirname(__FILE__));
            exit();
            //exec('mkdir '.$user_dir);
        }

        $this->datetime = date('YmdHis');
    }
}

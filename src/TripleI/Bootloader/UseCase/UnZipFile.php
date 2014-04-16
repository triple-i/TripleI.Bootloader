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
     * 解凍用ディレクトリのパスを取得する
     *
     * @return String
     **/
    public function getThawingDir ()
    {
        return $this->thawing_dir;
    }



    /**
     * datetimeディレクトリ名を取得する
     *
     * @return String
     **/
    public function getDateTime ()
    {
        return $this->datetime;
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

            // アプリケーションファイルの解凍
            $this->_thawingApplication();
        
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
        // 解凍用ディレクトリの有無を確認
        $user_dir = (IS_EC2) ? '/home/ec2-user': '/tmp';
        $thaw_dir = $user_dir.DS.'thawing';
        if (! is_dir($thaw_dir)) {
            mkdir ($thaw_dir);
        }

        $this->thawing_dir = $thaw_dir;
        $this->datetime = date('YmdHis');

        // アプリケーションディレクトリの生成
        if (! is_dir($thaw_dir.DS.$this->file_name)) {
            mkdir($thaw_dir.DS.$this->file_name);
        }

        // 日付ディレクトリの生成
        mkdir ($thaw_dir.DS.$this->file_name.DS.$this->datetime);
    }



    /**
     * 解凍処理
     *
     * @return void
     **/
    private function _thawingApplication ()
    {
        // datetimeディレクトリ内に解凍する
        $date_dir = $this->thawing_dir.DS.$this->file_name.DS.$this->datetime;
        $command = sprintf('cd %s && unzip %s ', $date_dir, $this->zip_path);
        exec($command);
    }
}

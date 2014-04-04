<?php


namespace TripleI\Bootloader\UseCase;

class DownloadZip
{

    /**
     * 引数パラメータ
     *
     * @var Array
     **/
    private $params;



    /**
     * @var TripleI\Bootloader\Utility\Aws\S3
     **/
    private $S3;



    /**
     * アプリケーション名
     *
     * @var String
     **/
    private $app_name;



    /**
     * zipファイル名
     *
     * @var String
     **/
    private $zip_name;



    /**
     * パラメータをセットする
     *
     * @param Array $params  引数
     * @return void
     **/
    public function setParameters (Array $params)
    {
        array_shift($params);
        $this->params = $params;
    }



    /**
     * S3クラスをセットする
     *
     * @param TrileI\Bootloader\Utility\Aws\S3
     * @return void
     **/
    public function setS3 ($S3)
    {
        $this->S3 = $S3;
    }



    /**
     * 処理の実行
     *
     * @return boolean
     **/
    public function execute ()
    {
        try {
            $this->_validateParameters();
            $response = $this->S3->download($this->app_name.'.zip');

            // zipファイルを保存する
            $zip_path = '/tmp/'.$this->zip_name;
            file_put_contents($zip_path, $response['Body']);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * 引数をバリデートする
     *
     * @return void
     **/
    private function _validateParameters ()
    {
        // パラメータがない場合
        if (! isset($this->params[0])) {
            throw new \Exception('第一引数にアプリ名を指定してください');
        }


        $this->app_name = array_shift($this->params);
        $this->zip_name = $this->app_name.'.zip';

        if (! $this->S3->doesObjectExist($this->app_name)) {
            throw new \Exception('指定したアプリケーションは存在しません');
        }
    }
}

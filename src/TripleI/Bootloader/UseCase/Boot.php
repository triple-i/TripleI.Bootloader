<?php


namespace TripleI\Bootloader\UseCase;

use TripleI\Bootloader\UseCase\DownloadZip,
    TripleI\Bootloader\UseCase\UnZipFile,
    TripleI\Bootloader\UseCase\CallToInitializer,
    TripleI\Bootloader\Utility\Aws\S3;

class Boot
{
    
    /**
     * パラメータ
     *
     * @var Array
     **/
    private $params;


    /**
     * @var TripleI\Bootloader\UseCase\DownloadZip
     **/
    private $dz;


    /**
     * @var TrippleI\Bootloader\UseCase\UnZipFile
     **/
    private $uzf;


    /**
     * @var TripleI\Bootloader\UseCase\CallToInitializer
     **/
    private $cti;


    /**
     * @var TripleI\Bootloader\Utility\Aws\S3
     **/
    private $S3;



    /**
     * パラメータをセットする
     *
     * @param Array $params  パラメータ配列
     * @return void
     **/
    public function setParameters ($params)
    {
        $this->params = $params;
    }


    /**
     * DownloadZipクラスをセットする
     *
     * @param DownloadZip $dz  S3からzipファイルをダウンロードするユースケースクラス
     * @return void
     **/
    public function setDownloadZip (DownloadZip $dz)
    {
        $this->dz = $dz;
    }


    /**
     * UnZipFileクラスをセットする
     *
     * @param UnZipFile $uzf  zipファイルを解凍するユースケースクラス
     * @return void
     **/
    public function setUnZipFile (UnZipFile $uzf)
    {
        $this->uzf = $uzf;
    }


    /**
     * CallToInitializerクラスをセットする
     *
     * @param CallToInitializer $cti  初期化クラスを呼び出すクラス
     * @return void
     **/
    public function setCallToInitializer (CallToInitializer $cti)
    {
        $this->cti = $cti;
    }



    /**
     * S3クラスをセットする
     *
     * @param S3 $S3  S3クラス
     * @return void
     **/
    public function setS3 (S3 $S3)
    {
        $this->S3 = $S3;
    }



    /**
     * TripleI\Bootloaderを起動する
     *
     * @return boolean
     **/
    public function execute ()
    {
        try {
            // パラメータのバリデート
            $this->_validateParameters();

            // DownloadZipクラスの実行
            $this->_initDownloadZip();
            $this->_executeDownloadZip();

            // UnZipFileクラスの実行
            $this->_initUnZipFile();
            $this->_executeUnZipFile();

            // CallToInitializerクラスの実行
            $this->_initCallToInitializer();
            $this->_executeCallToInitializer();
        
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
    private function _validateParameters ()
    {
        // パラメータ
        if (is_null($this->params)) {
            throw new \Exception('パラメータを指定してください');
        }

        // DownloadZip
        if (is_null($this->dz)) {
            throw new \Exception('DownloadZipクラスを指定してください');
        }

        // UnZipFile
        if (is_null($this->uzf)) {
            throw new \Exception('UnZipFileクラスを指定してください');
        }

        // CallToInitializer
        if (is_null($this->cti)) {
            throw new \Exception('CallToInitializerクラスを指定してください');
        }

        // S3
        if (is_null($this->S3)) {
            throw new \Exception('S3クラスを指定してください');
        }
    }



    /**
     * DownloadZipクラスの初期化
     *
     * @return void
     **/
    private function _initDownloadZip ()
    {
        $this->dz->setParameters($this->params);
        $this->dz->setS3($this->S3);
    }



    /**
     * DownloadZipクラスの実行
     *
     * @return boolean
     **/
    public function _executeDownloadZip ()
    {
        return $this->dz->execute();
    }



    /**
     * UnZipFileクラスの初期化
     *
     * @return void
     **/
    private function _initUnZipFile ()
    {
        $this->uzf->setFileName($this->params[0]);
    }



    /**
     * UnZipFileクラスの実行
     *
     * @return boolean
     **/
    private function _executeUnZipFile ()
    {
        return $this->uzf->execute();
    }



    /**
     * CallToInitializerクラスの初期化
     *
     * @return void
     **/
    private function _initCallToInitializer ()
    {
        $this->cti->setThawingDir($this->uzf->getThawingDir());
        $this->cti->setDateTime($this->uzf->getDateTime());
        $this->cti->setParameters($this->params);
    }



    /**
     * CallToInitializerクラスの実行
     *
     * @return boolean
     **/
    private function _executeCallToInitializer ()
    {
        return $this->cti->execute();
    }
}

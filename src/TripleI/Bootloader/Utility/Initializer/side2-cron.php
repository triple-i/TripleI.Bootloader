<?php


use TripleI\Bootloader\Utility\Initializer\AbstractInitializer;

use TripleI\Bootloader\UseCase\Boot,
    TripleI\Bootloader\UseCase\DownloadZip,
    TripleI\Bootloader\UseCase\UnZipFile,
    TripleI\Bootloader\UseCase\CallToInitializer;

use TripleI\Bootloader\Utility\Aws\S3;

class Side2cron extends AbstractInitializer
{

    // 定数
    const QUEUE    = 'GEMINI_QUEUE';
    const PUBLISH  = 'GEMINI_PUBLISH';
    const TEST     = 'GEMINI_TEST';
    const DOWNLOAD = 'DOWNLOAD';



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

            // gemini-xslをダウンロードしてシンボリックリンクを貼る
            $this->_downloadGeminiXsl();

            // キュー監視サービスを開始する
            $this->_executeSide2Cron();
        
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * パラメータをバリデートする
     *
     * @return void
     **/
    protected function _validateParameters ()
    {
        parent::_validateParameters();


        // キュー名称を引数に保持していない場合はDOWNLOADにする
        if (! isset($this->params[1])) {
            $this->params[1] = $this::DOWNLOAD;
        } 
        $queue = $this->params[1];

        // キュー名称が正しくない場合はDOWNLOADにする
        if ($queue != $this::PUBLISH && $queue !== $this::QUEUE && $queue !== $this::TEST) {
            $this->params[1] = $this::DOWNLOAD;
        }
    }



    /**
     * gemini-xslをダウンロードしてシンボリックリンクを貼る
     *
     * @return void
     **/
    protected function _downloadGeminiXsl ()
    {
        $xslt_dir = $this->root_path.DS.'library'.DS.'xslt'.DS.'gemini-xsl';
        if (! is_dir($xslt_dir)) mkdir($xslt_dir, 0777, true);

        $params = array(
            'gemini-xsl',
            $this->root_path.DS.'library'.DS.'xslt'.DS.'gemini-xsl'
        );


        $boot = new Boot();
        $boot->setParameters($params);
        $boot->setS3(new S3);
        $boot->setDownloadZip(new DownloadZip);
        $boot->setUnZipFile(new UnZipFile);
        $boot->setCallToInitializer(new CallToInitializer);
        $boot->execute();
    }



    /**
     * キュー監視サービスを開始する
     *
     * @return void
     **/
    protected function _executeSide2Cron ()
    {
        chdir($this->root_path.DS);

        // キューからオプションを構築
        switch ($this->params[1]) {
            case $this::PUBLISH:
                $option = '-p';
                break;

            case $this::TEST:
                $option = '-t';
                break;

            case $this::QUEUE:
                $option = '';
                break;

            default:
                return false;
                break;
        }

        // コマンドの構築
        $command = sprintf(
            'nohup %s %s > %s 2>&1 &',
            $this->root_path.DS.'scale-boot.sh',
            $option,
            $this->thawing_dir.DS.$this->params[0].DS.'exceed-log'
        );
        passthru($command);
    }
}

<?php


namespace TripleI\Bootloader\UseCase;

class CallToInitializer
{

    /**
     * 初期化クラスのファイル名
     *
     * @var String
     **/
    private $file_name;



    /**
     * 初期化クラス名
     *
     * @var String
     **/
    private $class_name;



    /**
     * 初期化クラスの置いてあるディレクトリ
     *
     * @var String
     **/
    private $class_path = 'src/TripleI/Bootloader/Utility/Initializer';



    /**
     * パラメータ
     *
     * @var Array
     **/
    private $params;



    /**
     * 解凍ディレクトリへのパス
     *
     * @var String
     **/
    private $thawing_dir;



    /**
     * 日付ディレクトリ名
     *
     * @var String
     **/
    private $datetime;



    /**
     * 初期化クラス
     *
     * @var TripleI\Bootloader\Utility\Initializer\AbstractInitializer
     **/
    private $init_class;



    /**
     * パラメータをセットする
     * その際、パラメータから初期化クラス名などを抽出する
     *
     * @param Array $params  パラメータ配列
     * @return void
     **/
    public function setParameters ($params)
    {
        $this->params = $params;
        $file_name    = array_shift($params);

        $this->file_name  = strtolower($file_name);
        $this->class_name = ucfirst($this->file_name);
    }



    /**
     * 解凍ディレクトリをセットする
     *
     * @param String $thawing_dir  解凍ディレクトリへのパス
     * @return void
     **/
    public function setThawingDir ($thawing_dir)
    {
        $this->thawing_dir = $thawing_dir;
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
     * 初期化クラスの呼び出し
     *
     * @return boolean
     **/
    public function execute ()
    {
        try {
            // パラメータのバリデート
            $this->_validateParameters();

            // 初期化クラスの処理を実行する
            $this->init_class->setThawingDir($this->thawing_dir);
            $this->init_class->setDateTime($this->datetime);
            $this->init_class->setParameters($this->params);
            $this->init_class->init();
        
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
        // 解凍ディレクトリのパスがセットされているかどうか
        if (is_null($this->thawing_dir)) {
            throw new \Exception('解凍ディレクトリを指定してください');
        }

        // 日付ディレクトリ名がセットされているかどうか
        if (is_null($this->datetime)) {
            throw new \Exception('日付ディレクトリ名を指定してください');
        }

        // パラメータのバリデート
        if (is_null($this->file_name)) {
            throw new \Exception('初期化クラスを指定してください');
        }

        // 初期化クラスの有無
        $initializer = ROOT_PATH.DS.$this->class_path.DS.$this->file_name.'.php';
        if (! file_exists($initializer)) {
            throw new \Exception('初期化クラスファイルが見つかりません');
        }


        // 初期化クラスの読み込み
        require_once $initializer;
        $class_name = ucfirst(preg_replace('/[^0-9a-zA-Z]/', '', $this->class_name));
        $this->init_class = new $class_name;

        if (! $this->init_class instanceof \TripleI\Bootloader\Utility\Initializer\AbstractInitializer) {
            throw new \Exception('正しい初期化クラスではありません');
        }
    }
}

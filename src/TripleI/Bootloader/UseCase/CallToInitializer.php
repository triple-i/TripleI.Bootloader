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
        // パラメータのバリデート
        if (is_null($this->file_name)) {
            throw new \Exception('初期化クラスを指定してください');
        }

        // 初期化クラスの有無
        $initializer = ROOT_PATH.DS.$this->class_path.DS.$this->class_name.'.php';
        if (! file_exists($initializer)) {
            throw new \Exception('初期化クラスファイルが見つかりません');
        }


        // 初期化クラスの読み込み
        require $initializer;
        $this->init_class = new \Initializer();
        //var_dump(get_parent_class($this->init_class));
        //exit();

        if (get_parent_class($this->init_class) != 'TripleI\Bootloader\Utility\Initializer\AbstractInitializer') {
            throw new \Exception('正しい初期化クラスではありません');
        }
    }
}

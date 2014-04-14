<?php


namespace TripleI\Bootloader\UseCase;

class CallToInitClass
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
     * ファイル名をセットする
     *
     * @param String $file_name  初期化クラスファイル名
     * @return void
     **/
    public function setFileName ($file_name)
    {
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


        //var_dump(ROOT_PATH.DS.$this->class_path);
        //exit();
        // 初期化クラスの有無をバリデート
        //if (ROOT_PATH.DS.$this->class_path) {
        
        //}
    }
}

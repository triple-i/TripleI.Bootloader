<?php


use TripleI\Bootloader\UseCase\CallToInitializer;

class CallToInitializerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TripleI\Bootloader\UseCase\CallToInitClass
     **/
    private $usecase;



    /**
     * 初期化クラスディレクトリへのパス
     *
     * @var String
     **/
    private $class_path = 'src/TripleI/Bootloader/Utility/Initializer';



    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->usecase = new CallToInitializer();
    }



    /**
     * テスト後始末
     *
     * @return void
     **/
    public function tearDown ()
    {
        // テスト用初期化クラスの削除
        $init_class = ROOT_PATH.DS.$this->class_path.DS.'Test.php';
        if (file_exists($init_class)) {
            unlink($init_class);
        }
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    解凍ディレクトリを指定してください
     * @group call
     * @group call-valid-thaw-dir
     */
    public function 解凍ディレクトリが指定されていない場合 ()
    {
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    日付ディレクトリ名を指定してください
     * @group call
     * @group call-valid-datetime
     */
    public function 日付ディレクトリ名が指定されていない場合 ()
    {
        $this->usecase->setThawingDir('/tmp');
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    初期化クラスを指定してください
     * @group call
     * @group call-valid-parameters
     */
    public function 引数が指定されていない場合 ()
    {
        $this->usecase->setThawingDir('/tmp');
        $this->usecase->setDateTime(date('YmdHis'));
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    初期化クラスファイルが見つかりません
     * @group call
     * @group call-not-exists-initializer
     */
    public function 初期化クラスが見つからない場合 ()
    {
        $params = array('hoge');

        $this->usecase->setThawingDir('/tmp');
        $this->usecase->setDateTime(date('YmdHis'));
        $this->usecase->setParameters($params);
        $this->usecase->execute();
    }



    /**
     * @test
     * @group call
     * @group call-execute
     */
    public function 正常な処理 ()
    {
        $params = array('test');

        // 初期化クラスの生成
        $text = '<?php'.PHP_EOL.
            'use TripleI\Bootloader\Utility\Initializer\AbstractInitializer;'.PHP_EOL.
            'class Initializer extends AbstractInitializer {'.PHP_EOL.
            'public function init () {}'.PHP_EOL.
            '}';
        file_put_contents(ROOT_PATH.DS.$this->class_path.DS.'Test.php', $text);

        $this->usecase->setThawingDir('/tmp');
        $this->usecase->setDateTime(date('YmdHis'));
        $this->usecase->setParameters($params);
        $result = $this->usecase->execute();

        $this->assertTrue($result);
    }
}

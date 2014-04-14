<?php


use TripleI\Bootloader\UseCase\UnZipFile;

class UnZipFileTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var TripleI\Bootloader\UseCase\UnZipFile
     **/
    private $usecase;



    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->usecase = new UnZipFile();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    ファイル名が指定されていません
     * @group unzip
     * @group unzip-not-enough-parameter
     */
    public function ファイル名の指定がない場合 ()
    {
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    指定したファイルが存在しません
     * @group unzip
     * @group unzip-not-exist-zipfile
     */
    public function 指定したzipファイルが存在しない場合 ()
    {
        $file_name = 'hoge';
        $this->usecase->setFileName($file_name);
        $this->usecase->execute();
    }



    /**
     * @test
     * @group unzip
     * @group unzip-execute
     */
    public function 正常な処理 ()
    {
        // zipファイルを生成しておく
        mkdir('/tmp/hoge');
        touch('/tmp/hoge/text.txt');
        exec('cd /tmp && zip -r /tmp/hoge.zip ./hoge');
        exec('rm -rf /tmp/hoge');

        $this->usecase->setFileName('hoge');
        $result = $this->usecase->execute();
        $this->assertTrue($result);

        $thaw_dir = $this->usecase->getThawingDir();
        $this->assertTrue(is_dir($thaw_dir));
        if (is_dir($thaw_dir)) {
            exec('rm -rf '.$thaw_dir);
        }
        exec('rm /tmp/hoge.zip');
    }
}

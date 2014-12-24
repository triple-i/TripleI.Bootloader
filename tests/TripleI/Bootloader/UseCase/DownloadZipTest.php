<?php


use TripleI\Bootloader\UseCase\DownloadZip;

class DownloadZipTest extends PHPUnit_Framework_TestCase
{


    /**
     * @var TripleI\Bootloader\UseCase\DownloadZip
     **/
    private $usecase;



    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->usecase = new DownloadZip();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    第一引数にアプリ名を指定してください
     * @group zip
     * @group zip-not-enough-parameter
     **/
    public function 引数が足りない場合 ()
    {
        $this->usecase->setParameters(array());
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    指定したアプリケーションは存在しません
     * @group zip-not-exist-zipfile
     * @group zip
     */
    public function 指定したzipファイルがS3にない場合 ()
    {
        $S3 = $this->getMock('TripleI\Bootloader\Utility\Aws\S3');
        $S3->expects($this->once())
            ->method('doesObjectExist')
            ->will($this->returnValue(false));

        $this->usecase->setParameters(array('hoge'));
        $this->usecase->setS3($S3);
        $this->usecase->execute();
    }



    /**
     * @test
     * @group zip
     * @group zip-download
     */
    public function S3から指定zipをダウンロードする ()
    {
        $S3 = $this->getMock('TripleI\Bootloader\Utility\Aws\S3');
        $S3->expects($this->once())
            ->method('doesObjectExist')
            ->will($this->returnValue(true));

        $S3->expects($this->once())
            ->method('download')
            ->will($this->returnValue(true));

        $this->usecase->setParameters(array('hoge'));
        $this->usecase->setS3($S3);
        $ret = $this->usecase->execute();

        $this->assertTrue($ret);
    }
}

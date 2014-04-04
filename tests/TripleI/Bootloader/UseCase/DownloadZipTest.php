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
        $this->usecase->setParameters(array('boot'));
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    指定したアプリケーションは存在しません
     * @group zip-not-exists-zipfile
     * @group zip
     */
    public function 指定したzipファイルがS3にない場合 ()
    {
        $S3 = $this->getMock('TripleI\Bootloader\Utility\Aws\S3');
        $S3->expects($this->once())
            ->method('doesObjectExist')
            ->will($this->returnValue(false));

        $this->usecase->setParameters(array('boot', 'hoge'));
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

        $response = $this->getMock('Guzzle\Service\Resource\Model');
        $response->set('Body', 'test');

        $S3->expects($this->once())
            ->method('download')
            ->will($this->returnValue($response));

        $this->usecase->setParameters(array('boot', 'hoge'));
        $this->usecase->setS3($S3);
        $this->usecase->execute();


        $zip_path = '/tmp/hoge.zip';
        $this->assertTrue(file_exists($zip_path));
        if (file_exists($zip_path)) {
            unlink($zip_path);
        }
    }
}

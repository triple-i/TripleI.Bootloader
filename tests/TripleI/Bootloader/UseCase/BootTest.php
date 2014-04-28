<?php

use TripleI\Bootloader\UseCase\Boot;

use TripleI\Bootloader\UseCase\DownloadZip,
    TripleI\Bootloader\UseCase\UnZipFile,
    TripleI\Bootloader\UseCase\CallToInitialize,
    TripleI\Bootloader\Utility\Aws\S3;

class BootTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TripleI\Bootloader\UseCase\Boot
     **/
    private $usecase;


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
     * パラメータ配列
     *
     * @var Array
     **/
    private $params;



    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->usecase = new Boot();
        $this->params  = array('hoge');

        $this->S3 = $this->getMock('TripleI\Bootloader\Utility\Aws\S3');
        $this->dz = $this->getMock('TripleI\Bootloader\UseCase\DownloadZip');
        $this->uzf = $this->getMock('TripleI\Bootloader\UseCase\UnZipFile');
        $this->cti = $this->getMock('TripleI\Bootloader\UseCase\CallToInitializer');
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    パラメータを指定してください  
     * @group boot
     * @group boot-valid-params
     */
    public function パラメータが指定されていない場合 ()
    {
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    DownloadZipクラスを指定してください
     * @group boot
     * @group boot-valid-downloadzip
     **/
    public function DownloadZipクラスがセットされていない場合 ()
    {
        $this->usecase->setParameters($this->params);
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    UnZipFileクラスを指定してください
     * @group boot
     * @group boot-valid-unzipfile
     */
    public function UnZipFileクラスが指定されていない場合 ()
    {
        $this->usecase->setParameters($this->params);
        $this->usecase->setDownloadZip($this->dz);
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    CallToInitializerクラスを指定してください
     * @group boot
     * @group boot-valid-calltoinitializer
     */
    public function CallToInitializerクラスが指定されていない場合 ()
    {
        $this->usecase->setParameters($this->params);
        $this->usecase->setDownloadZip($this->dz);
        $this->usecase->setUnZipFile($this->uzf);
        $this->usecase->execute();
    }



    /**
     * @test
     * @expectedException           Exception
     * @expectedExceptionMessage    S3クラスを指定してください
     * @group boot
     * @group boot-valid-s3
     */
    public function S3クラスが指定されていない場合 ()
    {
        $this->usecase->setParameters($this->params);
        $this->usecase->setDownloadZip($this->dz);
        $this->usecase->setUnZipFile($this->uzf);
        $this->usecase->setCallToInitializer($this->cti);
        $this->usecase->execute();
    }



    /**
     * @test
     * @group boot
     * @group boot-execute
     */
    public function 正常な処理 ()
    {
        // モックの初期化
        $this->_initMockClass();

        $this->usecase->setParameters($this->params);
        $this->usecase->setDownloadZip($this->dz);
        $this->usecase->setUnZipFile($this->uzf);
        $this->usecase->setCallToInitializer($this->cti);
        $this->usecase->setS3($this->S3);
        $result = $this->usecase->execute();

        $this->assertTrue($result);
    }



    /**
     * モックの初期化
     *
     * @return void
     **/
    private function _initMockClass ()
    {
        // DownloadZip
        $this->dz->expects($this->any())->method('setParameters');
        $this->dz->expects($this->any())->method('setS3')
            ->with($this->S3);
        $this->dz->expects($this->any())->method('execute')
            ->will($this->returnValue(true));

        // UnZipFile
        $this->uzf->expects($this->any())->method('setFileName');
        $this->uzf->expects($this->any())->method('execute')
            ->will($this->returnValue(true));
        $this->uzf->expects($this->any())->method('getThawingDir')
            ->will($this->returnValue('/tmp/thawing'));
        $this->uzf->expects($this->any())->method('getDateTime')
            ->will($this->returnValue(date('YmdHis')));

        // CallToInitializer
        $this->cti->expects($this->any())->method('setThawingDir');
        $this->cti->expects($this->any())->method('setDateTime');
        $this->cti->expects($this->any())->method('setParameters');
        $this->cti->expects($this->any())->method('execute')
            ->will($this->returnValue(true));
    }
}

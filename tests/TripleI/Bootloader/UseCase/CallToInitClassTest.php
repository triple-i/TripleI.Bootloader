<?php


use TripleI\Bootloader\UseCase\CallToInitClass;

class CallToInitClassTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var TripleI\Bootloader\UseCase\CallToInitClass
     **/
    private $usecase;



    /**
     * セットアップ
     *
     * @return void
     **/
    public function setUp ()
    {
        $this->usecase = new CallToInitClass();
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
        $this->usecase->execute();
    }
}

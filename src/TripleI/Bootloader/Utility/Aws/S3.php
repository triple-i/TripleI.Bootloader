<?php


namespace TripleI\Bootloader\Utility\Aws;

use Aws\S3\S3Client;
use Aws\Common\Enum\Region;
use Guzzle\Http\EntityBody;

class S3
{

    /**
     * @var S3Client
     **/
    private $client;


    
    /**
     * S3バケット名
     *
     * @var String
     **/
    public $bucket;



    /**
     * JSONを保存するパス
     *
     * @var String
     **/
    public $json_path;



    /**
     * aws.ini へのパス
     *
     * @var String
     **/
    private $aws_ini_path = 'data/config/aws.ini';



    /**
     * コンストラクタ
     *
     * @return void
     **/
    public function __construct ()
    {
        // AWSクライアントの設定
        $ini = parse_ini_file(ROOT_PATH.'/'.$this->aws_ini_path);

        // メンバ変数の設定
        $this->bucket = $ini['bucket'];

        $this->client = S3Client::factory(
            array(
                'key' => $ini['key'],
                'secret' => $ini['secret_key'],
                'region' => Region::AP_NORTHEAST_1
            )
        );
    }



    /**
     * 指定ファイルが存在するかどうか
     *
     * @param String $path  ファイルへのパス
     * @return boolean
     **/
    public function doesObjectExist ($path)
    {
        return $this->client->doesObjectExist($this->bucket, $path);
    }


    /**
     * 指定ファイルをS3からダウンロードする
     *
     * @param String $objKey
     * @throws \Exception
     * @return String
     */
    public function download ($objKey)
    {
        try {
            $response = $this->client->getObject(array(
                'Bucket' => $this->bucket,
                'Key' => $objKey,
                'SaveAs' => '/tmp/' . $objKey
            ));

        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }
}


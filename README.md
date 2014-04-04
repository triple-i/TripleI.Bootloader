TripleI.Bootloader
=======
S3 の zip ファイルからアプリケーションを起動するツールです。
---------------------------------------------
指定した zip ファイルを AmazonS3 からダウンロードして、解凍・起動させるコマンドラインツールです。  
初期化の機能も備えており柔軟にアプリケーションを起動させることが出来ます。


動作環境
------------
 * PHP 5.3+


セットアップ
---------------

### ライブラリのインストール
```
 $ composer install
```

### 設定ファイルの生成
aws.ini.orig ファイルから aws.ini ファイルを生成して、AWS のキー情報を入力します。

```
 $ cp data/config/aws.ini.orig data/config/aws.ini
 $ vi data/config/aws.ini
 ```



使い方
---------

### アプリケーションの起動
S3 にある foo.zip を落としてアプリケーションを起動したい場合。

```
 $ bin/boot foo
```


### 初期化処理の設定
初期化処理は src/TripleI/Bootloader/Utility/Initializer ディレクトリの中にクラスを生成して行います。  
hoge.zip の場合では、initializer ディレクトリ内の Hoge クラスが初期化を担います。


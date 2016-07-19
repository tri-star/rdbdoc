rdbdoc
===================================

![release](http://img.shields.io/github/release/tri-star/rdbdoc.svg?style=flat-square)
![license](http://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

# Overview
rdbdocはRDBMSのスキーマのメタデータと、各テーブル/カラムの説明を記述したファイルから
テーブル定義書を生成するコマンドラインツールです。

メンテナンスが煩雑になるテーブルのメタ情報は実際のスキーマから取得し、各テーブル/カラムの説明部分のみを
メンテナンスの対象とすることで、持続可能な

ドキュメントの生成形式は現時点では以下の2形式のみですが、プラグインによる拡張や形式のカスタマイズを可能にする予定です。

* [HTML(シングルページ)](https://github.com/tri-star/rdbdoc/blob/master/examples/01-single-page.html)
* [Excelブック(単一ファイル)](https://github.com/tri-star/rdbdoc/blob/master/examples/02-single-excel-book.xlsx)


## Usage
最初に、既存のスキーマからテーブル定義のテンプレート(schema.yaml)の生成を行います。
```
# root@localhost:3306 でMySQLに接続出来ると仮定します。
php rdbdoc.php generate:template --user=root db_name schema.yaml
```

生成されるテンプレートの例：db_nameというDBにtable1というテーブルがあった場合

(このファイルのname, descキーにテーブルやカラムの論理名、説明(改行可)を記入していきます)
```
database:
    name: db_name
    desc: ''
    tables:
        table1:
            name: ''
            desc: ''
            columns:
                id: { name: '', desc: '' }
                name: { name: '', desc: '' }
                type: { name: '', desc: '' }
                created: { name: '', desc: '' }
                updated: { name: '', desc: '' }
```

説明の記入例：

(コマンドでは、各カラムの説明はインラインで生成されますが、
YAMLの複数行の構文などで複数行の説明文も記述可能です)
```
database:
    name: db_name
    desc: 'テストDB'
    tables:
        table1:
            name: 'テストテーブル'
            desc: 'テーブルの説明文'
            columns:
                id: { name: 'ID', desc: '' }
                name: { name: '名前', desc: '' }
                type: 
                    name: '種別'
                    desc: |
                        1: タイプ1
                        2: タイプ2
                created: { name: '登録日時', desc: '' }
                updated: { name: '更新日時', desc: '' }
```


以下のコマンドを実行すると、./docsディレクトリ配下にExcelのBook形式でドキュメントが生成されます。

```
# root@127.0.0.1:3306 でMySQLに接続出来ると仮定します。
php rdbdoc.php generate:document --user=root --input=schema.yaml ./docs
```

ドキュメントの例：
* [HTML](https://github.com/tri-star/rdbdoc/blob/master/examples/01-single-page.html)
* [Excelブック](https://github.com/tri-star/rdbdoc/blob/master/examples/02-single-excel-book.xlsx)


### その他の使用方法

#### カラム追加の反映
以下のコマンドを実行すると、既存のスキーマ定義ファイルにDB側で行われた変更を反映することが可能です。

(出力ファイル名を入力ファイルと同名にすることも可能です。この場合、内容は上書きされます)

```
php rdbdoc.php update:template --user=root --input=schema.yaml schema-new.yaml
```

#### ホスト名、ポート番号の指定
--host, --portオプションで接続先ホスト、ポート番号を指定します。

```
php rdbdoc.php generate:template --host=172.18.0.1 --port=13306 --user=root test schema.yaml
```

#### コマンド一覧の確認
以下のコマンドで、実行可能なコマンドを一覧表示します。

```
php rdbdoc.php list
```

## Install

### 事前に必要なもの

* PHP: 5.3
* PHP拡張 (Redhat RPMパッケージ名)
    * php-pecl-zip(Excel形式の出力でのみ必要)
    * php-pdo
    * php-mysqlnd(またはphp-mysqli)

### Git経由でインストール

```
$ git clone https://github.com/tri-star/rdbdoc.git
$ cd rdbdoc.git
$ composer install
$ # 動作確認
$ php rdbdoc.php list
```

### composer経由でインストール

準備中


## Road map

* インデックスについての情報の追加
* ドキュメント生成処理のプラグイン化
* スキーマ情報<=>テンプレート間の差分の確認機能の追加
* PostgreSQL対応
* composer経由のインストールのサポート(satis)
* 国際化
* Packagistへの登録


## Contribution

準備中

## Licence

[MIT](https://github.com/tri-star/rdbdoc/blob/master/LICENSE)

## Author

[tri-star](https://github.com/tri-star)

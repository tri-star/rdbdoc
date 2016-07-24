rdbdoc
===================================

![release](http://img.shields.io/github/release/tri-star/rdbdoc.svg?style=flat-square)
![license](http://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

# 概要
rdbdocはRDBMSのスキーマのメタデータと、各テーブル/カラムの説明を記述したファイルから
テーブル定義書を生成するコマンドラインツールです。

メンテナンスが煩雑になるテーブルのメタ情報は実際のスキーマから取得し、各テーブル/カラムの説明部分のみを
メンテナンスの対象とすることで、テーブル定義書の管理を低コストで持続可能にすることを目指しています。

(現在はMySQLのみの対応ですが、PostgreSQLにも対応予定です)

ドキュメントの生成形式は現時点では以下の2形式のみですが、プラグインを追加することで拡張が可能です。

* [HTML(シングルページ)](http://static.urban-theory.net/projects/rdbdoc/examples/01-single-page.html)
* [Excelブック(単一ファイル)](http://static.urban-theory.net/projects/rdbdoc/examples/02-single-excel-book.xlsx)

## 基本的な使用方法

### テンプレートの生成
最初に、既存のスキーマからテーブル定義のテンプレート(schema.yaml)の生成を行います。
```
# root@localhost:3306 でMySQLに接続出来ると仮定します。
php rdbdoc.php generate:template --user=root db_name schema.yaml
```

生成されるテンプレートの例：db_nameというDBにtable1というテーブルがあった場合

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

* このファイルのname, descキーにテーブルやカラムの論理名、説明(改行可)を記入していきます
* コマンドでは、各カラムの説明はインラインで生成されますが、[YAMLの複数行の構文](https://en.wikipedia.org/wiki/YAML#Block_literals) などで複数行の説明文も記述可能です
* [説明の記入例](http://static.urban-theory.net/projects/rdbdoc/examples/example.yaml)


### ドキュメントの生成
以下のコマンドを実行すると、./docsディレクトリ配下にExcelのBook形式でドキュメントが生成されます。

```
# root@127.0.0.1:3306 でMySQLに接続出来ると仮定します。
php rdbdoc.php generate:document --user=root --input=schema.yaml ./docs
```

### 生成されるドキュメントの例
* [HTML(シングルページ)](http://static.urban-theory.net/projects/rdbdoc/examples/01-single-page.html)
* [Excelブック(単一ファイル)](http://static.urban-theory.net/projects/rdbdoc/examples/02-single-excel-book.xlsx)


## その他の使用方法

### カラム追加の反映
以下のコマンドを実行すると、既存のスキーマ定義ファイルにDB側で行われた変更を反映することが可能です。

(出力ファイル名を入力ファイルと同名にすることも可能です。この場合、内容は上書きされます)

```
php rdbdoc.php update:template --user=root --input=schema.yaml schema-new.yaml
```

### ホスト名、ポート番号の指定
--host, --portオプションで接続先ホスト、ポート番号を指定します。

```
php rdbdoc.php generate:template --host=172.18.0.1 --port=13306 --user=root test schema.yaml
```

### コマンド一覧の確認
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

* ~~ドキュメント生成処理のプラグイン化~~
* ~~スキーマ情報<=>テンプレート間の差分の確認機能の追加~~
* インデックスについての情報の追加
* composer経由のインストールのサポート(satis)
* PostgreSQL対応
* 国際化
* Packagistへの登録
* トリガのドキュメント化に対応

## Contribution

### 開発

準備中

### プラグイン開発

準備中


## Licence

[MIT](https://github.com/tri-star/rdbdoc/blob/master/LICENSE)

## Author

[tri-star](https://github.com/tri-star)

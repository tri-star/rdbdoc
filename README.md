rdbdoc
===================================

![license](http://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)

# Overview


## Usage
最初に、既存のスキーマからテーブル定義のテンプレート(schema.yaml)の生成を行います。
```
# root@127.0.0.1:3306 でMySQLに接続出来ると仮定します。
php dbdg.php generate:template --user=root db_name schema.yaml
```

このコマンドにより以下の内容のschema.yamlが生成されます。

このファイルのname, descキーにテーブルやカラムの論理名、説明(改行可)を記入していきます。

例：db_nameというDBにtable1というテーブルがあった場合
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

例：db_nameというDBにtable1というテーブルがあった場合

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
php dbdg.php generate:document --user=root --input=schema.yaml ./docs
```


### その他の使用方法

#### カラム追加の反映
以下のコマンドを実行すると、既存のスキーマ定義ファイルにDB側で行われた変更を反映することが可能です。

(出力ファイル名を入力ファイルと同名にすることも可能です。この場合、内容は上書きされます)

```
php dbdg.php update:template --user=root --input=schema.yaml schema-new.yaml
```

#### ホスト名、ポート番号の指定
--host, --portオプションで接続先ホス、ポート番号を指定します。

```
php dbdg.php generate:template --host=172.18.0.1 --port=13306 --user=root test schema.yaml
```

#### コマンド一覧の確認
以下のコマンドで、実行可能なコマンドを一覧表示します。

```
php dbdg.php list
```




## Install

### Git経由でインストール

準備中

### composer経由でインストール

準備中


## Road map


## Contribution

準備中

## Licence

[MIT](https://github.com/tri-star/rdbdoc/blob/master/LICENSE)

## Author

[tri-star](https://github.com/tri-star)

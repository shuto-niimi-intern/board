# slimインストール

## パッケージのインストール

### composer require
- slim/slim
- slim/psr7
- slim/twig-view
- slim/flash
- slim/string-extra(truncate)

### オートロードの設定
- composer dump-autoload
- "Slim\\Board\\Classes\\": "classes/"などベンダーパッケージフォルダと同じ名前つけるとベンダー読み込まれないので注意

### setting(使用断念)
- アプリケーションの設定を記述するファイルです。
- エラー表示の有無や、テンプレートやログの配置をここで設定しています。
- 任意のキーでユーザー独自の設定も作れます。
- 設定は次のようにフレームワーク内で取得できます。
```.php
$container = $app->getContainer();
$settings  = $container->get('settings');
```

### [ミドルウェアの設定](https://www.slimframework.com/docs/v4/middleware/error-handling.html#error-handlingrendering)
- キャッチされなかったすべての PHP 例外を受け取るエラー ハンドラがあります。このエラー ハンドラーは、現在の HTTP 要求および応答オブジェクトも受け取ります。エラー ハンドラは、HTTP クライアントに返される適切な Response オブジェクトを準備して返す必要があります。

### エラーハンドラの設定

### [エラー処理レンダリング](https://www.slimframework.com/docs/v4/middleware/error-handling.html#error-handlingrendering)

### phpDotEnvの設定
- vlucas/phpdotenv
- class内に定数としてグローバル変数の$_ENVを指定できない
- slim3ではappのインスタンス生成時にsettingでテンプレパス、ロギング、環境変数を与えて生成していた
- slim4不明

### slim/string-extra(truncate)の設定
- 文字数over...の...をつけたい人向け
- parentControllerで
- use Twig\Extra\String\StringExtension;
- $this->view->addExtension(new StringExtension);

### databaseの作成
- CREATE DATABASE けいじばん CHARACTER SET utf8;
- CREATE USER IF NOT EXISTS ゆ～ざ@localhost IDENTIFIED BY 'ゆ～ざぱすわ～ど'
- [GRANT ALL PRIVILEGES ON board.*TO admin@localhost;](https://qiita.com/ritukiii/items/afdc91e68d0cf3e0f383)
- mysql -u ゆーざ -p board --default-character-set=utf8 < " seed.sql

### SQL 結合などしてマシなSQL文に変える


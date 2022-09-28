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

### setting
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
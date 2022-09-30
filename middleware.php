<?php

/**
 * @author Syuto Niimi
 *
 */

use App\Board\Classes\exceptions\ErrorRenderer;
use Slim\Middleware\MethodOverrideMiddleware;

/**
 * ルーティング ミドルウェアは、ErrorMiddleware よりも前に追加する必要があります。
 * そうしないと、ミドルウェアからスローされた例外がミドルウェアによって処理されません
 */
$app->addRoutingMiddleware();
/**
 * エラーミドルウェアを追加
 *
 * @param bool                  $displayErrorDetails -> 本番環境では false に設定する必要があります
 * @param bool                  $logErrors -> パラメータはデフォルトの ErrorHandler に渡されます
 * @param bool                  $logErrorDetails -> エラーログにエラーの詳細を表示
 * @param LoggerInterface|null  $logger -> オプションの PSR-3 ロガー
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);


/**
 * エラー処理/レンダリング
 * url = https://www.slimframework.com/docs/v4/middleware/error-handling.html#error-handlingrendering
 */
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->registerErrorRenderer("text/html", ErrorRenderer::class);


/**
 * メソッドオーバライドミドルウェア
 * put patch deleteなどのリクエストメソッドを上書きするためのミドルウェア
 * Add MethodOverride middleware
 */
$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);

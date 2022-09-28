<?php

/**
 * @author
 */

namespace App\Board\\Classes\middlewares;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Board\Classes\exceptions\NoLoginException;

/**
 * ログインチェックミドルウェアクラス。
 */
class LoginCheck
{
  /**
   * ログインチェック処理。
   * ログインされていない状態を検知したらNoLoginExceptionが発生する。
   *
   * @param ServerRequestInterface $request リクエストオブジェクト。
   * @param RequestHandlerInterface $handler リクエストハンドラオブジェクト。
   * @return ResponseInterface レスポンスオブジェクト。
   */
  public function __invoke(ServerRequestInterface $request, RequestHandlerInterface
  $handler): ResponseInterface
  {
    if (
      !isset($_SESSION["loginFlg"]) || $_SESSION["loginFlg"] == false ||
      !isset($_SESSION["id"]) || !isset($_SESSION["name"]) || !isset($_SESSION["auth"])
    ) {
      // $result = true;
      throw new NoLoginException();
    }
    $response = $handler->handle($request);
    return $response;
  }
}

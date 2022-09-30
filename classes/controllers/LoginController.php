<?php

/**
 * @author Syuto Niimi
 */

namespace App\Board\Classes\controllers;

use PDO;
use PDOException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Board\Classes\exceptions\DataAccessException;
use App\Board\Classes\daos\UserDAO;
use App\Board\Classes\controllers\ParentController;

/**
 * ログイン・ログアウトに関するコントローラクラス。
 */
class LoginController extends ParentController
{
  /**
   * ログイン画面表示処理。
   */
  public function goLogin(ServerRequestInterface $request, ResponseInterface
  $response, array $args): ResponseInterface
  {
    $returnResponse = $this->view->render($response, "login.html");
    return $returnResponse;
  }

  /**
   * ログイン処理。
   */
  public function login(
    ServerRequestInterface $request,
    ResponseInterface $response,
    array $args
  ): ResponseInterface {

    $isRedirect = false;
    $templatePath = "login.html";
    $assign = [];
    /**
     * リクエスト受け取り
     */
    $postParams = $request->getParsedBody();
    $loginId = $postParams["loginId"];
    $loginPw = $postParams["loginPw"];
    $loginId = trim($loginId);
    $loginPw = trim($loginPw);
    /**
     * バリデーション
     */
    $validationMsgs = [];
    if (mb_strlen($loginId) !== 5 || !ctype_alpha($loginId)) {
      $validationMsgs[] = 'IDは英語の5文字で設定してください';
    }
    /**
     * クエリ
     */
    if (empty($validationMsgs)) {
      try {
        $db = new PDO('mysql:host=' . $_ENV['host'] . ';dbname=' . $_ENV['dbname'] . ';charset=utf8', $_ENV['user'], $_ENV['pass']);

        $userDAO = new UserDAO($db);
        $user = $userDAO->findByUserId($loginId);

        if ($user == null) {
          $validationMsgs[] = "存在しないIDです。正しいIDを入力してください。";
        } else {
          if ($loginPw === $user->getPassword()) {
            $id = $user->getId();
            $name = $user->getName();
            $auth = $user->getAuth();

            $_SESSION["loginFlg"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["name"] = $name;
            $_SESSION["auth"] = $auth; // 権限
            $isRedirect = true;
          } else {
            $validationMsgs[] =
              "パスワードが違います。正しいパスワードを入力してください。";
          }
        }
      } catch (PDOException $ex) {
        $exCode = $ex->getCode();
        throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
      } finally {
        $db = null;
      }
    }

    if ($isRedirect) {
      $returnResponse = $response->withStatus(302)->withHeader(
        "Location",
        "/reports/showList"
      );
    } else {
      if (!empty($validationMsgs)) {
        $assign["validationMsgs"] = $validationMsgs;
        $assign["loginId"] = $loginId;
      }
      $returnResponse = $this->view->render($response, $templatePath, $assign);
    }
    return $returnResponse;
  }

  /**
   * ログアウト処理。
   */
  public function logout(
    ServerRequestInterface $request,
    ResponseInterface $response,
    array $args
  ): ResponseInterface {
    session_destroy();
    $returnResponse = $response->withStatus(302)->withHeader(
      "Location",
      "/"
    );
    return $returnResponse;
  }
}

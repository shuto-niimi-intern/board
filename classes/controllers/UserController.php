<?php

/**
 * @author Syuto Niimi
 */

namespace App\Board\Classes\controllers;

use PDO;
use PDOException;
use Psr\Http\Message\ServerRequestInterface as ServerRequestInterface;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use App\Board\Classes\exceptions\DataAccessException;
use App\Board\Classes\entities\User;
use App\Board\Classes\daos\UserDAO;
use App\Board\Classes\controllers\ParentController;

/**
 * ユーザー情報管理に関するコントローラクラス。
 */
class UserController extends ParentController
{
  /**
   * レポート詳細画面表示処理。
   */
  public function showProfile(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    /**
     * 表示する画面、twig変数,フラグ
     */
    $templatePath = "user/userProfile.html";
    $assign = [];
    /**
     * リクエストパラメータ
     */
    $flashMessages = $this->flash->getMessages();
    if (isset($flashMessages)) {
      $assign["flashMsg"] = $this->flash->getFirstMessage("flashMsg");
    }
    $this->cleanSession();
    /**
     * query
     */
    try {
      $db = new PDO('mysql:host=' . $_ENV['host'] . ';dbname=' . $_ENV['dbname'] . ';charset=utf8', $_ENV['user'], $_ENV['pass']);
      // user情報
      $userDAO = new userDAO($db);
      $user = $userDAO->findByUserId($_SESSION['id']);
      if (empty($user)) {
        throw new DataAccessException("user情報の取得に失敗しました。");
      } else {
        $assign["user"] = $user;
      }
    } catch (PDOException $ex) {
      $exCode = $ex->getCode();
      throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
    } finally {
      $db = null;
    }
    /**
     * viewへの受け渡し
     */
    $assign['session'] = $_SESSION;
    // var_dump($assign);
    $returnResponse = $this->view->render($response, $templatePath, $assign);
    return $returnResponse;
  }
  /**
   * ユーザー情報登録画面表示処理。
   */
  public function goAdd(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $templatePath = "user/userAdd.html";
    $assign = [];
    // ログイン情報
    $assign['session'] = $_SESSION;
    $returnResponse = $this->view->render($response, $templatePath, $assign);
    return $returnResponse;
  }
  /**
   * ユーザー情報登録処理。
   */
  public function add(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $templatePath = "user/userAdd.html";
    $isRedirect = false; // 登録成功|失敗
    $assign = [];
    /**
     * request
     */
    foreach ($request->getParsedBody() as $key => $val) {
      $val = str_replace([' ', '　'], "", $val);
      ${$key} = trim($val);
    }
    $user = new User($addId, "マツコ・デラックス", $addPass, 1);
    /**
     * validate
     */
    $validationMsgs = [];
    if (empty($addId)) {
      $validationMsgs[] = "IDの入力は必須です。";
    }
    if (empty($addPass)) {
      $validationMsgs[] = "パスワードの入力は必須です。";
    }
    if (mb_strlen($addId) !== 5 || !ctype_alpha($addId)) {
      $validationMsgs[] = 'IDは英語の5文字で設定してください。';
    }
    /**
     * query
     */
    try {
      $db = new PDO('mysql:host=' . $_ENV['host'] . ';dbname=' . $_ENV['dbname'] . ';charset=utf8', $_ENV['user'], $_ENV['pass']);
      $userDAO = new UserDAO($db);
      /**
       * 同じIDが存在するか
       */
      if ($userDAO->findByUserId($addId) === null) {
        if (empty($validationMsgs)) {
          $userId = $userDAO->insert($user);
          if ($userId === -1) {
            throw new
              DataAccessException("情報登録に失敗しました。もう一度はじめからやり直してください。");
          } else {
            $_SESSION["loginFlg"] = true;
            $_SESSION["id"] = $addId;
            $_SESSION["name"] = 'マツコデラックス';
            $_SESSION["auth"] = 1; // 権限
            $isRedirect = true;
            $this->flash->addMessage(
              "flashMsg",
              "ユーザーID" . $addId . "でユーザー情報を登録しました。"
            );
          }
        } else {
          $assign["user"] = $user;
          $assign["validationMsgs"] = $validationMsgs;
        }
      } else {
        $validationMsgs['すでにそのIDは登録されています。'];
      }
    } catch (PDOException $ex) {
      echo $ex->getMessage() . " - " . $ex->getLine() . PHP_EOL;
      $exCode = $ex->getCode();
      throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
    } finally {
      $db = null;
    }
    // ログイン情報
    $assign['session'] = $_SESSION;
    if ($isRedirect) {
      $returnResponse = $response->withStatus(302)->withHeader(
        "Location",
        "/user/profile"
      );
    } else {
      $returnResponse = $this->view->render($response, $templatePath, $assign);
    }
    return $returnResponse;
  }

  /**
   * ユーザー情報更新画面表示処理。
   */
  public function prepareEdit(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
  {
    $templatePath = "user/userEdit.html";
    $assign = [];
    $editReportId = $_SESSION['id'];
    /**
     * tokenつかうならDB
     */
    try {
      $db = new PDO('mysql:host=' . $_ENV['host'] . ';dbname=' . $_ENV['dbname'] . ';charset=utf8', $_ENV['user'], $_ENV['pass']);
      // レポート情報
      $userDAO = new userDAO($db);
      $user = $userDAO->findByUserId($editReportId);
      if (empty($user)) {
        throw new DataAccessException("ユーザー情報の取得に失敗しました。");
      } else {
        $assign["user"] = $user;
      }
    } catch (PDOException $ex) {
      $exCode = $ex->getCode();
      throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
    } finally {
      $db = null;
    }
    // ログイン情報
    $assign['session'] = $_SESSION;
    $returnResponse = $this->view->render($response, $templatePath, $assign);
    return $returnResponse;
  }

  /**
   * ユーザー情報編集処理。
   */
  public function edit(ServerRequestInterface $request, ResponseInterface
  $response, array $args): ResponseInterface
  {
    $templatePath = "user/userEdit.html";
    $isRedirect = false;
    $assign = [];

    $postParams = $request->getParsedBody();
    foreach ($postParams as $key => $val) {
      $val = str_replace([' ', '　'], "", $val);
      ${$key} = trim($val);
    }
    $user = new User($_SESSION['id'], $editName, $editPass, 1);
    /**
     * validate
     */
    $validationMsgs = [];
    if (empty($editName)) {
      $validationMsgs[] = "IDの入力は必須です。";
    }
    if (empty($editPass)) {
      $validationMsgs[] = "パスワードの入力は必須です。";
    }
    if (mb_strlen($editName) !== 5 || !ctype_alpha($editName)) {
      $validationMsgs[] = 'IDは英語の5文字で設定してください。';
    }

    try {
      $db = new PDO('mysql:host=' . $_ENV['host'] . ';dbname=' . $_ENV['dbname'] . ';charset=utf8', $_ENV['user'], $_ENV['pass']);
      $userDAO = new userDAO($db);
      if (empty($validationMsgs)) {
        $result = $userDAO->update($user);
        if ($result) {
          $isRedirect = true;
          $this->flash->addMessage(
            "flashMsg",
            "userID" . $user->getId() . "でユーザー情報を更新しました。"
          );
        } else {
          throw new
            DataAccessException("情報更新に失敗しました。もう一度はじめからやり直してください。");
        }
      } else {
        // レポート詳細カラム
        $assign["user"] = $user;
        $assign["validationMsgs"] = $validationMsgs;
      }
    } catch (PDOException $ex) {
      $exCode = $ex->getCode();
      throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
    } finally {
      $db = null;
    }
    // ログイン情報
    $assign['session'] = $_SESSION;

    if ($isRedirect) {
      $returnResponse = $response->withStatus(302)->withHeader(
        "Location",
        "/user/profile"
      );
    } else {
      $returnResponse = $this->view->render($response, $templatePath, $assign);
    }
    return $returnResponse;
  }

  /**
   * ユーザー情報削除確認画面表示処理。
   */
  public function confirmDelete(
    ServerRequestInterface $request,
    ResponseInterface $response,
    array $args
  ): ResponseInterface {
    $templatePath = "/user/userConfirmDelete.html";
    $assign = [];

    // try {
    //   $db = new PDO('mysql:host=' . $_ENV['host'] . ';dbname=' . $_ENV['dbname'] . ';charset=utf8', $_ENV['user'], $_ENV['pass']);
    //   $userDAO = new userDAO($db);
    //   $user = $userDAO->findByUserId($_SESSION['id']);
    //   if (empty($user)) {
    //     throw new DataAccessException("ユーザー情報の取得に失敗しました。");
    //   } else {
    //     $assign["user"] = $user;
    //   }
    // } catch (PDOException $ex) {
    //   $exCode = $ex->getCode();
    //   throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
    // } finally {
    //   $db = null;
    // }
    // ログイン情報
    $assign['session'] = $_SESSION;
    // var_dump($assign);
    $returnResponse = $this->view->render($response, $templatePath, $assign);
    return $returnResponse;
  }

  /**
   * ユーザー情報削除処理。
   */
  public function delete(ServerRequestInterface $request, ResponseInterface
  $response, array $args): ResponseInterface
  {
    $deleteId = $_SESSION['id'];
    try {
      $db = new PDO('mysql:host=' . $_ENV['host'] . ';dbname=' . $_ENV['dbname'] . ';charset=utf8', $_ENV['user'], $_ENV['pass']);
      $userDAO = new userDAO($db);
      $result = $userDAO->delete($deleteId);
      if ($result) {
        session_destroy();
        $this->flash->addMessage(
          "flashMsg",
          "ユーザーID" . $deleteId . "のユーザー情報を削除しました。"
        );
      } else {
        throw new
          DataAccessException("情報削除に失敗しました。もう一度はじめからやり直してください。");
      }
    } catch (PDOException $ex) {
      $exCode = $ex->getCode();
      throw new DataAccessException("DB接続に失敗しました。", $exCode, $ex);
    } finally {
      $db = null;
    }
    // ログイン情報
    $returnResponse = $response->withStatus(302)->withHeader(
      "Location",
      "/"
    );
    return $returnResponse;
  }
}

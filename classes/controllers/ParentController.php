<?php

/**
 * @author
 */

namespace App\Board\Classes\controllers;

use Slim\Views\Twig;
use Slim\Flash\Messages;
use Twig\Extra\String\StringExtension;

/**
 * 各コントローラクラスの親クラス。
 * 共通処理がメソッドとして記述されている。
 */
class ParentController
{
  /**
   * テンプレート描画に使用するTwigインスタンス。
   */
  protected Twig $view;
  /**
   * フラッシュメッセージに利用するMessagesインスタンス。
   */
  protected Messages $flash;

  /**
   * コンストラクタ。
   * Twigインスタンスを生成してプロパティに格納する。
   */
  public function __construct()
  {
    $this->view = Twig::create(__DIR__ . "/../../templates");
    $this->view->addExtension(new StringExtension);
    $this->flash = new Messages();
  }

  /**
   * Session情報の掃除関数。
   * ログイン情報以外のセッション中の情報を一度破棄する。
   * 各ユースケース最初の実行メソッド内でこのメソッドを実行する。
   */
  protected function cleanSession(): void
  {
    $loginFlg = $_SESSION["loginFlg"];
    $id = $_SESSION["id"];
    $name = $_SESSION["name"];
    $mail = $_SESSION["mail"];
    $auth = $_SESSION["auth"];


    session_unset();

    $_SESSION["loginFlg"] = $loginFlg;
    $_SESSION["id"] = $id;
    $_SESSION["name"] = $name;
    $_SESSION["mail"] = $mail;
    $_SESSION["auth"] = $auth;
  }
}

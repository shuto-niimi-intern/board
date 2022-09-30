<?php

/**
 * レポートエンティティクラス
 * @author Syuto Niimi
 */

namespace App\Board\Classes\entities;

class User
{
  //********************* プロパティ **************************
  /**
   * ユーザID兼ログインID
   * IDは英単語5文字とする
   * wordleで鍛えられた英単語力で登録頑張れ
   * @var string
   */
  protected ?string $id = "";
  /**
   * ハンドルネーム
   * 初期値はマツコ・デラックスとする
   * 一応変えれるようにした
   * 面白い名前で頼む
   * @var string
   */
  protected ?string $name = "";
  /**
   * パスワード
   * ご自由に 空文字は駄目
   * @var string
   */
  protected ?string $password = "";
  /**
   * 権限
   * 基本権限 0=管理者,1=一般 権限の範囲はまだ決めてない
   * ユースケースに記述してまた書く
   * アカウント削除する場合復活させる気はないので
   * 削除日とかフラグをつける気はない
   * @var integer
   */
  protected ?int $auth = 1;

  //********************* コンストラクタ **************************
  /**
   * セッター
   *
   * @param string $id
   * @param string $name // SQLでDEFAULT設定してるけど..分からん
   * @param string $password
   * @param integer $auth
   */
  public function __construct(string $id, string $name = "マツコ・デラックス", string $password, int $auth = 1)
  {
    $this->id = $id;
    $this->name = $name;
    $this->password = $password;
    $this->auth = $auth;
  }

  /**
   * ゲッター
   * @return integer
   */
  public function getId(): ?string
  {
    return $this->id;
  }
  public function getName(): ?string
  {
    return $this->name;
  }
  public function getPassword(): ?string
  {
    return $this->password;
  }
  public function getAuth(): ?int
  {
    return (int)$this->auth;
  }
}

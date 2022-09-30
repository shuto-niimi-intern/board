<?php

/**
 * バリデーションクラス
 * @author Syuto Niimi
 */

namespace App\Board\Classes\entityRules;

use App\Board\Classes\entities\User;

class UserValidate extends User
{
  //********************* プロパティ **************************
  /**
   * 思いつかない
   */
  //********************* コンストラクタあり **************************
  /**
   * setter
   * @param string $id
   * @param string $name
   * @param string $password
   * @param integer $auth
   * @param string|null $createdAt
   * @param string|null $updatedAt
   */
  public function __construct(
    string $id,
    string $name = "マツコ・デラックス",
    string $password,
    int $auth = 1,
    string $createdAt = null,
    string $updatedAt = null
  ) {
    parent::__construct(
      $id,
      $name,
      $password,
      $auth,
      $createdAt,
      $updatedAt
    );
  }
  //********************* バリデート **************************
}

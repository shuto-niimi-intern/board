<?php

/**
 * @author Syuto Niimi
 */

namespace App\Board\Classes\daos;

use PDO;
use App\Board\Classes\entities\User;

class UserDAO
{
  /**
   * @var PDO DB接続オブジェクト
   */
  private PDO $db;

  /**
   * コンストラクタ
   *
   * @param PDO $db DB接続オブジェクト
   */
  public function __construct(PDO $db)
  {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $this->db = $db;
  }
  /**
   * loginId && userIdによる検索。
   *
   * @param string $userId userID 。
   * @return User 該当するUserオブジェクト。ただし、該当データがない場合はnull。
   */
  public function findByUserId(string $userId): ?User
  {
    $sql = "SELECT * FROM users WHERE id = :loginId";
    $stmt = $this->db->prepare($sql);

    $stmt->bindValue(":loginId", $userId, PDO::PARAM_STR);
    $result = $stmt->execute();
    $user = null;
    if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $id = $row["id"];
      $name = $row["name"];
      $password = $row["password"];
      $auth = $row["auth"];

      $user = new User($id, $name, $password, $auth);
    }
    return $user;
  }
  /**
   * admin用
   * ユーザリスト全取得
   * @return User Userオブジェクト 該当なしの場合null
   */
  public function findAll(): array
  {
    $sql = "SELECT * FROM users";
    $stmt = $this->db->prepare($sql);
    $result = $stmt->execute();
    $UserList = [];
    while ($row = $stmt->fetch()) {
      $User = new User($row['id'], $row['name'], $row['password'], $row['auth']);
      $UserList[$row['id']] = $User;
    }
    return $UserList;
  }
  /**
   * ユーザ新規作成画面
   * @param User
   * @return integer 登録失敗 = -1
   */
  public function insert(User $User): int
  {
    $sqlInsert = "INSERT INTO users (id,name,password,auth) VALUES(:id,:name,
:password,:auth)";
    $stmt = $this->db->prepare($sqlInsert);
    $stmt->bindValue(':id', $User->getId(), PDO::PARAM_STR);
    $stmt->bindValue(':name', $User->getName(), PDO::PARAM_STR);
    $stmt->bindValue(':password', $User->getPassword(), PDO::PARAM_STR);
    $stmt->bindValue(':auth', $User->getAuth(), PDO::PARAM_INT);
    $result = $stmt->execute();
    if ($result) {
      $UserId = $this->db->lastInsertId();
    } else {
      $UserId = -1;
    }
    return $UserId;
  }
  /**
   * ユーザ更新
   * @param User
   * @return boolean
   */
  public function update(User $User): bool
  {
    $sqlUpdate = " UPDATE users SET name = :name , password = :password WHERE id = :id";
    $stmt = $this->db->prepare($sqlUpdate);
    $stmt->bindValue('id', $User->getId(), PDO::PARAM_STR);
    $stmt->bindValue('name', $User->getName(), PDO::PARAM_STR);
    $stmt->bindValue('password', $User->getPassword(), PDO::PARAM_STR);
    $result = $stmt->execute();
    return $result;
  }
  /**
   * ユーザ削除
   * @param integer ユーザID
   * @return boolean 登録が成功したかどうか
   */
  public function delete(int $id): bool
  {
    $sql = "DELETE FROM Users WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_STR);
    $result = $stmt->execute();
    return $result;
  }
}

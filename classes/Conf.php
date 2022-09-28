<?php

/**
 * 設定クラス
 * @author Syuto Niimi
 */

namespace App\Board\Classes;

/**
 * 定数クラス
 */
class Conf
{
  const DB_DNS = 'mysql:host=' . $_ENV['host'] . ';dbname=' . $_ENV['dbname'] . ';charset=utf8';
  const DB_USERNAME = $_ENV['user'];
  const DB_PASSWORD = $_ENV['pass'];
}

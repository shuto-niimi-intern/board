<?php

/**
 * 設定
 * @author Syuto Niimi <ohs00727@ohs.hal.ac.jp>
 */

namespace App\Board\\Classes;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/**
 * 定数クラス
 */
class Conf
{
  const DB_DNS = 'mysql:host=' . $_ENV['host'] . ';dbname=' . $_ENV['dbname'] . ';charset=utf8';
  const DB_USERNAME = $_ENV['boardadmin'];
  const DB_PASSWORD = $_ENV['pass'];
}

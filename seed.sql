--
-- テーブル作成、データ登録SQLファイル
-- adminユーザで使用
-- コマンド: mysql -u admin -p board --default-character-set=utf8 < "./seed.sql"
--
-- @author Syuto Niimi

-- テーブル削除
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS board;

-- ユーザ
CREATE TABLE users
(
	id varchar(255) NOT NULL COMMENT 'ユーザID兼ログインID',
  name varchar(255) DEFAULT 'マツコ・デラックス' COMMENT 'ハンドルネーム',
	password varchar(255) NOT NULL COMMENT 'パスワード',
	auth tinyint(1) UNSIGNED DEFAULT 1 NOT NULL COMMENT '権限 :
0=管理者
1=一般',
	PRIMARY KEY (id)
) COMMENT = 'ユーザ';

-- 単一掲示板
CREATE TABLE boards
(
  id int(11) NOT
)


-- ユーザデータ挿入
INSERT INTO users (id, password, auth) VALUES ('aiueo', 'pass', '1');
INSERT INTO users (id, password, auth) VALUES ('apple', 'pass', '1');
INSERT INTO users (id, password, auth) VALUES ('polca', 'pass', '1');


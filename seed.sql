--
-- テーブル作成、データ登録SQLファイル
-- adminユーザで使用
-- コマンド: mysql -u admin -p board --default-character-set=utf8 < "./seed.sql"
--
-- @author Syuto Niimi

-- テーブル削除
DROP TABLE IF EXISTS users;

-- ユーザ
CREATE TABLE users
(
	id varchar(255) NOT NULL COMMENT 'ユーザID兼ログインID',
  name varchar(255) DEFAULT 'マツコデラックス' COMMENT 'ハンドルネーム',
	password varchar(255) NOT NULL COMMENT 'パスワード',
	auth int(11) UNSIGNED DEFAULT 1 NOT NULL COMMENT '権限 : 0=終了
1=管理者
2=一般',
created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (id)
) COMMENT = 'ユーザ';

-- ユーザデータ挿入
INSERT INTO users (id, password, auth) VALUES ('aiueo', 'pass', '1');

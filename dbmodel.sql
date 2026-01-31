-- 駒の配置状態を保存するテーブル
CREATE TABLE IF NOT EXISTS `piece` (
  `piece_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `piece_container` varchar(50) NOT NULL,
  `piece_position` int(10) unsigned NOT NULL DEFAULT '0',
  `piece_type` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '駒番号(1-108)',
  `piece_face` enum('front','back') NOT NULL DEFAULT 'back' COMMENT '表裏の状態',
  PRIMARY KEY (`piece_id`),
  KEY `container` (`piece_container`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- ゲーム状態ラベル（スコアラベル）を保持するテーブル
CREATE TABLE IF NOT EXISTS `gamestate_labels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `label` (`label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;




































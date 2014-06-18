ALTER TABLE  `doc_types` ADD  `water_mark_text` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER  `path`;
ALTER TABLE  `doc_types` ADD  `water_mark_font_size` int NULL AFTER `water_mark_text`;
ALTER TABLE  `doc_types` ADD  `water_mark_opacity` float NULL AFTER `water_mark_font_size`;
ALTER TABLE  `doc_types` ADD  `water_mark_angle` int NULL AFTER `water_mark_opacity`;
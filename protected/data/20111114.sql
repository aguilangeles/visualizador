ALTER TABLE  `ocr_meta` ADD  `is_special` BOOLEAN NULL DEFAULT  '0' AFTER  `ocr_meta_id`;
ALTER TABLE  `carat_meta` ADD  `is_special` BOOLEAN NULL DEFAULT  '0' AFTER  `carat_meta_id`;

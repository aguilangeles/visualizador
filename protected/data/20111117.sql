CREATE TABLE  `visualizador`.`rotulos` (
`rotulo_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`rotulo_desc` VARCHAR( 255 ) NOT NULL ,
UNIQUE (
`rotulo_desc`
)
) ENGINE = INNODB;

CREATE TABLE  `visualizador`.`rotulos_doctype` (
`rotulos_doctype_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`rotulo_id` INT NOT NULL ,
`doc_type_id` INT NOT NULL
) ENGINE = INNODB;

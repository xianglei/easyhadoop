ALTER TABLE  `ehm_hosts` CHANGE  `mount_mrsystem`  `mount_system` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE  `ehm_hosts` CHANGE  `mount_mrlocal`  `mount_local` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE  `ehm_hosts` ADD COLUMN  `is_formatted`  TINYINT( 1 ) NOT NULL COMMENT  '0=false;1=true';
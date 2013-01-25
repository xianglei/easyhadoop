ALTER TABLE ehm_hosts ADD COLUMN `rack` INT(10) NOT NULL DEFAULT '1';
ALTER TABLE ehm_hosts ADD COLUMN `mount_name` TEXT NULL;
ALTER TABLE ehm_hosts ADD COLUMN `mount_data` TEXT NULL;
ALTER TABLE ehm_hosts ADD COLUMN `mount_mrlocal` TEXT NULL;
ALTER TABLE ehm_hosts ADD COLUMN `mount_mrsystem` TEXT NULL;
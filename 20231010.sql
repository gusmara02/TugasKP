-- SQL 2023-10-10
-- SQL ini untuk:
-- - Fitur Gaji Berkala

INSERT INTO
    user_sub_menu(menu_id, title, url, icon, is_active)
VALUES
    (
        1,
        "Gaji Berkala",
        "admin/gaji_berkala",
        "fas fa-fw fa-receipt",
        1
    );

CREATE TABLE `database`.`gaji_berkala` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_user` INT NOT NULL,
    `role_id` INT NOT NULL,
    `nik` VARCHAR(255) NOT NULL,
    `nama` VARCHAR(255) NOT NULL,
    `jabatan` VARCHAR(255) NOT NULL,
    `bagian` VARCHAR(255) NOT NULL,
    `tgl_cetak` DATE NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
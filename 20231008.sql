-- SQL 2023-10-08
-- SQL ini untuk:
-- - Mengupdate menu navigasi
UPDATE
    `user_sub_menu`
SET
    `title` = 'Beranda Admin'
WHERE
    id = 1;

UPDATE
    `user_sub_menu`
SET
    `title` = 'Beranda SDM'
WHERE
    id = 22;

UPDATE
    `user_sub_menu`
SET
    `title` = 'Kepegawaian'
WHERE
    id = 23;

UPDATE
    `user_sub_menu`
SET
    `title` = 'List Cuti Tahunan'
WHERE
    id = 24;

INSERT INTO
    user_sub_menu(menu_id, title, url, icon, is_active)
VALUES
(
        3,
        "Approval",
        "kaur/cuti_staf",
        "fas fa-fw fa-check-circle",
        1
    ),
    (
        5,
        "Riwayat",
        "absensi",
        "fas fa-fw fa-check-circle",
        1
    ),
    (
        1,
        "Absensi User",
        "admin/absensi",
        "fas fa-fw fa-check-circle",
        1
    );

INSERT INTO
    `user_menu`
VALUES
    (5, "Absensi");

INSERT INTO
    user_access_menu(role_id, menu_id)
VALUES
    (1, 5),
    (2, 5),
    (3, 5),
    (4, 5);

CREATE TABLE `database`.`absensi` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_user` INT NOT NULL,
    `role_id` INT NOT NULL,
    `nik` VARCHAR(255) NOT NULL,
    `nama` VARCHAR(255) NOT NULL,
    `bagian` VARCHAR(255) NOT NULL,
    `check_in` DATETIME NOT NULL,
    `check_out` DATETIME NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
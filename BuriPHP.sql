CREATE TABLE
    `PERMISSION` (
        `ID` bigint(11) UNSIGNED NOT NULL,
        `PERMISSION` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `DESCRIPTION` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `VALUE` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

INSERT INTO
    `PERMISSION` (
        `ID`,
        `PERMISSION`,
        `DESCRIPTION`,
        `VALUE`
    )
VALUES (
        1,
        'sysadmin',
        'Administrador de sistemas',
        'use_dashboard,read_user,create_user,update_user,delete_user'
    ), (
        2,
        'administrator',
        'Administrador',
        'use_dashboard,read_user,create_user,update_user,delete_user'
    ), (3, 'customer', 'Cliente', NULL);

CREATE TABLE
    `USER` (
        `ID` bigint(11) UNSIGNED NOT NULL,
        `EMAIL` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `PASSWORD` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `USERNAME` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `NAME` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `PHONE` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `PERMISSION_ID` bigint(11) DEFAULT NULL,
        `STATUS` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `DATE_CREATED` timestamp NULL DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

INSERT INTO
    `USER` (
        `ID`,
        `EMAIL`,
        `PASSWORD`,
        `USERNAME`,
        `NAME`,
        `PHONE`,
        `PERMISSION_ID`,
        `STATUS`,
        `DATE_CREATED`
    )
VALUES (
        1,
        'davidgomezmacias@gmail.com',
        'a5b3794354691d43c779666d985300082f8dcf16:5BsUFmhBalg8jtRpmXVfyzH4EhNeNmJiHfj0qB4lrfkjuKGPMPvqxUtj9TXOTtTK',
        'davidgomezmacias',
        'David Miguel Gomez Macias',
        NULL,
        1,
        'active',
        '2023-05-15 10:32:32'
    ), (
        2,
        'gergomez18@gmail.com',
        'fb43f8f01c99855f7783ea34132d5063e742e211:JCoRRSyHOOjnjlrqUQMAetkaYQh3bDz3priuVNQ1x2ow1wX32799EyvK1ODiygNS',
        'gergomez18',
        'Gersón Aarón Gómez Macías',
        NULL,
        1,
        'active',
        '2023-06-05 21:07:33'
    );

CREATE TABLE
    `SUBSCRIPTION` (
        `ID` bigint(11) UNSIGNED NOT NULL,
        `USER_ID` bigint(11) DEFAULT NULL,
        `BILLING_PERIOD` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `PRICE` decimal(11, 0) DEFAULT NULL,
        `STATUS` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `DATA` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `NOTE` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `DATE_PAYMENT_UPDATE` timestamp NULL DEFAULT NULL,
        `DATE_PAYMENT_NEXT` timestamp NULL DEFAULT NULL,
        `METHOD_PAYMENT` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `DATE_CREATED` timestamp NULL DEFAULT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

ALTER TABLE `PERMISSION` ADD PRIMARY KEY (`ID`);

ALTER TABLE `USER` ADD PRIMARY KEY (`ID`);

ALTER TABLE `SUBSCRIPTION` ADD PRIMARY KEY (`ID`);
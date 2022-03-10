CREATE TABLE `lhc_cloudtalkio_agent_native` (
                                                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                                `user_id` bigint(20) unsigned NOT NULL,
                                                `cloudtalk_user_id` bigint(20) unsigned NOT NULL,
                                                `in_sync` bigint(20) unsigned NOT NULL,
                                                `updated_at` bigint(20) unsigned NOT NULL,
                                                `firstname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                                `lastname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                                `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                                `availability_status` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                                PRIMARY KEY (`id`),
                                                KEY `cloudtalk_user_id` (`cloudtalk_user_id`),
                                                KEY `email` (`email`),
                                                KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lhc_cloudtalkio_call` (
                                        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                                        `cloudtalk_user_id` bigint(20) unsigned NOT NULL,
                                        `exclude_autoasign` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                        `user_id` bigint(20) unsigned NOT NULL,
                                        `contact_id` bigint(20) unsigned NOT NULL,
                                        `call_id` bigint(20) unsigned NOT NULL DEFAULT 0,
                                        `chat_id` bigint(20) unsigned NOT NULL,
                                        `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                        `status_call` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                        `contact_removed` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                        `updated_at` bigint(20) unsigned NOT NULL,
                                        `created_at` bigint(20) unsigned NOT NULL,
                                        `date_from` bigint(20) unsigned NOT NULL,
                                        `date_to` bigint(20) unsigned NOT NULL,
                                        `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `waiting_time` int(11) unsigned NOT NULL,
                                        `talking_time` int(11) unsigned NOT NULL,
                                        `wrapup_time` int(11) unsigned NOT NULL,
                                        `call_variables` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `nick` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `call_uuid` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `recording_url` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
                                        `status_outcome` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                        `direction` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                        `msg_id` bigint(20) unsigned NOT NULL DEFAULT 0,
                                        `answered_at` bigint(20) unsigned NOT NULL DEFAULT 0,
                                        `dep_id` bigint(20) unsigned NOT NULL,
                                        PRIMARY KEY (`id`),
                                        KEY `call_id` (`call_id`),
                                        KEY `cloudtalk_user_id` (`cloudtalk_user_id`),
                                        KEY `call_uuid` (`call_uuid`),
                                        KEY `msg_id` (`msg_id`),
                                        KEY `contact_removed` (`contact_removed`),
                                        KEY `phone` (`phone`),
                                        KEY `email` (`email`),
                                        KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lhc_cloudtalkio_phone_number` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `dep_id` bigint(20) unsigned NOT NULL,
    `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
    `phone` varchar(100) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `lhc_lhesctcall_index` (
                                        `call_id` bigint(20) unsigned NOT NULL,
                                        `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
                                        `udate` bigint(20) unsigned NOT NULL DEFAULT 0,
                                        UNIQUE KEY `call_id` (`call_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
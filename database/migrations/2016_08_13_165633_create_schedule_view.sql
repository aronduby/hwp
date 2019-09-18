CREATE OR REPLACE VIEW `schedule` AS
  SELECT
    'tournament' AS `type`,
    `tournaments`.`id` AS `id`,
    `tournaments`.`site_id` AS `site_id`,
    `tournaments`.`season_id` AS `season_id`,
    `tournaments`.`location_id` AS `location_id`,
    `tournaments`.`team` AS `team`,
    CAST(`tournaments`.`start` AS DATETIME) AS `start`,
    CAST(`tournaments`.`end` AS DATETIME) AS `end`,
    NULL AS `district`,
    `tournaments`.`title` AS `opponent`,
    NULL AS `score_us`,
    NULL AS `score_them`,
    'App\\Models\\Tournament' AS `scheduled_type`,
    `tournaments`.`id` AS `scheduled_id`,
    NULL AS `album_id`,
    NULL AS `join_id`
  FROM
    `tournaments`
  UNION SELECT
      'game' AS `type`,
      `games`.`id` AS `id`,
      `games`.`site_id` AS `site_id`,
      `games`.`season_id` AS `season_id`,
      `games`.`location_id` AS `location_id`,
      `games`.`team` AS `team`,
      `games`.`start` AS `start`,
      `games`.`end` AS `end`,
      `games`.`district` AS `district`,
      `games`.`opponent` AS `opponent`,
      `games`.`score_us` AS `score_us`,
      `games`.`score_them` AS `score_them`,
      'App\\Models\\Game' AS `scheduled_type`,
      `games`.`id` AS `scheduled_id`,
      `games`.`album_id` AS `album_id`,
      `games`.`id` AS `join_id`
    FROM
      `games`
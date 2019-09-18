CREATE OR REPLACE VIEW `game_with_album_fallback` AS
    select `games`.`id`                                         AS `id`,
           `games`.`site_id`                                    AS `site_id`,
           `games`.`season_id`                                  AS `season_id`,
           `games`.`tournament_id`                              AS `tournament_id`,
           `games`.`location_id`                                AS `location_id`,
           ifnull(`games`.`album_id`, `tournaments`.`album_id`) AS `album_id`,
           `games`.`badge_id`                                   AS `badge_id`,
           `games`.`team`                                       AS `team`,
           `games`.`title_append`                               AS `title_append`,
           `games`.`start`                                      AS `start`,
           `games`.`end`                                        AS `end`,
           `games`.`district`                                   AS `district`,
           `games`.`opponent`                                   AS `opponent`,
           `games`.`score_us`                                   AS `score_us`,
           `games`.`score_them`                                 AS `score_them`,
           `games`.`created_at`                                 AS `created_at`,
           `games`.`updated_at`                                 AS `updated_at`
    from (`games`
             left join `tournaments` on ((`tournaments`.`id` = `games`.`tournament_id`)));

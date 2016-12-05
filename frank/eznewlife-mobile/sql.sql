print_r($post);

<pre>WP_Post Object
(
    [ID] => 108
    [post_author] => 2
    [post_date] => 2012-02-05 06:48:01
    [post_date_gmt] => 2012-02-04 22:48:01
    [post_content] => <img src="http://eznewlife.com/wp-content/uploads/2012/02/001243.jpg" alt="" title="001" width="600" height="509" class="alignnone size-full wp-image-1584" />

草泥馬,草枝擺,趕羚羊,都到齊了!!


    [post_title] => 高雄＠六合夜市大牛，針對陸客貼出公告
    [post_excerpt] => 
    [post_status] => publish
    [comment_status] => closed
    [ping_status] => open
    [post_password] => 
    [post_name] => %e9%ab%98%e9%9b%84%ef%bc%a0%e5%85%ad%e5%90%88%e5%a4%9c%e5%b8%82%e5%a4%a7%e7%89%9b%ef%bc%8c%e9%87%9d%e5%b0%8d%e9%99%b8%e5%ae%a2%e8%b2%bc%e5%87%ba%e5%85%ac%e5%91%8a
    [to_ping] => 
    [pinged] => 
    [post_modified] => 2013-03-11 02:36:59
    [post_modified_gmt] => 2013-03-10 18:36:59
    [post_content_filtered] => 
    [post_parent] => 0
    [guid] => http://eznewlife.com/?p=108
    [menu_order] => 0
    [post_type] => post
    [post_mime_type] => 
    [comment_count] => 0
    [filter] => raw
)
</pre>

找theme ------------------------------------------------------------------------------------------
INSERT INTO `wp_options` (`option_name`, `option_value`, `autoload`) VALUES ('_site_transient_timeout_theme_roots', '1362858343', 'yes') ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)
INSERT INTO `wp_options` (`option_name`, `option_value`, `autoload`) VALUES ('_site_transient_theme_roots', 'a:8:{s:8:\"arras1.5\";s:7:\"/themes\";s:17:\"carrington-mobile\";s:7:\"/themes\";s:16:\"eznewlife-mobile\";s:7:\"/themes\";s:12:\"jquerymobile\";s:7:\"/themes\";s:12:\"twentyeleven\";s:7:\"/themes\";s:9:\"twentyten\";s:7:\"/themes\";s:12:\"twentytwelve\";s:7:\"/themes\";s:11:\"wp_apptouch\";s:7:\"/themes\";}', 'yes') ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)

到 themes 之前的sql------------------------------------------------------------------------------------------
SELECT option_name, option_value FROM wp_options WHERE autoload = 'yes'
SELECT option_value FROM wp_options WHERE option_name = 'uninstall_plugins' LIMIT 1
SELECT * FROM wp_users WHERE user_login = 'Frank'
SELECT user_id, meta_key, meta_value FROM wp_usermeta WHERE user_id IN (1)
SELECT option_value FROM wp_options WHERE option_name = 'widget_pages' LIMIT 1
SELECT option_value FROM wp_options WHERE option_name = 'widget_calendar' LIMIT 1
SELECT option_value FROM wp_options WHERE option_name = 'widget_tag_cloud' LIMIT 1
SELECT option_value FROM wp_options WHERE option_name = 'widget_nav_menu' LIMIT 1
SELECT option_value FROM wp_options WHERE option_name = 'rewrite_rules' LIMIT 1
SELECT SQL_CALC_FOUND_ROWS wp_posts.ID FROM wp_posts WHERE 1=1 AND wp_posts.post_type = 'post' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'private') ORDER BY wp_posts.post_date DESC LIMIT 0, 10
SELECT FOUND_ROWS()
SELECT wp_posts.* FROM wp_posts WHERE ID IN (108,105,103,101,98,94,91,85,82,77)
SELECT t.*, tt.*, tr.object_id FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON tt.term_id = t.term_id INNER JOIN wp_term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ('category', 'post_tag', 'post_format') AND tr.object_id IN (77, 82, 85, 91, 94, 98, 101, 103, 105, 108) ORDER BY t.name ASC
SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE post_id IN (77,82,85,91,94,98,101,103,105,108)
沒登入的版本 ------------------------------------------------------------------------------------------
SELECT option_name, option_value FROM wp_options WHERE autoload = 'yes'
SELECT option_value FROM wp_options WHERE option_name = 'uninstall_plugins' LIMIT 1
SELECT option_value FROM wp_options WHERE option_name = 'widget_pages' LIMIT 1
SELECT option_value FROM wp_options WHERE option_name = 'widget_calendar' LIMIT 1
SELECT option_value FROM wp_options WHERE option_name = 'widget_tag_cloud' LIMIT 1
SELECT option_value FROM wp_options WHERE option_name = 'widget_nav_menu' LIMIT 1
SELECT option_value FROM wp_options WHERE option_name = 'rewrite_rules' LIMIT 1
SELECT SQL_CALC_FOUND_ROWS wp_posts.ID FROM wp_posts WHERE 1=1 AND wp_posts.post_type = 'post' AND (wp_posts.post_status = 'publish') ORDER BY wp_posts.post_date DESC LIMIT 0, 10
SELECT FOUND_ROWS()
SELECT wp_posts.* FROM wp_posts WHERE ID IN (108,105,103,101,98,94,91,85,82,77)
SELECT t.*, tt.*, tr.object_id FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON tt.term_id = t.term_id INNER JOIN wp_term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ('category', 'post_tag', 'post_format') AND tr.object_id IN (77, 82, 85, 91, 94, 98, 101, 103, 105, 108) ORDER BY t.name ASC
SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE post_id IN (77,82,85,91,94,98,101,103,105,108)
have_posts() ------------------------------------------------------------------------------------------

the_post() ------------------------------------------------------------------------------------------
SELECT * FROM wp_users WHERE ID = '2'

分頁 ?cat=4&paged=2 ------------------------------------------------------------------------------------------
SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE taxonomy = 'category' AND term_id IN (4,87,88) 
SELECT t.*, tt.* FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'category' AND t.term_id = 4 LIMIT 1
SELECT SQL_CALC_FOUND_ROWS wp_posts.ID FROM wp_posts INNER JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) WHERE 1=1 AND ( wp_term_relationships.term_taxonomy_id IN (4,91,92) ) AND wp_posts.post_type = 'post' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'private') GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC LIMIT 10, 10
SELECT FOUND_ROWS()
SELECT wp_posts.* FROM wp_posts WHERE ID IN (48027,48156,47892,48174,47852,47693,47888,48101,47747,47844)
SELECT t.*, tt.*, tr.object_id FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON tt.term_id = t.term_id INNER JOIN wp_term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ('category', 'post_tag', 'post_format') AND tr.object_id IN (47693, 47747, 47844, 47852, 47888, 47892, 48027, 48101, 48156, 48174) ORDER BY t.name ASC
SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE post_id IN (47693,47747,47844,47852,47888,47892,48027,48101,48156,48174)
SELECT * FROM wp_posts WHERE ID = 48029 LIMIT 1
SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE post_id IN (48029)

分頁 ?cat=4&paged=3 ------------------------------------------------------------------------------------------
SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE taxonomy = 'category' AND term_id IN (4,87,88) 
SELECT t.*, tt.* FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'category' AND t.term_id = 4 LIMIT 1
SELECT SQL_CALC_FOUND_ROWS wp_posts.ID FROM wp_posts INNER JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) WHERE 1=1 AND ( wp_term_relationships.term_taxonomy_id IN (4,91,92) ) AND wp_posts.post_type = 'post' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'private') GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC LIMIT 20, 10
SELECT FOUND_ROWS()
SELECT wp_posts.* FROM wp_posts WHERE ID IN (48088,48005,47884,47912,47823,47431,47295,48083,47706,47384)
SELECT t.*, tt.*, tr.object_id FROM wp_terms AS t INNER JOIN wp_term_taxonomy AS tt ON tt.term_id = t.term_id INNER JOIN wp_term_relationships AS tr ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ('category', 'post_tag', 'post_format') AND tr.object_id IN (47295, 47384, 47431, 47706, 47823, 47884, 47912, 48005, 48083, 48088) ORDER BY t.name ASC
SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE post_id IN (47295,47384,47431,47706,47823,47884,47912,48005,48083,48088)
SELECT * FROM wp_posts WHERE ID = 48090 LIMIT 1
SELECT post_id, meta_key, meta_value FROM wp_postmeta WHERE post_id IN (48090)


分類 跟 沒分的差別
分類
SELECT SQL_CALC_FOUND_ROWS wp_posts.ID FROM wp_posts INNER JOIN wp_term_relationships ON (wp_posts.ID = wp_term_relationships.object_id) 
WHERE 1=1 
AND ( wp_term_relationships.term_taxonomy_id IN (4,91,92) ) 
AND wp_posts.post_type = 'post' 
AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'private') 
GROUP BY wp_posts.ID ORDER BY wp_posts.post_date DESC LIMIT 20, 10

沒分
SELECT SQL_CALC_FOUND_ROWS wp_posts.ID FROM wp_posts
WHERE 1=1 
AND wp_posts.post_type = 'post' 
AND (wp_posts.post_status = 'publish' 
OR wp_posts.post_status = 'private') 
ORDER BY wp_posts.post_date DESC LIMIT 0, 10
<?php

/*----------------------------------------------------*/
// Database
/*----------------------------------------------------*/
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
$table_prefix = getenv('DB_PREFIX') ? getenv('DB_PREFIX') : 'wp_';

/*----------------------------------------------------*/
// Authentication unique keys and salts
/*----------------------------------------------------*/
/**
 * @link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service 
 */
define('AUTH_KEY',         '_z1%!F1c=3G+|H;f 1WP=P-i~an|2$`=:^-djd/Z7F0(%y|}C6pX]{65J03J++ID');
define('SECURE_AUTH_KEY',  '[WB|;t#ncYI,A!KAi0X pY|-,t/ST.6R+c~RWld{3w5?.KR:]6w[]nfQd>0cYW0!');
define('LOGGED_IN_KEY',    'NOVN7[Jmg`LYE)z~Zh%?reC |/HO;6&4iIh7;4v4<mLw^3  sJiO+i/0rWieGZhg');
define('NONCE_KEY',        '9)N tPA^.[j(IZBKlP`vc$1~6H<ean4O#d74V&s?])%(&sKbm>.#}G7HZom2Cm:W');
define('AUTH_SALT',        '|+fJoBgPfRkgG)pp43>)*iq47F2KrHw[)3cr|loNvH+d+AjfTczw|CMC|emh< er');
define('SECURE_AUTH_SALT', '~{`O2kjgWxIG@~pYG_;[Wf%[$ZhpATDG48&%#.jCv.9K;{UL3s|n|y$=]Vn0;W8I');
define('LOGGED_IN_SALT',   '#*mF04M)XQltZU2W#i1r`-]0oueoicZx(?zv$0pg.~sC~b$87]ZLizo!++e|MF`+');
define('NONCE_SALT',       'e!VpuuVyi|Tjc^E@naGda,@;>x_FH#HVRdL}4(}*3,kt%qg.;:kB8V/-;Xk2i-LO');

/*----------------------------------------------------*/
// Custom settings
/*----------------------------------------------------*/
define('WP_AUTO_UPDATE_CORE', false);
define('DISALLOW_FILE_EDIT', true);

/* That's all, stop editing! Happy blogging. */

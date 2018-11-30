<?php
# MantisBT - A PHP based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

/**
 * My View Page
 *
 * @package MantisBT
 * @copyright Copyright 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
 * @copyright Copyright 2002  MantisBT Team - mantisbt-dev@lists.sourceforge.net
 * @link http://www.mantisbt.org
 *
 * @uses core.php
 * @uses access_api.php
 * @uses authentication_api.php
 * @uses category_api.php
 * @uses compress_api.php
 * @uses config_api.php
 * @uses constant_inc.php
 * @uses current_user_api.php
 * @uses gpc_api.php
 * @uses helper_api.php
 * @uses html_api.php
 * @uses lang_api.php
 * @uses print_api.php
 * @uses user_api.php
 */
$myCSP =  "Content-Security-Policy:";
/*
      default-src 'self' *.irgups.ru; 
        frame-src 'self' *.irgups.ru;
    unsafe-inline 'self' *.irgups.ru;
sandbox allow-forms allow-same-origin";

*/
require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'authentication_api.php' );
require_api( 'category_api.php' );
require_api( 'compress_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'user_api.php' );
require_api( 'layout_api.php' );
require_css( 'status_config.php' );

//auth_ensure_user_authenticated();
$t_current_user_id = 1; //auth_get_current_user_id();
$g_cache_current_user_id = 1;

# Improve performance by caching category data in one pass

compress_enable();

# don't index my view page
html_robots_noindex();

$t_project_id = -1;

$query = "SELECT p.id projectId,p.name projectName,
b.id bugId, b.summary bugTitle, 
n.id noteId, t.note noteText, n.date_submitted
FROM {bugnote} n
JOIN {bugnote_text} t ON n.bugnote_text_id=t.id
JOIN {bug} b ON n.bug_id=b.id
JOIN {project} p ON b.project_id=p.id
WHERE
FROM_UNIXTIME(n.date_submitted) > DATE_SUB(NOW(),INTERVAL 7 DAY)
AND
(t.note LIKE '%@news%' OR t.note LIKE '%@новости%')
ORDER BY n.date_submitted";
$result = db_query($query,array());
$records = array();
while(($rec = $result->FetchRow()) != false){
   $records[] = $rec;
}
print json_encode($records);

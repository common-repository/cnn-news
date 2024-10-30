<?php
/*
Plugin Name: CNN News
Description: Displays a selectable CNN News RSS feed, inline, widget or in theme.
Version:     2.6
Author:      Olav Kolbu
Author URI:  http://www.kolbu.com/
Plugin URI:  http://wordpress.org/extend/plugins/cnn-news/
License:     GPL

Minor parts of WordPress-specific code from various other GPL plugins.

TODO: Multiple widget instances support (possibly)
      Internationalize more output
      See if nofollow can/should be added on links
*/
/*
Copyright (C) 2008 kolbu.com (olav AT kolbu DOT com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

include_once(ABSPATH . WPINC . '/rss.php');

global $cnn_news_instance;

if ( ! class_exists('cnn_news_plugin')) {
    class cnn_news_plugin {

        // So we don't have to query database on every replacement
        var $settings;


        var $cnnfeeds = array(
           "Africa [INTL]" => "edition_africa.rss",
           "All Stories [MONEY]" => "money_latest.rss",
           "Apple 2.0 [MONEY]" => "http://rss.cnn.com/fortuneapple20",
           "Americas [INTL]" => "edition_americas.rss",
           "Asia [INTL]" => "edition_asia.rss",
           "Ask Annie [MONEY]" => "http://rss.cnn.com/fortuneaskannieblog",
           "Ask The Expert [MONEY]" => "http://rss.cnn.com/moneyasktheexpert",
           "Autos [MONEY]" => "money_autos.rss",
           "Bonds [MONEY]" => "money_markets_bondcenter.rss",
           "Business 360 [INTL]" => "edition_business360.rss",
           "C-Suite [MONEY]" => "http://rss.cnn.com/fortunecsuiteblog",
           "CNN.com Behind the Scenes Blog" => "cnn_behindthescenes.rss",
           "Companies [MONEY]" => "money_news_companies.rss",
           "College [MONEY]" => "money_pf_college.rss",
           "Connect The World [INTL]" => "edition_connecttheworld.rss",
           "Crime" => "cnn_crime.rss",
           "Do The Right Thing [MONEY]" => "http://rss.cnn.com/moneyethics",
           "Economy [MONEY]" => "money_news_economy.rss",
           "Entertainment" => "cnn_showbiz.rss",
           "Entertainment [INTL]" => "edition_entertainment.rss",
           "Europe [INTL]" => "edition_europe.rss",
           "Fantasy [SI]" => "si_fantasy.rss",
           "Football [INTL]" => "edition_football.rss",
           "FORTUNE [MONEY]" => "magazines_fortune.rss",
           "FORTUNE Small Business [MONEY]" => "magazines_fsb.rss",
           "FORTUNE International [MONEY]" => "magazines_fortuneintl.rss",
           "Fortune Finance [MONEY]" => "http://rss.cnn.com/fortunefinance.rss",
           "Fortune Tech [MONEY]" => "http://rss.cnn.com/fortunetech.rss",
           "Funds [MONEY]" => "money_funds.rss",
           "Golf [INTL]" => "edition_golf.rss",
           "Golf [SI]" => "http://feeds.feedburner.com/GolfToursNews",
           "Google 24/7 [MONEY]" => "http://rss.cnn.com/fortunegoogle247",
           "Health" => "cnn_health.rss",
           "High School [SI]" => "si_highschool.rss",
           "In the Field [INTL]" => "edition_inthefield.rss",
           "Inside the Middle East [INTL]" => "edition_ime.rss",
           "Insurance [MONEY]" => "money_pf_insurance.rss",
           "International [MONEY]" => "money_news_international.rss",
           "International Desk [INTL]" => "edition_idesk.rss",
           "iReports on CNN" => "http://rss.ireport.com/feeds/oncnn.rss",
           "Lifestyle [MONEY]" => "money_lifestyle.rss",
           "Living" => "cnn_living.rss",
           "Management and Career [MONEY]" => "http://rss.cnn.com/fortunemanagement",
           "Markets [MONEY]" => "money_markets.rss",
           "Middle East [INTL]" => "edition_meast.rss",
           "MLB [SI]" => "si_mlb.rss",
           "MMA [SI]" => "si_mma.rss",
           "Money Helps [MONEY]" => "http://rss.cnn.com/moneyhelps",
           "Money Magazine [MONEY]" => "magazines_moneymag.rss",
           "More Sports [SI]" => "si_more.rss",
           "Most Popular" => "cnn_mostpopular.rss",
           "Most Popular [MONEY]" => "money_mostpopular.rss",
           "Most Popular [SI]" => "si_mostpopular.rss",
           "Most Recent" => "cnn_latest.rss",
           "Motorsport [INTL]" => "edition_motorsport.rss",
           "NBA [SI]" => "si_nba.rss",
           "NCAAB [SI]" => "si_ncaab.rss",
           "NCAAF [SI]" => "si_ncaaf.rss",
           "NFL [SI]" => "si_nfl.rss",
           "NHL [SI]" => "si_hockey.rss",
           "Personal Finance [MONEY]" => "money_pf.rss",
           "Personal Tech [MONEY]" => "money_technology_personaltech.rss",
           "Politics" => "cnn_allpolitics.rss",
           "Postcards [MONEY]" => "http://rss.cnn.com/fortunepostcards",
           "Prism [INTL]" => "edition_prismblog.rss",
           "Quest Means Business [INTL]" => "edition_questmeansbusiness.rss",
           "Real Estate [MONEY]" => "money_realestate.rss",
           "Racing [SI]" => "si_motorsports.rss",
           "Retirement [MONEY]" => "money_retirement.rss",
           "Science & Space [INTL]" => "edition_space.rss",
           "Small Business [MONEY]" => "money_smbusiness.rss",
           "SI Writers [SI]" => "si_writers.rss",
           "Soccer [SI]" => "si_soccer.rss",
           "Sports (SI.com)" => "si_topstories.rss",
           "SportsBiz [MONEY]" => "money_commentary_columnsportsbiz.rss",
           "Street Sweep [MONEY]" => "http://rss.cnn.com/fortunestreetsweep",
           "Student News" => "cnn_studentnews.rss",
           "Taxes [MONEY]" => "money_pf_taxes.rss",
           "Technology" => "cnn_tech.rss",
           "Technology [INTL]" => "edition_technology.rss",
           "Technology [MONEY]" => "money_technology.rss",
           "Tennis [INTL]" => "edition_tennis.rss",
           "Tennis [SI]" => "si_tennis.rss",
           "Term Sheet [MONEY]" => "http://rss.cnn.com/fortunetermsheet",
           "The Buzz [MONEY]" => "http://rss.cnn.com/cnnmoneymorningbuzz",
           "The Mole [MONEY]" => "http://rss.cnn.com/AskTheMole",
           "The Screening Room [INTL]" => "edition_screeningroom.rss",
           "The Wheel Deal [MONEY]" => "http://rss.cnn.com/fortunewheeldeal",
           "Top Stories" => "cnn_topstories.rss",
           "Top Stories [INTL]" => "edition.rss",
           "Top Stories [SI]" => "si_topstories.rss",
           "Top Tips [MONEY]" => "money_pf_saving.rss",
           "Travel" => "cnn_travel.rss",
           "Travel [INTL]" => "edition_travel.rss",
           "U.S." => "cnn_us.rss",
           "U.S. [INTL]" => "edition_us.rss",
           "Video" => "cnn_freevideo.rss",
           "Video [MONEY]" => "money_video_business.rss",
           "World" => "cnn_world.rss",
           "World [INTL]" => "edition_world.rss",
           "World Business [INTL]" => "edition_business.rss",
           "World Sport [INTL]" => "edition_sport.rss",
        );

        var $desctypes = array(
            'Short' => '',
            'Long' => 'l',
        );

        // Constructor
        function cnn_news_plugin() {

            // Form POSTs dealt with elsewhere
            if ( is_array($_POST) ) {
                if ( $_POST['cnn_news-widget-submit'] ) {
                    $tmp = $_POST['cnn_news-widget-feed'];
                    $alloptions = get_option('cnn_news');
                    if ( $alloptions['widget-1'] != $tmp ) {
                        if ( $tmp == '*DEFAULT*' ) {
                            $alloptions['widget-1'] = '';
                        } else {
                            $alloptions['widget-1'] = $tmp;
                        }
                        update_option('cnn_news', $alloptions);
                    }
                } else if ( $_POST['cnn_news-options-submit'] ) {
                    // noop
                } else if ( $_POST['cnn_news-submit'] ) {
                    // noop
                }
            }

	    add_filter('the_content', array(&$this, 'insert_news')); 
            add_action('admin_menu', array(&$this, 'admin_menu'));
            add_action('plugins_loaded', array(&$this, 'widget_init'));

            // Hook for theme coders/hackers
            add_action('cnn_news', array(&$this, 'display_feed'));

            // Makes it backwards compat pre-2.5 I hope
            if ( function_exists('add_shortcode') ) {
                add_shortcode('cnn-news', array(&$this, 'my_shortcode_handler'));
             }

        }

        // *************** Admin interface ******************

        // Callback for admin menu
        function admin_menu() {
            add_options_page('CNN News Options', 'CNN News',
                             'administrator', __FILE__, 
                              array(&$this, 'plugin_options'));
            add_management_page('CNN News', 'CNN News', 
                                'administrator', __FILE__,
                                array(&$this, 'admin_manage'));
               
        }

        // Settings -> CNN News
        function plugin_options() {

           if (get_bloginfo('version') >= '2.7') {
               $manage_page = 'tools.php';
            } else {
               $manage_page = 'edit.php';
            }
            print <<<EOT
            <div class="wrap">
            <h2>CNN News</h2>
            <p>This plugin allows you to define a number of CNN News 
               feeds and have them displayed anywhere in content, in a widget
               or in a theme. Any number of inline replacements or theme
               inserts can be made, but only one widget instance is
               permitted in this release. To use the feeds insert one or more
               of the following special html comments or Shortcodes 
               anywhere in user content. Note that Shortcodes, i.e. the
               ones using square brackets, are only available in 
               WordPress 2.5 and above.<p>
               <ul><li><b>&lt;--cnn-news--&gt</b> (for default feed)</li>
               <li><b>&lt;--cnn-news#feedname--&gt</b></li>
               <li><b>[cnn-news]</b> (also for default feed)</li>
               <li><b>[cnn-news name="feedname"]</b></li></ul><p>
               To insert in a theme call <b>do_action('cnn_news');</b> or 
               alternatively <b>do_action('cnn_news', 'feedname');</b><p>
               To manage feeds, go to <a href="$manage_page?page=cnn-news/cnn_news.php">Manage -> CNN News</a>, where you will also find more information.<p>
               <a href="http://www.kolbu.com/donations/">Donations Page</a>... ;-)<p>
               <a href="http://www.kolbu.com/2008/04/03/cnn-news-plugin/">Widget Home Page</a>, leave a comment if you have questions etc.<p>
               <a href="http://edition.cnn.com/services/rss/index.html">CNN Terms Of Use</a><p>
EOT;
        }

        // Manage -> CNN News
        function admin_manage() {
            // Edit/delete links
            $mode = trim($_GET['mode']);
            $id = trim($_GET['id']);

            $this->upgrade_options();

            $alloptions = get_option('cnn_news');

            $flipcnnfeeds     = array_flip($this->cnnfeeds);
            $flipdesctypes   = array_flip($this->desctypes);

            if ( is_array($_POST) && $_POST['cnn_news-submit'] ) {

                $newoptions = array();
                $id                       = $_POST['cnn_news-id'];

                $newoptions['name']       = $_POST['cnn_news-name'];
                $newoptions['title']      = $_POST['cnn_news-title'];
                $newoptions['feedurl']     = $_POST['cnn_news-feedurl'];
                $newoptions['numnews']    = $_POST['cnn_news-numnews'];
                $newoptions['desctype']    = $_POST['cnn_news-desctype'];
                $newoptions['feedtype']   = $flipcnnfeeds[$newoptions['feedurl']];

                if ( $alloptions['feeds'][$id] == $newoptions ) {
                    $text = 'No change...';
                    $mode = 'main';
                } else {
                    $alloptions['feeds'][$id] = $newoptions;
                    update_option('cnn_news', $alloptions);
 
                    $mode = 'save';
                }
            } else if ( is_array($_POST) && $_POST['cnn_news-options-cachetime-submit'] ) {
                if ( $_POST['cnn_news-options-cachetime'] != $alloptions['cachetime'] ) {
                    $alloptions['cachetime'] = $_POST['cnn_news-options-cachetime'];
                    update_option('cnn_news', $alloptions);
                    $text = "Cache time changed to {$alloptions[cachetime]} seconds.";
                } else {
                    $text = "No change in cache time...";
                }
                $mode = 'main';
            }

            if ( $mode == 'newfeed' ) {
                $newfeed = 0;
                foreach ($alloptions['feeds'] as $k => $v) {
                    if ( $k > $newfeed ) {
                        $newfeed = $k;
                    }
                }
                $newfeed += 1;

                $text = "Please configure new feed and press Save.";
                $mode = 'main';
            }

            if ( $mode == 'save' ) {
                $text = "Saved feed {$alloptions[feeds][$id][name]} [$id].";
                $mode = 'main';
            }

            if ( $mode == 'edit' ) {
                if ( ! empty($text) ) {
                     echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>';
                }
                $text = "Editing feed {$alloptions[feeds][$id][name]} [$id].";

                $edit_id = $id;
                $mode = 'main';
            }

            if ( $mode == 'delete' ) {

                $text = "Deleted feed {$alloptions[feeds][$id][name]} [$id].";
                
                unset($alloptions['feeds'][$id]);

                update_option('cnn_news', $alloptions);
 
                $mode = 'main';
            }

            // main
            if ( empty($mode) or ($mode == 'main') ) {

                if ( ! empty($text) ) {
                     echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$text.'</p></div>';
                }
                print '<div class="wrap">';
                print ' <h2>';
                print _e('Manage CNN News Feeds','cnn_news');
                print '</h2>';
                print ' <table id="the-list-x" width="100%" cellspacing="3" cellpadding="3">';
                print '  <thead>';
                print '   <tr>';
                print '    <th scope="col">';
                print _e('Key','cnn_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Name','cnn_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Admin-defined title','cnn_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Feed','cnn_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Item length','cnn_news');
                print '</th>';
                print '    <th scope="col">';
                print _e('Max items','cnn_news');
                print '</th>';
                print '    <th scope="col" colspan="3">';
                print _e('Action','cnn_news');
                print '</th>';
                print '   </tr>';
                print '  </thead>';

                if (get_bloginfo('version') >= '2.7') {
                    $manage_page = 'tools.php';
                } else {
                    $manage_page = 'edit.php';
                }

                if ( $alloptions['feeds'] || $newfeed ) {
                    $i = 0;

                    foreach ($alloptions['feeds'] as $key => $val) {
                        if ( $i % 2 == 0 ) {
                            print '<tr class="alternate">';
                        } else {
                            print '<tr>';
                        }
                        if ( isset($edit_id) && $edit_id == $key ) {
                            print "<form name=\"cnn_news_options\" action=\"".
                                  htmlspecialchars($_SERVER['REQUEST_URI']).
                                  "\" method=\"post\" id=\"cnn_news_options\">";
                                    
                            print "<th scope=\"row\">".$key."</th>";
                            print '<td><input size="10" maxlength="20" id="cnn_news-name" name="cnn_news-name" type="text" value="'.$val['name'].'" /></td>';
                            print '<td><input size="20" maxlength="20" id="cnn_news-title" name="cnn_news-title" type="text" value="'.$val['title'].'" /></td>';
                            print '<td><select name="cnn_news-feedurl">';
                            $feedurl = $val['feedurl'];
                            foreach ($this->cnnfeeds as $k => $v) {
                                print '<option '.(strcmp($v,$feedurl)?'':'selected').' value="'.$v.'" >'.$k.'</option>';
                            }
                            print '</select></td>';
                            print '<td><select name="cnn_news-desctype">';
                            $desctype = $val['desctype'];
                            foreach ($this->desctypes as $k => $v) {
                                print '<option '.(strcmp($v,$desctype)?'':'selected').' value="'.$v.'" >'.$k.'</option>';
                            }
                            print '</select></td>';
                            print '<td><input size="3" maxlength="3" id="cnn_news-numnews" name="cnn_news-numnews" type="text" value="'.$val['numnews'].'" /></td>';
                            print '<td><input type="submit" value="Save  &raquo;">';
                            print "</td>";
                            print "<input type=\"hidden\" id=\"cnn_news-id\" name=\"cnn_news-id\" value=\"$edit_id\" />";
                            print "<input type=\"hidden\" id=\"cnn_news-submit\" name=\"cnn_news-submit\" value=\"1\" />";
                            print "</form>";
                        } else {
                            print "<th scope=\"row\">".$key."</th>";
                            print "<td>".$val['name']."</td>";
                            print "<td>".$val['title']."</td>";
                            print "<td>".$flipcnnfeeds[$val['feedurl']]."</td>";
                            print "<td>".$flipdesctypes[$val['desctype']]."</td>";
                            print "<td>".$val['numnews']."</td>";
                            print "<td><a href=\"$manage_page?page=cnn-news/cnn_news.php&amp;mode=edit&amp;id=$key\" class=\"edit\">";
                            print __('Edit','cnn_news');
                            print "</a></td>\n";
                            print "<td><a href=\"$manage_page?page=cnn-news/cnn_news.php&amp;mode=delete&amp;id=$key\" class=\"delete\" onclick=\"javascript:check=confirm( '".__("This feed entry will be erased. Delete?",'cnn_news')."');if(check==false) return false;\">";
                            print __('Delete', 'cnn_news');
                            print "</a></td>\n";
                        }
                        print '</tr>';

                        $i++;
                    }
                    if ( $newfeed ) {

                        print "<form name=\"cnn_news_options\" action=\"".
                              htmlspecialchars($_SERVER['REQUEST_URI']).
                              "\" method=\"post\" id=\"cnn_news_options\">";
                                
                        print "<th scope=\"row\">".$newfeed."</th>";
                        print '<td><input size="10" maxlength="20" id="cnn_news-name" name="cnn_news-name" type="text" value="NEW" /></td>';
                        print '<td><input size="20" maxlength="20" id="cnn_news-title" name="cnn_news-title" type="text" value="" /></td>';
                        print '<td><select name="cnn_news-feedurl">';
                        $feedurl = 'cnn_topstories.rss';
                        foreach ($this->cnnfeeds as $k => $v) {
                            print '<option '.(strcmp($v,$feedurl)?'':'selected').' value="'.$v.'" >'.$k.'</option>';
                        }
                        print '</select></td>';
                        print '</select></td>';
                        print '<td><select name="cnn_news-desctype">';
                        foreach ($this->desctypes as $k => $v) {
                            print '<option value="'.$v.'" >'.$k.'</option>';
                        }
                        print '</select></td>';
                        print '<td><input size="3" maxlength="3" id="cnn_news-numnews" name="cnn_news-numnews" type="text" value="5" /></td>';
                        print '<td><input type="submit" value="Save  &raquo;">';
                        print "</td>";
                        print "<input type=\"hidden\" id=\"cnn_news-id\" name=\"cnn_news-id\" value=\"$newfeed\" />";
                        print "<input type=\"hidden\" id=\"cnn_news-newfeed\" name=\"cnn_news-newfeed\" value=\"1\" />";
                        print "<input type=\"hidden\" id=\"cnn_news-submit\" name=\"cnn_news-submit\" value=\"1\" />";
                        print "</form>";
                    } else {
                        print "</tr><tr><td colspan=\"12\"><a href=\"$manage_page?page=cnn-news/cnn_news.php&amp;mode=newfeed\" class=\"newfeed\">";
                        print __('Add extra feed','cnn_news');
                        print "</a></td></tr>";

                    }
                } else {
                    print '<tr><td colspan="12" align="center"><b>';
                    print __('No feeds found(!)','cnn_news');
                    print '</b></td></tr>';
                    print "</tr><tr><td colspan=\"12\"><a href=\"$manage_page?page=cnn-news/cnn_news.php&amp;mode=newfeed\" class=\"newfeed\">";
                    print __('Add feed','cnn_news');
                    print "</a></td></tr>";
                }
                print ' </table>';
                print '<h2>';
                print _e('Global configuration parameters','cnn_news');
                print '</h2>';
                print ' <form method="post">';
                print ' <table id="the-cachetime" cellspacing="3" cellpadding="3">';
                print '<tr><td><b>Cache time:</b></td>';
                print '<td><input size="6" maxlength="6" id="cnn_news-options-cachetime" name="cnn_news-options-cachetime" type="text" value="'.$alloptions['cachetime'].'" /> seconds</td>';
                print '<input type="hidden" id="cnn_news-options-cachetime-submit" name="cnn_news-options-cachetime-submit" value="1" />';
                print '<td><input type="submit" value="Save  &raquo;"></td></tr>';
                print ' </table>';
                print '</form>'; 

                print '<h2>';
                print _e('Information','cnn_news');
                print '</h2>';
                print ' <table id="the-list-x" width="100%" cellspacing="3" cellpadding="3">';
                print '<tr><td><b>Key</b></td><td>Unique identifier used internally.</td></tr>';
                print '<tr><td><b>Name</b></td><td>Optional name to be able to reference a specific feed as e.g. ';
                print ' <b>&lt;!--cnn-news#myname--&gt;</b>. ';
                print ' If more than one feed shares the same name, a random among these will be picked each time. ';
                print ' The one(s) without a name will be treated as the default feed(s), i.e. used for <b>&lt;!--cnn-news--&gt;</b> ';
                print ' or widget feed type <b>*DEFAULT*</b>. If you have Wordpress 2.5 ';
                print ' or above, you can also use Shortcodes on the form <b>[cnn-news]</b> ';
                print ' (for default feed) or <b>[cnn-news name="feedname"]</b>. And finally ';
                print ' you can use <b>do_action(\'cnn_news\');</b> or <b>do_action(\'cnn_news\', \'feedname\');</b> ';
                print ' in themes.</td></tr>';
                print '<tr><td><b>Admin-defined title</b></td><td>Optional feed title. If not set, a reasonable title based on ';
                print 'Region and Type will be used. Note CNN Terms of Service require you to show that the feeds come from ';
                print 'CNN News.</td></tr>';
                print '<tr><td><b>Feed</b></td><td>The actual feed to use.</td></tr>';
                print '<tr><td><b>Max items</b></td><td>Maximum number of news items to show for this feed. If the feed contains ';
                print 'less than the requested items, only the number of items in the feed will obviously be displayed.</td></tr>';
                print '<tr><td><b>Cache time</b></td><td>Minimum number of seconds that WordPress should cache a CNN News feed before fetching it again.</td></tr>';
                print ' </table>';
                print '</div>';
            }
        }

        // ************* Output *****************

        // The function that gets called from themes
        function display_feed($data) {
            global $settings;
            $settings   = get_option('cnn_news');
            print $this->random_feed($data);
            unset($settings);
        }

        // Callback for inline replacement
        function insert_news($data) {
            global $settings;

            // Allow for multi-feed sites
            $tag = '/<!--cnn-news(|#.*?)-->/';

            // We may have old style options
            $this->upgrade_options();

            // Avoid getting this for each callback
            $settings   = get_option('cnn_news');

            $result = preg_replace_callback($tag, 
                              array(&$this, 'inline_replace_callback'), $data);

            unset($settings);

            return $result;
        }


        // *********** Widget support **************
        function widget_init() {

            // Check for the required plugin functions. This will prevent fatal
            // errors occurring when you deactivate the dynamic-sidebar plugin.
            if ( !function_exists('register_sidebar_widget') )
                return;

            register_widget_control('CNN News', 
                                   array(&$this, 'widget_control'), 200, 100);

            // wp_* has more features, presumably fixed at a later date
            register_sidebar_widget('CNN News',
                                   array(&$this, 'widget_output'));

        }

        function widget_control() {

            // We may have old style options
            $this->upgrade_options();

            $alloptions = get_option('cnn_news');
            $thisfeed = $alloptions['widget-1'];

            print '<p><label for="cnn_news-feed">Select feed:</label>';
            print '<select style="vertical-align:middle;" name="cnn_news-widget-feed">';

            $allfeeds = array();
            foreach ($alloptions['feeds'] as $k => $v) {
                $allfeeds[strlen($v['name'])?$v['name']:'*DEFAULT*'] = 1;
            } 
            foreach ($allfeeds as $k => $v) {
                print '<option '.($k==$thisfeed?'':'selected').' value="'.$k.'" >'.$k.'</option>';
            }
            print '</select><p>';
            print '<input type="hidden" id="cnn_news-widget-submit" name="cnn_news-widget-submit" value="1" />';


        }

        // Called every time we want to display ourselves as a sidebar widget
        function widget_output($args) {
            extract($args); // Gives us $before_ and $after_ I presume
                        
            // We may have old style options
            $this->upgrade_options();

            $alloptions = get_option('cnn_news');
            $matching_feeds = array();
            foreach ($alloptions['feeds'] as $k => $v) {
                if ( (string)$v['name'] == $alloptions['widget-1'] ) { 
                    $matching_feeds[] = $k;
                } 
            }
            if ( ! count($matching_feeds) ) {
                if ( ! strlen($alloptions['widget-1']) ) {
                    $content = '<ul><b>No default feed available</b></ul>';
                } else {
                    $content = "<ul>Unknown feed name <b>{$alloptions[widget-1]}</b> used</ul>";
                }
                echo $before_widget;
                echo $before_title . __('CNN News<br>Error','cnn_news') . $after_title . '<div>';
                echo $content;
                echo '</div>' . $after_widget;
                return;
            }
            $feed_id = $matching_feeds[rand(0, count($matching_feeds)-1)];
            $options = $alloptions['feeds'][$feed_id];

            $feedtype   = $options['feedtype'];
            $cachetime  = $alloptions['cachetime'];

            if ( strlen($options['title']) ) {
                $title = $options['title'];
            } else {
                $title = 'CNN News<br>'.$feedtype;
            }

            echo $before_widget;
            echo $before_title . $title . $after_title . '<div>';
            echo $this->get_feed($options, $cachetime);
            echo '</div>' . $after_widget;
        }

        // ************** The actual work ****************
        function get_feed(&$options, $cachetime) {

            if ( ! isset($options['feedurl']) ) {
                return 'Options not set, visit plugin configuation screen.'; 
            }

            $feedurl    = $options['feedurl'] ? $options['feedurl'] : 'cnn_topstories.rss';
            $numnews    = $options['numnews'] ? $options['numnews'] : 5;
            $desctype   = $options['desctype'];

            $result = '<ul>';

	    // Support non-rss.cnn.com urls
            if ( substr($feedurl, 0, 5) != "http:" ) {
                $rssurl = 'http://rss.cnn.com/rss/'.$feedurl;
            } else {
	    	$rssurl = $feedurl;
	    }

            // Using the WP RSS fetcher (MagpieRSS). It has serious
            // GC problems though.
            define('MAGPIE_CACHE_AGE', $cachetime);
            define('MAGPIE_CACHE_ON', 1);
            define('MAGPIE_DEBUG', 1);

            $rss = fetch_rss($rssurl);

            if ( ! is_object($rss) ) {
                return 'CNN News unavailable</ul>';
            }

            $rss->items = array_slice($rss->items, 0, $numnews);
            foreach ( $rss->items as $item ) {
                $description = $this->html_decode($item['description']);

                // Bunch of useless links after first <p> in desc 
                $bloc = strpos($description, '<p>');
                if ( $bloc ) {
                    $description = substr($description, 0, $bloc);
                }

                // No markup in tooltips
                $tooltip = preg_replace('/<[^>]+>/','',$description);
                $tooltip = preg_replace('/"/','\'',$tooltip);

                $title = $this->html_decode($item['title']);
                $date = $item['pubdate'];
                $link = $item['link'];
                if ( strlen($desctype) ) {
                    $result .= "<li><a href=\"$link\" target=\"_blank\">$title</a><br>$description</li>";
                } else {
                    $result .= "<li><a href=\"$link\" target=\"_blank\" ".
                               "title=\"$tooltip\">$title</a></li>";
                }
            } 
            return $result.'</ul>';
        }

        // *********** Shortcode support **************
        function my_shortcode_handler($atts, $content=null) {
            global $settings;
            $settings = get_option('cnn_news');
            return $this->random_feed($atts['name']);
            unset($settings);
        }

        
        // *********** inline replacement callback support **************
        function inline_replace_callback($matches) {

            if ( ! strlen($matches[1]) ) { // Default
                $feedname = '';
            } else {
                $feedname = substr($matches[1], 1); // Skip #
            }
            return $this->random_feed($feedname);
        }

        // ************** Support functions ****************

        function random_feed($name) {
            global $settings;

            $matching_feeds = array();
            foreach ($settings['feeds'] as $k => $v) {
                if ( (string)$v['name'] == $name ) { 
                    $matching_feeds[] = $k;
                } 
            }
            if ( ! count($matching_feeds) ) {
                if ( ! strlen($name) ) {
                    return '<ul><b>No default feed available</b></ul>';
                } else {
                    return "<ul>Unknown feed name <b>$name</b> used</ul>";
                }
            }
            $feed_id = $matching_feeds[rand(0, count($matching_feeds)-1)];
            $feed = $settings['feeds'][$feed_id];

            if ( strlen($feed['title']) ) {
                $title = $feed['title'];
            } else {
                $title = 'CNN News : '.$feed['feedtype'];
            }

            $result = '<!-- Start CNN News code -->';
            $result .= "<div id=\"cnn-news-inline\"><h3>$title</h3>";
            $result .= $this->get_feed($feed, $settings['cachetime']);
            $result .= '</div><!-- End CNN News code -->';
            return $result;
        }

        function html_decode($in) {
            $patterns = array(
                '/&amp;/',
                '/&quot;/',
                '/&lt;/',
                '/&gt;/',
            );
            $replacements = array(
                '&',
                '"',
                '<',
                '>',
            );
            $tmp = preg_replace($patterns, $replacements, $in);
            return preg_replace('/&#39;/','\'',$tmp);

        }

        // Unfortunately, we didn't finalize on a data structure
        // until version 2.1ish of the plugin so we need to upgrade
        // if needed
        function upgrade_options() {
            $options = get_option('cnn_news');

            if ( !is_array($options) ) {

                // a:6:{s:5:"title";s:0:"";s:8:"feedname";s:13:"CNN.com: U.S.";s:7:"feedurl";s:33:"http://rss.cnn.com/rss/cnn_us.rss";s:7:"numnews";s:1:"5";s:11:"usefeedname";b:1;s:8:"getfeeds";b:0;}

                // From 1.0
                $oldoptions = get_option('widget_cnn_news_widget');
                if ( is_array($oldoptions) ) {

                    $flipcnnfeeds     = array_flip($this->cnnfeeds);

                    $tmpfeed = array();
                    $tmpfeed['title']      = $oldoptions['title'];
                    $tmpfeed['name']       = '';
                    $tmpfeed['numnews']    = $oldoptions['numnews'];
                    $tmpfeed['feedurl']    = $oldoptions['feedurl'];
                    $tmpfeed['feedtype']   = $flipcnnfeeds[substr($tmpfeed['feedurl'], 23)];

                    $options = array();
                    $options['feeds']     = array( $tmpfeed );
                    $options['widget-1']  = 0;
                    $options['cachetime'] = 300;
                    
                    delete_option('widget_cnn_news_widget');
                    update_option('cnn_news', $options);
                } else {
                    // First time ever
                    $options = array();
                    $options['feeds']     = array( $this->default_feed() );
                    $options['widget-1']  = 0;
                    $options['cachetime'] = 300;
                    update_option('cnn_news', $options);
                }
            }
        }

        function default_feed() {
            return array( 'numnews' => 5,
                          'feedurl' => 'cnn_topstories.rss',
                          'name' => '',
                          'feedtype' => 'Top Stories');
        }
    }

    // Instantiate
    $cnn_news_instance &= new cnn_news_plugin();

}
?>

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quiz_Maker
 * @subpackage Quiz_Maker/public
 * @author     AYS Pro LLC <info@ays-pro.com>
 */
class Ays_Quiz_Maker_Extra_Shortcodes_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    protected $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $html_class_prefix = 'ays-quiz-extra-shortcodes-';
    private $html_name_prefix = 'ays-quiz-';
    private $name_prefix = 'ays_quiz_';
    private $unique_id;
    private $unique_id_in_class;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_shortcode('ays_quiz_avg_score', array($this, 'ays_generate_avg_score_method'));
        add_shortcode('ays_quiz_passed_users_count', array($this, 'ays_generate_passed_users_count_method'));
        add_shortcode('ays_quiz_failed_users_count_by_score', array($this, 'ays_generate_failed_users_count_by_score_method'));
        add_shortcode('ays_quiz_passed_users_count_by_score', array($this, 'ays_generate_passed_users_count_by_score_method'));
        add_shortcode('ays_quiz_user_passed_quizzes_count', array($this, 'ays_generate_user_passed_quizzes_count_method'));
        add_shortcode('ays_quiz_user_all_passed_quizzes_count', array($this, 'ays_generate_user_all_passed_quizzes_count_method'));
    }

    /*
    ==========================================
        AVG score | Start
    ==========================================
    */

    public function ays_generate_avg_score_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $user_progress_html = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $user_progress_html);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $avg_score_quiz_html = $this->ays_quiz_avg_score_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $avg_score_quiz_html);
    }

    public function ays_quiz_get_avg_score_by_id( $id ){
        global $wpdb;

        $reports_table = esc_sql( $wpdb->prefix . "aysquiz_reports" );

        if (is_null($id) || $id == 0 ) {
            return null;
        }

        $result = Quiz_Maker_Data::ays_get_average_of_scores($id);

        return $result;

    }

    public function ays_quiz_avg_score_html( $id ){

        $results = $this->ays_quiz_get_avg_score_by_id( $id );

        $content_html = array();

        if( is_null( $results ) || $results == 0 ){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", $this->plugin_name ) . "</p>";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."avg-score-box' id='". $this->html_name_prefix ."avg-score-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results . "%";
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        AVG score | End
    ==========================================
    */

    /*
    ==========================================
        Passed users count | Start
    ==========================================
    */

    public function ays_generate_passed_users_count_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $passed_users_count_html = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_users_count_html);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $passed_users_count_html = $this->ays_quiz_passed_users_count_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_users_count_html);
    }

    public function ays_quiz_passed_users_count_html( $id ){

        $results = Quiz_Maker_Data::get_quiz_results_count_by_id($id);

        $content_html = array();

        if($results === null){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", $this->plugin_name ) . "</p>";
            return $content_html;
        }

        $passed_users_count = (isset( $results['res_count'] ) && $results['res_count'] != '') ? sanitize_text_field( $results['res_count'] ) : 0;

        $content_html[] = "<span class='". $this->html_name_prefix ."passed-users-count-box' id='". $this->html_name_prefix ."passed-users-count-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $passed_users_count;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Passed users count | End
    ==========================================
    */

    /*
    ==========================================
        Failed users count by score | Start
    ==========================================
    */


    public function ays_generate_failed_users_count_by_score_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $failed_users_count_html = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $failed_users_count_html);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $failed_users_count_html = $this->ays_quiz_failed_users_count_by_score_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $failed_users_count_html);
    }

    public function get_quiz_passed_and_failed_users_count_by_id( $id, $type = 'passed' ) {
        global $wpdb;

        $reports_table = esc_sql( $wpdb->prefix . "aysquiz_reports" );
        $quizzes_table = esc_sql( $wpdb->prefix . "aysquiz_quizes" );

        if (is_null($id) || $id == 0 ) {
            return null;
        }

        $id = absint( $id );

        $sql = "SELECT * FROM {$quizzes_table} WHERE `id`=" . $id;

        $quiz_data = $wpdb->get_row($sql, 'ARRAY_A');

        $options = (isset( $quiz_data['options'] ) && $quiz_data['options'] != '') ? json_decode( $quiz_data['options'], true ) : array();

        $quiz_pass_score = ( isset( $options['pass_score'] ) && $options['pass_score'] != '' ) ? absint( sanitize_text_field( $options['pass_score'] ) ) : 0;

        $sql = "SELECT COUNT(*) AS res_count
                FROM {$reports_table}
                WHERE quiz_id=". $id ." AND `status` = 'finished' ";

        if ($type == 'failed') {
            $sql .= " AND `score` < {$quiz_pass_score}";
        } else {
            $sql .= " AND `score` >= {$quiz_pass_score}";
        }


        $results = $wpdb->get_row($sql, 'ARRAY_A');

        return $results;
    }

    public function ays_quiz_failed_users_count_by_score_html( $id ){

        $results = $this->get_quiz_passed_and_failed_users_count_by_id( $id, 'failed' );

        $content_html = array();

        if($results === null){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", $this->plugin_name ) . "</p>";
            return $content_html;
        }

        $failed_users_count_by_score = (isset( $results['res_count'] ) && $results['res_count'] != '') ? sanitize_text_field( $results['res_count'] ) : 0;

        $content_html[] = "<span class='". $this->html_name_prefix ."failed-users-count-by-score-box' id='". $this->html_name_prefix ."failed-users-count-by-score-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $failed_users_count_by_score;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Failed users count by score | End
    ==========================================
    */

    /*
    ==========================================
        Passed users count by score | Start
    ==========================================
    */

    public function ays_generate_passed_users_count_by_score_method( $attr ){

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint( sanitize_text_field($attr['id']) ) : null;

        if (is_null($id) || $id == 0 ) {
            $passed_users_count_html = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_users_count_html);
        }

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;


        $passed_users_count_html = $this->ays_quiz_passed_users_count_by_score_html( $id );
        return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_users_count_html);
    }

    public function ays_quiz_passed_users_count_by_score_html( $id ){

        $results = $this->get_quiz_passed_and_failed_users_count_by_id( $id, 'passed' );

        $content_html = array();

        if($results === null){
            $content_html = "<p style='text-align: center;font-style:italic;'>" . __( "There are no results yet.", $this->plugin_name ) . "</p>";
            return $content_html;
        }

        $failed_users_count_by_score = (isset( $results['res_count'] ) && $results['res_count'] != '') ? sanitize_text_field( $results['res_count'] ) : 0;

        $content_html[] = "<span class='". $this->html_name_prefix ."passed-users-count-by-score-box' id='". $this->html_name_prefix ."passed-users-count-by-score-box-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $failed_users_count_by_score;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Passed users count by score | End
    ==========================================
    */


    /*
    ==========================================
        Passed quizzes count per user | Start
    ==========================================
    */
    public function get_user_passed_quizzes_count( $user_id ){
        global $wpdb;

        $reports_table = esc_sql( $wpdb->prefix . "aysquiz_reports" );

        if (is_null($user_id) || $user_id == 0 ) {
            return null;
        }

        $user_id = absint( $user_id );

        $sql = "SELECT COUNT(a.count) FROM ( SELECT COUNT(*) AS count FROM `{$reports_table}` WHERE `user_id` = {$user_id} GROUP BY `quiz_id` ) AS a";

        $results = $wpdb->get_var($sql);

        if ( ! empty( $results ) ) {
            $results = absint( $results );
        } else {
            $results = 0;
        }

        return $results;
    }

    public function ays_generate_user_passed_quizzes_count_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $passed_quizzes_count_html = "";
        if(is_user_logged_in()){
            $passed_quizzes_count_html = $this->ays_generate_user_passed_quizzes_count_html();
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $passed_quizzes_count_html);
    }

    public function ays_generate_user_passed_quizzes_count_html(){
        $user_id = get_current_user_id();

        $results = $this->get_user_passed_quizzes_count( $user_id );

        $content_html = array();

        if($results === null){
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."passed-quizzes-count-per-user' id='". $this->html_name_prefix ."passed-quizzes-count-per-user-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        Passed quizzes count per user | End
    ==========================================
    */

    /*
    ==========================================
        All passed quizzes count per user | Start
    ==========================================
    */
    public function get_user_all_passed_quizzes_count( $user_id ){
        global $wpdb;

        $reports_table = esc_sql( $wpdb->prefix . "aysquiz_reports" );

        if (is_null($user_id) || $user_id == 0 ) {
            return null;
        }

        $user_id = absint( $user_id );

        $sql = "SELECT SUM(a.count) FROM ( SELECT COUNT(*) AS count FROM `{$reports_table}` WHERE `user_id` = {$user_id} GROUP BY `quiz_id` ) AS a";

        $results = $wpdb->get_var($sql);

        if ( ! empty( $results ) ) {
            $results = absint( $results );
        } else {
            $results = 0;
        }

        return $results;
    }

    public function ays_generate_user_all_passed_quizzes_count_method(){

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $unique_id;

        $all_passed_quizzes_count_html = "";
        if(is_user_logged_in()){
            $all_passed_quizzes_count_html = $this->ays_generate_user_all_passed_quizzes_count_html();
        }
        return str_replace(array("\r\n", "\n", "\r"), "\n", $all_passed_quizzes_count_html);
    }

    public function ays_generate_user_all_passed_quizzes_count_html(){
        $user_id = get_current_user_id();

        $results = $this->get_user_all_passed_quizzes_count( $user_id );

        $content_html = array();

        if( is_null( $results ) || $results == 0 ){
            $content_html = "";
            return $content_html;
        }

        $content_html[] = "<span class='". $this->html_name_prefix ."all-passed-quizzes-count-per-user' id='". $this->html_name_prefix ."all-passed-quizzes-count-per-user-". $this->unique_id_in_class ."' data-id='". $this->unique_id ."'>";
            $content_html[] = $results;
        $content_html[] = "</span>";

        $content_html = implode( '' , $content_html);

        return $content_html;
    }

    /*
    ==========================================
        All passed quizzes count per user | End
    ==========================================
    */

}

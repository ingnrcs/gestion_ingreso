<?php
/**
 * Created by PhpStorm.
 * User: biggie18
 * Date: 6/15/18
 * Time: 3:34 PM
 */
    $tab_url = "?page=".$this->plugin_name."-question-tags";
?>
<div id="categories">
    <div class="wrap ays-quiz-tab-content ays-quiz-tab-content-active">
        <h1 class="wp-heading-inline">
            <?php
            echo __(esc_html(get_admin_page_title()),$this->plugin_name);
            echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action">' . __('Add New', $this->plugin_name) . '</a>', esc_attr( $_REQUEST['page'] ), 'add');
            ?>
        </h1>
        <div class="nav-tab-wrapper">
            <a href="#poststuff" class="nav-tab nav-tab-active">
                <?php echo __("Categories", $this->plugin_name);?>
            </a>
            <a href="<?php echo $tab_url; ?>" class="no-js nav-tab">
                <?php echo __("Tags", $this->plugin_name);?>
            </a>
        </div>
        <div id="poststuff">
            <div id="post-body" class="metabox-holder">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->question_categories_obj->views();
                    ?>
                    <form method="post">
                            <?php
                                $this->question_categories_obj->prepare_items();
                                $search = __( "Search", $this->plugin_name );
                                $this->question_categories_obj->search_box($search, $this->plugin_name);
                                $this->question_categories_obj->display();
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
        <h1 class="wp-heading-inline">
            <?php
            echo __(esc_html(get_admin_page_title()),$this->plugin_name);
            echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action">' . __('Add New', $this->plugin_name) . '</a>', esc_attr( $_REQUEST['page'] ), 'add');
            ?>
        </h1>
    </div>
</div>

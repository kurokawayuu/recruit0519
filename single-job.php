<?php
/**
 * 求人情報詳細ページテンプレート
 * Template Name: 求人情報詳細
 * 
 * このテンプレートは求人情報の詳細ページを表示するためのものです。
 * WordPressのカスタム投稿タイプ「job」と連携し、カスタムフィールドやタクソノミーから取得したデータを整形して表示します。
 */

// Cocoon テーマの不要なウィジェットを削除する
// 1. サイドバーから特定のウィジェットを削除（IDベース）
add_filter('sidebars_widgets', 'remove_specific_cocoon_widgets');
function remove_specific_cocoon_widgets($sidebars_widgets) {
    // 求人投稿タイプのシングルページでのみ適用（または常に適用）
    if (!is_singular('job')) {
        return $sidebars_widgets;
    }
    
    // 削除するウィジェットIDのパターン
    $patterns_to_remove = array(
        'popular_entries',  // 人気記事
        'new_entries',      // 新着記事
        'categories',       // カテゴリー
        'recent-posts',     // 新着記事（別の形式）
        'archives',         // アーカイブ
        'recent-comments'   // 最近のコメント
    );
    
    // すべてのサイドバーを処理
    foreach ($sidebars_widgets as $sidebar_id => $widgets) {
        if (is_array($widgets)) {
            foreach ($widgets as $key => $widget_id) {
                // パターンにマッチするウィジェットIDを削除
                foreach ($patterns_to_remove as $pattern) {
                    if (strpos($widget_id, $pattern) !== false) {
                        unset($sidebars_widgets[$sidebar_id][$key]);
                        break;
                    }
                }
            }
        }
    }
    
    return $sidebars_widgets;
}

// 2. Cocoon テーマのウィジェット表示をフィルタリング
add_filter('widget_display_callback', 'filter_cocoon_widgets', 10, 3);
function filter_cocoon_widgets($instance, $widget, $args) {
    // 求人投稿タイプのシングルページでのみ適用
    if (!is_singular('job')) {
        return $instance;
    }
    
    // ウィジェットのクラス名またはIDを取得
    $widget_class = get_class($widget);
    $widget_id = $widget->id;
    
    // 特定のウィジェットを非表示にする条件
    if (
        // クラス名で判定
        strpos($widget_class, 'Popular_Entries') !== false ||
        strpos($widget_class, 'New_Entries') !== false ||
        strpos($widget_class, 'Categories') !== false ||
        strpos($widget_class, 'Recent') !== false ||
        strpos($widget_class, 'Archives') !== false ||
        
        // IDで判定
        strpos($widget_id, 'popular_entries') !== false ||
        strpos($widget_id, 'new_entries') !== false ||
        strpos($widget_id, 'categories') !== false ||
        strpos($widget_id, 'recent-posts') !== false ||
        strpos($widget_id, 'archives') !== false ||
        
        // タイトルで判定（設定があれば）
        (isset($instance['title']) && (
            strpos($instance['title'], '人気') !== false ||
            strpos($instance['title'], '新着') !== false ||
            strpos($instance['title'], 'カテゴリー') !== false ||
            strpos($instance['title'], 'アーカイブ') !== false ||
            strpos($instance['title'], '最近のコメント') !== false
        ))
    ) {
        return false; // ウィジェットを表示しない
    }
    
    return $instance; // その他のウィジェットは表示する
}

// 3. Cocoon テーマ用のCSS対策を追加
add_action('wp_head', 'hide_cocoon_widgets_css', 999);
function hide_cocoon_widgets_css() {
    // 求人投稿タイプのシングルページでのみ適用
    if (!is_singular('job')) {
        return;
    }
    ?>
    <style>
    /* Cocoonテーマの人気記事・新着記事ウィジェットを非表示 */
    .widget_popular_entries,
    .widget_new_entries,
    #popular_entries-2,
    #new_entries-2,
    #categories-2,
    #archives-2,
    #recent-posts-2,
    #recent-comments-2,
    .widget-sidebar[id*="popular_entries"],
    .widget-sidebar[id*="new_entries"],
    .widget-sidebar[id*="categories"],
    .widget-sidebar[id*="recent-posts"],
    .widget-sidebar[id*="archives"],
    .widget-sidebar[id*="recent-comments"] {
        display: none !important;
    }
    
    /* タイトルベースでの非表示（より確実な対策） */
    .widget-sidebar .widget-title:contains("人気記事"),
    .widget-sidebar .widget-title:contains("新着記事"),
    .widget-sidebar .widget-title:contains("カテゴリー"),
    .widget-sidebar .widget-title:contains("アーカイブ"),
    .widget-sidebar .widget-title:contains("最近のコメント") {
        display: none !important;
    }
    
    /* 親要素全体を非表示（タイトルが一致する場合） */
    .widget-sidebar:has(.widget-title:contains("人気記事")),
    .widget-sidebar:has(.widget-title:contains("新着記事")),
    .widget-sidebar:has(.widget-title:contains("カテゴリー")),
    .widget-sidebar:has(.widget-title:contains("アーカイブ")),
    .widget-sidebar:has(.widget-title:contains("最近のコメント")) {
        display: none !important;
    }
    </style>
    <?php
}

// 4. 特定のウィジェットを登録解除（完全な削除）
add_action('widgets_init', 'unregister_specific_cocoon_widgets', 99);
function unregister_specific_cocoon_widgets() {
    // 常に実行するか、条件付きで実行
    // if (is_singular('job')) { // ※ページロード時点では is_singular が機能しないため注意
        // 特定のウィジェットを登録解除する試み
        // 注：クラス名は実際のテーマに合わせて調整する必要があるかもしれません
        if (class_exists('Popular_Entries_Widget')) {
            unregister_widget('Popular_Entries_Widget');
        }
        if (class_exists('New_Entries_Widget')) {
            unregister_widget('New_Entries_Widget');
        }
        if (class_exists('WP_Widget_Categories')) {
            unregister_widget('WP_Widget_Categories');
        }
        if (class_exists('WP_Widget_Recent_Posts')) {
            unregister_widget('WP_Widget_Recent_Posts');
        }
        if (class_exists('WP_Widget_Archives')) {
            unregister_widget('WP_Widget_Archives');
        }
        if (class_exists('WP_Widget_Recent_Comments')) {
            unregister_widget('WP_Widget_Recent_Comments');
        }
    // }
}

// 5. Cocoon特有のフィルターがあれば追加
add_filter('pre_get_posts', 'modify_sidebar_for_job_posts');
function modify_sidebar_for_job_posts($query) {
    // メインクエリの場合のみ
    if (!is_admin() && $query->is_main_query() && $query->is_singular('job')) {
        // Cocoonテーマ特有の変数やフィルターがあれば設定
        // 例: グローバル変数を設定
        global $g_sidebar_widget_mode;
        if (isset($g_sidebar_widget_mode)) {
            $g_sidebar_widget_mode = 'no_display';
        }
    }
    return $query;
}

// 以下は元の求人詳細ページテンプレートのコード
// デフォルトのサイドバーウィジェットを非表示にする - より限定的な方法で実装
add_filter('sidebars_widgets', 'disable_specific_sidebar_widgets');
function disable_specific_sidebar_widgets($sidebars_widgets) {
    if (is_singular('job')) {
        if (isset($sidebars_widgets['sidebar'])) {
            foreach ($sidebars_widgets['sidebar'] as $key => $widget_id) {
                // 特定のウィジェットのみを削除
                if (strpos($widget_id, 'recent-posts') !== false ||
                    strpos($widget_id, 'categories') !== false ||
                    strpos($widget_id, 'popular-posts') !== false) {
                    unset($sidebars_widgets['sidebar'][$key]);
                }
            }
        }
    }
    return $sidebars_widgets;
}

// 特定のウィジェットを非表示にする
add_action('widgets_init', 'remove_specific_widgets', 99);
function remove_specific_widgets() {
    if (is_singular('job')) {
        // グローバルに削除するのではなく条件付きで表示/非表示を制御
        add_filter('widget_display_callback', 'filter_widget_display', 10, 3);
    }
}

// ウィジェットの表示/非表示を制御
function filter_widget_display($instance, $widget, $args) {
    // 特定のウィジェットのみ非表示にする
    if ($widget instanceof WP_Widget_Recent_Posts ||
        $widget instanceof WP_Widget_Categories ||
        (class_exists('WP_Widget_Popular_Posts') && $widget instanceof WP_Widget_Popular_Posts)) {
        return false; // false を返すとウィジェットは表示されない
    }
    return $instance; // 他のウィジェットは通常通り表示
}

get_header();

// 以下は元のテンプレートコードなので、ここにそのまま残します
// ...（略）

// JavaScriptとスタイルシートを読み込み
wp_enqueue_style('job-listing-style', get_template_directory_uri() . '/assets/css/job-listing.css', array(), '1.0.0');
wp_enqueue_script('job-listing-script', get_template_directory_uri() . '/assets/js/job-listing.js', array('jquery'), '1.0.0', true);

// 投稿データ
$post_id = get_the_ID();
$job_title = get_the_title();
$job_content = get_the_content();
// タクソノミーデータ（名前で取得）
$job_location = wp_get_object_terms($post_id, 'job_location', array('fields' => 'names'));
$job_position = wp_get_object_terms($post_id, 'job_position', array('fields' => 'names'));
$job_type = wp_get_object_terms($post_id, 'job_type', array('fields' => 'names'));
$facility_type = wp_get_object_terms($post_id, 'facility_type', array('fields' => 'names'));
$job_feature = wp_get_object_terms($post_id, 'job_feature', array('fields' => 'names'));
// タクソノミーIDも取得（関連求人用）
$job_location_ids = wp_get_object_terms($post_id, 'job_location', array('fields' => 'ids'));
$job_position_ids = wp_get_object_terms($post_id, 'job_position', array('fields' => 'ids'));
// カスタムフィールドデータ
$job_content_title = get_post_meta($post_id, 'job_content_title', true);
$salary_range = get_post_meta($post_id, 'salary_range', true);
$working_hours = get_post_meta($post_id, 'working_hours', true);
$holidays = get_post_meta($post_id, 'holidays', true);
$benefits = get_post_meta($post_id, 'benefits', true);
$requirements = get_post_meta($post_id, 'requirements', true);
$application_process = get_post_meta($post_id, 'application_process', true);
$contact_info = get_post_meta($post_id, 'contact_info', true);
$bonus_raise = get_post_meta($post_id, 'bonus_raise', true);
$capacity = get_post_meta($post_id, 'capacity', true);
$staff_composition = get_post_meta($post_id, 'staff_composition', true);
$daily_schedule_items = get_post_meta($post_id, 'daily_schedule_items', true);

// スタッフの声データの取得
$staff_voice_role = get_post_meta($post_id, 'staff_voice_role', true);
$staff_voice_years = get_post_meta($post_id, 'staff_voice_years', true);
$staff_voice_comment = get_post_meta($post_id, 'staff_voice_comment', true);
$staff_voice_image = get_post_meta($post_id, 'staff_voice_image', true);

// スタッフの声データを配列に整形
$staff_voice_items = array();
if (is_array($staff_voice_role)) {
    for ($i = 0; $i < count($staff_voice_role); $i++) {
        if (!empty($staff_voice_role[$i])) {
            $image_url = '';
            if (!empty($staff_voice_image[$i])) {
                // 画像IDから画像URLを取得
                $image_id = intval($staff_voice_image[$i]);
                $image_url = wp_get_attachment_url($image_id);
            }
            
            $staff_voice_items[] = array(
                'position' => $staff_voice_role[$i],
                'years' => isset($staff_voice_years[$i]) ? $staff_voice_years[$i] : '',
                'comment' => isset($staff_voice_comment[$i]) ? $staff_voice_comment[$i] : '',
                'image' => $image_url
            );
        }
    }
}

// 施設情報
$facility_name = get_post_meta($post_id, 'facility_name', true);
$facility_address = get_post_meta($post_id, 'facility_address', true);
$facility_tel = get_post_meta($post_id, 'facility_tel', true);
$facility_hours = get_post_meta($post_id, 'facility_hours', true);
$facility_url = get_post_meta($post_id, 'facility_url', true);
$facility_company = get_post_meta($post_id, 'facility_company', true);
$facility_map = get_post_meta($post_id, 'facility_map', true);
// サムネイル画像URL（複数画像対応）
$thumbnail_url = get_the_post_thumbnail_url($post_id, 'large');
$gallery_images = get_post_meta($post_id, 'gallery_images', true); // ギャラリー画像用のカスタムフィールド
if (!$thumbnail_url) {
    $thumbnail_url = get_template_directory_uri() . '/assets/images/no-image.jpg';
}
// 求人特徴タグ（画像内で表示されているオレンジ/ピンクのアイコン）
$job_tags = wp_get_object_terms($post_id, 'job_feature', array('fields' => 'all'));

// 施設タイプのアイコン表示用関数
function get_facility_type_icon($type) {
    switch ($type) {
        case '放課後等デイサービス':
            return '<img src="' . get_template_directory_uri() . '/assets/images/icon-houday.jpg" alt="放デイ" width="70" height="70">';
        case '児童発達支援':
            return '<img src="' . get_template_directory_uri() . '/assets/images/icon-jidou.jpg" alt="児発支援" width="70" height="70">';
        // 他の施設タイプも同様に追加
        default:
            return '';
    }
}

// サブタイトルの生成
$job_subtitle = $facility_name . 'の' . (!empty($job_position) ? $job_position[0] : '') . '(' . (!empty($job_type) ? $job_type[0] : '') . ')の求人情報';
?>

<div class="cont">
    <!-- ヘッダーセクション -->
    <div class="company-name"><?php echo esc_html($facility_company); ?></div>
    <h1 class="job-title1"><?php echo esc_html($job_subtitle); ?></h1>
    <div class="job-subtitle"><?php echo esc_html($job_title); ?></div>
    
    <div class="facility-type">
        <?php
        if (!empty($facility_type)) {
            foreach ($facility_type as $type) {
                echo get_facility_type_icon($type);
            }
        }
        ?>
    </div>
    
    <!-- メイン画像と求人詳細を横並びに -->
    <div class="slideshow-container">
        <div class="slideshow">
    <?php
    // 複数サムネイル画像を取得
    $thumbnail_ids = get_post_meta($post_id, 'job_thumbnail_ids', true);
    
    // 画像がある場合は表示
    if (!empty($thumbnail_ids) && is_array($thumbnail_ids)) {
        foreach ($thumbnail_ids as $thumb_id) {
            $image_url = wp_get_attachment_url($thumb_id);
            if ($image_url) {
                echo '<img src="' . esc_url($image_url) . '" alt="施設画像">';
            }
        }
    } elseif (!empty($gallery_images)) {
        // 互換性のために$gallery_imagesがある場合はそれを使用
        foreach ($gallery_images as $image) {
            echo '<img src="' . esc_url($image) . '" alt="施設画像">';
        }
    } else {
        // サムネイル画像がなければデフォルト画像を表示
        echo '<img src="' . esc_url($thumbnail_url) . '" alt="施設画像">';
    }
    ?>
</div>
        
        <div class="job-details">
            <div class="job-position">
    <span class="position"><?php echo !empty($job_position) ? esc_html($job_position[0]) : ''; ?></span>
    <?php
    $employment_type = !empty($job_type) ? esc_html($job_type[0]) : '';
    $type_class = 'other'; // デフォルトクラス
    
    // 雇用形態によってクラスを設定
    if ($employment_type === '正社員') {
        $type_class = 'full-time';
    } else if ($employment_type === 'パート・アルバイト') {
        $type_class = 'part-time';
    }
    ?>
    <span class="employment-type <?php echo $type_class; ?>"><?php echo $employment_type; ?></span>
</div>
            
            <div class="job-salary">
                <div class="salary-label">住所</div>
                <div class="salary-range">
    <?php 
    // 郵便番号と住所を分けて表示
    $address = esc_html($facility_address);
    // 郵便番号と住所部分を分割
    $address_parts = preg_split('/(\〒\d{3}-\d{4})/', $address, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    
    if (count($address_parts) >= 2) {
        // 郵便番号と住所部分が分かれている場合
        echo $address_parts[0] . "<br>"; // 郵便番号
        echo $address_parts[1]; // 住所部分
    } else {
        // 分割できなかった場合はそのまま表示
        echo $address;
    }
    ?>
</div>
                <div class="salary-label">給与</div>
<div class="salary-range">
    <?php 
    // 賃金形態を取得（MONTH/HOUR）
    $salary_type = get_post_meta($post_id, 'salary_type', true);
    
    // 賃金形態の表示テキスト
    $salary_type_text = '';
    if ($salary_type == 'HOUR') {
        $salary_type_text = '時給 ';
    } else {
        $salary_type_text = '月給 ';
    }
    
    // 給与範囲を表示
    echo esc_html($salary_type_text . $salary_range);
    
    // 「円」を追加（ただし既に「円」が含まれている場合は追加しない）
    if (strpos($salary_range, '円') === false) {
        echo '円';
    }
    ?>
</div>
            
            <!-- ボタン -->
            <div class="button-group">
                <div class="keep-button">★ キープ</div>
                <div class="contact-button">応募画面へ</div>
            </div>
        </div>
    </div>
 </div>
    <!-- 情報タブヘッダー -->
    <div class="info-tabs">情報目次</div>
    
    <!-- ナビゲーションタブ -->
    <div class="tab-navigation">
        <a href="#job-info" class="active"><i>✓</i>募集内容</a>
        <a href="#workplace-info"><i>✓</i>職場の環境</a>
        <a href="#facility-info"><i>✓</i>事業所の情報</a>
    </div>
    
    <!-- 求人紹介文 -->
    <div class="job-introduction">
        <?php if (!empty($job_content_title)) : ?>
    <h2 class="job-content-title"><?php echo esc_html($job_content_title); ?></h2>
    <?php endif; ?>
    <?php echo wpautop($job_content); ?>
</div>
    
    <div class="content-area">
        <!-- メインコンテンツ -->
        <div class="main-content">
            <!-- 求人詳細情報 -->
            <div id="job-info" class="job-description">
                <h2 class="section-title">募集情報</h2>
                <table class="job-info-table">
                    <tr>
                        <th>職種名称</th>
                        <td><?php echo !empty($job_position) ? esc_html($job_position[0]) : ''; ?></td>
                    </tr>
                    <tr>
                        <th>雇用形態</th>
                        <td><?php echo !empty($job_type) ? esc_html($job_type[0]) : ''; ?></td>
                    </tr>
                    <tr>
  <th>給与</th>
  <td>
    <?php 
    // 賃金形態を取得（MONTH/HOUR）
    $salary_type = get_post_meta($post_id, 'salary_type', true);
    $salary_form = get_post_meta($post_id, 'salary_form', true);
    $salary_remarks = get_post_meta($post_id, 'salary_remarks', true);
    
    // 賃金形態の表示テキスト
    $salary_type_text = '';
    if ($salary_type == 'HOUR') {
      $salary_type_text = '時給 ';
    } else {
      $salary_type_text = '月給 ';
    }
    
    // 給与範囲を表示
    echo esc_html($salary_type_text . $salary_range);
    
    // 「円」を追加（ただし既に「円」が含まれている場合は追加しない）
    if (strpos($salary_range, '円') === false) {
      echo '円';
    }
    
    // 給与についての備考があれば表示
    if (!empty($salary_remarks)) {
      echo '<div class="salary-remarks">';
      echo nl2br(esc_html($salary_remarks));
      echo '</div>';
    }
    ?>
  </td>
</tr>
                    <tr>
                        <th>仕事内容</th>
                        <td><?php echo nl2br(esc_html($job_content_title)); ?></td>
                    </tr>
                    <tr>
                        <th>応募要件</th>
                        <td><?php echo nl2br(esc_html($requirements)); ?></td>
                    </tr>
                    <tr>
                        <th>勤務時間</th>
                        <td><?php echo nl2br(esc_html($working_hours)); ?></td>
                    </tr>
                    <tr>
                        <th>休日・休暇</th>
                        <td><?php echo nl2br(esc_html($holidays)); ?></td>
                    </tr>
                    <tr>
                        <th>福利厚生</th>
                        <td><?php echo nl2br(esc_html($benefits)); ?></td>
                    </tr>
                    <tr>
                        <th>昇給・賞与</th>
                        <td><?php echo nl2br(esc_html($bonus_raise)); ?></td>
                    </tr>
                    <tr>
                        <th>選考プロセス</th>
                        <td>
                            <?php if (!empty($application_process)) : ?>
                                <ul class="checklist">
                                    <?php 
                                    $process_items = explode("\n", $application_process);
                                    foreach ($process_items as $item) :
                                        if (trim($item) !== '') :
                                    ?>
                                        <li><?php echo esc_html(trim($item)); ?></li>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                
                <!-- 求人タグ - 修正済み -->
                <?php if (!empty($job_feature)) : ?>
                <div class="feature-section">
                    <h3 class="feature-title">この求人の特徴</h3>
                    <div class="tag-container">
                        <?php foreach ($job_feature as $feature) : ?>
                            <div class="job-tag"><?php echo esc_html($feature); ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- 職場環境 - 修正済み -->
            <div id="workplace-info" class="workplace-environment">
                <h2 class="environment-title">職場の環境</h2>
                
                <!-- 一日のスケジュール -->
                <?php if (!empty($daily_schedule_items)) : ?>
                <div class="schedule-section">
                    <h3 class="section-subtitle"><span class="orange-dot"></span>仕事の一日の流れ</h3>
                    <div class="daily-schedule">
                        <?php
                        $schedule_items = maybe_unserialize($daily_schedule_items);
                        if (is_array($schedule_items)) :
                            foreach ($schedule_items as $item) :
                                // 時間表示の種類を判定（「〜」で始まる場合は白枠、それ以外はオレンジ）
                                $time_class = (strpos($item['time'], '〜') === 0) ? 'timeline-time-white' : 'timeline-time-orange';
                        ?>
                        <div class="timeline-row">
                            <div class="<?php echo $time_class; ?>"><?php echo esc_html($item['time']); ?></div>
                            <div class="timeline-content">
                                <div class="timeline-title"><?php echo esc_html($item['title']); ?></div>
                                <?php if (!empty($item['description'])) : ?>
                                <div class="timeline-description"><?php echo esc_html($item['description']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- スタッフの声 -->
                <?php
// スタッフの声データを取得
$staff_voice_items = get_post_meta($post_id, 'staff_voice_items', true);
if (!empty($staff_voice_items) && is_array($staff_voice_items)) : 
?>
<div class="voice-section">
    <h3 class="section-subtitle"><span class="orange-dot"></span>職員の声</h3>
    <div class="staff-voices-container">
        <?php foreach ($staff_voice_items as $voice) : 
            if (empty($voice['role'])) continue;
            
            // 画像URLの取得
            $image_url = '';
            if (!empty($voice['image_id'])) {
                $image_url = wp_get_attachment_url($voice['image_id']);
            }
        ?>
        <div class="staff-voice">
            <div class="staff-info">
                <div class="staff-photo">
                    <?php if (!empty($image_url)) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="スタッフ画像" class="staff-img">
                    <?php else : ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/no-staff-image.jpg" alt="スタッフ画像なし" class="staff-img">
                    <?php endif; ?>
                </div>
                <div class="staff-details">
                    <div class="staff-role"><span class="staff-label">職種：</span><?php echo esc_html($voice['role']); ?></div>
                    <div class="staff-years"><span class="staff-label">勤続年数：</span><?php echo esc_html($voice['years']); ?></div>
                </div>
            </div>
            <div class="staff-comment"><?php echo nl2br(esc_html($voice['comment'])); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
            </div>
            
            <!-- 施設詳細 -->
            <div id="facility-info" class="facility-details">
                <h2 class="section-title">事業所の情報</h2>
                <table class="facility-info-table">
                    <tr>
                        <th>施設名</th>
                        <td><?php echo esc_html($facility_name); ?></td>
                    </tr>
                    <tr>
    <th>住所</th>
    <td>
        <?php 
        // 郵便番号と住所を分けて表示
        $address = esc_html($facility_address);
        // 郵便番号と住所部分を分割
        $address_parts = preg_split('/(\〒\d{3}-\d{4})/', $address, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        
        if (count($address_parts) >= 2) {
            // 郵便番号と住所部分が分かれている場合
            echo $address_parts[0] . "<br>"; // 郵便番号
            echo $address_parts[1]; // 住所部分
        } else {
            // 分割できなかった場合はそのまま表示
            echo $address;
        }
        ?>
    </td>
</tr>
                    <tr>
    <th>MAP</th>
    <td>
        <?php if (!empty($facility_map)) : ?>
        <div class="map-container">
            <?php echo $facility_map; // 地図埋め込みコード ?>
        </div>
        <?php endif; ?>
        <!-- 以下の住所表示を削除 -->
        <!-- <div><?php echo esc_html($facility_address); ?></div> -->
    </td>
</tr>
                    <tr>
                        <th>サービス種別</th>
                        <td><?php echo !empty($facility_type) ? esc_html(implode('・', $facility_type)) : ''; ?></td>
                    </tr>
                    <tr>
                        <th>利用定員数</th>
                        <td>定員：<?php echo esc_html($capacity); ?></td>
                    </tr>
                    <tr>
                        <th>スタッフ構成</th>
                        <td class="facility-staff">
                            <?php 
                            if (!empty($staff_composition)) :
                                $staff_items = explode("\n", $staff_composition);
                                foreach ($staff_items as $staff) :
                                    if (trim($staff) !== '') :
                            ?>
                                <div>・<?php echo esc_html(trim($staff)); ?></div>
                            <?php 
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td><?php echo esc_html($facility_tel); ?></td>
                    </tr>
                    <tr>
                        <th>営業時間</th>
                        <td><?php echo nl2br(esc_html($facility_hours)); ?></td>
                    </tr>
                    <?php if (!empty($facility_url)) : ?>
                    <tr>
                        <th>施設URL</th>
                        <td><a href="<?php echo esc_url($facility_url); ?>" target="_blank"><?php echo esc_url($facility_url); ?></a></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>運営会社名</th>
                        <td><?php echo esc_html($facility_company); ?></td>
                    </tr>
                    <?php if (!empty($facility_url)) : ?>
                    <tr>
                        <th>運営会社URL</th>
                        <td><a href="<?php echo esc_url($facility_url); ?>" target="_blank"><?php echo esc_url($facility_url); ?></a></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        <!-- サイドバー -->
        <div class="custom-sidebar">
            <!-- 関連求人セクション：担当者ありバージョン -->
            <div class="related-jobs">
                <h3>担当者：保育士<br>その他の求人</h3>
                <?php
                // 同じ施設の別の求人を取得
                $related_args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 5,
                    'post__not_in' => array($post_id),
                    'meta_query' => array(
                        array(
                            'key' => 'facility_name',
                            'value' => $facility_name,
                            'compare' => '='
                        )
                    )
                );
                $related_query = new WP_Query($related_args);
                
                if ($related_query->have_posts()) :
                    while ($related_query->have_posts()) : $related_query->the_post();
                        $rel_position = wp_get_object_terms(get_the_ID(), 'job_position', array('fields' => 'names'));
                        $rel_type = wp_get_object_terms(get_the_ID(), 'job_type', array('fields' => 'names'));
                        $rel_thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        if (!$rel_thumb) {
                            $rel_thumb = get_template_directory_uri() . '/assets/images/no-image.jpg';
                        }
                ?>
                <div class="related-job-item">
                    <a href="<?php the_permalink(); ?>">
                        <div class="related-job-thumb">
                            <img src="<?php echo esc_url($rel_thumb); ?>" alt="関連求人">
                        </div>
                        <div class="related-job-title"><?php echo esc_html($facility_name); ?></div>
                        <div class="related-job-subtitle">
                            <?php 
                            echo !empty($rel_position) ? esc_html($rel_position[0]) : ''; 
                            echo !empty($rel_type) ? '（' . esc_html($rel_type[0]) . '）' : '';
                            ?>
                        </div>
                    </a>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                ?>
                <div class="no-related-jobs">
                    <p>この施設の他の求人はありません。</p>
                </div>
                <?php endif; ?>
                <a href="<?php echo esc_url(home_url('/jobs/')); ?>" class="see-more">もっと見る</a>
            </div>
            
            <!-- 同じエリア・同じ職種の求人 -->
            <div class="related-jobs">
                <h3>こどもプラス施設求人<br>この他の求人</h3>
                <?php
                // 同じエリア・職種の求人を取得
                $area_job_args = array(
                    'post_type' => 'job',
                    'posts_per_page' => 5,
                    'post__not_in' => array($post_id),
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'job_location',
                            'field'    => 'id',
                            'terms'    => $job_location_ids,
                        ),
                        array(
                            'taxonomy' => 'job_position',
                            'field'    => 'id',
                            'terms'    => $job_position_ids,
                        ),
                    ),
                );
                $area_job_query = new WP_Query($area_job_args);
                
                if ($area_job_query->have_posts()) :
                    while ($area_job_query->have_posts()) : $area_job_query->the_post();
                        $rel_facility = get_post_meta(get_the_ID(), 'facility_name', true);
                        $rel_position = wp_get_object_terms(get_the_ID(), 'job_position', array('fields' => 'names'));
                        $rel_type = wp_get_object_terms(get_the_ID(), 'job_type', array('fields' => 'names'));
                        $rel_thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        if (!$rel_thumb) {
                            $rel_thumb = get_template_directory_uri() . '/assets/images/no-image.jpg';
                        }
                ?>
                <div class="related-job-item">
                    <a href="<?php the_permalink(); ?>">
                        <div class="related-job-thumb">
                            <img src="<?php echo esc_url($rel_thumb); ?>" alt="関連求人">
                        </div>
                        <div class="related-job-title"><?php echo esc_html($rel_facility); ?></div>
                        <div class="related-job-subtitle">
                            <?php 
                            echo !empty($rel_position) ? esc_html($rel_position[0]) : ''; 
                            echo !empty($rel_type) ? '（' . esc_html($rel_type[0]) . '）' : '';
                            ?>
                        </div>
                    </a>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                ?>
                <div class="no-related-jobs">
                    <p>関連する他の求人はありません。</p>
                </div>
                <?php endif; ?>
                <a href="<?php echo esc_url(home_url('/jobs/?location=' . implode(',', $job_location) . '&position=' . implode(',', $job_position))); ?>" class="see-more">もっと見る</a>
            </div>
            
            <!-- 施設のブログ記事 -->
            <?php
            // 施設URLからブログURLを生成
            $blog_url = '';
            if (!empty($facility_url)) {
                $blog_url = trailingslashit($facility_url) . 'blog/';
            }
            
            // ブログのRSSフィードがあれば取得
            $rss_items = array();
            if (!empty($blog_url)) {
                // RSSフィードを安全に取得
                if (function_exists('fetch_feed')) {
                    $rss_url = $blog_url . 'feed/';
                    $rss = fetch_feed($rss_url);
                    
                    if (!is_wp_error($rss)) {
                        $max_items = $rss->get_item_quantity(3);
                        $rss_items = $rss->get_items(0, $max_items);
                    }
                }
            }
            ?>
            
            <div class="related-jobs">
                <h3>こどもプラス施設家ブログ<br>ブログ一覧</h3>
                
                <?php if (!empty($rss_items)) : ?>
                    <?php foreach ($rss_items as $item) : ?>
                    <div class="related-job-item">
                        <a href="<?php echo esc_url($item->get_permalink()); ?>" target="_blank">
                            <div class="related-job-thumb">
                                <?php
                                // RSSから画像を取得する例（簡易的）
                                $content = $item->get_content();
                                preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
                                $image_url = !empty($matches[1]) ? $matches[1] : get_template_directory_uri() . '/assets/images/blog-default.jpg';
                                ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="ブログ画像">
                            </div>
                            <div class="related-job-title"><?php echo esc_html($item->get_title()); ?></div>
                            <div class="related-job-subtitle"><?php echo esc_html($item->get_date('Y年m月d日')); ?></div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <!-- デフォルト表示 -->
                    <div class="related-job-item">
                        <a href="<?php echo esc_url($blog_url ? $blog_url : '#'); ?>" <?php echo $blog_url ? 'target="_blank"' : ''; ?>>
                            <div class="related-job-thumb">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/blog-default.jpg" alt="ブログ画像">
                            </div>
                            <div class="related-job-title">施設のブログをご覧ください</div>
                            <div class="related-job-subtitle">最新の活動情報を公開中</div>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($blog_url)) : ?>
                <a href="<?php echo esc_url($blog_url); ?>" target="_blank" class="see-more">もっと見る</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- JavaScriptの追加 -->
<script>
jQuery(document).ready(function($) {
    // ナビゲーションタブの切り替え
    $('.tab-navigation a').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        
        // アクティブクラスの切り替え
        $('.tab-navigation a').removeClass('active');
        $(this).addClass('active');
        
        // スクロール処理
        $('html, body').animate({
            scrollTop: $(target).offset().top - 100
        }, 500);
    });
    
    // キープボタン処理
    $('.keep-button').on('click', function() {
        var postId = <?php echo $post_id; ?>;
        var button = $(this);
        
        <?php if (is_user_logged_in()) : ?>
            // ログイン済みユーザーの場合はAJAXでサーバーに保存
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'toggle_job_favorite',
                    job_id: postId,
                    nonce: '<?php echo wp_create_nonce('job_favorite_nonce'); ?>'
                },
                beforeSend: function() {
                    button.prop('disabled', true);
                },
                success: function(response) {
                    if (response.success) {
                        if (response.data.favorited) {
                            button.text('★ キープ済み');
                            button.css('background-color', '#fff3e0');
                        } else {
                            button.text('★ キープ');
                            button.css('background-color', '#fff');
                        }
                    } else {
                        alert('エラーが発生しました: ' + response.data.message);
                    }
                },
                error: function() {
                    alert('通信エラーが発生しました。');
                },
                complete: function() {
                    button.prop('disabled', false);
                }
            });
        <?php else : ?>
            // 未ログインユーザーの場合は会員登録ページへリダイレクト
            window.location.href = '<?php echo home_url('/register/'); ?>';
            return false;
        <?php endif; ?>
    });
    
    // 既にキープされているかチェック
    var currentPostId = <?php echo $post_id; ?>;
    <?php if (is_user_logged_in()) : ?>
        // ログイン済みユーザーの場合はユーザーメタから取得
        var userFavorites = <?php echo json_encode(get_user_meta(get_current_user_id(), 'user_favorites', true) ?: array()); ?>;
        
        if (userFavorites.includes(currentPostId)) {
            $('.keep-button').text('★ キープ済み');
            $('.keep-button').css('background-color', '#fff3e0');
        }
    <?php else : ?>
        // 未ログインユーザーの場合：キープボタンの表示は通常のまま
        // localStorageの処理は削除
    <?php endif; ?>
    
    // お問い合わせボタン処理
    $('.contact-button').on('click', function() {
        // 応募フォームへのリンク
        window.location.href = '<?php echo esc_url(home_url('/apply/?job_id=' . $post_id)); ?>';
    });
});
</script>

<!-- スタイルシート -->
<style>
/* 全体レイアウト */
.cont {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* ヘッダー部分 */
.company-name {
    font-size: 12px;
    color: #777;
    margin-bottom: 5px;
    text-align: right;
}
.job-title1 {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 5px;
}
.job-subtitle1 {
    font-size: 16px;
    margin-bottom: 15px;
}
.facility-type {
    display: flex;
    position: absolute;
    right: 35px;
    top: 75px;
}
.facility-type img {
    width: 70px;
    height: 70px;
    margin-left: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

/* メイン画像と詳細情報部分 */
.slideshow-container {
    position: relative;
    display: flex;
    margin-bottom: 30px;
    background-color: #fff;
    padding: 15px;
    min-height: 450px; /* 十分な高さを確保 */
}
.slideshow {
    width: 65%;
    height: 420px; /* 高さを増やす */
    overflow: hidden;
    position: relative;
}
.slideshow img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.job-details {
    width: 35%;
    padding: 0 20px;
    margin: 0;
    display: flex;
    flex-direction: column;
    height: 420px; /* slideshow と同じ高さに */
}
.job-position {
    flex: 0 0 auto; /* サイズ固定 */
    margin-bottom: 30px;
}
.job-position span {
    display: inline-block;
}
.job-position .position {
    font-size: 18px;
    font-weight: bold;
    margin-right: 10px;
}
.job-position .employment-type {
    background-color: #cae7fd;
    color: #333;
    padding: 2px 10px;
    border-radius: 15px;
    font-size: 14px;
}
.job-salary {
    flex: 1 0 auto;
    display: flex;
    flex-direction: column;
    margin-bottom: 30px;
    min-height: 100px;
}
.salary-label {
    font-size: 14px;
    color: #777;
    margin-bottom: 5px;
    margin-top: 15px;
    display: block !important;
}
.salary-label:first-child {
    margin-top: 0;
}
.salary-range {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 15px;
    display: block !important;
    word-break: break-word;
    line-height: 1.4;
}
.button-group {
    flex: 0 0 auto;
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: auto;
}
.keep-button, .contact-button {
    padding: 12px;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    font-weight: bold;
    font-size: 16px;
}
.keep-button {
    border: 1px solid #ffb74d;
    color: #f57c00;
    background-color: #fff;
}
.contact-button {
    background-color: #26b7a0;
    color: #fff;
    border: none;
}

/* 情報タブ部分 */
.info-tabs {
    background-color: #ffc069;
    border-radius: 8px 8px 0 0;
    margin-bottom: 0;
    margin-top: 20px;
    padding: 15px;
    text-align: center;
    font-weight: bold;
    color: #fff;
}
.tab-navigation {
    background-color: #fff;
    border-radius: 0 0 8px 8px;
    margin-bottom: 20px;
    padding: 15px;
    display: flex;
    justify-content: space-around;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.tab-navigation a {
    color: #777;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
}
.tab-navigation a.active {
    color: #333;
    font-weight: bold;
}
.tab-navigation a i {
    background-color: #e0e0e0;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 5px;
}
.job-introduction {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* コンテンツエリア */
.content-area {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.main-content {
    flex: 1;
    min-width: 60%;
}
.custom-sidebar {
    width: 300px;
}
.job-description, .facility-details {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.section-title {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    background-color: #fff8e1;
    padding: 15px;
    border-radius: 5px 5px 0 0;
    margin: -20px -20px 15px -20px; /* ボックスにくっつけるための調整 */
    background-image: none !important;
    box-shadow: none !important;
    border-left: none !important;
    border-right: none !important;
    border-top: none !important;
    padding-left: 15px !important;
}
.section-title:after {
    display: none !important;
    content: none !important;
}
/* 事業所情報の見出し背景をグレーに変更 */
#facility-info .section-title {
    background-color: #f5f5f5;
}

/* 求人情報テーブルスタイル */
.job-info-table, .facility-info-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
}
.job-info-table th, .job-info-table td,
.facility-info-table th, .facility-info-table td {
    padding: 12px !important;
    border-bottom: 1px solid #eee !important;
    vertical-align: top;
    border-top: none !important;
    border-left: none !important;
    border-right: none !important;
}
.job-info-table th, .facility-info-table th {
    width: 25%;
    text-align: left;
    font-weight: normal;
    color: #777;
    background-color: #fff !important; /* 全ての背景を白に変更 */
}
/* テーブルの交互の背景色を打ち消し */
table tr:nth-of-type(2n+1) {
    background-color: #fff !important; /* 全ての行を白背景に */
}

/* 求人特徴のスタイル */
.feature-section {
    background-color: #fff;
    padding: 15px;
    margin: 20px 0;
    border-radius: 8px;
}
.feature-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
}
.tag-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.job-tag {
    background-color: #fff;
    border: 1px solid #ffb74d;
    color: #f57c00;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 14px;
}

/* 職場環境のスタイル */
.workplace-environment {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.environment-title {
    font-size: 20px;
    font-weight: bold;
    margin: -20px -20px 20px -20px;
    padding: 15px;
    background-color: #fff8e1;
    border-radius: 8px 8px 0 0;
    text-align: center;
}
.section-subtitle {
    font-size: 18px;
    font-weight: bold;
    margin: 25px 0 15px;
    display: flex;
    align-items: center;
}
.orange-dot {
    display: inline-block;
    width: 12px;
    height: 12px;
    background-color: #ff9800;
    border-radius: 50%;
    margin-right: 8px;
}

/* タイムラインのスタイル */
.daily-schedule {
    margin: 20px 0;
}
.timeline-row {
    display: flex;
    margin-bottom: 15px;
    align-items: flex-start;
}
/* オレンジ色の時間表示（正方形） */
.timeline-time-orange {
    width: 55px;
    background-color: #ffa726;
    color: white;
    font-weight: bold;
    font-size: 16px;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    border-radius: 5px;
    margin-right: 15px;
    flex-shrink: 0;
}
/* 白枠の時間表示（横長） */
.timeline-time-white {
    min-width: 85px;
    background-color: #fff;
    color: #f57c00;
    font-weight: bold;
    padding: 8px 10px;
    text-align: center;
    border-radius: 5px;
    margin-right: 15px;
    border: 1px solid #ffa726;
    flex-shrink: 0;
}
.timeline-content {
    flex-grow: 1;
    padding-top: 3px;
    text-align: left;
}
.timeline-title {
    font-weight: bold;
    margin-bottom: 5px;
    text-align: left;
}
.timeline-description {
    color: #666;
    font-size: 14px;
    text-align: left;
}

/* スタッフの声のスタイル - 更新版 */
.voice-section {
    margin: 25px 0;
    padding: 0 0 20px 0;
    border-bottom: 1px solid #eee;
}

.staff-voices-container {
    margin-top: 20px;
}

.staff-voice {
    background-color: #fff;
    padding: 15px;
    margin: 10px 0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.staff-info {
    display: flex;
    margin-bottom: 15px;
}

.staff-photo {
    width: 80px;
    height: 80px;
    margin-right: 15px;
    flex-shrink: 0;
    overflow: hidden;
    border-radius: 5px;
}

.staff-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.staff-details {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.staff-role, .staff-years {
    margin-bottom: 5px;
    font-size: 14px;
}

.staff-label {
    font-weight: bold;
    color: #555;
}

.staff-comment {
    font-size: 15px;
    line-height: 1.6;
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
    margin-top: 10px;
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
    .staff-info {
        flex-direction: column;
    }
    
    .staff-photo {
        width: 60px;
        height: 60px;
        margin-bottom: 10px;
    }
    
    .staff-details {
        padding-left: 0;
    }
}
/* マップ・施設情報 */
.map-container {
    height: 150px;
    background-color: #eee;
    border-radius: 5px;
    margin-bottom: 15px;
    overflow: hidden;
}
.map-container iframe {
    width: 100%;
    height: 100%;
    border: none;
}
.facility-staff div {
    margin-bottom: 5px;
}
/* 本文タイトルのスタイル */
.job-content-title {
    font-size: 1.2em;
    font-weight: bold;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ffc069;
    color: #333;
}
/* サイドバースタイル */
.related-jobs {
    background-color: #fff;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.related-jobs h3 {
    font-size: 16px;
    margin-bottom: 15px;
    padding-bottom: 5px;
    border-bottom: 1px solid #eee;
    color: #ff9800;
    line-height: 1.4;
}
.related-job-item {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}
.related-job-item:last-child {
    border-bottom: none;
}
.related-job-item a {
    text-decoration: none;
    color: inherit;
    display: block;
}
.related-job-item a:hover {
    opacity: 0.8;
}
.related-job-thumb {
    width: 100%;
    height: 100px;
    background-color: #eee;
    border-radius: 5px;
    margin-bottom: 10px;
    overflow: hidden;
}
.related-job-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.related-job-title {
    font-weight: bold;
    margin-bottom: 5px;
    font-size: 14px;
    color: #333;
}
.related-job-subtitle {
    font-size: 12px;
    color: #777;
}
.see-more {
    display: block;
    text-align: center;
    color: #4db6ac;
    text-decoration: none;
    margin-top: 10px;
    font-size: 12px;
    padding: 8px;
    border: 1px solid #4db6ac;
    border-radius: 5px;
}
.see-more:hover {
    background-color: #4db6ac;
    color: #fff;
}

/* 関連求人なしの表示 */
.no-related-jobs {
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
    text-align: center;
    margin-bottom: 10px;
}
.no-related-jobs p {
    margin: 0;
    color: #777;
}

/* チェックリスト */
.checklist {
    list-style-type: none;
    padding-left: 0;
    margin: 0;
}
.checklist li {
    margin-bottom: 8px;
    position: relative;
    padding-left: 25px;
}
.checklist li:before {
    content: "□";
    position: absolute;
    left: 0;
}

/* WordPressデフォルトウィジェットの選択的非表示 */
.widget_recent_entries,
.widget_categories,
.widget_popular_posts {
    display: none !important;
}

/* 給与備考欄のスタイル */
.salary-remarks {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dotted #eee;
    color: #666;
    font-size: 14px;
}

/* レスポンシブデザイン対応 */
@media (max-width: 768px) {
    .slideshow-container {
        flex-direction: column;
        min-height: auto;
    }
    .slideshow, .job-details {
        width: 100%;
        height: auto;
    }
    .slideshow {
        margin-bottom: 20px;
        height: 200px;
    }
    .job-details {
        padding: 15px 0;
        min-height: 350px;
    }
    .job-position {
        margin-bottom: 20px;
    }
    .job-salary {
        margin-bottom: 20px;
        min-height: 130px;
    }
    .button-group {
        margin-top: 20px;
    }
    .content-area {
        flex-direction: column;
    }
    .main-content, .custom-sidebar {
        width: 100%;
    }
    .facility-type {
        position: static;
        justify-content: flex-end;
        margin-bottom: 15px;
    }
    .tab-navigation {
        flex-direction: column;
        gap: 10px;
    }
    
    /* レスポンシブ対応 - タイムライン */
    .timeline-row {
        flex-direction: column;
    }
    
    .timeline-time-orange,
    .timeline-time-white {
        margin-bottom: 8px;
        margin-right: 0;
    }
}
	
/* 雇用形態ごとの色分け */
.employment-type.full-time {
    background-color: #C5E5FF;
    color: #5599FF;
}

.employment-type.part-time {
    background-color: #FFD5F9;
    color: #F58284;
}

.employment-type.other {
    background-color: #D5FFEC;
    color: #0FB96A;
}
	
/**
 * 求人詳細ページのスライドショー用CSSスタイル (修正版)
 */

/* スライドショーコンテナのスタイル */
.slideshow {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* 画像のスタイル */
.slideshow img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* ナビゲーションドットのコンテナ - 修正 */
.slideshow-dots {
    position: absolute;
    bottom: 15px;
    left: 0;
    right: 0;
    text-align: center;
    z-index: 10;
    /* 画像幅の中に収まるように調整 */
    margin: 0 auto;
    width: auto;
    max-width: 100%;
}

/* ドットのスタイル */
.slideshow-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    margin: 0 5px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

/* アクティブなドットのスタイル */
.slideshow-dot.active {
    background-color: #fff;
    transform: scale(1.2);
}

/* ナビゲーションボタンの共通スタイル */
.slideshow-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    background-color: rgba(0, 0, 0, 0.3);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 24px;
    line-height: 1;
    text-align: center;
    cursor: pointer;
    z-index: 10;
    transition: background-color 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1; /* 常に表示 */
}

/* 前へボタンの位置 */
.slideshow-nav.prev {
    left: 10px;
}

/* 次へボタンの位置 */
.slideshow-nav.next {
    right: 10px;
}

/* ホバー時のナビゲーションボタンのスタイル */
.slideshow-nav:hover {
    background-color: rgba(0, 0, 0, 0.6);
}

/* モバイル対応 */
@media (max-width: 768px) {
    .slideshow-nav {
        width: 30px;
        height: 30px;
        font-size: 18px;
    }
    
    .slideshow-dot {
        width: 8px;
        height: 8px;
        margin: 0 3px;
    }
}

/* ホバー時のナビゲーションボタンの表示（不要なので削除） */
/* .slideshow:hover .slideshow-nav {
    opacity: 1;
} */

/* 前へボタンの位置 */
.slideshow-nav.prev {
    left: 10px;
}

/* 次へボタンの位置 */
.slideshow-nav.next {
    right: 10px;
}

/* ホバー時のナビゲーションボタンのスタイル */
.slideshow-nav:hover {
    background-color: rgba(0, 0, 0, 0.6);
}

/* モバイル対応 */
@media (max-width: 768px) {
    .slideshow-nav {
        width: 30px;
        height: 30px;
        font-size: 18px;
    }
    
    .slideshow-dot {
        width: 8px;
        height: 8px;
        margin: 0 3px;
    }
}
/* ナビゲーションボタンの共通スタイル */
.slideshow-nav {
    opacity: 1; /* 元の値は0でホバー時のみ表示、常時表示するために1に変更 */
}

/* ホバー時のナビゲーションボタンの表示（不要なので削除） */
/* .slideshow:hover .slideshow-nav {
    opacity: 1;
} */

/* ナビゲーションドットのコンテナ */
.slideshow-dots {
    width: 100%; /* 画像の幅全体を使用するように指定 */
}

/* モバイル対応 */
@media (max-width: 768px) {
    .slideshow-nav {
        /* opacity: 1; の記述は不要になったので削除 */
    }
}
</style>
<?php 
// フッターからデフォルトのウィジェットを削除
if (function_exists('remove_action')) {
    remove_action('wp_footer', 'wp_widget_recent_entries_render');
    remove_action('wp_footer', 'wp_widget_categories_render');
    remove_action('wp_footer', 'wp_widget_popular_posts_render');
}

get_footer();
?>
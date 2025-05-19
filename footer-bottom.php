<?php //フッターの最下部のテンプレート
/**
 * Cocoon WordPress Theme
 * @author: yhira
 * @link: https://wp-cocoon.com/
 * @license: http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 */
if (!defined('ABSPATH')) exit; ?>

<!-- フッター -->
<footer class="site-footer">
  <div class="footer-container">
    <!-- ロゴと問い合わせセクション -->
    <div class="footer-left">
      <div class="footer-logo">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo_kodomoplus.png" alt="こどもプラス">
      </div>
      <p class="footer-info">ご不明点はこちらをご確認ください</p>
      <a href="#" class="footer-btn">よくある質問</a>
      <p class="footer-info">解決しない場合はこちらへ</p>
      <a href="#" class="footer-btn">お問い合わせ</a>
      
      <nav class="footer-nav">
        <ul>
          <li><a href="#">こどもプラスについて</a></li>
          <li><a href="#">お役立ちコラム</a></li>
          <li><a href="#">会社概要</a></li>
          <li><a href="#">プライバシーポリシー</a></li>
        </ul>
      </nav>
    </div>
    
    <!-- 求人検索リンク -->
    <div class="footer-center">
      <h3 class="footer-heading">求人を探す</h3>
      <div class="footer-divider"></div>
      
      <!-- エリアから探す -->
      <div class="footer-search-group">
        <h4 class="search-category"><span class="dot"></span>エリアから探す</h4>
        <div class="search-links">
          <div class="search-region">
            <p class="region-title">関東エリア：</p>
            <p class="region-links">東京、神奈川、埼玉、千葉、茨城、栃木、群馬</p>
          </div>
          <div class="search-region">
            <p class="region-title">近畿エリア：</p>
            <p class="region-links">大阪、兵庫、京都、滋賀、奈良、和歌山</p>
          </div>
          <div class="search-region">
            <p class="region-title">東海エリア：</p>
            <p class="region-links">愛知、静岡、岐阜、三重</p>
          </div>
          <div class="search-region">
            <p class="region-title">北海道・東北エリア：</p>
            <p class="region-links">北海道、宮城、福島、青森、岩手、山形、秋田</p>
          </div>
          <div class="search-region">
            <p class="region-title">北陸・甲信越エリア：</p>
            <p class="region-links">新潟、長野、石川、富山、山梨、福井</p>
          </div>
          <div class="search-region">
            <p class="region-title">中国・四国エリア：</p>
            <p class="region-links">広島、岡山、山口、島根、鳥取、愛媛、香川、徳島、高知</p>
          </div>
          <div class="search-region">
            <p class="region-title">九州・沖縄エリア：</p>
            <p class="region-links">福岡、熊本、鹿児島、長崎、大分、宮崎、佐賀、沖縄</p>
          </div>
        </div>
      </div>
      
      <!-- 職種から探す -->
      <div class="footer-search-group">
        <h4 class="search-category"><span class="dot"></span>職種から探す</h4>
        <ul class="search-list">
          <li>児童発達支援管理責任者</li>
          <li>児童指導員</li>
          <li>保育士</li>
          <li>理学療法士</li>
          <li>作業療法士</li>
          <li>言語聴覚士</li>
          <li>その他</li>
        </ul>
      </div>
      
      <!-- 雇用形態から探す -->
      <div class="footer-search-group">
        <h4 class="search-category"><span class="dot"></span>雇用形態から探す</h4>
        <ul class="search-list">
          <li>正社員</li>
          <li>パート・アルバイト</li>
          <li>その他</li>
        </ul>
      </div>
      
      <!-- 施設形態から探す -->
      <div class="footer-search-group">
        <h4 class="search-category"><span class="dot"></span>施設形態から探す</h4>
        <ul class="search-list">
          <li>放課後等デイサービス・児童発達支援</li>
          <li>放課後等デイサービスのみ</li>
          <li>児童発達支援のみ</li>
        </ul>
      </div>
    </div>
    
    <!-- 特徴から探す -->
    <div class="footer-right">
      <!-- 特徴から探す -->
      <div class="footer-search-group">
        <h4 class="search-category"><span class="dot"></span>特徴から探す</h4>
        <div class="search-features">
          <div class="feature-group">
            <h5 class="feature-title">福利厚生</h5>
            <ul class="feature-list">
              <li>• 賞与あり</li>
              <li>• 退職金あり</li>
              <li>• 研修制度あり</li>
              <li>• 資格取得支援あり</li>
              <li>• 託児所あり</li>
              <li>• 託児施設・保育支援あり</li>
            </ul>
          </div>
          
          <div class="feature-group">
            <h5 class="feature-title">勤務時間</h5>
            <ul class="feature-list">
              <li>• 扶養内勤務OK</li>
              <li>• 曜日相談OK</li>
              <li>• 週1日〜OK</li>
              <li>• 2〜6時間勤務相談OK</li>
              <li>• 短時間OK</li>
              <li>• フルタイム歓迎</li>
              <li>• 残業ほぼなし</li>
            </ul>
          </div>
          
          <div class="feature-group">
            <h5 class="feature-title">アクセス</h5>
            <ul class="feature-list">
              <li>• 駅近（徒歩〜10分以内）</li>
              <li>• 車通勤OK</li>
              <li>• 自転車通勤OK</li>
            </ul>
          </div>
          
          <div class="feature-group">
            <h5 class="feature-title">応募条件・定年</h5>
            <ul class="feature-list">
              <li>• 未経験歓迎</li>
              <li>• 資格保持者歓迎</li>
              <li>• Wワーク・フレックスOK</li>
              <li>• 無資格者相談OK</li>
              <li>• 中高年歓迎（定年60歳以上〜定年なし）</li>
            </ul>
          </div>
          
          <div class="feature-group">
            <h5 class="feature-title">休日</h5>
            <ul class="feature-list">
              <li>• 週休2日制</li>
              <li>• 土日祝休み</li>
              <li>• 夏冬休暇あり</li>
              <li>• リフレッシュ休暇あり</li>
              <li>• 年間休日110日以上</li>
            </ul>
          </div>
          
          <div class="feature-group">
            <h5 class="feature-title">その他</h5>
            <ul class="feature-list">
              <li>• 送迎業務なし</li>
              <li>• 高収入求人</li>
              <li>• オープニングスタッフ</li>
              <li>• 施設見学OK</li>
              <li>• WEB面接OK</li>
              <li>• 急募</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- バナー -->
  <div class="footer-banners">
    <a href="#" class="footer-banner"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/footer-banner1.jpg" alt="運動保育士資格取得"></a>
    <a href="#" class="footer-banner"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/footer-banner2.jpg" alt="運動あそび指導"></a>
    <a href="#" class="footer-banner"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/footer-banner3.jpg" alt="運動教室"></a>
    <a href="#" class="footer-banner"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/footer-banner4.jpg" alt="こどもプラスFC加盟募集サイト"></a>
  </div>
</footer>
<?php
require_once __DIR__ . '/admin_page_header.php';

?><?php require_once __DIR__ . '/../' . 'header.php'; ?>
<style>
    .plugin-header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 64px;
    background: #F4F4F4;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 20px;
    padding-right: 40px;
  }

  .container > h2 {
    font-family: "Lato";
    font-weight: 700;
    font-size: 24px;
    line-height: 30px;
    margin-top: 64px;
  }

  .nav-link-line {
    position: relative;
    align-items: center;
    gap: 30px;
  }

  .nav-link-line:after {
    background-color: #e2e2e3;
    content: "";
    display: inline-block;
    height: 2px;
    position: absolute;
    top: 25px;
    width: 100%;
  }

  .nav-link-line > .nav-link-page > .side-menu__label{
    cursor: pointer;
    font-family: Lato !important;
    font-size: 13px !important;
    font-weight: 400 !important;
    line-height: 14px !important;
    text-decoration: none !important;
    color: #4e4e4e !important;
  }

  .nav-link-line > .nav-link-page > .side-menu__label:hover {
    color: #f98ab1 !important;
    transition: all 0.4s;
  }

  .nav-link-line > .active {
    background: none !important;
    padding: 0 !important;
    margin: 0 10px 0 10px !important;
    border: none;
    border-radius: 0 !important;
    display: flex;
    height: auto;
    font-weight: 700 !important;
  }

  .nav-link-line > .active > .side-menu__label {
    color: #f98ab1 !important;
    position: relative;
  }

  .nav-link-line > .active > .side-menu__label:before {
    background-color: #f98ab1;
    content: "";
    display: inline-block;
    height: 2px;
    position: absolute;
    top: 25px;
    width: 100%;
    z-index: 1000;
  }
  
  .container {
    padding: 40px !important;
    padding-top: 20px !important;
  }

  .user-notiflications, .user-img-header {
    cursor: pointer;
  }

  
.my_acc_popup {
  position: fixed;
  width: 20%;
  border: 1px solid #D4D4D5;
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
  display: flex;
  flex-direction: column;
  gap: 40px;
  z-index: 100000;
  background: #FFF;
  top: -1000px;
  transition: all 0.4s;
  right: 80px;
  justify-content: space-between;
}

.my_acc_popup_active {
  top: 70px;
}

.name_acc {
  margin-bottom: 15px;
  font-weight: 700;
  font-size: 18px;
  line-height: 23px;
  color: #000;
  opacity: 0.9;
}

.email_acc {
margin-bottom: 15px;
font-size: 14px;
line-height: 16px;
font-weight: 400;
color: #000;
opacity: 0.4;
}

.manage_acc {
color: #000;
opacity: 0.8;
font-size: 14px;
line-height: 18px;
text-decoration: underline;
}

.gap-15 {
  gap: 15px;
}

.log-out {
  padding: 15px 0 25px 25px;
  border: 1px solid #707070;
  margin: 0;
  transition: all 0.4s;
}

.log-out:hover {
  background-color: rgba(0, 0, 0, 0.1);
}

.log-out-link {
  font-size: 14px;
  line-height: 18px;
  font-weight: 400;
  color: #000;
  opacity: 0.9;
  text-decoration: none;
  transition: all 0.4s;
}

.log-out-link:hover {
  color: #f98ab1 !important;
}

.user-img-header {
  cursor: pointer;
}

.notiflication-title {
  color: #FFFFFF;
  opacity: 0.9;
  font-size: 24px;
  line-height: 30px;
  font-weight: 700;
  margin-bottom: 15px;
}

.notiflication-tab {
  background-color: #FFF;
  border: 1px solid #D4D4D5;
  margin-bottom: 15px;
  border-radius: 6px;
  height: auto;
  min-height: 428px;
  position: relative;
}

.notiflication-tab-content {
  padding: 20px 60px 15px 10px;
}

.tab-content-title {
  font-weight: 700;
  font-size: 18px;
  line-height: 23px;
  color: #000;
  opacity: 0.9;
  margin-bottom: 25px;
}

h2, p {
  margin: 0;
}

.tab-content-text {
  font-size: 14px;
  font-weight: 400;
  line-height: 18px;
  color: #000;
  opacity: 0.8;
  margin-bottom: 55px;
}

.op-40 {
  opacity: 0.4;
}

.mb-1 {
  margin-bottom: 0.5em;
}

.tab-content-line {
  border-top: 1px solid #DADADB;
  margin-top: -55px;
}

a {
  color: inherit;
  text-decoration: none;
}

a:hover {
  color: inherit;
}

.tab-content-nav {
  padding-left: 25px;
  padding-bottom: 30px;
  position: absolute;
  bottom: 0;
  display: flex;
  gap: 45px;
}

.content-nav-link {
  font-size: 16px;
  line-height: 24px;
  font-weight: 400;
  color: #707070;
  transition: all 0.4s;
}

.adjust-goals {
  color: #F98AB1;
}

.content-nav-link:hover {
  color: #F98AB1;
}

.notiflication-body.notiflication-body-active::-webkit-scrollbar {
  width: 0px;
  background: rgba(255, 255, 255, 0.0);
}

.notiflication-body {
  background-color: #42273A;
  padding: 30px 20px 70px 20px;
  width: 21%;
  min-height: 100vh;
  position: fixed;
  overflow-y: auto;
  top:70px;
  bottom:0;
  right: 100%;
  z-index: 100000;
  transition: all 0.4s;
}

.notiflication-body-active {
  right: 500px;
}

.gap-15 > .user-img-header {
  width: 39px;
  height: 39px;
  border-radius: 50%;
}

.circle-gal-menu {
  width: 30px;
  height: 30px;
  border-radius: 50%;
}

</style>
<div class="sp-admin-overlay">
    <div class="sp-admin-container">
        <?php include __DIR__ . '/../' . "left_sidebar.php"; ?>
        <!-- main-content opened -->
        <div class="main-content horizontal-content">
            <div class="page">
            <?php include __DIR__ . '/../' . "page_header.php"; ?>
                <?php include SP_PLUGIN_DIR_PATH . "pages/header_js.php"; ?>
                <!-- container opened -->
                <div class="container">
                    <h2 class="purchase-or-title"><?php echo esc_html(__( 'Store Performance', QA_MAIN_DOMAIN )); ?></h2>
                    <div class="d-flex nav-link-line" style="margin-top: 40px;">
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_home' ? 'active' : ''); ?>" href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_home')); ?>"><span  class="side-menu__label"> <?php echo esc_html(__('Home', QA_MAIN_DOMAIN)); ?></span></a>
                          <a class="nav-link-page <?php echo esc_attr(sanitize_text_field($_GET['page']) == 'shelf_planner_retail_insights' ? 'active' : ''); ?>"  href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_retail_insights')); ?>"><span class="side-menu__label"> <?php echo esc_html(__('Store Perfomance', QA_MAIN_DOMAIN)); ?></span></a>
                        </div>
                    <?php do_action('after_page_header'); ?>
                    <div class="wrap">
                        <div id="root"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php include __DIR__ . '/../' . "popups.php"; ?>
        <script>
          var userImg = document.querySelectorAll('.user-img-header');
var accPopup = document.querySelectorAll('.my_acc_popup');
var userNotiflication = document.querySelectorAll('.user-notiflications');
var userNotiflicationBody = document.querySelectorAll('.notiflication-body')

for (let i = 0; i < userImg.length; i++) {
  userImg[i].addEventListener('click', (e) => {
    accPopup[i].classList.toggle('my_acc_popup_active')
  })
};

for (let j = 0; j < userNotiflication.length; j++) {
  userNotiflication[j].addEventListener('click', (e) => {
    userNotiflicationBody[j].classList.toggle('notiflication-body-active')
  })
}
        </script>
    </div>
</div>

<?php require_once __DIR__ . '/../' . 'footer.php';

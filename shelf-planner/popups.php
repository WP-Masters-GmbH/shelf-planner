

<style>
    .dismiss-btn {
    border: none;
    background: none;
    padding: 0;
  }

  .tab-content-nav {
  gap: 30px;
  }

  .notiflication-tab {
    margin-bottom: 35px;
  }

  .notiflication-tab-content {
    padding-right: 50px;
  }

  .notiflication-body {
    padding: 30px 30px 70px 30px;
  }
</style>



<div class="my_acc_popup">
  <div style="padding: 15px; padding-left: 25px;">
    <div class="d-flex gap-15">
    <img class="circle-gal-menu user-img-header" src="<?php if(isset($my_account_settings['sp_avatar_account'])) { echo esc_url($my_account_settings['sp_avatar_account']); } else { echo esc_url( plugin_dir_url( __FILE__ ) )."../assets/img/circle-gal.png"; } ?>" >
      <div>
        <p class="name_acc"><?php if(isset($my_account_settings['first_name'])) { echo esc_html($my_account_settings['first_name']); } ?> <?php if(isset($my_account_settings['last_name'])) { echo esc_html($my_account_settings['last_name']); } ?></p>
        <p class="email_acc"><?php if(isset($my_account_settings['email_address'])) { echo esc_html($my_account_settings['email_address']); } ?></p>
        <a href="<?php echo esc_url(admin_url('admin.php?page=shelf_planner_my_account')); ?>" class="manage_acc">
          Manage Account
        </a>
      </div>
    </div>
    </div>
    <label class="log-out h-100">
      <a class="log-out-link" href="<?php echo esc_url(admin_url('admin.php?page=wc-admin')); ?>" id="log-out-btn">
        Log Out
      </a>
    </label>
  </div>
  </div>

  <div class="notiflication-body">
    <div class="notiflication-body-scroll">
    <h2 class="notiflication-title">Good Morning <?php if(isset($my_account_settings['first_name'])) { echo esc_html($my_account_settings['first_name']); } ?></h2>
    <div class="notiflication-tab">
      <div class="notiflication-tab-content">
        <h2 class="tab-content-title">
          Low Stock Items
        </h2>
        <p class="tab-content-text op-40 mb-1">
          From the Shelf Planner Team
        </p>
        <p class="tab-content-text">
          Please have a look at the following items as they are running low on stock.
        </p>
      </div>
      <div class="tab-content-line"></div>
      <div class="tab-content-nav">
        <a class="content-nav-link adjust-goals" href="#">Adjust Goals</a>
        <button class="content-nav-link dismiss-btn" type="button">Dismiss</button>
        <a class="content-nav-link" href="#">Learn More</a>
      </div>
    </div>
    <div class="notiflication-tab">
      <div class="notiflication-tab-content">
        <h2 class="tab-content-title">
          Overstock Stock Items
        </h2>
        <p class="tab-content-text op-40 mb-1">
          From the Shelf Planner Team
        </p>
        <p class="tab-content-text">
          Please have a look at the following items as they are running low on stock.
        </p>
      </div>
      <div class="tab-content-line"></div>
      <div class="tab-content-nav">
        <a class="content-nav-link adjust-goals" href="#">Adjust Goals</a>
        <button type="button" class="content-nav-link dismiss-btn">Dismiss</button>
        <a class="content-nav-link" href="#">Learn More</a>
      </div>
    </div>
    <div class="notiflication-tab">
      <div class="notiflication-tab-content">
        <h2 class="tab-content-title">
          Stock Items
        </h2>
        <p class="tab-content-text op-40 mb-1">
          From the Shelf Planner Team
        </p>
        <p class="tab-content-text">
          Please have a look at the following items as they are running low on stock.
        </p>
      </div>
      <div class="tab-content-line"></div>
      <div class="tab-content-nav">
        <a class="content-nav-link adjust-goals" href="#">Adjust Goals</a>
        <button type="button" class="content-nav-link dismiss-btn">Dismiss</button>
        <a class="content-nav-link" href="#">Learn More</a>
      </div>
    </div>
  </div>
  </div>
  <script>
    const notiflicationTab = document.querySelectorAll('.notiflication-tab');
    const dismissBtn = document.querySelectorAll('.dismiss-btn');
    

    for (let y = 0; y < notiflicationTab.length; y++) {
      dismissBtn[y].addEventListener('click', (e) => {
      notiflicationTab[y].remove();
    })}
  </script>
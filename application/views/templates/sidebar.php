<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url() ?>">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fa fa-book"></i>
    </div>
    <h4 class="sidebar-brand-text mx-3 text-lowercase">Komik King</h4>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider ">

  <?php
  $role_id = $user_data["role_id"];
  $queryMenu = "SELECT * FROM `user_access_menu` JOIN `user_menu` ON `user_access_menu`.`menu_id` = `user_menu`.`menu_id` WHERE `user_access_menu`.`role_id` = $role_id AND `user_menu`.`is_active`";
  $results = $this->db->query($queryMenu)->result_array();


  ?>

  <?php foreach ($results as $result) : ?>
    <!-- Heading -->
    <div class="sidebar-heading">
      <?= $result["menu_name"] ?>
    </div>
    <?php
      $menu_id = $result['menu_id'];
      $querySubMenu = "SELECT * FROM `user_sub_menu` AS `sub_menu` JOIN `user_menu` ON `sub_menu`.`menu_id` =  `user_menu`.`menu_id` WHERE `sub_menu`.`menu_id` = $menu_id AND `sub_menu`.`sm_active` = 1";

      $subs = $this->db->query($querySubMenu)->result_array();

      ?>
    <?php foreach ($subs as $submenu) : ?>
      <!-- Nav Item - Charts -->
      <?php if (strtolower($title) == strtolower($submenu['sm_title'])) : ?>
        <li class="nav-item active">
        <?php else : ?>
        <li class="nav-item">
        <?php endif; ?>
        <a class="nav-link py-2" href="<?= base_url($submenu["sm_url"]) ?>">
          <i class="<?= $submenu["sm_icon"] ?>"></i>
          <span><?= $submenu["sm_title"]; ?></span></a>
        </li>
      <?php endforeach ?>

      <!-- Divider -->
      <hr class="sidebar-divider ">

    <?php endforeach ?>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
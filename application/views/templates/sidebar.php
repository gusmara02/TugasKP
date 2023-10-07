<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
        </div>
        <div class="sidebar-brand-text">BBPMP</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- query menu -->
    <?php
    $role_id = $this->session->userdata('role_id');
    $queryMenu = "SELECT user_menu.id,menu
                    FROM user_menu JOIN user_access_menu
                      ON user_menu.id = user_access_menu.menu_id
                   WHERE user_access_menu.role_id = $role_id
                ORDER BY user_access_menu.menu_id ASC 
                ";
    $menu = $this->db->query($queryMenu)->result_array();
    ?>
    <!-- looping menu -->
    <?php foreach ($menu as $m) : ?>
        <!-- Heading -->
        <div class="sidebar-heading">
            <?php echo $m['menu']; ?>
        </div>

        <!-- siapkan submenu sesuai menu -->
        <?php
        $menuId = $m['id'];
        $querySubMenu = " SELECT * 
                            FROM user_sub_menu
                            JOIN user_menu
                              ON user_sub_menu . menu_id = user_menu . id
                           WHERE user_sub_menu . menu_id = $menuId
                             AND user_sub_menu . is_active = 1 
        ";
        $subMenu = $this->db->query($querySubMenu)->result_array();
        ?>

        <?php foreach ($subMenu as $sm) : ?>
            <?php if ($title == $sm['title']) : ?>
                <li class="nav-item active">
                <?php else : ?>
                <li class="nav-item">
                <?php endif; ?>
                <a class="nav-link pb-0" href="<?php echo base_url($sm['url']); ?>">
                    <i class="<?php echo $sm['icon']; ?>"></i>
                    <span><?php echo $sm['title']; ?></span></a>
                </li>
            <?php endforeach; ?>

            <hr class="sidebar-divider mt-2">

        <?php endforeach; ?>

        <li class="nav-item">
            <a class="nav-link pt-0" href="<?= base_url('auth/logout'); ?>" id="tombol-logout">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

</ul>
<!-- End of Sidebar -->
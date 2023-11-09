 <?php
    $result = $this->db->like("check_in", date("Y-m-d"), "after")->get_where('absensi', ["id_user" => $user["id"]])->row();
    $disabled = false;
    $text = "Anda belum melakukan absensi, klik untuk check in.";
    $color = "success";
    if ($result) {
        $disabled = (strtotime(date("Y-m-d H:i:s")) < strtotime(date("Y-m-d 16:00:00")));
        $text = "Anda sudah check in";
        $color = "secondary";

        if (!$disabled && $result->check_out == null) {
            $text = "Anda sudah bisa checkout, klik untuk check out.";
            $color = "success";
        } else if ($result->check_out != null) {
            $disabled = true;
            $text = "Anda sudah checkout. Terima kasih. :)";
            $color = "success";
        }
    }
    ?>

 <!-- Content Wrapper -->
 <div id="content-wrapper" class="d-flex flex-column">

     <!-- Main Content -->
     <div id="content">

         <!-- Topbar -->
         <nav class="navbar navbar-expand navbar-light bg-light topbar mb-3 static-top shadow">

             <!-- Sidebar Toggle (Topbar) -->
             <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                 <i class="fa fa-bars"></i>
             </button>
             <!-- Topbar Navbar -->
             <ul class="navbar-nav ml-auto">
                 <a href="<?= base_url('absensi/check'); ?>" class="btn btn-<?= $color ?> btn-sm ml-2 my-3 <?= $disabled ? 'disabled' : '' ?>"><?= $text ?></a>
                 <div class="topbar-divider d-none d-sm-block"></div>
                 <!-- Nav Item - User Information -->
                 <li class="nav-item dropdown no-arrow">
                     <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <span class="mr-2 d-none d-lg-inline text-gray-600 small"><strong> <?php echo $user['nama']; ?></strong></span>
                         <img class="img-profile rounded-circle" src="<?= base_url('assets/img/profile/') . $user['image']; ?>">
                     </a>
                     <!-- Dropdown - User Information -->
                     <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                         <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>" data-toggle="modal" data-target="#logoutModal">
                             <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                             Logout
                         </a>
                     </div>
                 </li>
             </ul>

         </nav>
         <!-- End of Topbar -->
         <div class="container-fluid">
             <div class="alert alert-primary alert-dismissible fade show" role="alert">
                 <strong>Selamat Datang,</strong> <?php echo $user['nama']; ?>
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
         </div>
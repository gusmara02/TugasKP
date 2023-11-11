   <!-- Begin Page Content -->
   <div class="container-fluid">
       <div class="flash-data" data-flashdata="<?= $this->session->flashdata('message'); ?>"></div>
       <?php echo $this->session->flashdata('msg'); ?>
       <?php if (validation_errors()) { ?>
           <div class="alert alert-danger">
               <a class="close" data-dismiss="alert">x</a>
               <strong><?php echo strip_tags(validation_errors()); ?></strong>
           </div>
       <?php } ?>
       <h5 class="h5 mb-3 text-gray-800"></h5>
       <!-- Earnings (Monthly) Card Example -->
       <div class="card">
           <h5 class="card-header">
               <strong><?= $title; ?></strong>
               <a class="btn btn-secondary btn-sm float-right" href="<?php echo base_url('admin/add_user'); ?>" data-toggle="modal" data-target="#ubah-pass"><i class="fas fa-key"></i> Ubah Password</a>
               <a class="btn btn-secondary btn-sm float-right mr-2" href="<?php echo base_url('admin/add_user'); ?>" data-toggle="modal" data-target="#ubah-prof"><i class="fas fa-user-edit"></i> Ubah Profile</a>
           </h5>
           <div class="card-body">
               <div class="row">
                   <div class="col-xl-3 col-md-6 mb-4">
                       <div class="card border-left-primary shadow h-100 py-2">
                           <div class="card-body">
                               <div class="row no-gutters align-items-center">
                                   <div class="col mr-2">
                                       <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Status Pengajuan Cuti</div>
                                       <?php if ($sisa_cuti['is_approve'] == 2) : ?>
                                           <div class="h5 mb-0 font-weight-bold text-gray-800">Cuti Ditolak</div>
                                       <?php elseif ($sisa_cuti['is_approve'] == 0) : ?>
                                           <div class="h5 mb-0 font-weight-bold text-gray-800">Cuti Disetujui</div>
                                       <?php else : ?>
                                           <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count; ?> Menunggu</div>
                                       <?php endif; ?>
                                   </div>
                                   <div class="col-auto">
                                       <i class="far fa-calendar-alt fa-2x text-gray-300"></i>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- Earnings (Monthly) Card Example -->
                   <div class="col-xl-3 col-md-6 mb-4">
                       <div class="card border-left-success shadow h-100 py-2">
                           <div class="card-body">
                               <div class="row no-gutters align-items-center">
                                   <div class="col mr-2">
                                       <div class="text-xs font-weight-bold text-success text-uppercase mb-1">History Cuti</div>
                                       <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="<?php echo base_url('staf/history'); ?>" style="text-decoration:none;"><?php echo $history_count; ?> List Cuti</a></div>
                                   </div>
                                   <div class="col-auto">
                                       <i class="far fa-edit fa-2x text-gray-300"></i>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- Earnings (Monthly) Card Example -->
                   <div class="col-xl-3 col-md-6 mb-4">
                       <div class="card border-left-info shadow h-100 py-2">
                           <div class="card-body">
                               <div class="row no-gutters align-items-center">
                                   <div class="col mr-2">
                                       <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Cuti Lain</div>
                                       <div class="row no-gutters align-items-center">
                                           <div class="col-auto">
                                               <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><a href="<?php echo base_url('staf/history_cutilain'); ?>" style="text-decoration:none;"><?php echo $history_countcutilain; ?> List Cuti</a></div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="col-auto">
                                       <i class="fas fa-edit fa-2x text-gray-300"></i>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- Pending Requests Card Example -->
                   <div class="col-xl-3 col-md-6 mb-4">
                       <div class="card border-left-warning shadow h-100 py-2">
                           <div class="card-body">
                               <div class="row no-gutters align-items-center">
                                   <div class="col mr-2">
                                       <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Info Saya</div>
                                       <div class="h5 mb-0 font-weight-bold text-gray-800"><a href="#" style="text-decoration:none;">Detail</a></div>
                                   </div>
                                   <div class="col-auto">
                                       <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>

               <div class="row">
                   <div class="col-md-12">
                       <canvas id="drawChart"></canvas>
                   </div>
               </div>
               <!-- Content Row -->
               <div class="row">
                   <div class="col-md-4">
                       <div class="card border-light mb-3 mr-1">
                           <div class="card-body text-dark">
                               <span class="card-text" style="font-size:20px;"><strong>Status Cuti</strong></span><br>
                               <?php if ($sisa_cuti['kode_unik'] == NULL) : ?>
                                   <span class="font-weight-bolder">Tidak Ada Data</span>
                               <?php else : ?> <?php if ($sisa_cuti['is_approve'] == 2) : ?> <div class="alert alert-warning" role="alert">
                                           <h5 class="text-danger" style="font-weight:700;"> <strong>CUTI DITOLAK</strong> </h5>
                                           <a href="<?php echo base_url('staf/add_cuti'); ?>" class="btn btn-info btn-sm">Ajukan Ulang</a>
                                           <ul>
                                           <?php else : ?>
                                               <li> Tanggal :<strong> <?php echo $sisa_cuti['cuti']; ?> s/d <?php echo $sisa_cuti['cuti2']; ?></strong> </li>
                                               <li> Keterangan : <strong> <?php echo $sisa_cuti['keterangan']; ?></strong></li>
                                               <li> Ambil Cuti : <strong> <?php echo $sisa_cuti['jml_cuti']; ?> hari</strong></li>
                                               <li> Sisa Cuti : <strong> <?php echo $sisa_cuti['sisa_cuti']; ?> hari</strong></li>
                                               <?php if ($sisa_cuti['is_approve'] == 1) : ?>
                                                   <li><strong>Status : </strong><strong><span class="font-weight-bolder" style="font-size:18px;">Menunggu</span></strong>
                                                       <strong><a href="<?php echo base_url(); ?>staf/edit_cuti/<?php echo $sisa_cuti['id']; ?>" class="btn btn-dark btn-sm"><i class="fas fa-edit"></i> Edit Data</a></strong>
                                                   </li>
                                               <?php else : ?>
                                                   <li>Status : <strong><span class="font-weight-bolder" style="font-size:18px;">Disetujui</span></strong>
                                                       <a class="btn btn-primary btn-sm" href="<?php echo base_url(); ?>staf/cetak_data/<?php echo $sisa_cuti['id']; ?>" target="_blank" role="button"><i class="fas fa-print"></i> Cetak Data</a>
                                                   </li>
                                               <?php endif; ?>
                                           <?php endif; ?>
                                           </ul>
                                       <?php endif; ?>
                                       </div>
                           </div>
                       </div>
                       <div class="col-md-8">
                           <ul class="nav nav-tabs">
                               <li class="active"><a data-toggle="tab" href="#menu1" class="btn btn-light btn-sm font-weight-bolder mr-2">Cuti Tahunan</a></li>
                               <li><a data-toggle="tab" href="#menu2" class="btn btn-light btn-sm font-weight-bolder">Cuti Lain</a></li>
                           </ul>
                           <div class="tab-content">
                               <div id="menu1" class="tab-pane fade">
                                   <div class="table-responsive mt-3">
                                       <table class="table table-hover" id="id-table">
                                           <thead>
                                               <tr>
                                                   <th scope="col">#</th>
                                                   <th scope="col">Cuti Awal</th>
                                                   <th scope="col">Cuti akhir</th>
                                                   <th scope="col">Masuk</th>
                                                   <th scope="col">Status</th>
                                               </tr>
                                           </thead>
                                           <tbody>
                                               <?php $i = 1; ?>
                                               <?php foreach ($cuti_user as $cu) : ?>
                                                   <tr>
                                                       <th><?php echo $i++; ?></th>
                                                       <td><?php echo format_indo($cu['cuti']); ?></td>
                                                       <td><?php echo format_indo($cu['cuti2']); ?></td>
                                                       <td><?php echo format_indo($cu['masuk']); ?></td>
                                                       <?php if ($cu['is_approve'] == 1) : ?>
                                                           <td>Tunggu</td>
                                                       <?php elseif ($cu['is_approve'] == 2) : ?>
                                                           <td>Ditolak</td>
                                                       <?php else : ?>
                                                           <td>Diterima</td>
                                                       <?php endif; ?>
                                                   </tr>
                                               <?php endforeach; ?>
                                           </tbody>
                                       </table>
                                   </div>
                               </div>
                               <div id="menu2" class="tab-pane fade">
                                   <div class="table-responsive mt-3">
                                       <table class="table table-hover" id="table-id">
                                           <thead>
                                               <tr>
                                                   <th scope="col">#</th>
                                                   <th scope="col">Cuti Awal</th>
                                                   <th scope="col">Cuti Akhir</th>
                                                   <th scope="col">Masuk</th>
                                                   <th scope="col">Status</th>
                                               </tr>
                                           </thead>
                                           <tbody>
                                               <?php $i = 1; ?>
                                               <?php foreach ($cuti_lain_user as $clu) : ?>
                                                   <tr>
                                                       <th><?php echo $i++; ?></th>
                                                       <td><?php echo format_indo($clu['cuti']); ?></td>
                                                       <td><?php echo format_indo($clu['cuti2']); ?></td>
                                                       <td><?php echo format_indo($clu['masuk']); ?></td>
                                                       <?php if ($clu['is_approve'] == 1) : ?>
                                                           <td>Tunggu</td>
                                                       <?php elseif ($clu['is_approve'] == 2) : ?>
                                                           <td>Ditolak</td>
                                                       <?php else : ?>
                                                           <td>Diterima</td>
                                                       <?php endif; ?>
                                                   </tr>
                                               <?php endforeach; ?>
                                           </tbody>
                                       </table>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       <!-- /.container-fluid -->

       <div class="modal fade" id="ubah-prof" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel">Ubah Profile</h5>
                   </div>
                   <div class="modal-body">
                       <?php echo form_open_multipart('staf/edit'); ?>
                       <div class="form-group row">
                           <label class="col-sm-2 col-form-label">Username</label>
                           <div class="col-sm-10">
                               <input type="text" class="form-control form-control-sm" name="username" value="<?php echo $user['username']; ?>" readonly>
                           </div>
                       </div>
                       <div class="form-group row">
                           <label class="col-sm-2 col-form-label">NIP</label>
                           <div class="col-sm-10">
                               <input type="text" class="form-control form-control-sm" name="nik" value="<?php echo $user['nik']; ?>" readonly>
                           </div>
                       </div>
                       <div class="form-group row">
                           <label class="col-sm-2 col-form-label">Nama</label>
                           <div class="col-sm-10">
                               <input type="text" class="form-control form-control-sm" name="nama" value="<?php echo $user['nama']; ?>">
                           </div>
                       </div>
                       <div class="form-group row">
                           <label class="col-sm-2 col-form-label">Jabatan</label>
                           <div class="col-sm-10">
                               <input type="text" class="form-control form-control-sm" name="jabatan" value="<?php echo $user['jabatan']; ?>">
                           </div>
                       </div>
                       <div class="form-group row">
                           <label class="col-sm-2 col-form-label">Unit Kerja</label>
                           <div class="col-sm-10">
                               <input type="text" class="form-control form-control-sm" name="bagian" value="<?php echo $user['bagian']; ?>">
                           </div>
                       </div>
                       <div class="form-group row">
                           <div class="col-sm-2">Photo</div>
                           <div class="col-sm-10">
                               <div class="row">
                                   <div class="col-sm-3">
                                       <img src="<?php echo base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail" id="imgProfile">
                                   </div>
                                   <div class="col-sm-9">
                                       <div class="custom-file">
                                           <input type="file" class="custom-file-input" name="image" id="inputImgProfile" onchange="editProfileImageUpdated()">
                                           <label class="custom-file-label" for="image">Choose file</label>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                       <button type="submit" class="btn btn-primary">Simpan Perubahan </button>
                       <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                       </form>
                   </div>
               </div>
           </div>
       </div>

       <div class="modal fade" id="ubah-pass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel">Ubah Password</h5>
                   </div>
                   <div class="modal-body">
                       <form action="<?php echo base_url('staf/changepassword'); ?>" method="post">
                           <div class="form-group">
                               <label>Password Lama</label>
                               <input type="password" class="form-control form-control-sm" name="current_password" required>
                           </div>
                           <div class="form-group">
                               <label>Password Baru</label>
                               <input type="password" class="form-control form-control-sm" name="new_password1" required>
                           </div>
                           <div class="form-group">
                               <label>Ulang Password</label>
                               <input type="password" class="form-control form-control-sm" name="new_password2" placeholder="Ketik ulang password baru" required>
                           </div>
                           <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                           <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                       </form>
                   </div>
               </div>
           </div>
       </div>
       <!-- Chart JS -->
       <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
       <script>
           const ctx = document.getElementById('drawChart');

           new Chart(ctx, {
               type: 'line',
               data: <?php echo json_encode($chartData)?>,
               options: {
                   scales: {
                       y: {
                           beginAtZero: true
                       }
                   }
               }
           });
       </script>
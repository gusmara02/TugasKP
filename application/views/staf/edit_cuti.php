 <!-- Begin Page Content -->
 <div class="container-fluid">
     <div class="card">
         <div class="flash-data" data-flashdata="<?= $this->session->flashdata('message'); ?>"></div>
         <?php echo $this->session->flashdata('msg'); ?>
         <?php if (validation_errors()) { ?>
             <div class="alert alert-danger">
                 <a class="close" data-dismiss="alert">x</a>
                 <strong><?php echo strip_tags(validation_errors()); ?></strong>
             </div>
         <?php } ?>
         <div class="card-header">
             <strong><?= $title; ?></strong>
             <a href="javascript:window.history.go(-1);" class="btn btn-secondary btn-sm float-right">Kembali</a>
         </div>

         <div class="card-body">
             <form action="<?php echo base_url('staf/edit_cuti'); ?>" method="post">
                 <div class="row">
                     <div class="col-lg-6">
                         <div class="row">
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <input type="hidden" name="id" value="<?php echo $sisa_cuti['id']; ?>">
                                     <input type="hidden" name="role_id" value="<?php echo $sisa_cuti['role_id']; ?>">
                                     <label for="input">Tgl Input</label>
                                     <input type="text" placeholder="tanggal/bulan/tahun" onfocus="(this.type='date')"  onblur="(this.type='text')" id="input" name="input" class="form-control" value="<?php echo $sisa_cuti['input']; ?>" readonly>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="nik">NIP</label>
                                     <input type="text" id="nik" name="nik" class="form-control" value="<?php echo $sisa_cuti['nik']; ?>" readonly>
                                 </div>
                             </div>
                         </div>
                         <div class="form-group">
                             <label for="nama">Nama</label>
                             <input type="text" id="nama" name="nama" class="form-control" value="<?php echo $sisa_cuti['nama']; ?>" readonly>
                         </div>
                         <div class="form-group">
                             <label for="bagian">Unit Kerja</label>
                             <input type="text" id="bagian" name="bagian" class="form-control" value="<?php echo $sisa_cuti['bagian']; ?>" readonly>
                         </div>
                         <div class="form-group">
                             <label for="jabatan">Jabatan</label>
                             <input type="text" id="jabatan" name="jabatan" class="form-control" value="<?php echo $sisa_cuti['jabatan']; ?>" readonly>
                         </div>
                         <div class="form-group">
                             <label for="jenisCuti">Jenis Cuti</label>
                             <input type="text" id="jenisCuti" name="jenis_cuti" class="form-control" value="<?php echo $sisa_cuti['jenis_cuti']; ?>">
                         </div>
                     </div>
                     <div class="col-lg-6">
                         <div class="form-group">
                             <label for="ket">Keterangan :</label>
                             <input type="text" id="ket" name="keterangan" class="form-control" value="<?php echo $sisa_cuti['keterangan']; ?>">
                             <?php echo form_error('keterangan', '<small class="text-danger pl-1">', '</small>'); ?>
                         </div>
                         <div class="row">
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label for="txt1">Cuti Terakhir</label>
                                     <input type="text" id="txt1" class="form-control" value="<?php echo ($sisa_cuti['jml_cuti'] + $sisa_cuti['sisa_cuti']); ?>" readonly>
                                 </div>
                             </div>
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label for="txt2">Ambil Cuti</label>
                                     <input type="text" id="txt2" name="jml_cuti" class="form-control" onkeyup="sum();" value="<?php echo $sisa_cuti['jml_cuti']; ?>">
                                     <?php echo form_error('jml_cuti', '<small class="text-danger pl-1">', '</small>'); ?>
                                 </div>
                             </div>
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label for="txt3">Sisa Cuti</label>
                                     <input type="text" id="txt3" name="sisa_cuti" class="form-control" value="<?php echo $sisa_cuti['sisa_cuti']; ?>" readonly>
                                     <?php echo form_error('sisa_cuti', '<small class="text-danger pl-1">', '</small>'); ?>
                                 </div>
                             </div>
                         </div>
                         <div class="row">
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label for="tglCuti1">Tgl Awal Cuti</label>
                                     <input type="text" placeholder="tanggal/bulan/tahun" onfocus="(this.type='date')"  onblur="(this.type='text')" id="tglCuti1" name="cuti" class="form-control" value="<?php echo $sisa_cuti['cuti']; ?>">
                                 </div>
                             </div>
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label for="tglCuti2">Tgl Akhir Cuti</label>
                                     <input type="text" placeholder="tanggal/bulan/tahun" onfocus="(this.type='date')"  onblur="(this.type='text')" id="tglCuti2" name="cuti2" class="form-control" value="<?php echo $sisa_cuti['cuti2']; ?>">
                                 </div>
                             </div>
                             <div class="col-md-4">
                                 <div class="form-group">
                                     <label for="tglMasuk">Masuk</label>
                                     <input type="text" placeholder="tanggal/bulan/tahun" onfocus="(this.type='date')"  onblur="(this.type='text')" id="tglMasuk" name="masuk" class="form-control" value="<?php echo $sisa_cuti['masuk']; ?>">
                                 </div>
                             </div>
                         </div>
                         <div class="form-group">
                             <label for="alamat">Alamat</label>
                             <input type="text" id="alamat" name="alamat" class="form-control" value="<?php echo $sisa_cuti['alamat']; ?>">
                         </div>
                         <div class="form-group">
                             <label for="telp">Telp</label>
                             <input type="varchar" id="telp" name="telp" class="form-control" value="<?php echo $sisa_cuti['telp']; ?>">
                         </div>
                     </div>
                 </div>
                 <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
         </div>
     </div>
     </form>
 </div>

 </div>
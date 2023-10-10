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
	<div class="card">

		<div class="card-body">
			<div class="card">
				<div class="card-header">
					<a class="btn btn-primary" href="<?php echo base_url('admin/add_user'); ?>" data-toggle="modal" data-target="#add-user"><i class="fas fa-user-plus"></i> Tambah Gaji Berkala</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-hover" id="table-id">
							<thead>
								<tr>
									<th scope="col">#</th>
									<th scope="col">Nama</th>
									<th scope="col">NIP</th>
									<th scope="col">Jabatan</th>
									<th scope="col">Unit Kerja</th>
									<th scope="col">Tanggal Cetak</th>
									<th scope="col">Opsi</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1; ?>
								<?php foreach ($daftar_gaji_berkala as $item) : ?>
									<tr>
										<td><?php echo $i++; ?></td>
										<td><?php echo $item['nama']; ?></td>
										<td><?php echo $item['nik']; ?></td>
										<td><?php echo $item['jabatan']; ?></td>
										<td><?php echo $item['bagian']; ?></td>
										<td><?php echo $item['tgl_cetak']; ?></td>
										<td>
											<a href="#" class="tombol-edit btn btn-warning btn-block btn-sm"><i class="fas fa-print"></i></a>
											<button class="tombol-edit btn btn-info btn-block btn-sm" data-id="<?php echo $item['id']; ?>" data-toggle="modal" data-target="#edit-user"><i class="fas fa-edit"></i></button>
											<a href="<?php echo base_url('admin/delete_gaji_berkala'); ?>?id=<?php echo $item["id"]; ?>" class="tombol-edit btn btn-danger btn-block btn-sm"><i class="fas fa-trash"></i></a>
										</td>
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
<!-- /.container-fluid -->


<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Gaji Berkala</h5>
			</div>
			<div class="modal-body">
				<form action="<?php echo base_url('admin/add_gaji_berkala'); ?>" method="post">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Pilih User:</label>
								<select class="form-control form-control-sm" name="id_user" id="select-user">
									<option value="">Pilih User</option>
									<?php foreach ($daftar_user as $item) : ?>
										<option value="<?php echo $item["id"]; ?>"><?php echo $item["nama"]; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="form-group">
								<label>Nama Lengkap :</label>
								<input type="text" class="form-control form-control-sm" name="nama" id="nama" readonly>
							</div>
							<div class="form-group">
								<label>Jabatan :</label>
								<input type="text" class="form-control form-control-sm" name="jabatan" id="jabatan" readonly>
							</div>
							<div class="form-group">
								<label>Unit Kerja :</label>
								<input list="bagian" class="form-control form-control-sm" name="bagian" id="bagian" readonly>
							</div>
							<div class="form-group">
								<label>NIP :</label>
								<input type="text" class="form-control form-control-sm" name="nik" id="nik" readonly>
							</div>
							<div class="form-group">
								<label>Tanggal Cetak :</label>
								<input type="date" id="tgl_cetak" name="tgl_cetak" class="form-control" value="<?php echo date("Y-m-d"); ?>">
							</div>
						</div>
					</div>
					<button type="submit" name="add_user" class="btn btn-primary">Simpan Data</button>
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
			</div>
			<div class="modal-body">
				<form action="<?php echo base_url('admin/add_gaji_berkala'); ?>" method="post">
					<input type="hidden" class="form-control form-control-sm" name="id" id="edit_id" readonly>
					<div class="form-group">
						<label>Nama Lengkap :</label>
						<input type="text" class="form-control form-control-sm" name="nama" id="edit_nama" readonly>
					</div>
					<div class="form-group">
						<label>Jabatan :</label>
						<input type="text" class="form-control form-control-sm" name="jabatan" id="edit_jabatan" readonly>
					</div>
					<div class="form-group">
						<label>Unit Kerja :</label>
						<input list="bagian" class="form-control form-control-sm" name="bagian" id="edit_bagian" readonly>
					</div>
					<div class="form-group">
						<label>NIP :</label>
						<input type="text" class="form-control form-control-sm" name="nik" id="edit_nik" readonly>
					</div>
					<div class="form-group">
						<label>Tanggal Cetak :</label>
						<input type="date" id="edit_tgl_cetak" name="tgl_cetak" class="form-control" value="<?php echo date("Y-m-d"); ?>">
					</div>
					<button type="submit" name="add_user" class="btn btn-primary">Simpan Data</button>
					<button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
				</form>
			</div>
		</div>
	</div>
</div>



<script>
	$('.tombol-edit').on('click', function() {
		const id = $(this).data('id');
		$.ajax({
			url: '<?php echo base_url('admin/get_gaji_berkala'); ?>',
			data: {
				id: id
			},
			method: 'post',
			dataType: 'json',
			success: function(data) {
				$('#edit_id').val(data.id);
				$('#edit_nama').val(data.nama);
				$('#edit_jabatan').val(data.jabatan);
				$('#edit_bagian').val(data.bagian);
				$('#edit_nik').val(data.nik);
				$('#edit_tgl_cetak').val(data.tgl_cetak);
			}
		});
	});

	$('#select-user').on('change', function() {
		const id = $(this).find(":selected").val();
		$.ajax({
			url: '<?php echo base_url('admin/get_user'); ?>',
			data: {
				id: id
			},
			method: 'post',
			dataType: 'json',
			success: function(data) {
				$('#nama').val(data.nama);
				$('#jabatan').val(data.jabatan);
				$('#bagian').val(data.bagian);
				$('#nik').val(data.nik);
			}
		});
	});
</script>
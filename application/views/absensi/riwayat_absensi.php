<!-- Begin Page Content -->
<div class="container-fluid">
  <!-- Page Heading -->
  <div class="card">
    <h5 class="card-header">
      <strong><?= $title; ?></strong>
    </h5>
    <div class="card-body">
      <table class="table table-hover" id="table-id">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">NIK</th>
            <th scope="col">Nama</th>
            <th scope="col">Bagian</th>
            <th scope="col">Check In</th>
            <th scope="col">Check Out</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php foreach ($absensi as $item) : ?>
            <tr>
              <th scope="row"><?php echo $i++;  ?></th>
              <td><?php echo $item['nik']; ?></td>
              <td><?php echo $item['nama']; ?></td>
              <td><?php echo $item['bagian']; ?></td>
              <td><?php echo format_indo_datetime($item['check_in']); ?></td>
              <td><?php echo $item['check_out'] ? format_indo_datetime($item['check_out']) : "Belum checkout"; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>


</div>
<!-- /.container-fluid -->
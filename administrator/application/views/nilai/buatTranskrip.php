<section class="content-header">
      <br>
      <ol class="breadcrumb">
        <li><a href="../admin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Transkrip Nilai</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-body">

			<!-- Menampilkan Transkrip Nilai -->
			<?php
				$ci = get_instance(); // Memanggil object utama
				$ci->load->helper('my_function'); // Memanggil fungsi pada helper dengan nama my_function
			?>
			<center>
					<legend>TRANKRIP NILAI</legend>
					<table>
						<tr>
							<td>NIM </td> <td> : <?php echo $nim; ?></td>
						</tr>
						<tr>
							<td>Nama </td><td> : <?php echo $nama; ?></td>
						</tr>
						<tr>
							<td>Program Studi </td> <td> : <?php echo $prodi; ?></td>
						</tr>
					</table>
					<br />
					<table  class="table table-bordered table table-striped">
					<tr>
						<td>NO</td>
						<td>KODE</td>
						<td>MATAKULIAH</td>
						<td align="center">SKS</td>
						<td align="center">NILAI</td>
						<td align="center">SKOR</td>
					</tr>
				<?php
					$no   =0; // Nomor urut dalam menampilkan data
					$jSks =0; // Jumlah SKS awal yaitu 0
					$jSkor=0; // Jumlah Skor awal yaitu 0

					// Menampilkan data transkrip atau nilai
					foreach ($trans as $row){
					 $no++;
				?>
					 <tr>
					   <td><?php echo $no; ?></td>
					   <td><?php echo $row->kode_matakuliah; ?></td>
					   <td><?php echo $row->nama_matakuliah; ?></td>
					   <td align="center"><?php echo $row->sks; ?></td>
					   <td align="center"><?php echo $row->nilai; ?></td>
					   <td align="center"><?php echo skorNilai($row->nilai,$row->sks); ?></td>
					   <?php
					   $jSks+=$row->sks;
					   $jSkor+=skorNilai($row->nilai,$row->sks);
					   ?>
					 </tr>
				<?php
					}
				?>
					 <tr>
						<td colspan="3">Jumlah </td>
						<td align="center"><?php echo $jSks; ?></td>
						<td>&nbsp;</td>
						<td align="center"><?php echo $jSkor; ?></td>
					  </tr>
					</table>
					Indeks Prestasi : <?php echo number_format($jSkor/$jSks,2); ?>
			</center>



<?php
$ci = get_instance(); // Memanggil object utama
$ci->load->helper('my_function'); // Memanggil fungsi pada helper dengan nama my_function
$ci->load->model('M_krs'); // Memanggil Krs_model yang terdapat pada model
$ci->load->model('M_mahasiswa'); // Memanggil Mahasiswa_model yang terdapat pada model
$ci->load->model('M_matakuliah'); // Memanggil Matakuliah_model yang terdapat pada model
$ci->load->model('M_thn_akad'); // Memanggil Thn_akad_semester_model yang terdapat pada model

$krs             = $ci->M_krs->get_by_id($id_krs[0]); // Menampilkan nilai KRS berdasarkan id
$kode_matakuliah = $krs->kode_matakuliah; // Mengambil data kode_matakuliah
$id_thn_akad     = $krs->id_thn_akad; // Mengambil data id_thn_akad
?>
<section class="content-header">
      <h1>
        Stikom Poltek Cirebon
        <small>code your life with your style</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="admin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo $back ?>">Nilai Permatakuliah</a></li>
        <li class="active"><?php echo $button ?> Nilai Permatakuliah</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-body">

			<!-- Form Input Nilai Permatakuliah Akhir -->
			<center>
				<legend>MASUKKAN NILAI AKHIR</legend>

				<table>
					<tr>
						<td>Kode Matakuliah </td>
						<td>: <?php echo $kode_matakuliah;?></td>
					</tr>
					<tr>
						<td>Matakuliah</td>
						<td> : <?php echo $ci->Matakuliah_model->get_by_id($kode_matakuliah)->nama_matakuliah;?></td>
					</tr>
					<tr>
						<td>SKS </td>
						<td> : <?php echo $ci->Matakuliah_model->get_by_id($kode_matakuliah)->sks;?></td>
					</tr>
					<?php
						$thn      = $ci->Thn_akad_semester_model->get_by_id($id_thn_akad); // Memanggil data berdasarkan id
						$semester = $thn->semester == 1; // Semester ditampilkan dalam bentuk interger yaitu 1 (ganjil dan 2 (genap)

						// Merubah data semester dalam bentuk string
						if($semester){
							$tampilSemester = "Ganjil";
						}
						else{
							$tampilSemester = "Genap";
						}
					?>

					<tr>
						<td>Tahun akademik (semester)</td> <td> : <?php echo $thn->thn_akad ." (". $tampilSemester .")"; ?> </td>
					</tr>
				</table>
			</center>
			<div>&nbsp;</div>
				<table  class="table table-bordered table table-striped">
					<tr>
						<td>NO</td>
						<td>NIM</td>
						<td>NAMA LENGKAP</td>
						<td>NILAI</td>
					</tr>
				<?php
					$no=0;
					for ($i=0; $i<sizeof($id_krs); $i++)
					{
					 $no++;
				?>
					<tr>
						<td><?php echo $no; ?></td>
					        <?php $nim = $ci->Krs_model->get_by_id($id_krs[$i])->nim; ?>
					    <td><?php echo $nim; ?></td>
					    <td><?php echo $ci->Mahasiswa_model->get_by_id($nim)->nama_lengkap; ?></td>
					    <td><?php echo $ci->Krs_model->get_by_id($id_krs[$i])->nilai; ?></td>
					</tr>
				<?php
					}
				?>
				</table>
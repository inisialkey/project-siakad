<section class="content-header">
      <br>
      <ol class="breadcrumb">
        <li><a href="../admin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cetak Transkrip Nilai</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-body">

			<!-- Form Cetak Transkrip Nilai -->
			<h2 style="margin-top:0px">Cetak Transkrip Nilai </h2>
			<form action="<?php echo $action; ?>" method="POST">
				<?php echo validation_errors(); ?>

				<div class="form-group">
					<label for="char">NIM <?php echo form_error('nim') ?></label>
					<input type="text" class="form-control" name="nim" id="nim" placeholder="NIM" value="<?php echo $nim; ?>" />
				</div>

				<button type="submit" class="btn btn-primary">Proses</button>

			</form>

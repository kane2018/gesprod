<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar_gerant') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                
                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Nouveau Frais</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php echo form_open(uri_string()); ?>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nomfrais">Nom du Frais</label>
                            <?php echo form_input($nomfrais, null, array('class' => 'form-control')); ?>
                        </div>
                        <div class="form-group">
                            <label for="somme">Somme du frais</label>
                            <?php echo form_input($somme, null, array('class' => 'form-control')); ?>
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                    </form>
                </div>
                <!-- /.box -->
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->    

<?php $this->load->view('include/pied') ?>
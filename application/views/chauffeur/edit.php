<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h1><?php echo lang('create_group_heading'); ?></h1>
                <p><?php echo lang('create_group_subheading'); ?></p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Example</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php echo form_open(uri_string()); ?>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nom">Prénom du chauffeur</label>
                            <?php echo form_input($prenom, null, array('class' => 'form-control')); ?>
                        </div>
                        <div class="form-group">
                            <label for="lieu">Nom du chauffeur</label>
                            <?php echo form_input($nom, null, array('class' => 'form-control')); ?>
                        </div>

                        <div class="form-group">
                            <?php echo lang('create_user_phone_label', 'phone'); ?> <br />
                            <?php echo form_input($phone, null, array('class' => 'form-control', 'data-inputmask' => '\'mask\': \'99 999 99 99\'', 'data-mask' => '')); ?>
                        </div>

                        <div class="form-group">
                            <label for="vehicule">Sélectionner le véhicule</label>
                            
                            <?php echo form_dropdown('vehicule', $vehicules, '', 'class="form-control" id="vehicule"'); ?>
                            
                        </div>

                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>

                    <?php echo form_hidden('id', $chauffeur->idChauf); ?>
                    <?php echo form_hidden($csrf); ?>
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
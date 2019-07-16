<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h1>Créer un véhicule</h1>
                <p>Veuillez entrer les informations du véhicule ci-dessous.</p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Example</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php echo form_open("vehicule/create"); ?>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="immatricule">Immatriculation</label>
                            <?php echo form_input($immatricule, null, array('class' => 'form-control')); ?>
                        </div>
                        <div class="form-group">
                            <label for="marque">Marque</label>
                            <?php echo form_input($marque, null, array('class' => 'form-control')); ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="modele">Modèle</label>
                            <?php echo form_input($modele, null, array('class' => 'form-control')); ?>
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
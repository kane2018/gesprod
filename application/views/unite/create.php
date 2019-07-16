<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h1>Créer une unité</h1>
                <p>Veuillez entrer les informations de l'unité ci-dessous.</p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php echo form_open("unite/create"); ?>
                    <div class="box-body">
                        
                        
                        <div class="form-group">
                            <label for="nom">Nom de l'unité</label>
                            <?php echo form_input($nom, null, array('class' => 'form-control')); ?>
                        </div>
                        <div class="form-group">
                            <label for="prix">Prix de l'unité</label>
                            <?php echo form_input($prix, null, array('class' => 'form-control')); ?>
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
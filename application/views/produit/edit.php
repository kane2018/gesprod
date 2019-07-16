<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h1>Editer un produit</h1>
                <p>Veuillez entrer les informations du produit ci-dessous.</p>

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
                            <label for="vehicule">Sélectionner le dépot</label>
                            
                            <?php echo form_dropdown('depot', $depots, '', 'class="form-control" id="vehicule"'); ?>
                            
                        </div>
                        <div class="form-group">
                            <label for="vehicule">Sélectionner le fournisseur</label>
                            
                            <?php echo form_dropdown('fournisseur', $fournisseurs, '', 'class="form-control" id="vehicule"'); ?>
                            
                        </div>
                        
                        <div class="form-group">
                            <label for="vehicule">Sélectionner la catégorie</label>
                            
                            <?php echo form_dropdown('categorie', $categories, '', 'class="form-control" id="vehicule"'); ?>
                            
                        </div>
                        
                        <div class="form-group">
                            <label for="nom">Nom du produit</label>
                            <?php echo form_input($nom, null, array('class' => 'form-control')); ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="quantite">Quantité du produit</label>
                            <?php echo form_input($quantite, null, array('class' => 'form-control')); ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="prix">Prix du produit</label>
                            <?php echo form_input($prix, null, array('class' => 'form-control')); ?>
                        </div>
                        
                        <!-- select -->
                
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                    <?php echo form_hidden('id', $produit->idProd); ?>
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



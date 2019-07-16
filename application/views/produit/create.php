<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">
                <h1>Ajout d'un produit</h1>
                <p>Veuillez entrer les informations du produit ci-dessous.</p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quick Example</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <?php echo form_open("produit/create"); ?>
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

                            <label for="oui">En stock</label>
                            <input type="radio" name="stock" value="oui" id="oui" />
                            <label for="non">Pas en stock</label>
                            <input type="radio" name="stock" value="non" id="non" checked="checked" />

                        </div>

                        <input type="hidden" name="enstock" value="0" id="enstock"/>

                        <div id="stock">
                            <div class="form-group">
                                <label for="quantite">Quantité du produit</label>
                                <?php echo form_input($quantite, null, array('class' => 'form-control', 'min' => 0)); ?>
                            </div>

                            <div class="form-group">
                                <label for="prix">Prix du produit</label>
                                <?php echo form_input($prix, null, array('class' => 'form-control', 'min' => 0)); ?>
                            </div>

                        </div>

                        <div id="produitcharge">

                            <div class="form-group">
                                <label for="unites">Sélectionner les unités pour ce produit</label>

                                <?php echo form_dropdown('unites[]', $unites, '', 'class="form-control" id="unites" multiple="multiple"'); ?>

                            </div>


                            

                        </div>
                        <!-- select -->

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

<script>
    $(document).ready(function () {

        $("#stock").hide(1000);

        $("input#oui").on('ifChanged', function () {
            $("#stock").slideDown(1000);
            $("#produitcharge").hide(1000);
            $("#enstock").val(1);
        });

        $("input#non").on('ifChanged', function () {
            $("#stock").hide(1000);
            $("#produitcharge").slideDown(1000);
            $("#enstock").val(0);
        });

    });
</script>
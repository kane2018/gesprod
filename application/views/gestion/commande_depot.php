<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <div id="infoMessage"><?php echo $message; ?></div>
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Commande pour un dépôt</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Numéro</th>
                                <th>Nom du produit</th>
                                <th>Nom unité</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Total</th>
                            </tr>
                            
                            <?php foreach ($this->session->userdata('panierDepot') as $p): ?>
                            <tr>
                                <td><?php echo $p['id']; ?></td>
                                <td><?php echo $p['nom']; ?></td>
                                <td><?php echo $p['unite']; ?></td>
                                <td><?php echo $p['quantite']; ?></td>
                                <td><?php echo $p['prix']; ?></td>
                                <td><?php echo $p['total']; ?></td>

                            </tr>
                            <?php endforeach; ?>

                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        
                        <?php if($this->session->userdata('panierDepot') != null): ?>
                        
                        <a class="btn btn-primary" href="<?php echo site_url('commande/finaliserDepot'); ?>">Valider</a>
                        
                        <a class="btn btn-warning pull-right" href="<?php echo site_url('commande/annulerComDepot'); ?>">Annuler</a>
                        
                        <?php endif; ?>

                    </div>
                </div>
                <!-- /.box -->

                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info">
                    Ajouter un produit pour la commande
                </button>

                <div class="modal modal-info fade" id="modal-info">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Préparation Commande</h4>
                            </div>
                            <div class="modal-body">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Nouvelle commande</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <?php echo form_open("commande/createComDepot"); ?>
                                    <div class="box-body">

                                        <div class="form-group">
                                            <label for="categorie" style="color: #000">Sélectionner la catégorie</label>

                                            <?php echo form_dropdown('categorie', $categories, '', 'class="form-control" id="categorie"'); ?>

                                        </div>
                                        <div class="form-group">
                                            <label for="produit" style="color: #000">Sélectionner le produit</label>

                                            <select name="produit" id="produit" class="form-control">
<!--                                                <option></option>-->
                                            </select>

                                        </div>

                                        <div id="typevente">
                                            
                                        </div>

                                        


                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                <!--                                <button type="button" class="btn btn-outline">Save changes</button>-->
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

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

        $("#categorie").change(function (e) {
            var id = encodeURIComponent($("#categorie").val());
            $('#produit').empty();
            if (id !== "") {
                $.ajax({
                    url: '<?php echo site_url('commande/getProduitsDepot'); ?>',
                    method: 'post',
                    data: {idCat: id},
                    dataType: 'json',
                    success: function (json) {
                        $('#produit').append(json);
                    }
                });
            }
        });
        
        $("#produit").change(function (e) {
            var id = encodeURIComponent($("#produit").val());
            $('#typevente').empty();
            if (id !== "") {
                $.ajax({
                    url: '<?php echo site_url('commande/getTypeVente'); ?>',
                    method: 'post',
                    data: {idProd: id},
                    dataType: 'json',
                    success: function (json) {
                        $('#typevente').append(json);
                    }
                });
            }
        });

    });
</script>



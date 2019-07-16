<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar_gerant') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <h1>Produits</h1>
                <p>Ci-dessous se trouve la liste des produits.</p>

                <div id="infoMessage"><?php echo $message; ?></div>

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bordered Table</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">

                        <table class="table table-bordered table-hover table-condensed">
                            <tr>
                                <th>Catégorie</th>
                                <th>Dépôt</th>
                                <th>Fournisseur</th>
                                <th>Nom</th>
                                <th>Quantité</th>
                                <th>Prix</th>
                                <th>Charges</th>
                                <th>Unités</th>
                            </tr>
                            <?php $i = 1;
                            foreach ($produits as $produit): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($produit->nomCat, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($produit->nomDepot, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($produit->company, ENT_QUOTES, 'UTF-8'); ?> <br/><?php echo htmlspecialchars($produit->prenom . ' ' . $produit->nom, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($produit->nomProd, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo $produit->enStock == 1 ? htmlspecialchars($produit->quantite, ENT_QUOTES, 'UTF-8') : ''; ?></td>
                                    <td><?php echo $produit->enStock == 1 ? htmlspecialchars($produit->prix, ENT_QUOTES, 'UTF-8') : ''; ?></td>
                                    <td>
                                        <?php if ($produit->enStock == 0): ?>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-charge<?php echo $i; ?>">
                                                <span class="glyphicon glyphicon-zoom-in"></span>
                                            </button>

                                            <div class="modal modal-info fade" id="modal-charge<?php echo $i; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Détails de charge du produit</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="box box-primary">
                                                                <div class="box-header with-border">
                                                                    <h3 class="box-title"></h3>
                                                                </div>
                                                                <!-- /.box-header -->
                                                                <!-- form start -->

                                                                <div class="box-body table-responsive" style="color: #000077">
                                                                    <table class="table table-bordered table-condensed">
                                                                        <tr>
                                                                            <th>Numéro</th>
                                                                            <th>Somme de la charge</th>
                                                                            <th>Date d'enregistrement</th>
                                                                            <th>Date de modification</th>
                                                                            <th>Action</th>
                                                                        </tr>

        <?php foreach ($produit->charges as $pc): ?>
                                                                            <tr>
                                                                                <td><?php echo $pc->id; ?></td>
                                                                                <td><?php echo $pc->somme; ?></td>
                                                                                <td><?php echo dateFormatTime('d-m-Y H:i:s', $pc->datecreation); ?></td>
                                                                                <td><?php echo $pc->dateupdate != null ? dateFormatTime('d-m-Y H:i:s', $pc->dateupdate) : 'Non modifiée'; ?></td>
                                                                                <?php if($produit->principal == 1): ?>
                                                                                <td><a href="<?php echo site_url('produit/updatecharge/' . $pc->id); ?>" class="btn btn-warning"><span class="fa fa-edit"></span></a></td>
                                                                                <?php endif; ?>
                                                                            </tr>
        <?php endforeach; ?>
                                                                    </table>
                                                                </div>
                                                                <!-- /.box-body -->

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                                            <?php if($produit->principal == 1): ?>
                                                            <a href="<?php echo site_url('produit/addcharge/'.$produit->idProd.'/'.$produit->idDepot); ?>" class="btn btn-primary"><span class="fa fa-plus-square"></span></a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
    <?php if ($produit->enStock == 0): ?>

                                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info<?php echo $i; ?>">
                                                <span class="fa fa-eye"></span>
                                            </button>

                                            <div class="modal modal-info fade" id="modal-info<?php echo $i; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Unités du produit</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="box box-primary">
                                                                <div class="box-header with-border">
                                                                    <h3 class="box-title"></h3>
                                                                </div>
                                                                <!-- /.box-header -->
                                                                <!-- form start -->

                                                                <div class="box-body table-responsive" style="color: #000077">
                                                                    <table class="table table-bordered table-condensed">
                                                                        <tr>
                                                                            <th>Numéro</th>
                                                                            <th>Nom de l'unité</th>
                                                                            <th>Somme</th>
                                                                        </tr>

        <?php foreach ($produit->unites as $pc): ?>
                                                                            <tr>
                                                                                <td><?php echo $pc->id; ?></td>
                                                                                <td><?php echo $pc->nom; ?></td>
                                                                                <td><?php echo $pc->prix; ?></td>
                                                                            </tr>
        <?php endforeach; ?>
                                                                    </table>
                                                                </div>
                                                                <!-- /.box-body -->

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                                            
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>

    <?php endif; ?>

                                    </td>
                                </tr>
    <?php $i++;
endforeach; ?>
                        </table>

                    </div>
                </div>

                


            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php $this->load->view('include/pied') ?>


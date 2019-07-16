<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Livraisons des clients avec nos véhicules</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th>Numéro de la Livraison</th>
                                <th>Chauffeur</th>
                                <th>Véhicule</th>
                                <th>Prénom et Nom du client</th>
                                <th>Date de livraison</th>
                                <th>Détails de la Livraison</th>
                                <th>Statut de la Livraison</th>
                                <th>Valider la Livraison</th>
                                <th>Voir la commande</th>
                                <th>Imprimer la Facture / Voir Facture</th>
                            </tr>

                            <?php $i = 1; foreach ($livraisonClientsAvec as $cc): ?>
                                <tr>
                                    <td><?php echo $cc->idLiv; ?></td>
                                    <td><?php echo $cc->prenomChauf.' '.$cc->nomChauf; ?></td>
                                    <td><?php echo $cc->marque.'<br/> '.$cc->immatricule; ?></td>
                                    <td><?php echo $cc->prenom.' '.$cc->nom; ?></td>
                                    <td><?php echo $cc->dateLiv != null ? dateFormatTime('d-m-Y H:i:s', $cc->dateLiv) : 'Non validé'; ?></td>
                                    <td>

                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info<?php echo $i; ?>">
                                            <span class="fa fa-eye"></span>
                                        </button>

                                        <div class="modal modal-info fade" id="modal-info<?php echo $i; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Détails de la Livraison</h4>
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
                                                                        <th>Nom du frais</th>
                                                                        <th>Somme</th>
                                                                        <th>Action</th>
                                                                    </tr>

                                                                    <?php foreach ($cc->frais as $pc): ?>
                                                                        <tr>
                                                                            <td><?php echo $pc->idFrais; ?></td>
                                                                            <td><?php echo $pc->nomFrais; ?></td>
                                                                            <td><?php echo $pc->somme; ?></td>
                                                                            <td>
                                                                                <?php if($cc->statut == 0): ?>
                                                                                <a href="<?php echo site_url('livraison/annulerFrais/'.$pc->idFrais); ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove-circle"></span></a></td>
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
                                                        <?php if($cc->statut == 0): ?>
                                                        <a href="<?php echo site_url('livraison/ajouterFraisClient/'.$cc->idLiv); ?>" class="btn btn-outline"><span class="fa fa-plus-square"></span></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                        <!-- /.modal -->
                                    </td>
                                    <td>
                                        <?php if($cc->statut == 1): ?> <button class="btn btn-success"><span class="fa fa-thumbs-o-up"></span></button><?php endif; ?>
                                        <?php if($cc->statut == 0): ?> <button class="btn btn-warning"><span class="fa  fa-thumbs-o-down"></span></button><?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($cc->statut == 0): ?>
                                        <a class="btn btn-success" href="<?php echo site_url('livraison/validerLivraisonClient/'.$cc->idLiv); ?>"><span class="glyphicon glyphicon-ok-sign"></span></a>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td><a class="btn btn-info" target="_blank" href="<?php echo site_url('livraison/voirCommandeClient/'.$cc->idCommande); ?>"><span class="glyphicon glyphicon-eye-close"></span></a></td>
                                    <td><a class="btn btn-primary" target="_blank" onclick="rafraichir()" href="<?php echo ($cc->facture == 0) ? site_url('livraison/imprimerFactureClient/'.$cc->idLiv) : site_url('livraison/afficher/'.$cc->facture); ?>"><span class="<?php echo ($cc->facture == 0) ? 'glyphicon glyphicon-print' : 'glyphicon glyphicon-eye-open' ?>"></span></a></td>
                                </tr>
                            <?php $i++; endforeach; ?>

                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">


                    </div>
                </div>
                <!-- /.box -->

                <div class="box">
                    <div class="box-header box-primary">
                        <h3 class="box-title">Livraisons directes des clients</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th>Numéro de la Livraison</th>
                                <th>Prénom et Nom</th>
                                <th>Date de livraison</th>
                                <th>Statut de la Livraison</th>
                                <th>Valider la Livraison</th>
                                <th>Voir la commande</th>
                                <th>Imprimer la Facture / Voir Facture</th>
                            </tr>

                            <?php $i = 1; foreach ($livraisonClientsSans as $cc): ?>
                                <tr>
                                    <td><?php echo $cc->idLiv; ?></td>
                                    <td><?php echo $cc->prenom.' '.$cc->nom; ?></td>
                                    <td><?php echo $cc->dateLiv != null ? dateFormatTime('d-m-Y H:i:s', $cc->dateLiv) : 'Non validé'; ?></td>
                                    
                                    <td>
                                        <?php if($cc->statut == 1): ?> <button class="btn btn-success"><span class="fa fa-thumbs-o-up"></span></button><?php endif; ?>
                                        <?php if($cc->statut == 0): ?> <button class="btn btn-warning"><span class="fa  fa-thumbs-o-down"></span></button><?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($cc->statut == 0): ?>
                                        <a class="btn btn-success" href="<?php echo site_url('livraison/validerLivraisonClient/'.$cc->idLiv); ?>"><span class="glyphicon glyphicon-ok-sign"></span></a>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td><a class="btn btn-info" target="_blank" href="<?php echo site_url('livraison/voirCommandeClient/'.$cc->idCommande); ?>"><span class="glyphicon glyphicon-eye-close"></span></a></td>
                                    <td><a class="btn btn-primary" target="_blank" onclick="rafraichir()" href="<?php echo ($cc->facture == 0) ? site_url('livraison/imprimerFactureClient/'.$cc->idLiv) : site_url('livraison/afficher/'.$cc->facture); ?>"><span class="<?php echo ($cc->facture == 0) ? 'glyphicon glyphicon-print' : 'glyphicon glyphicon-eye-open' ?>"></span></a></td>
                                </tr>
                            <?php $i++; endforeach; ?>

                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">


                    </div>
                </div>
                <!-- /.box -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->    

<?php $this->load->view('include/pied') ?>

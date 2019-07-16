<?php $this->load->view('include/navbar') ?>

<?php $this->load->view('include/main_sidebar_gerant') ?>

<div class="content-wrapper">

    <?php $this->load->view('include/content_header') ?>

    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-offset-1 col-md-10">

                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Commandes ou Achats des clients</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table class="table table-bordered table-condensed" id="commandegerant">
                            <thead>
                            <tr>
                                <th>Numéro de la commande</th>
                                <th>Date de la commande</th>
                                <th>Prénom et Nom du client</th>
                                <th>Adresse du client</th>
                                <th>Téléphone du client</th>
                                <th>Détail de la commande</th>
                                <th>Statut de la commande</th>
                                <th>Livrer la commande</th>
                            </tr>
                            </thead>
                            <tbody>
                                
                                
                            </tbody>

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






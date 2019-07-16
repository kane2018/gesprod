<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">DEPOT : <?php echo $this->session->userdata('nomDepot'); ?></li>
            
            <li class="treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-tasks"></i>
                    <span>Magazins</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo site_url('gerant/mesProduits'); ?>"><i class="fa fa-circle-o"></i>Produit</a></li>
                    <!--<li><a href="<?php echo site_url('unite/index'); ?>"><i class="fa fa-circle-o"></i>Unité de Vente</a></li>-->
                </ul>
            </li>
            
            
            <li class="treeview">
                <a href="#"><i class="fa fa-shopping-cart"></i> Commandes
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo site_url('gerant/commandeClient'); ?>"><i class="fa fa-circle-o"></i> Pour un client</a></li>
                    <li><a href="<?php echo site_url('gerant/listeCommandeClient'); ?>"><i class="fa fa-circle-o"></i> Commandes des clients</a></li>
                    
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#"><i class="fa fa-truck"></i> Livraisons
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo site_url('gerant/livraisonClient'); ?>"><i class="fa fa-circle-o"></i> Pour un client</a></li>
                    
                </ul>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo site_url('user/listeUser'); ?>"><i class="fa fa-user"></i> Utilisateur</a></li>
                    <!--<li><a href="<?php echo site_url('depot/index'); ?>"><i class="fa fa-server"></i> Dépot</a></li>-->
                    <li><a href="<?php echo site_url('fournisseur/index'); ?>"><i class="fa fa-building"></i> Fournisseur</a></li>
                    <li><a href="<?php echo site_url('chauffeur/index'); ?>"><i class="fa fa-drivers-license"></i> Chauffeur</a></li>
                    <li><a href="<?php echo site_url('vehicule/index'); ?>"><i class="fa fa-bus"></i> Véhicule</a></li>
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#">
                    <i class="glyphicon glyphicon-tasks"></i>
                    <span>Magazins</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo site_url('categorie/index'); ?>"><i class="fa fa-circle-o"></i>Catégorie</a></li>
                    <li><a href="<?php echo site_url('produit/index'); ?>"><i class="fa fa-circle-o"></i>Produit</a></li>
                    <li><a href="<?php echo site_url('unite/index'); ?>"><i class="fa fa-circle-o"></i>Unité de Vente</a></li>
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#"><i class="fa fa-shopping-cart"></i> Commandes
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <!--<li><a href="<?php echo site_url('commande/commandeDepot'); ?>"><i class="fa fa-circle-o"></i> Pour un dépôt</a></li>-->
                    <li><a href="<?php echo site_url('commande/commandeClient'); ?>"><i class="fa fa-circle-o"></i> Pour un client</a></li>
                    <li><a href="<?php echo site_url('commande/listeCommandeClient'); ?>"><i class="fa fa-circle-o"></i> Commandes des clients</a></li>
                    <!--<li><a href="<?php echo site_url('commande/listeCommandeDepot'); ?>"><i class="fa fa-circle-o"></i> Commandes pour les dépôts</a></li>-->

                </ul>
            </li>
            
            <li class="treeview">
                <a href="#"><i class="fa fa-truck"></i> Livraisons
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <!--<li><a href="<?php echo site_url('livraison/livraisonDepot'); ?>"><i class="fa fa-circle-o"></i> Pour un dépôt</a></li>-->
                    <li><a href="<?php echo site_url('livraison/livraisonClient'); ?>"><i class="fa fa-circle-o"></i> Pour un client</a></li>
                    <li><a href="<?php echo site_url('livraison/releve'); ?>"><i class="fa fa-circle-o"></i> Relevé pour une date</a></li>
                </ul>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
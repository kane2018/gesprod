<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?php
        $id = $this->ion_auth->get_user_id();
        $groups = $this->ion_auth->get_users_groups($id)->result();

        $nb = count($groups);
        for ($i = 0; $i < $nb; $i++) {
            echo $groups[$i]->name;
            echo ($i < $nb - 1) ? ' - ' : '';
        }

        //var_dump($groups);
        ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> <?= $fonctionnalite ?></a></li>
        <li><a href="#"><?= $action ?></a></li>
    </ol>
</section>
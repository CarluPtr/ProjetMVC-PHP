<?php $title = 'Blog de Carlu';
?>

<?php ob_start();
?>
<div class="container pb-5">
    <h3 class="fw-light">Derniers billets du blog :</h2>
</div>
        
        <?php
        while ($data = $posts->fetch())
        {
        ?>
            <div class="container py-2">
                <div class=" card card-body">
                    <h3>
                        <?= htmlspecialchars($data['title']) ?>
                        <em class="fw-light fs-5">le <?= $data['creation_date_fr'] ?></em>
                    </h3>
                    
                    <p>
                        <?= nl2br(htmlspecialchars($data['content'])) ?>
                        <br />
                        <em><a href="index.php?action=post&amp;id=<?= $data['id'] ?>">Commentaires</a></em>
                    </p>
                </div>
        
            </div>
        <?php
        }
        $posts->closeCursor();
        ?>
        <?php $content = ob_get_clean(); ?>

        <?php require ('view/template.php'); ?>

<?php
use App\App;
?>

<h1>Pay <?php echo $value; ?> $</h1>

<?php if ($pay) { ?>
    <form action="<?= App::get()->routes->makeUrl('home/confirm'); ?>" method="POST">
        Подтвердите транзакцию: <?php echo $value; ?> $
        <input type="hidden" name="pay-hash" value="<?= $pay; ?>"/>
        <button name="action">Оплатить</button>
    </form>
<?php } ?>

<?php if ($error) { ?>
    <?php echo $error; ?>
<?php } ?>

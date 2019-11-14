<?php use App\App; ?>
<h1><?php echo $user->username; ?></h1>
<h2>У Вас на счету: <?= $userBalance; ?></span></h2>

<form action="<?= App::get()->routes->makeUrl('home/pay'); ?>" method="POST">
    <div class="form-group">
        <label for="inputMoney">Сколько денег хотите списать?</label>
        <input type="number" step="0.01" min="0.01" max="9999999" name="money" id="inputMoney" placeholder="0.00"
               required autofocus>
    </div>
    <button name="action">Перевести</button>
</form>

<hr/>


<?php if (!empty($listTransactions)) { ?>
    <h3>Account history</h3>
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($listTransactions as $item) { ?>
            <tr>
                <td><?= $item['id']; ?></td>
                <td><?= $item['value']; ?></td>
                <td><?= $item['datetime']; ?></td>
                <td><?= $item['status']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p>Транзакций не найдено</p>
<?php } ?>

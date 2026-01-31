<div class="login-overlay" onclick="if(event.target === this) window.location='<?= getCurrentBaseUrl() ?>'">
    <div class="login-box">
        <h3><?= $lang['management'] ?></h3>
        <form method="POST">
            <input type="hidden" name="admin_login" value="1">
            <input type="password" name="password" placeholder="<?= $lang['pwd_label'] ?>" required autofocus>
            <button type="submit"><?= $lang['btn_login'] ?></button>
        </form>
    </div>
</div>

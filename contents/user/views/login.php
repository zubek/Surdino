<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : login.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : view
 * 
 * Description:
 */?>
<div id="form-layout">
    <form action="/user/login/" method="post">
        <table>
            <tr>
                <div class="form-group">
                <td>

                    <label for="field-login" class="field-label form-control" >Логин</label>

                </td>
                <td>
                    <input type="text" name="login" id="field-login" class="form-control" />
                </td>
</div>
            </tr>
            <tr>
                <div class="form-group">
                <td>
                    <label for="field-password" class="field-label form-control">Пароль</label>
                </td>
                <td>
                    <input type="password" name="password" id="field-password" class="form-control" />
                </td>
                </div>
            </tr>
        </table>

        <div id="errors"><?php if(isset($notes['err_msg'])) echo $notes['err_msg']; ?></div>
        <div id="copyright">&copy; Цифровая Тува</div>
        <input type="submit" value="Войти" id="submit-button" />
        <input type="hidden" name="action" value="auth" />
    </form>
</div>
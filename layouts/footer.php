
<footer class="footer">
    <div class="footer__block block no-margin-bottom">
        <div class="container-fluid text-center">
            <!-- Please do not remove the backlink to us unless you support us at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
            <p class="no-margin-bottom">2021 &copy; <strong>The TUT Team</strong>.</p>
        </div>
    </div>
</footer>
<?php if(isset($_SESSION['islogged'])) {

    $_SESSION['user']=='student'? include('./../student/modal.php'): (include('./../admin/modal.php'));
    include('./../layouts/scripts.php');
}?>






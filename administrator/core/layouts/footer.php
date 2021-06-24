<?php defined('_EXEC') or die; ?>
        <!-- Footer -->
        <footer class="footer d-print-none">
            <div class="container">
                © <?= date('Y') ?> <b>{$_webpage}</b> <i class="mdi mdi-heart text-danger"></i> by <a href="https://codemonkey.com.mx" target="_blank">codemonkey.com.mx</a>
            </div>
        </footer>
        <!-- End Footer -->

        <script src="{$path.js}jquery-3.4.1.min.js"></script>
        <script src="{$path.js}valkyrie.js?v=1.0"></script>
        <script src="{$path.js}codemonkey-1.2.0.js?v=1.0"></script>
        <script src="{$path.js}waves.js"></script>
        <script src="{$path.js}scripts.js?v=1.0"></script>

        <script src="{$path.plugins}alertify/js/alertify.js"></script>
        <script src="{$path.plugins}sweet-alert2/sweetalert2.min.js"></script>
        {$dependencies.js}

        {$dependencies.other}
    </body>
</html>

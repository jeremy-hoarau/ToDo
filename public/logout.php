<?php
require_once('../private/config.php');
require_once(PRIVATE_PATH . '/database.php');
require_once(PRIVATE_PATH . '/query.php');
$page_name = 'Logout';

if(isset($_SESSION['id']))
    unset($_SESSION['id']);
?>
<script>
    document.location="<?php echo url_for('/log.php') ?>";
</script>

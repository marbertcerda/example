<style>

#logo {
        display: block;
        margin: 0 auto;
        max-width: 80%; 
        margin-bottom: 20px; 
    }
	
</style>

<nav id="sidebar" class='mx-lt-5 bg-light'>
    <img id="logo" src="assets/img/Collab Box.png" alt="Logo">
    <div class="sidebar-list">
       
        <?php if ($_SESSION['login_type'] == 1): ?>
            <a href="index1.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
            <a href="index1.php?page=files" class="nav-item nav-files"><span class='icon-field'><i class="fa fa-file"></i></span> Files</a>
            <a href="index1.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
        <?php else: ?>
            <a href="index1.php?page=indexhome" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
            <a href="index1.php?page=files" class="nav-item nav-files"><span class='icon-field'><i class="fa fa-file"></i></span> Files</a>
        <?php endif; ?>
    </div>
</nav>
<script>
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
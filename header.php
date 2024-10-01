<style>
    .navbar {
  padding: 5px 10px;
        
    }
</style>
<script type="text/javascript" src="js/notificaciones.js"></script>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

<?php if($sesion_id_empresa==116){
echo "<button id='sidebar-toggle' >+</button>";
} ?>
            
            
            <a class="navbar-brand" href="#">Bienvenido <?php echo $sesion_empresa_nombre ?></a>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ">
                    <li class="nav-item ms-auto">
                        <a href="cerrarSesion.php" class="btn btn-secondary px-4 py-2" id="cerrarSesion">
                          <i class="fa fa-close fa-1x" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
<script>

      const showPasswordButton = document.getElementById('cerrarNav');

      showPasswordButton.addEventListener('click', () => {
          
        if (showPasswordButton.innerHTML === '<i class="fa fa-eye" aria-hidden="true"></i>') {
            
          showPasswordButton.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
          
        } else {
            
          showPasswordButton.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
          
        }
        
      });
    
</script>



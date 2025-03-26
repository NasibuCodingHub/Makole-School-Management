<nav>
            <i class='bx bx-menu SidebarOpener'></i>
            <form id="unknowingForm">
                <div class="form-input">
                    <input type="search" placeholder="Search..." id="topMostSearchBar">
                    <button class="search-btn" type="button" id="topMostSearchBarBtn"><i class='bx bx-search-alt'></i></button>
                </div>
            </form>
         

            
           

            <input type="checkbox" id="theme-toggle" hidden>
            <label for="theme-toggle" class="theme-toggle" onload="checkAndChangeTheme()"></label>
            <span class="admin-text">Teacher</span>

            <a href="settings.php" class="profile" id="navbar_profile_pic">
                
                <img src="../images/user.png" >
               
            </a>

           
            <div class="dropdown dropdown-center">
                <a class=" menu" href="#"  data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bx-dots-vertical-rounded icon-hover-circle'></i>
                </a>
              
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                  <!-- <li><a class="dropdown-item" href="#"></a></li> -->
                  <li><a class="dropdown-item"  data-bs-toggle="modal" data-bs-target="#logout-modal">Logout</a></li>
                </ul>
              </div>
</nav>

<?php 
  // session_start();
  $theme = "";

  if(isset($_SESSION['theme'])){
    $theme = $_SESSION['theme'];
  }else{
    $theme = 'light';
  }

?>


<style>
        .admin-text {
            font-weight: bold;
            font-size: 1em; /* Adjust size as needed */
            color: #007bff;  /* Default color (blue) */
        }
        
        body.dark-theme .admin-text {
            color: #ffffff;  /* White color for dark theme */
        }
        
        /* Example additional styling for profile link */
        .profile {
            color: inherit; /* Inherit color from parent */
            text-decoration: none;
        }
    </style>
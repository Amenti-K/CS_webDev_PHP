<?php 
if (isset($_SESSION['emailUser'])){
  $logged = isset($_SESSION['emailUser']); 
  $user = $_SESSION['emailUser'];
} else {
  $logged = false;
  $user = '';
}
// base URL dynamically
$base_url = "http://" . $_SERVER['HTTP_HOST'] . '/GHM mine/users';
?> 
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400..800&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
    :root {
      --primaryColor: rgb(22, 120, 186); /* Lighter shade for button hover */
      --fontColor: #fff;
      --fontSecondary: #fff4; 

      /* --fontFamily: 'syne', sans-serif; */
      --fontFamily: 'Montserrat', sans-serif;
      --fontWeight: 800;
      --fontStyle: normal;
    }
    header{
      position: relative;
      top: 0;
      left: 0;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      z-index: 5;
      height: 100px;
      margin-bottom: 0;
      background-color: var(--primaryColor);
    }
    nav {
      box-sizing: border-box;
      padding: 0 9%;
      height: 100%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    a.nav_logo {
      text-decoration: none;
      font-size: 2.5rem;
      font-family: var(--fontFamily);
      transition: color .4s;
      color: var(--fontColor);
    }
    
    .nav_actions {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: center;
      column-gap: 1.5rem;
    }
    
    .nav_menue .nav_close,
    .nav_actions .user,
    .nav_actions .nav_toggle {
      font-size: 1.5rem;
      cursor: pointer;
      transition: color .4s;
      color: #fff;
    }

    :is(.nav_logo, .user, .nav_toggle):hover {
      color: var(--primaryColor);
    }

    .nav_link:hover{
      transform: scale(1.2);
    }

    .nav_actions .loggedUser {
      position: relative;
    }

    .nav_actions .loggedUser .userOptions {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translateX(-50%);
      background-color: white;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      padding: 5px 10px ;
      width: 150px;
      z-index: 10;
    }

    .nav_actions .loggedUser:hover .userOptions,
    .nav_actions .loggedUser .userOptions:hover {
      display: block;
      top: 10px;
    }

    .userOptions li {
      list-style: none;
      margin: 10px 0;
      color: var(--primaryColor);
    }

    .userOptions li a {
      text-decoration: none;
      color: black;
      display: block;
      text-align: left;
    }

    .nav_actions .sign {
      display: flex;
      flex-direction: row;
      gap: 1.5rem;
    }
    .nav_actions .sign a {
      text-decoration: none;
    }

    /* navigation for mobile */
    @media screen and (max-width: 1023px) {
      .nav_menu {
        position: fixed;
        top: -100%;
        left: 0;
        box-shadow: 0 8px 16px hsl(230, 75%, 32%, .15);
        width: 100%;
        padding-block: 4.5rem, 4rem;
        transition: top .6s;
        background-color: var(--primaryColor);
        z-index: 10;
      }
      .nav_list li {
        list-style: none;
        display: flex;
        text-align: center;
      }
      .nav_list li a {
        text-decoration: none;
        color: var(--fontColor); 
        margin: auto;
      }
    }
    

    .nav_list {
      display: flex;
      flex-direction: column;
      row-gap: 1rem;
      text-align: center;
    }
    .nav_link {
      display: flex;
      color: var(--fontColor);
      transition: .4s;
      letter-spacing: .1rem;
    }
    .nav_close {
      cursor: pointer;
      position: absolute;
      scale: 1.2;
      top: 1.15rem;
      right: 1.5rem;
      color: var(--fontColor);
    }
    .show-menu {
      top: 5%;
      padding-top: 20px;
      padding-bottom: 20px;
    }

    /* for large devices */
    @media screen and (min-width: 1023px) {
      .nav {
        height: 100%;
        column-gap: 3rem;
      }
      .nav_close, .nav_toggle {
        display: none;
      }
      .nav_menu {
        margin-left: auto;
      }
      .nav_list {
        flex-direction: row;
        column-gap: 3rem;
      } 
      .nav_list li {
        list-style: none;
      }
      .nav_list li a {
        text-decoration: none;
        color: var(--fontColor) 
      }
    }

    @media screen and (min-width: 1150px) {
      .container {
        margin-inline: auto;
      }
    }
  </style>
</head>
<header>
  <nav class="nav container">
    <a href="" class="nav_logo">Guest House</a>
    <div class="nav_menu" id="nav_menu" >
      <ul class="nav_list" >
        <li class="nav_item">
          <a href="<?php echo $base_url ?>/" class="nav_link">Home</a>
        </li>
        <li class="nav_item">
          <a href="<?php echo $base_url ?>/#" class="nav_link">About Us</a>
        </li>
        <li class="nav_item">
          <a href="<?php echo $base_url ?>/#" class="nav_link">Contact Us</a>
        </li>
        <li class="nav_item">
          <a href="<?php echo $base_url ?>/rooms/availableRooms.php" class="nav_link">Guest Houses</a>
        </li>
      </ul>
      <div class="nav_close" id="nav_close">
        <i class="fa fa-times"></i>
      </div>
    </div> 
    <div class="nav_actions"> 
      <?php if ($logged) { ?>
        <div class="loggedUser">
          <i class="fa fa-user user"></i>
          <ul class="userOptions">
            <li><?php echo $user; ?></li>
            <li><a href="<?php echo $base_url ?>/rooms/reservedRoom/userReserved.php">Reserved Rooms</a></li>
            <li><a href="<?php echo $base_url ?>/rooms/reservedRoom/favorite/fetch_favorites.php">Favorites</a></li>
            <li class="logout"><a href="<?php echo $base_url ?>/rooms/navBar/logout.php">Logout</a></li>
          </ul>
        </div>
      <?php } else { ?>
        <div class="sign">
          <a href="<?php echo $base_url ?>/user/index.html" class="signUp nav_link">Sign Up</a>
          <a href="<?php echo $base_url ?>/user/index.html" class="signin nav_link">Log In</a>
        </div>
      <?php } ?>
      <div class="nav_toggle" id="nav_toggle">
        <i class="fa fa-bars" aria-hidden="true"></i>
      </div>
    </div>
  </nav>
</header>
<script>
  const navMenu = document.getElementById('nav_menu'),
        navToggle = document.getElementById('nav_toggle'),
        navClose = document.getElementById('nav_close');

  navToggle.addEventListener('click', () => {
    navMenu.classList.add('show-menu');
  });

  navClose.addEventListener('click', () => {
    navMenu.classList.remove('show-menu');
  });
</script>

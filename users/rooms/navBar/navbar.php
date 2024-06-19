<?php 
if (isset($_SESSION['emailUser'])){
  $logged = isset($_SESSION['emailUser']);
  $user = $_SESSION['emailUser'];
} else {
  $logged = false;
  $user = '';
}
?> 
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400..800&display=swap');
    header{
      position: relative;
      top: 0;
      left: 0;
      width: 100%;
      box-shadow: black;
      z-index: 5;
      height: 60px;
      background-color: blue;
      margin-bottom: 0;
    }
    nav {
      box-sizing: border-box;
      padding: 0 80px 0;
      height: 100%;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    a.nav_logo {
      text-decoration: none;
      color: black;
      font-size: x-large;
      font-weight: 500;
      transition: color .4s;
    }
    
    .nav_actions {
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: center;
      column-gap: 1rem;
    }
    
    .nav_menue .nav_close,
    .nav_actions .user,
    .nav_actions .nav_toggle {
      font-size: 1.25rem;
      cursor: pointer;
      transition: color .4s;
      color: black;
    }

    :is(.nav_logo, .user, .nav_toggle, .nav_link):hover {
      color: red;
    }

    .nav_actions .loggedUser {
      position: relative;
    }

    .nav_actions .loggedUser .userOptions {
      display: none;
      position: absolute;
      top: 40px;
      left: 50%;
      transform: translateX(-50%);
      background-color: white;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      padding: 10px;
      width: 150px;
      z-index: 10;
    }

    .nav_actions .loggedUser:hover .userOptions,
    .nav_actions .loggedUser .userOptions:hover {
      display: block;
      top: 5px;
    }

    .userOptions li {
      list-style: none;
      margin: 10px 0;
    }

    .userOptions li a {
      text-decoration: none;
      color: black;
      display: block;
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
        background-color: grey;
      }
      .nav_list li {
        list-style: none;
      }
      .nav_list li a {
        text-decoration: none;
        color: black; 
      }
    }

    .nav_list {
      display: flex;
      flex-direction: column;
      row-gap: 2.5rem;
      text-align: center;
    }
    .nav_link {
      color: blue;
      font-weight: 600;
      transition: color .4s;
    }
    .nav_close {
      cursor: pointer;
      position: absolute;
      top: 1.15rem;
      right: 1.5rem;
    }
    .show-menu {
      top: 0px;
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
        color: black; 
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
          <a href="" class="nav_link">home</a>
        </li>
        <li class="nav_item">
          <a href="" class="nav_link">about us</a>
        </li>
        <li class="nav_item">
          <a href="" class="nav_link">contact us</a>
        </li>
        <li class="nav_item">
          <a href="" class="nav_link">rooms</a>
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
            <li><a href="">Reserved Rooms</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      <?php } else { ?>
        <div class="sign">
          <a href="" class="signUp">Sign Up</a>
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

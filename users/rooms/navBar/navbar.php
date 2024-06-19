<?php 
if (isset($_SESSION['emailUser'])){
  $logged = isset($_SESSION['emailUser']);
  $user = $_SESSION['emailUser'];
} else {
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
      background-color: burlywood;
      box-shadow: black;
      z-index: 5;
      height: 50px;
    }
    .nav {
      background-color: blueviolet;
      height: 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .nav_logo{
      color: black;
      font-weight: 400;
      transition: color .4s;
    }
    .nav_action {
      display: flex;
      align-items: center;
      column-gap: 1rem;
    }
    .nav_search, .nav_login, .nav_toggle, .nav_close {
      font-size: 1.25rem;
      color: black;
      cursor: pointer;
      transition: color .4s;
    }
    :is(.nav_logo, .nav_search, .nav_login, .nav_toggle, .nav_link):hover {
      color: blue;
    }

    /* @media screen and (max-width: 900px) {
      .nav_menu {
        position: fixed;
        top: -100%;
        left: 0;
        background-color: burlywood;
        box-shadow: 0 8px 16px hsla(230, 75%, 32%, .15);
        width: 100%;
        padding-block: 4.5rem 4rem;
        transition: top .4s;
      }
    } */

    .nav_list {
      display: flex;
      flex-direction: column;
      row-gap: 2.5rem;
      text-align: center;
    }
    .nav_link {
      color: wheat;
      font-weight: 500;
      transition: color .4s;
    }
    .nav_close {
      position: absolute;
      top: 1.15rem;
      right: 1.5rem;
    }
    .show-menu {
      top: 0;
    }
    /* @media screen and (min-width: 900px) {
      nav {
        height: 50px;
        padding: auto 20%;
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
    } */
  </style>
</head>
<header>
  <nav class="nav container">
    <a href="" class="nav_logo">Logo</a>
    <div class="nav_menu" id="nav_menu" >
      <ul class="nav_list">
        <li class="nav_item">
          <a href="" class="nav_link">home</a>
        </li>
        <li class="nav_item">
          <a href="" class="nav_link">aboutus</a>
        </li>
        <li class="nav_item">
          <a href="" class="nav_link">contacUs</a>
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
      <div class="user" id="user">
            <a href="" class="nax_link">
              <i class="fa fa-user"></i>
            </a>
      </div>
      <div class="nav_toggle" id="nav_toggle">
        <i class="fa fa-bars" aria-hidden="true"></i>
      </div>
    </div>
  </nav>
</header>
<script>
  const navMenu = document.getElementById('nav_menu'),
        navToggle = document.getElementById('nav_toggle'),
        navClose = document.getElementById('nav_close')
  navToggle.addEventListener('click', () => {
    navMenu.classList.add('show-menu')
  })
  navClose.addEventListener('click', () => {
    navMenu.classList.remove('show-menu')
  })
</script>
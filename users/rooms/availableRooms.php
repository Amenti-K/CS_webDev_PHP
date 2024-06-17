<?php 
session_start();
if (isset($_SESSION['emailUser'])){
  $user = $_SESSION['emailUser'];
}
else $user = '';
// $email = isset($_GET['email']) ? $_GET['email'] : 0;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Available Rooms</title>
    <link rel="stylesheet" href="stylesRoomUser.css">
    
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        fetch("fetch_rooms.php")
          .then((response) => response.json())
          .then((data) => {
            const roomsContainer = document.getElementById("rooms-container");
            data.forEach((room) => {
              const roomDiv = document.createElement("div");
              roomDiv.classList.add("room");

              const roomLink = document.createElement("a");
              roomLink.href = `./roomDetails/room_details.php?id=${room.id}`;

              const roomImg = document.createElement("img");
              roomImg.src =  "./roomDtail/" + room.image_path1;
              roomImg.alt = `Image of ${room.name}`;

              const roomName = document.createElement("h2");
              roomName.textContent = room.name;

              const roomDescription = document.createElement("p");
              roomDescription.textContent = room.description;

              const roomPrice = document.createElement("p");
              roomPrice.textContent = `Price: $${room.price}`;

              roomLink.appendChild(roomImg);
              roomLink.appendChild(roomName);
              roomLink.appendChild(roomDescription);
              roomLink.appendChild(roomPrice);

              roomDiv.appendChild(roomLink);
              roomsContainer.appendChild(roomDiv);
            });
          });
      });
    </script>
  </head>
  <body>
    <?php  echo "<p> $user </P>"; ?>
    <header>
      <nav>
        <div class="logo">Guest House</div>
        <div class="nav-content">
          <div>
            <ul class="nav-links">
              <li><a href="#">Home</a></li>
              <li><a href="./rooms/availableRooms.php">Rooms</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </div>
            
          <div>
          <ul class="nav-auth">
            <li><a href="#" id="signInButton">Sign In</a></li>
            <li><a href="#" id="signUpButton">Sign Up</a></li>
          </ul>
          </div>
          <!-- <div id="profileMenu" class="profile-menu">
            <img
              src="images/profile.avif"
              alt="Profile Icon"
              class="profile-icon"
              id="profileIcon"
            />
            <div class="dropdown-content" id="dropdownContent">
              <p>Email: user@example.com</p>
              <p>Name: John Doe</p>
              <a href="#">Edit Profile</a>
              <a href="#" id="logoutButton">Logout</a>
            </div>
          </div> -->
        </div>
      </nav>
      <div class="searchFilter">
        <div class="search-bar">
          <input type="text" placeholder="Search" />
          <button>
            <img src="" alt="Search" />
          </button>
        </div>
        <div class="filter-bar"></div>
      </div>
    </header>
    <div id="rooms-container"></div>
  </body>
</html>

<!-- <style>
      .room {
        border: 1px solid #ccc;
        padding: 16px;
        margin: 16px 0;
      }
      .room img {
        max-width: 100%;
        height: auto;
      }
      .room h2,
      .room p {
        margin: 8px 0;
      }
      .room a {
        text-decoration: none;
        color: #000;
      }
    </style> -->
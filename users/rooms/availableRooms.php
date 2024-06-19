<?php 
session_start();
if (isset($_SESSION['emailUser'])){
  $user = $_SESSION['emailUser'];
}
else $user = '';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Available Rooms</title>
    <link rel="stylesheet" href="stylesRoomUser.css">
    <style>
      .room {
        border: 1px solid #ccc;
        padding: 16px;
        margin: 16px 0;
      }
      .room img {
        max-width: 100%;
        height: auto;
      }
      .room h2, .room p {
        margin: 8px 0;
      }
      .room a {
        text-decoration: none;
        color: #000;
      }
      .stars {
        display: flex;
      }
      .star {
        font-size: 24px;
        color: #ddd;
      }
      .star.selected {
        color: #f5b301;
      }
    </style>
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
              roomImg.src = "./roomDetails/" + room.image_path1;
              roomImg.alt = `Image of ${room.name}`;

              const roomName = document.createElement("h2");
              roomName.textContent = room.name;

              const roomDescription = document.createElement("p");
              roomDescription.textContent = room.description;

              const roomPrice = document.createElement("p");
              roomPrice.textContent = `Price: $${room.price}`;

              const roomRating = document.createElement("div");
              roomRating.classList.add("stars");
              for (let i = 1; i <= 5; i++) {
                const star = document.createElement("span");
                star.classList.add("star");
                if (i <= room.average_rating) {
                  star.classList.add("selected");
                }
                star.textContent = "★";
                roomRating.appendChild(star);
              }

              roomLink.appendChild(roomImg);
              roomLink.appendChild(roomName);
              roomLink.appendChild(roomDescription);
              roomLink.appendChild(roomPrice);
              roomLink.appendChild(roomRating);

              roomDiv.appendChild(roomLink);
              roomsContainer.appendChild(roomDiv);
            });
          });
      });
    </script>
  </head>
  <body>
    <?php include './navBar/navbar.php'; ?>
    <div id="rooms-container"></div>
  </body>
</html>

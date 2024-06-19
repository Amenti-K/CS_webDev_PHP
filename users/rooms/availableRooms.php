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

              const roomNameRating = document.createElement("div");
              roomNameRating.classList.add("room-name-rating");

              const roomName = document.createElement("h2");
              roomName.textContent = room.name;

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

              roomNameRating.appendChild(roomName);
              roomNameRating.appendChild(roomRating);

              const roomDescription = document.createElement("p");
              roomDescription.textContent = room.description.length > 100 ? room.description.substring(0, 100) + '...' : room.description;

              const roomPrice = document.createElement("p");
              roomPrice.classList.add("room-price");
              roomPrice.textContent = `Price: $${room.price}`;

              roomLink.appendChild(roomImg);
              roomLink.appendChild(roomNameRating);
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
    <?php include './navBar/navbar.php'; ?>
    <div id="rooms-container"></div>
  </body>
</html>

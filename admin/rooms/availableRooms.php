<?php 
session_start();
if (isset($_SESSION['emailAdmin'])){
  $user = $_SESSION['emailAdmin'];
} else {
  $user = '';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Available Rooms</title>
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
      .room h2,
      .room p {
        margin: 8px 0;
      }
      .room a {
        text-decoration: none;
        color: #000;
      }
    </style>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        fetch("fetch_rooms.php")
          .then((response) => {
            if (!response.ok) {
              throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
          })
          .then((data) => {
            const roomsContainer = document.getElementById("rooms-container");
            data.forEach((room) => {
              const roomDiv = document.createElement("div");
              roomDiv.classList.add("room");

              const roomLink = document.createElement("a");
              roomLink.href = `room_details.php?id=${room.id}`;

              const roomImg = document.createElement("img");
              roomImg.src =  "./add_rooms/" + room.image_path1;
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
          })
          .catch((error) => {
            console.error('There was a problem with the fetch operation:', error);
            const roomsContainer = document.getElementById("rooms-container");
            roomsContainer.innerHTML = '<p>Error fetching rooms. Please try again later.</p>';
          });
      });
    </script>
</head>
<body>
    <h1>Available Rooms</h1>
    <?php echo "<p>$user</p>"; ?>
    <a href="add_rooms/addRooms.html"><button type="button">ADD rooms</button></a>
    <div id="rooms-container"></div>
</body>
</html>

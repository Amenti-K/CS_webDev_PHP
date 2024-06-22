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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./stylesRoomUser.css">
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const searchForm = document.getElementById("search-form");

        function fetchRooms(params = '') {
          fetch(`fetch_rooms.php${params}`)
            .then((response) => response.json())
            .then((data) => {
              const roomsContainer = document.getElementById("rooms-container");
              roomsContainer.innerHTML = ""; // Clear previous results
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
                roomPrice.textContent = `$ ${room.price} * night`;

                roomLink.appendChild(roomImg);
                roomLink.appendChild(roomNameRating);
                roomLink.appendChild(roomDescription);
                roomLink.appendChild(roomPrice);

                roomDiv.appendChild(roomLink);
                roomsContainer.appendChild(roomDiv);
              });
            });
        }

        // Fetch all rooms on page load
        fetchRooms();

        searchForm.addEventListener("submit", function (event) {
          event.preventDefault();
          const location = document.getElementById("location").value;
          const checkInDate = document.getElementById("check-in-date").value;
          const checkOutDate = document.getElementById("check-out-date").value;

          let params = [];
          if (location) params.push(`location=${location}`);
          if (checkInDate) params.push(`check_in_date=${checkInDate}`);
          if (checkOutDate) params.push(`check_out_date=${checkOutDate}`);

          params = params.length > 0 ? `?${params.join('&')}` : '';
          fetchRooms(params);
        });
      });
    </script>
  </head>
  <body>
    <div class="nav">
      <?php include './navBar/navbar.php'; ?>
    </div>
    <div id="search-container">
      <form id="search-form">
        <input type="text" id="location" name="location" placeholder="Location">
        <input type="date" id="check-in-date" name="check_in_date" placeholder="check in">
        <input type="date" id="check-out-date" name="check_out_date" placeholder="check out">
        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
      </form>
    </div>
    <div id="rooms-container"></div>
  </body>
</html>

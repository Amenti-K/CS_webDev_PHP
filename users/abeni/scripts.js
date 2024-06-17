document.addEventListener("DOMContentLoaded", function () {
  const signInButton = document.getElementById("signInButton");
  const signUpButton = document.getElementById("signUpButton");
  const profileMenu = document.getElementById("profileMenu");
  const profileIcon = document.getElementById("profileIcon");
  const dropdownContent = document.getElementById("dropdownContent");

  // Initially hide the profile menu
  profileMenu.style.display = "none";

  // Simulate sign-in/sign-up
  function simulateSignIn() {
    signInButton.style.display = "none";
    signUpButton.style.display = "none";
    profileMenu.style.display = "block";
  }

  // Toggle dropdown content
  profileIcon.addEventListener("click", function () {
    dropdownContent.style.display =
      dropdownContent.style.display === "block" ? "none" : "block";
  });

  // Simulate logout
  document
    .getElementById("logoutButton")
    .addEventListener("click", function () {
      signInButton.style.display = "inline";
      signUpButton.style.display = "inline";
      profileMenu.style.display = "none";
      dropdownContent.style.display = "none";
    });

  // Event listeners for sign-in/sign-up buttons
  signInButton.addEventListener("click", simulateSignIn);
  signUpButton.addEventListener("click", simulateSignIn);
});

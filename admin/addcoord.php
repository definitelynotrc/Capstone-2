<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="./css/footer.css" />
    <link rel="icon" href="./img/Group 236.svg" type="image/x-icon" />
    <link
      href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              fontColor: "#1E1E1E",
              card1: "#FBC0C0",
              card2: "#F4E87A",
              card3: "#AEA8F8",
              card4: "#F4E884",
              input: "#D0C7C7",
            },
            fontFamily: {
              sans: ["IBM Plex Sans", "sans-serif"],
            },
          },
        },
      };
    </script>
    <style>
      .sidebar {
        transform: translateX(100%);
      }
      .sidebar.show {
        transform: translateX(0);
      }
      .custom-card {
        border-radius: 10px;
        padding: 10px;
        margin: 10px;
        width: 260px;
        height: 150px;
        border: 1px solid black;
      }
      .custom-border {
        border: 1px solid #d0c7c7;
        border-radius: 4px;
      }
      .mini-card {
        width: 130px;
        height: 80px;
        background-color: white;
        border-radius: 6px;
      }
      .custom-shadow {
        box-shadow: 0px 3px 5px 0px rgba(0, 0, 0, 0.75);
        -webkit-box-shadow: 0px 3px 5px 0px rgba(0, 0, 0, 0.75);
        -moz-box-shadow: 0px 3px 5px 0px rgba(0, 0, 0, 0.75);
      }
      .custom-file-input::-webkit-file-upload-button {
        background: #d0c7c7;
        color: #333333;
        border: 1px solid #333333;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
      }
    </style>

    <title>InterLink</title>
</head>
<body>
    <h2>Admin Panel - Add Coordinator</h2>
    <form action="add_coordinator.php" method="post">
        <label for="firstname">First Name:</label><br>
        <input type="text" id="firstname" name="firstname" required><br>
        
        <label for="middlename">Middle Name:</label><br>
        <input type="text" id="middlename" name="middlename"><br>
        
        <label for="lastname">Last Name:</label><br>
        <input type="text" id="lastname" name="lastname" required><br>
        
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br>
        
        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone" required><br>
        
        <label for="department">Department:</label><br>
        <input type="text" id="department" name="department" required><br>
        
        <button type="submit">Add Coordinator</button>
    </form>
</body>
</html>

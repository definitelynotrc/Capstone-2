<?php
session_start();
include 'db.php';


function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {


$email = sanitize_input($_POST['email']);
$password = sanitize_input($_POST['pw']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format";
    exit();
}

$stmt = $conn->prepare("SELECT u.id, u.firstname, u.middlename, u.lastname, u.email, s.password AS student_password, c.password AS company_password, co.password AS coordinator_password
                        FROM user u
                        LEFT JOIN student s ON u.id = s.user_id
                        LEFT JOIN company c ON u.id = c.user_id
                        LEFT JOIN coordinator co ON u.id = co.user_id
                        WHERE u.email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $firstname, $middlename, $lastname, $email, $student_password, $company_password, $coordinator_password);
    $stmt->fetch();
    
    if (($student_password && password_verify($password, $student_password)) ||
        ($company_password && password_verify($password, $company_password)) ||
        ($coordinator_password && password_verify($password, $coordinator_password))) {
        
        $_SESSION['user_id'] = $user_id;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['email'] = $email;

        
        if ($student_password && password_verify($password, $student_password)) {
            $_SESSION['user_type'] = 'student';
            header("Location: student_dashboard.php");
        } elseif ($company_password && password_verify($password, $company_password)) {
            $_SESSION['user_type'] = 'company';
            header("Location: company_dashboard.php");
        } elseif ($coordinator_password && password_verify($password, $coordinator_password)) {
            $_SESSION['user_type'] = 'coordinator';
            header("Location: coordinator_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid email or password";
    }
} else {
    echo "Invalid email or password";
}

$stmt->close();
$conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="./css/footer.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <link rel="icon" href="./img/Group 236.svg" type="image/x-icon" />
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
    </style>

    <title>InterLink</title>
  </head>
  <body class="font-sans max-w-[1920px]">
    <nav
      class="flex flex-row w-full lg:px-10 lg:py-4 px-4 py-4 justify-between mx-auto items-center"
    >
      <div><h1 class="font-semibold text-xl lg:text-2xl">InterLink</h1></div>
      <div class="lg:hidden">
        <svg
          onclick="toggleMenu()"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
          class="lucide lucide-menu"
        >
          <line x1="4" x2="20" y1="12" y2="12" />
          <line x1="4" x2="20" y1="6" y2="6" />
          <line x1="4" x2="20" y1="18" y2="18" />
        </svg>
      </div>
      <div class="hidden lg:block">
        <ul class="flex flex-row gap-10 font-medium">
          <li>About</li>
          <li>Students</li>
          <li>Employer</li>
        </ul>
      </div>
    </nav>
    <div
      id="sidebar"
      class="sidebar fixed top-0 right-0 w-64 h-full bg-white shadow-xl z-50 transition-transform duration-300 ease-in-out lg:hidden"
    >
      <div class="p-4 flex justify-between items-center">
        <button class="text-black items-start" onclick="toggleMenu()">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="lucide lucide-x"
          >
            <line x1="18" x2="6" y1="6" y2="18" />
            <line x1="6" x2="18" y1="6" y2="18" />
          </svg>
        </button>
      </div>
      <ul class="flex flex-col p-4 space-y-4">
        <li>About</li>
        <li>Students</li>
        <li>Employer</li>
      </ul>
    </div>
    <main class="w-full flex flex-col mx-auto gap-2">
      <div
        class="w-full flex flex-row items-center justify-center py-2 px-8 gap-40"
      >
        <div class="hidden lg:block lg:w-[564.85px] h-[653.01px]">
          <img src="./img/Group 233.png" alt="" class="w-full h-full" />
        </div>
        <form action="" method="post" class="flex flex-col items-center lg:w-[30%] gap-4">
          <h1 class="text-2xl font-bold lg:text-4xl">Welcome Back!</h1>
          <div class="w-[200px] h-[40px] flex flex-col lg:w-full lg:h-[55px]">
            <label for="" class="text-sm font-medium">Email address</label
            ><input
              type="email"
              name="email"
              class="custom-border w-full h-full"
            />
          </div>
          <div class="w-[200px] h-[40px] flex flex-col lg:w-full lg:h-[55px]">
            <label for="" class="text-sm font-medium">Password</label
            ><input
              type="password"
              name="pw"
              class="custom-border w-full h-full"
            />
          </div>
          <button type="submit">Log In</button>
          <div class="mt-4 flex flex-col items-center gap-2">
            <span class="text-[12px]">Or sign in using</span>

            <svg
              width="30"
              height="30"
              viewBox="0 0 53 52"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              class="lg:w-[40px] lg:h-[40px]"
            >
              <rect
                x="1"
                y="0.5"
                width="51"
                height="51"
                rx="4.5"
                fill="#FF0000"
                stroke="black"
              />
              <g clip-path="url(#clip0_58_347)">
                <path
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M21.5 25V27.4H25.47C25.31 28.429 24.27 30.42 21.5 30.42C19.11 30.42 17.16 28.441 17.16 26C17.16 23.56 19.11 21.58 21.5 21.58C22.86 21.58 23.77 22.16 24.29 22.66L26.19 20.83C24.97 19.69 23.39 19 21.5 19C17.63 19 14.5 22.13 14.5 26C14.5 29.87 17.63 33 21.5 33C25.54 33 28.221 30.16 28.221 26.16C28.221 25.7 28.17 25.35 28.11 25H21.5ZM38.5 27H35.5V30H33.5V27H30.5V25H33.5V22H35.5V25H38.5V27Z"
                  fill="white"
                />
              </g>
              <defs>
                <clipPath id="clip0_58_347">
                  <rect
                    width="24"
                    height="24"
                    fill="white"
                    transform="translate(14.5 14)"
                  />
                </clipPath>
              </defs>
            </svg>
            <span class="text-[12px] lg:text-"
              >Don’t have an account? <a href=""><b> Register now!</b></a></span
            >
          </div>
        </form>
      </div>
    </main>
  </body>
</html>

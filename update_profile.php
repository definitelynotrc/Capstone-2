<?php
session_start();

// Connect to the database
include 'db.php';

// Fetch user data from the form
$userId = $_SESSION['user_id'];


$firstName = $_POST['firstName'] ?? '';
$middleName = $_POST['middleName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$emailAddress = $_POST['emailAddress'] ?? '';
$studentNo = $_POST['StudentNo'] ?? '';
$section = $_POST['section'] ?? '';
$dob = $_POST['DoB'] ?? '';
$course = $_POST['course'] ?? '';
$phoneNo = $_POST['phoneNo'] ?? '';
$experience = $_POST['Experience'] ?? '';
$aboutMe = $_POST['AboutMe'] ?? '';
$street = $_POST['Street'] ?? '';
$barangay = $_POST['Barangay'] ?? ''; 
$city = $_POST['City'] ?? '';
$province = $_POST['Province'] ?? '';
$skills = $_POST['skills'] ?? '';
$photo = $_FILES['photo']['name'] ?? '';

if (!empty($_FILES['photo']['name'])) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES['photo']['name']);
    $uploadOk = 1;
    
    // Check if file is an image
    $check = getimagesize($_FILES['photo']['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        echo "File is not an image.";
    }
    
    // Check if file already exists
    if (file_exists($targetFile)) {
        $uploadOk = 0;
        echo "Sorry, file already exists.";
    }
    
    // Check file size (5MB max)
    if ($_FILES['photo']['size'] > 5000000) {
        $uploadOk = 0;
        echo "Sorry, your file is too large.";
    }
    
    // Allow certain file formats
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $uploadOk = 0;
        echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
    }
    
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES['photo']['name'])) . " has been uploaded.";
            
            // Update database
            $uploadedPhotoPath = $targetFile;
            $stmt = $conn->prepare("UPDATE student SET photo = ? WHERE userId = ?");
            $stmt->bind_param("si", $uploadedPhotoPath, $user_id);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}



$userCheckQuery = "SELECT COUNT(*) as count FROM user WHERE userId = ?";
$stmt = $conn->prepare($userCheckQuery);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {

    $insertUserQuery = "INSERT INTO user (userId, firstName, middleName, lastName, emailAddress) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertUserQuery);
    $stmt->bind_param('issss', $userId, $firstName, $middleName, $lastName, $emailAddress);
    $stmt->execute();
} else {

    $updateUserQuery = "UPDATE user SET firstName = COALESCE(NULLIF(?, ''), firstName), middleName = COALESCE(NULLIF(?, ''), middleName), lastName = COALESCE(NULLIF(?, ''), lastName), emailAddress = COALESCE(NULLIF(?, ''), emailAddress) WHERE userId = ?";
    $stmt = $conn->prepare($updateUserQuery);
    $stmt->bind_param('ssssi', $firstName, $middleName, $lastName, $emailAddress, $userId);
    $stmt->execute();
}


$studentCheckQuery = "SELECT COUNT(*) as count FROM student WHERE userId = ?";
$stmt = $conn->prepare($studentCheckQuery);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {

    $insertStudentQuery = "INSERT INTO student (userId, studentNo, section, dob, course, phoneNo, experience, aboutMe, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertStudentQuery);
    $stmt->bind_param('isssssss', $userId, $studentNo, $section, $dob, $course, $phoneNo, $experience, $aboutMe, $photo);
    $stmt->execute();
} else {

    $updateStudentQuery = "UPDATE student SET studentNo = COALESCE(NULLIF(?, ''), studentNo), section = COALESCE(NULLIF(?, ''), section), dob = COALESCE(NULLIF(?, ''), dob), course = COALESCE(NULLIF(?, ''), course), phoneNo = COALESCE(NULLIF(?, ''), phoneNo), experience = COALESCE(NULLIF(?, ''), experience), aboutMe = COALESCE(NULLIF(?, ''), aboutMe), photo = COALESCE(NULLIF(?, ''), photo) WHERE userId = ?";
    $stmt = $conn->prepare($updateStudentQuery);
    $stmt->bind_param('ssssssssi', $studentNo, $section, $dob, $course, $phoneNo, $experience, $aboutMe, $photo, $userId);
    $stmt->execute();
}


$addressCheckQuery = "SELECT COUNT(*) as count FROM location WHERE userId = ?";
$stmt = $conn->prepare($addressCheckQuery);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] == 0) {

    $insertAddressQuery = "INSERT INTO location (userId, Street, Barangay, City, Province) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertAddressQuery);
    $stmt->bind_param('issss', $userId, $street, $barangay, $city, $province);
    $stmt->execute();
} else {

    $updateAddressQuery = "UPDATE location SET Street = COALESCE(NULLIF(?, ''), Street), Barangay = COALESCE(NULLIF(?, ''), Barangay), City = COALESCE(NULLIF(?, ''), City), Province = COALESCE(NULLIF(?, ''), Province) WHERE userId = ?";
    $stmt = $conn->prepare($updateAddressQuery);
    $stmt->bind_param('ssssi', $street, $barangay, $city, $province, $userId);
    $stmt->execute();
}   


if (!empty($skills)) {
    $skillsArray = array_map('trim', explode('-=-', $skills));

    $deleteSkillsQuery = "DELETE FROM skills WHERE userId = ?";
    $stmt = $conn->prepare($deleteSkillsQuery);
    $stmt->bind_param('i', $userId);
    $stmt->execute();


    foreach ($skillsArray as $skill) {
        $skill = trim($skill);

     
        $skillQuery = "SELECT skillId FROM skills WHERE skillname = ?";
        $stmt = $conn->prepare($skillQuery);
        $stmt->bind_param('s', $skill);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
    
            $row = $result->fetch_assoc();
            $skillId = $row['skillId'];
        } else {
    
            $insertSkillQuery = "INSERT INTO skills (skillname, UserId) VALUES (?, ?)";
            $stmt = $conn->prepare($insertSkillQuery);
            $stmt->bind_param('si', $skill, $userId);
            $stmt->execute();
            $skillId = $stmt->insert_id;
        }

 
    }
}


header("Location: studentprof.php");
exit();
?>

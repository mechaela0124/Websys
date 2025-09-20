<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BIO DATA</title>
  <style>
      body {
          font-family: Arial, sans-serif;
          background: #f4f4f4;
          margin: 0;
          padding: 20px;
      }
      .container {
          max-width: 900px;
          margin: auto;
          background: #fff;
          padding: 30px;
          border: 2px solid #333;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0,0,0,0.2);
      }
      h1, h2 {
          text-align: center;
          text-transform: uppercase;
          margin-bottom: 20px;
      }
      .biodata {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 15px;
      }
      .biodata div {
          padding: 8px;
          border-bottom: 1px solid #ddd;
      }
      .label {
          font-weight: bold;
          color: #444;
      }
      .photo {
          text-align: center;
          margin-bottom: 20px;
      }
      .photo img {
          width: 150px;
          height: 150px;
          border-radius: 10px;
          border: 2px solid #333;
          object-fit: cover;
      }
      .form-container {
          margin-top: 40px;
          padding: 20px;
          border: 1px dashed #666;
          background: #fafafa;
      }
      input, select {
          padding: 8px;
          margin: 5px 0;
          width: 48%;
          border: 1px solid #aaa;
          border-radius: 5px;
      }
      input[type="radio"] {
          width: auto;
      }
      input[type="submit"], input[type="reset"] {
          width: auto;
          background: #333;
          color: white;
          border: none;
          cursor: pointer;
          padding: 10px 15px;
          margin-top: 15px;
      }
      input[type="submit"]:hover, input[type="reset"]:hover {
          background: #555;
      }
  </style>
</head>
<body>

<div class="container">
    <?php
    if($_SERVER["REQUEST_METHOD"]== "POST"){
        $fname= htmlspecialchars(trim($_POST["fname"]));
        $Mname= htmlspecialchars(trim($_POST["Mname"]));
        $Lname= htmlspecialchars(trim($_POST["Lname"]));
        $age= htmlspecialchars(trim($_POST["age"]));
        $addr= htmlspecialchars(trim($_POST["addr"]));
        $bdate= htmlspecialchars(trim($_POST["bdate"]));
        $bplace= htmlspecialchars(trim($_POST["bplace"]));
        $gender= htmlspecialchars(trim($_POST["gender"]?? ""));
        $nationality= htmlspecialchars(trim($_POST["nationality"]));
        $number= htmlspecialchars(trim($_POST["number"]));
        $cs= htmlspecialchars(trim($_POST["cs"]));
        $email= htmlspecialchars(trim($_POST["email"]));
        $elem= htmlspecialchars(trim($_POST["elem"]));
        $junior= htmlspecialchars(trim($_POST["junior"]));
        $senior= htmlspecialchars(trim($_POST["senior"]));
        $college= htmlspecialchars(trim($_POST["college"]));
        $eyear= htmlspecialchars(trim($_POST["eyear"]));
        $jyear= htmlspecialchars(trim($_POST["jyear"]));
        $syear= htmlspecialchars(trim($_POST["syear"])); 
        $degree= htmlspecialchars(trim($_POST["degree"]));

        // File upload
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        $file_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . uniqid("file_", true) . "." . strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $uploaded_img = $target_file;
        } else {
            $uploaded_img = "";
        }

        // Display Biodata
        echo "<h1>BIO DATA</h1>";
        echo "<div class='photo'>";
        if ($uploaded_img != "") {
            echo "<img src='".$uploaded_img."' alt='Profile Photo'>";
        }
        echo "</div>";

        echo "<div class='biodata'>
                <div><span class='label'>Full Name:</span> $fname $Mname $Lname</div>
                <div><span class='label'>Gender:</span> $gender</div>
                <div><span class='label'>Age:</span> $age</div>
                <div><span class='label'>Address:</span> $addr</div>
                <div><span class='label'>Nationality:</span> $nationality</div>
                <div><span class='label'>Phone:</span> $number</div>
                <div><span class='label'>Birth Date:</span> $bdate</div>
                <div><span class='label'>Birth Place:</span> $bplace</div>
                <div><span class='label'>Civil Status:</span> $cs</div>
                <div><span class='label'>Email:</span> $email</div>
              </div>";

        echo "<h2>Educational Background</h2>
              <div class='biodata'>
                <div><span class='label'>Elementary:</span> $elem ($eyear)</div>
                <div><span class='label'>Junior High:</span> $junior ($jyear)</div>
                <div><span class='label'>Senior High:</span> $senior ($syear)</div>
                <div><span class='label'>College:</span> $college - $degree</div>
              </div>";
    }
    ?>

    <!-- Input Form -->
    <div class="form-container">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
          <h2>Fill up Biodata Form</h2>
          <input type="file" name="image" required><br>
          <input type="text" name="fname" placeholder="First name" required>
          <input type="text" name="Mname" placeholder="Middle name">
          <input type="text" name="Lname" placeholder="Last name"><br>

          <label>Gender:</label>
          <input type="radio" name="gender" value="Male"> Male
          <input type="radio" name="gender" value="Female"> Female <br><br>

          <input type="number" name="age" placeholder="Age">
          <input type="text" name="addr" placeholder="Address"><br>
          <input type="text" name="nationality" placeholder="Nationality">
          <input type="number" name="number" placeholder="Phone"><br>
          <input type="date" name="bdate">
          <input type="text" name="bplace" placeholder="Birthplace"><br>
          <input type="text" name="cs" placeholder="Civil Status">
          <input type="email" name="email" placeholder="Email"><br>

          <h3>Education</h3>
          <input type="text" name="elem" placeholder="Elementary School">
          <input type="number" name="eyear" placeholder="Year Graduated"><br>
          <input type="text" name="junior" placeholder="Junior High School">
          <input type="number" name="jyear" placeholder="Year Graduated"><br>
          <input type="text" name="senior" placeholder="Senior High School">
          <input type="number" name="syear" placeholder="Year Graduated"><br>
          <input type="text" name="college" placeholder="College/University">
          <input type="text" name="degree" placeholder="Degree"><br>

          <input type="submit" value="Submit">
          <input type="reset" value="Reset">
      </form>
    </div>
</div>

</body>
</html>

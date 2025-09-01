<?php

$profile_picture = "profile.jpg";
$full_name = "Abella, Mechaela Batjer";
$email = "22ur0637@psu.edu.ph";
$phone = "09167200948";
$address = "Samon Santa Maria Pangasinan";
$date_of_birth = "March 24, 2004";
$occupation = "Student";
$nationality = "Filipino";
$gender = "Female";
$linkedin = "https://www.linkedin.com/in/mechaela-abella-a8677a348/";
$gitlab = "NA"; 

function showValue($value) {
    return $value != "" ? $value : "NA";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $full_name; ?> - Resume</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            margin: 0;
            background: #fdfdfd;
            color: #333;
            line-height: 1.6;
        }
        .resume {
            width: 800px;
            margin: auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #1E5BA0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid #1E5BA0;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            color: #1E5BA0;
        }
        .header h2 {
            margin: 5px 0 15px;
            font-size: 18px;
            font-weight: normal;
            color: #666;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .main {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        h3 {
            color: #1E5BA0;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .section p, .section li {
            font-size: 15px;
            margin: 5px 0;
        }
        ul {
            padding-left: 20px;
        }
        .skills span {
            display: inline-block;
            background: #e8f1fb;
            border: 1px solid #bcd2f0;
            padding: 6px 12px;
            margin: 4px;
            border-radius: 15px;
            font-size: 14px;
        }
        .right-col {
            border-left: 2px solid #f0f0f0;
            padding-left: 20px;
        }
        .info-line {
            margin: 5px 0;
            font-size: 14px;
        }
        .info-line strong {
            display: inline-block;
            width: 90px;
            color: #1E5BA0;
        }
    </style>
</head>
<body>

<div class="resume">
    <div class="header">
        <?php
        if ($profile_picture != "" && file_exists($profile_picture)) {
            echo "<img src='$profile_picture' alt='Profile Picture'>";
        } else {
            echo "<div style='width:120px;height:120px;border-radius:50%;border:4px solid #1E5BA0;
            display:inline-block;line-height:120px;text-align:center;color:#aaa;font-size:14px;'>No Image</div>";
        }
        ?>
        <h1><?php echo showValue($full_name); ?></h1>
        <h2><?php echo showValue($occupation); ?></h2>
 
    </div>

    <div class="main">
        <div>
            <div class="section">
                <h3>Summary</h3>
                <p>
                    Passionate about learning and driven to succeed in the IT industry, with a focus on
                     innovative technologies and collaborative problem-solving. Skilled at adapting to new 
                     challenges and delivering high-quality solutions to drive business growth and success.
                </p>
            </div>

            <div class="section">
                <h3>Experience</h3>
                <ul>
                    <li>NA</li>
                    <li>NA</li>
                    <li>NA</li>
                </ul>
            </div>

            <div class="section">
                <h3>Education</h3>
                <p><strong>College</strong><br>Pangasinan State University, Urdaneta City Campus<br>
                <em>Bachelor of Science in Information Technology</em><br>
                2022 - present </p>

                <p><strong>Senior High School</strong> <br> Eastern Pangasinan Agricultural College<br>
                <em>Science, Technology, Engineering and Mathematics</em><br>
                2020 - 2022 </p>

                <p><strong>Junior High School</strong> <br> Eastern Pangasinan Agricultural College<br>
                2016 - 2020 </p>
            </div>
        </div>

        <div class="right-col">
            <div class="section">
                <h3>Personal Information</h3>
                <p class="info-line"><strong>DOB:</strong> <?php echo showValue($date_of_birth); ?></p>
                <p class="info-line"><strong>Gender:</strong> <?php echo showValue($gender); ?></p>
                <p class="info-line"><strong>Nationality:</strong> <?php echo showValue($nationality); ?></p>
                <p class="info-line"><strong>Email:</strong> <?php echo showValue($email); ?></p>
                <p class="info-line"><strong>Phone:</strong> <?php echo showValue($phone); ?></p>
                <p class="info-line"><strong>Address:</strong> <?php echo showValue($address); ?></p>
                <p class="info-line"><strong>Linkedin:</strong> <?php echo showValue($linkedin); ?></p>
                <p class="info-line"><strong>GitLab:</strong> <?php echo showValue($gitlab); ?></p>
            </div>

            <div class="section">
                <h3>Skills</h3>
                <div class="skills">
                    <span>C++</span>
                    <span>Java</span>
                    <span>HTML</span>
                    <span>Mysql</span>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

<?php

session_Start();

require "connection.php";

if (isset($_SESSION["u"])) {
    $name = $_POST["n"];
    $country = $_POST["c"];

    if (isset($_FILES["image"])) {
        $image = $_FILES["image"];
        $allowed_image_extentions = array("image/jpg", "image/jpeg", "image/png", "image/svg+xml");
        $file_ex = $image["type"];
        echo ($file_ex);

        if (!in_array($file_ex, $allowed_image_extentions)) {
            echo ("please select a valid image");
        } else {
            $new_file_extention;

            if ($file_ex == "image/jpg") {
                $new_file_extention = ".jpg";
            } else if ($file_ex == "image/jpeg") {
                $new_file_extention = ".jpeg";
            } else if ($file_ex == "image/png") {
                $new_file_extention = ".png";
            } else if ($file_ex == "image/svg+xml") {
                $new_file_extention = ".svg";
            }

            $file_name = "images/images/profile_img/" . $_SESSION["u"]["name"] . "_" . uniqid() . $new_file_extention;

            move_uploaded_file($image["tmp_name"], $file_name);

            $image_rs = Database::search("SELECT * FROM `image` WHERE 
            `user_email`='" . $_SESSION["u"]["email"] . "'");
            $image_num = $image_rs->num_rows;

            if ($image_num == 1) {

                Database::iud("UPDATE `image` SET `path`='" . $file_name . "' WHERE 
                `user_email`='" . $_SESSION["u"]["email"] . "'");
            } else {

                Database::iud("INSERT INTO `image` (`path`,`user_email`) VALUES 
                ('" . $file_name . "','" . $_SESSION["u"]["email"] . "')");
            }
        }
    }



    echo ("  successfully Updated");
} else {
    echo ("Please login first");
}

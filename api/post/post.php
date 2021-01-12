<?php

require_once "../../vendor/autoload.php";
require_once "../../core/rest_init.php";

use classes\{Config, Validation, Hash, Token, Common};
use models\{User, Post};

header("Access-Control-Allow-Origin: *");
header("Content-Type: form-data;");
header("Access-Control-Allow-Methods: POST, FILES");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../../functions/sanitize_id.php";
require_once "../../functions/sanitize_text.php";

/* First we check if the tokens matches, then we check if owner of the post exists, if so we sanitize
   All the necessary data comming to us and we check the image uploaded (IN THIS CASE WE WORK ON ONLY ONE IMAGE)
   then we see if validator passed, then we need to see if the owner is exists in database
*/

if(Token::check(Common::getInput($_POST, "token_post"), "share-post")) {
    $validator = new Validation();
    if(isset($_POST["post_owner"])) {
        $id = sanitize_id($_POST["post_owner"]);
        $post_visibility = 0;
        $text_content = sanitize_text($_POST["post-textual-content"]);

        $validator->check($_FILES, array(
            "photo-or-video"=>array(
                "name"=>"Picture",
                "image"=>"image"
            )
        ));

        if($validator->passed()) {
            $user_id_exists = User::user_exists("id", $id);

            if($user_id_exists) {
                $user = new User();
                $user->fetchUser("id", $id);

                // Create unique id for post
                $post_id = uniqid('', true);

                $user_posts_path = "../../data/users/" . $user->getPropertyValue("username") . "/posts";
                createPostFolders($user_posts_path, $post_id);
                
                $post_images_dir = $user_posts_path . "/" . $post_id . "/media/pictures/";

                $file = $_FILES["photo-or-video"]["name"];
                $original_extension = (false === $pos = strrpos($file, '.')) ? '' : substr($file, $pos);
                // Generate post name
                $generatedName = Hash::unique();
                $generatedName = htmlspecialchars($generatedName);
                $targetFile = $post_images_dir . $generatedName . $original_extension;

                $post = new Post();
                $post->setData(array(
                    "post_owner"=> $id,
                    "post_visibility"=> 0,
                    "post_date"=> date("Y/m/d h:i:s"),
                    "text_content"=> $text_content,
                    "picture_media"=> "data/users/" . $user->getPropertyValue("username") . "/posts/$post_id/pictures/",
                    "video_media"=> "data/users/" . $user->getPropertyValue("username") . "/posts/$post_id/videos/",
                ));

                if(!empty($_FILES["photo-or-video"]["name"])) {
                    if(move_uploaded_file($_FILES["photo-or-video"]["tmp_name"], $targetFile)) {
                    
                    } else {
                        $validator->addError("Sorry, there was an error uploading your post picture.");
                    }
                }
                
                $post->add();
            } else {
                // Print error or store it in a variable to sown later, then return false
                $validator->addError("Sorry, the poster doesn not exist !");
            }
        } else {

        }
    }

    if($validator->passed()) {
        return true;
    } else {
        return $validator->errors();
    }
}

function createPostFolders($user_posts_path, $post_id) {
    if(!file_exists($user_posts_path)) {
        mkdir($user_posts_path, 0777, true);
    }

    mkdir($user_posts_path . "/$post_id", 0777, true);
    mkdir($user_posts_path . "/$post_id/media", 0777, true);
    mkdir($user_posts_path . "/$post_id/media/pictures", 0777, true);
    mkdir($user_posts_path . "/$post_id/media/videos", 0777, true);
}


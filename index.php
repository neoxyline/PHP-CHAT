
<?php

    require_once "vendor/autoload.php";
    require_once "core/init.php";

    use classes\{DB, Config, Validation, Common, Session, Token, Hash, Redirect, Cookie};
    use models\Post;
    // DONT'T FORGET $user OBJECT IS DECLARED WITHIN INIT.PHP (REALLY IMPORTANT TO SEE TO SEE [IMPORTANT#4]
    // Here we check if the user is not logged in and we redirect him to login page

    if(!$user->getPropertyValue("isLoggedIn")) {
        Redirect::to("login/login.php");
    }

    $welcomeMessage = '';
    if(Session::exists("register_success") && $user->getPropertyValue("username") == Session::get("new_username")) {
        $welcomeMessage = Session::flash("new_username") . ", " . Session::flash("register_success");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V01D47</title>
    <link rel='shortcut icon' type='image/x-icon' href='assets/images/favicons/favicon.ico' />
    <link rel="stylesheet" href="styles/global.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/index.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="javascript/header.js" defer></script>
    <script src="javascript/index.js" defer></script>
    <script src="javascript/global.js" defer></script>
</head>
<body>
    <?php include_once "components/basic/header.php"; ?>
    <main>
        <div id="global-container">
            <div id="master-left">
                <div class="flex-space">
                    <h3 class="title-style-2">Home</h3>
                    <div class="flex">
                        <a href="" class="menu-button-style-3 video-background" id="go-to-videos"></a>
                        <a href="" class="menu-button-style-3 messages-button"></a>
                        <a href="" class="menu-button-style-3 search-background go-to-search"></a>
                    </div>
                </div>
                <div>
                    <div>
                        <div class="menu-item-style-1 row-v-flex">
                            <img src="<?php echo Config::get("root/path") . ($user->getPropertyValue("picture") != "" ? $user->getPropertyValue("picture") : "assets/images/icons/user.png"); ?>" class="image-style-2" alt="">
                            <p class="label-style-3"><?php echo $user->getPropertyValue("username"); ?></p>
                        </div>
                        <div class="menu-item-style-2 row-v-flex">
                            <div class="image-style-2 flex-row-column">
                                <img src="assets/images/icons/home-w.png" class="image-style-5" alt="">
                            </div>
                            <p class="label-style-3">Home</p>
                        </div>
                        <div class="menu-item-style-2 row-v-flex">
                            <div class="image-style-2 flex-row-column">
                                <img src="assets/images/icons/notification.png" class="image-style-5" alt="">
                            </div>
                            <p class="label-style-3">Notifications</p>
                        </div>
                        <div class="menu-item-style-2 row-v-flex">
                            <div class="image-style-2 flex-row-column">
                                <img src="assets/images/icons/messages.png" class="image-style-5" alt="">
                            </div>
                            <p class="label-style-3">Messages</p>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div id="master-middle">
                <div class="green-message">
                    <p class="green-message-text"><?php echo $welcomeMessage; ?></p>
                    <script type="text/javascript" defer>
                        if($(".green-message-text").text() !== "") {
                            $(".green-message").css("display", "block");
                        }
                    </script>
                </div>
                <div class="create-post-container">
                    <div class="post-created-message">
                        <p>Post created successfully and added to your <span id="post-creation-place">timeline</span>.</p>
                    </div>
                    <div class="flex-space create-post-header">
                        <div class="row-v-flex">
                            <img src="<?php echo Config::get("root/path") . $user->getPropertyValue("picture"); ?>" class="image-style-2" alt="">
                            <div class="horizontal-menu-item-wrapper" style="margin-left: 8px">
                                <a href="" class="button-style-4 button-with-suboption more-button">Post to timeline</a>
                                <div class="sub-options-container sub-options-container-style-2" style="z-index: 1">
                                    <div class="paragraph-wrapper-style-1">
                                        <p class="label-style-2">Post to: Timeline</p>
                                    </div>
                                    <!-- When this link get pressed you need to redirect the user to the notification post -->
                                    <div class="options-container-style-1">
                                        <div class="sub-option-style-2 post-to-option">
                                            <label for="" class="flex">Timeline</label>
                                            <input type="radio" checked name="post-to" form="create-post-form" value="timeline" class="flex rad-opt">
                                        </div>
                                        <div class="sub-option-style-2 post-to-option">
                                            <label for="" class="flex">Groups</label>
                                            <div class="flex-row-column">
                                                <input type="radio" name="post-to" form="create-post-form" value="group" class="flex rad-opt">
                                                <div href="" class="button-more-style-1">></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="full-screen-create-post" class="relative">
                            <a href="" class="button-style-5 full-screen-background "></a>
                            <div class="viewer">

                            </div>
                        </div>
                    </div>
                    <div>
                        <textarea name="post-textual-content" form="create-post-form" id="create-post-textual-content" class="textarea-style-2" placeholder="What's on your mind .."></textarea>
                    </div>
                    <div class="image-post-uploaded-container">
                        
                    </div>
                    <div class="row-v-flex horizontal-frame-style-1">
                        <div class="relative" style="overflow: hidden; width: 40px">
                            <input type="file" name="photo-or-video" multiple size="5" form="create-post-form" id="create-post-photo-or-video" class="absolute no-opacity-element">
                            <div class="photo-or-video-background button-style-6"></div>
                        </div>
                        <div class="relative" style="overflow: hidden; width: 40px">
                            <a type="file" class="no-opacity-element"></a>
                            <div class="black-live button-style-6"></div>
                        </div>
                    </div>
                    <div class="button-style-7-container" id="post-create-button">
                        <form action="api/post/post.php" method="POST" id="create-post-form" name="create-post-form" enctype="multipart/form-data">
                            <input type="hidden" name="post_owner" value="<?php echo $user->getPropertyValue("id"); ?>">
                            <input type="hidden" name="token_post" value="<?php 
                                    if(Session::exists("share-post")) 
                                        echo Session::get("share-post");
                                    else {
                                        echo Token::generate("share-post");
                                    }
                            ?>">
                            <input type="submit" name="share-post-button" value="POST" class="button-style-7 share-post">
                        </form>
                    </div>
                </div>
            </div>
            <div id="master-right">
                <div class="flex-space relative">
                    <h3 class="title-style-2">Contacts</h3>
                    <div>
                        <a href="" id="contact-search"></a>
                    </div>
                    <div class="absolute" id="contact-search-field-container">
                        <input type="text" id="contact-search-field" placeholder="Search by friend or group ..">
                        <a class="not-link" href=""><img src="assets/images/icons/close.png" id="close-contact-search" class="image-style-4" alt=""></a>
                    </div>
                </div>
                <div id="contacts-container">
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto223</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a><a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">Loupgarou</p>
                            <img src="assets/images/icons/online.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                    <a href="" class="contact-user-button">
                        <div class="contact-user">
                            <img src="assets/images/logos/logo512.png" class="image-style-3 contact-user-picture" alt="">
                            <p class="contact-user-name">grotto489</p>
                            <img src="assets/images/icons/offline.png" class="image-style-4 contact-user-connection-icon" alt="">
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
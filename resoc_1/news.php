<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>ReSoC - Actualités</title>
    <meta name="author" content="Julien Falconnet">
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php
    include 'header.php';
    ?>
    <div id="wrapper">
        <aside>
            <img src="user.jpg" alt="Portrait de l'utilisatrice" />
            <section>
                <h3>Présentation</h3>
                <p>Sur cette page vous trouverez les derniers messages de
                    tous les utilisatrices du site.</p>
            </section>
        </aside>
        <main>
            <?php

            // Etape 1: Ouvrir une connexion avec la base de donnée.
            
            include 'getDataBase.php';
            //verification
            if ($mysqli->connect_errno) {
                echo "<article>";
                echo ("Échec de la connexion : " . $mysqli->connect_error);
                echo ("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                echo "</article>";
                exit();
            }

            // Etape 2: Poser une question à la base de donnée et récupérer ses informations
            // cette requete vous est donnée, elle est complexe mais correcte, 
            // si vous ne la comprenez pas c'est normal, passez, on y reviendra
            $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.user_id,
                    posts.id,
                    users.alias as author_name,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.id ORDER BY tags.label ASC) AS tag_ids,
                    GROUP_CONCAT(DISTINCT tags.label ORDER BY tags.label ASC) AS taglist 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 5
                    ";
            $lesInformations = $mysqli->query($laQuestionEnSql);
            // Vérification
            if (!$lesInformations) {
                echo "<article>";
                echo ("Échec de la requete : " . $mysqli->error);
                echo ("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                exit();
            }

            // Etape 3: Parcourir ces données et les ranger bien comme il faut dans du html
            // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
            
            $array_button_post_id = array();
            // $button_post_id = $array_button_post_id[$];
            
            while ($post = $lesInformations->fetch_assoc()) {
                //la ligne ci-dessous doit etre supprimée mais regardez ce 
                //qu'elle affiche avant pour comprendre comment sont organisées les information dans votre 
                //  echo "<pre>" . print_r($post, 1) . "</pre>";
<<<<<<< HEAD
                $postID = $post['id'];
                echo $postID;

=======
                 $postID = $post['id'];
                //  echo $postID;
            
>>>>>>> a940d8f367ffc38c7e554d505f18f115adc8e2eb
                // @todo : Votre mission c'est de remplacer les AREMPLACER par les bonnes valeurs
                // ci-dessous par les bonnes valeurs cachées dans la variable $post 
                // on vous met le pied à l'étrier avec created
                // 
                // avec le ? > ci-dessous on sort du mode php et on écrit du html comme on veut... mais en restant dans la boucle
                ?>
                <article>
                    <h3>
                        <time>
                            <?php echo $post['created'] ?>
                        </time>
                    </h3>
                    <address><a href="usersPosts.php?user_id=<?php echo $post['user_id'] ?>">
                            <?php echo $post['author_name'] ?>
                        </a>
                    </address>
                    <div>
                        <p>
                            <?php echo $post['content'] ?>
                        </p>
                    </div>
                    <footer>
                        <small>♥
                            <?php echo $post['like_number'] ?>
                            <!-- ADD LIKE BUTTON HERE -->

                            <body>
                                <form method="post" action="">
                                    <?php
                                    // Check if user is not logged in
                                    if (!isset($_SESSION['connected_id'])) {
                                        ?>
                                        <!-- Display button for not logged in user -->
                                        <input type="submit" name="submit" value="Like">
                                    <?php } else {
                                        // Check if the user has liked the post
                                        $liked_post_id = $post['id'];
                                        $current_user_id = $_SESSION['connected_id'];
                                        $check_like_query = "SELECT COUNT(*) as count FROM likes WHERE post_id = $liked_post_id AND user_id = $current_user_id";
                                        // $check_like_query = "SELECT COUNT(*) as count FROM likes WHERE post_id = 12 AND user_id = $current_user_id";
                                        echo $liked_post_id;
                                        // echo $current_user_id;
                                        // echo $check_like_query;
                                        $result = $mysqli->query($check_like_query);
                                        // echo $result;
                                        $row = $result->fetch_assoc();
                                        // echo $row;
                                        $isLiked = ($row['count'] > 0);
                                        echo $isLiked;

                                        // Set button label based on follow status
                                        $buttonLabel = ($isLiked) ? "Dislike" : "Like";
                                        echo $buttonLabel;

                                        // Display the follow/unfollow button
                                        ?>
                                        <input type="submit" name="submit" id="<?php $liked_post_id ?>"
                                            value="<?php echo $liked_post_id, $buttonLabel; ?>">
                                    <?php } ?>
                                </form>
                            </body>


                            <!--  include 'likeButton.php'; -->
                            <?php array_push($array_button_post_id, $postID);
                            ?>

                        </small>
                        <?php
                        $tags = explode(',', $post['taglist']); // Explode the taglist into an array of tags
                        $tagIDs = explode(',', $post['tag_ids']);
                        $totalTags = count($tags); // Get the total number of tags
                        foreach ($tags as $index => $tag) {
                            // Trim each tag to remove any leading or trailing spaces
                            $tag = trim($tag);
                            // Display each tag preceded by #
                            echo '<a href="tags.php?tag_id=' . $tagIDs[$index] . '">#' . $tag . '</a>';
                            // Append a comma if it's not the last tag
                            if ($index < $totalTags - 1) {
                                echo ', ';
                            }
                        }
                        ?>

                    </footer>
                </article>
                <?php
                // avec le <?php ci-dessus on retourne en mode php 
            } // cette accolade ferme et termine la boucle while ouverte avant.
            
            // Check if the button has been clicked when not logged in
            if (isset($_POST['submit'])) {
                if (!isset($_SESSION['connected_id'])) {
                    header('Location: login.php');
                    exit;
                    // If the button is clicked when logged in
                } else {
                    // $liked_post_id = $post['id'];
                    // $current_user_id = $_SESSION['connected_id'];
                    // If user is following, unfollow them; otherwise, follow them
                    if ($isLiked) {
                        $deleteLikeQuery = "DELETE FROM likes WHERE post_id = $liked_post_id AND user_id = $current_user_id";
                        $mysqli->query($deleteLikeQuery);
                        echo $deleteLikeQuery;
                    } else {
                        $insertLikeQuery = "INSERT INTO likes (id, user_id, post_id) VALUES (NULL, $current_user_id, $liked_post_id)";
                        // $insertLikeQuery = "INSERT INTO likes (id, user_id, post_id) VALUES (NULL, $current_user_id, 9)";
                        $mysqli->query($insertLikeQuery);
                        echo $insertLikeQuery;
                    }
                    header("Location: " . $_SERVER['REQUEST_URI']);
                    exit;
                }
            }

            echo "<pre>" . print_r($array_button_post_id) . "<pre>";
            ?>


        </main>
    </div>
</body>

</html>
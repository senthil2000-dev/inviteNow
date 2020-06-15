<?php require_once("includes/header.php"); ?>

<div class="historySection">
    <?php
        $query=$con->prepare("SELECT statusPaused2 FROM users WHERE username=:user");
        $query->bindParam(":user", $username);
        $username=$userLoggedInObj->getUsername();
        $query->execute();
        $_SESSION["status2"]=$query->fetchColumn();
        $checked=$_SESSION["status2"]?"checked":"";
        $status=0;
        $query=$con->prepare("SELECT * FROM searchhistory where username=:username AND statusPaused=:status ORDER BY id DESC");
        $query->bindParam(":username", $usernameLoggedIn);
        $query->bindParam(":status", $status);
        $query->execute();
        $n=$query->rowCount();
        if($n==0) {
            $result="Your search history is empty";
            $deleteFull="";
        }
        else if($n==1) {
            $result="1 search found";
            $deleteFull="<span class='deleteMessage' onclick='deleteAll()'>Delete full history</span>
            <img class='deleteSearch' onmouseover='hover(this)' onmouseout='unhover(this)' onclick='deleteAll()' src='assets\images\icons\deletefull.png' title='delete full history' alt='Delete Full History Button'></img>";
        }
        else {
            $result="$n searches found";
            $deleteFull="<span class='deleteMessage' onclick='deleteAll()'>Delete full history</span>
            <img class='deleteSearch' onmouseover='hover(this)' onmouseout='unhover(this)' onclick='deleteAll()' src='assets\images\icons\deletefull.png' title='delete full history' alt='Delete Full History Button'></img>";
        }
        
        $html="<div class='searchHistoryHeader'>
                <span class='searchFound'>$result</span>
                $deleteFull
                <form id='submitForm' action='history.php' method='GET'>
                            <div class='right'>
                                <label>Pause search history:</label>
                                <label class='switch'>
                                <input type='checkbox' id='checking' onchange='status2()' $checked>
                                <span class='slider round'></span>
                                </label>
                            </div>
                </form>
                </div>";
        while($row=$query->fetch(PDO::FETCH_ASSOC)) {
            $searchTopic=$row["searchTopic"];
            $href=$row["searchResults"];
            $timespan=time_elapsed_string($row["time"]);
            $name=$row["id"];
            
            $html.="<div class='historyItem'>
                        <a class='searchItem' href='$href'>
                            <h3>$searchTopic</h3>
                            <img name='$name' class='deleteSearch' src='assets/images/icons/52-512.webp' title='delete' alt='Delete Button'></img>
                            <span class='timestamp'>$timespan</span>
                        </a>
                    </div>";
        }
        echo $html;

        function time_elapsed_string($datetime, $full = false) {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
        
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
        
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
        
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }
    
    ?>
    
    </div>

<?php require_once("includes/footer.php"); ?>
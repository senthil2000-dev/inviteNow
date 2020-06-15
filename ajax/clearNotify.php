<?php
require_once("../includes/config.php");

    $ids=explode(";",$_POST["ids"]);
    $condition="";
    $i=0;
    while($i<sizeof($ids)) {
            if($i==0) {
                $condition.= "WHERE id=?";
            }
            else {
                $condition.= " OR id=?";
            }
            $i++;
    }
        $inviteSql="UPDATE notifications SET seen=? $condition";
        $inviteQuery=$con->prepare($inviteSql);
        $i=1;
        $seen=1;
        $inviteQuery->bindValue(1, $seen);
        foreach($ids as $id) {
             $inviteQuery->bindValue(($i+1), $id);
             $i++;
        }

        echo $inviteQuery->execute();
?>
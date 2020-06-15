<?php
require_once("includes/header.php");
require_once("includes/classes/SearchResultsProvider.php");

if(!isset($_GET["term"]) || $_GET["term"]=="") {
    echo "You must enter a search term";
    exit();
}

$term=$_GET["term"];
$url="search.php?term=".$term;
$username=$userLoggedInObj->getUsername();

if(!isset($_GET["orderBy"])||$_GET["orderBy"]=="accepted") {
    $orderBy="accepted";
}
else {
    $orderBy="uploadDate";
}

$searchResultsProvider = new SearchResultsProvider($con, $userLoggedInObj);
$replys=$searchResultsProvider->getInvites($term, $orderBy);

$replyGrid= new InviteGrid($con, $userLoggedInObj);
?>

<div class="largeInviteGridContainer">
    <?php
    if(sizeof($replys)>1) {
        echo $replyGrid->createLarge($replys, sizeof($replys) . " results found", true);
    }
    elseif(sizeof($replys)==1) {
        echo $replyGrid->createLarge($replys, sizeof($replys) . " result found", true);
    }
    else {
        echo "No results found";
    }
    ?>
</div>
<?php
require_once("includes/footer.php");
?>
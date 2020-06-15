<?php
class InviteDetailsFormProvider{
    private $con, $content;

    public function __construct($con, $content){
        $this->con=$con;
        $this->content=$content;
    }
    public function createUploadForm($theme){
        $fileInput=$this->createFileInput();
        $titleInput=$this->createTitleInput(null);
        $descriptionInput=$this->createDescriptionInput(null);
        $privacyInput=$this->createPrivacyInput(null);
        $categoriesInput=$this->createCategoriesInput(null);
        $uploadButton=$this->createUploadButton();
        $dateEvent=$this->createDateInput(null);
        $deadlineEvent=$this->createDeadlineInput(null);
        $action="processing.php";
        $text=$this->content;
        $template=$theme;
        $search=$this->search();
        
        return "<form onsubmit='saveDetails(this)' action='$action' method='POST' enctype='multipart/form-data'>
                    <input type='text' name='inviteText' value='$text' hidden>
                    <input type='text' name='template' value='$template' hidden>
                    $fileInput
                    $titleInput
                    $descriptionInput
                    $privacyInput
                    $categoriesInput
                    $search
                    <input type='text' name='members' id='hiddenMem' hidden>
                    $dateEvent
                    $deadlineEvent
                    $uploadButton
                </form>";
    }
    public function createDateInput($value) {
        if($value==null) $value="";
        $start=date('Y-m-d');
        return "<div class='form-group'>
                <label for='eventDate'>Event Date:</label>
                <input type='date' min='$start' class='form-control-file' id='eventDate' name='eventDateInput' value='$value' required>
            </div>";
    }

    public function createDeadlineInput($value) {
        if($value==null) $value="";
        $start=date('Y-m-d H:i');
        $start=str_replace(" ", "T", $start);
        return "<div class='form-group'>
                <label for='eventDeadline'>Invitation Deadline:</label>
                <input type='datetime-local' min='$start' class='form-control-file' id='eventDeadline' name='deadlineInput' value='$value' required>
            </div>";
    }
    public function search() {
        $username=$_SESSION["userLoggedIn"];
        $user=new User($this->con, $username);
        $friends=$user->getFriends();
        $htmlFriends="";
        for($k=0;$k<sizeof($friends);$k++) {
            $htmlFriends.="<li>".$friends[$k]->getUsername()."</li>";
        }
        return "<div class='searchBarContainer'>
                        <div class='autocomplete' style='max-width:600px;width:100%;'>
                            <input type='text' id='recipient' class='searchBar searchInput' name='term' autocomplete='off' placeholder='Add recipients..'>
                        </div>
                        <div class='searchButton' onclick='addInvitee()'>
                        <img src='assets/images/icons/searchAdd.jpg'>
                        </div>
                </div>
                <ol id='invitees'>$htmlFriends</ol>";
    }
    public function createEditDetailsForm($invite){
        $titleInput=$this->createTitleInput($invite->getTitle());
        $descriptionInput=$this->createDescriptionInput($invite->getDescription());
        $dateEvent=$this->createDateInput($invite->getDate());
        $deadline=$invite->getDeadline();
        $deadline=str_replace(" ", "T", $deadline);
        $deadlineEvent=$this->createDeadlineInput($deadline);
        $saveButton=$this->createSaveButton();
        $text=$this->content;
        return "<form method='POST' id='draftPublish'>
                    <input type='text' id='draftHtml' name='inviteText' hidden>
                    $titleInput
                    $descriptionInput
                    $dateEvent
                    $deadlineEvent
                    $saveButton
                </form>";
    }

    private function createFileInput(){

    return "<div class='form-group'>
                <label for='exampleFormControlFile1'>Your coverphoto</label>
                <input type='file' class='form-control-file' id='exampleFormControlFile1' name='fileInput' required>
            </div>";
    }
    private function createTitleInput($value){
        if($value==null) $value="";
        return "<div class='form-group'>
                    <input class='form-control' type='text' placeholder='Title(max 60 characters)' name='titleInput' value='$value'>
                </div>";
    }
    private function createDescriptionInput($value){
        if($value==null) $value="";
        return "<div class='form-group'>
                    <textarea  class='form-control' placeholder='Description' name='descriptionInput' style='resize:none;' rows='3'>$value</textarea>
                </div>";
    }
    private function createPrivacyInput($value){
        if($value==null) $value="";

        $privateSelected=($value==0) ? "selected='selected'" : "";
        $publicSelected=($value==1) ? "selected='selected'" : "";

        return "<div class='form-group'>
                    <select class='form-control' id='privacyValue' name='privacyInput' onchange='privacyChange()'>
                        <option value='0' $privateSelected>Private</option>
                        <option value='1' $publicSelected>Public</option>
                    </select>
                </div>
                ";
        
    }
    private function createCategoriesInput($value) {
        if($value==null) $value="";
        $query=$this->con->prepare("SELECT * FROM categories");
        $query->execute();
        $html="<div class='form-group'>
        <select class='form-control' name='categoryInput'>";

        while($row=$query->fetch(PDO::FETCH_ASSOC)){
            $id=$row["id"];
            $name=$row["name"];
            $selected=($id==$value) ? "selected='selected'" : "";

            $html.="<option value='$id' $selected>$name</option>";
        }

        $html.="</select>
            </div>";
        
        return $html;
    }
    private function createUploadButton(){
        return "<button type='submit' class='btn btn-primary' name='uploadButton'>Upload</button>";
    }

    private function createSaveButton(){
        return "<button onclick='submitForm()' id='saveButton' class='btn btn-primary' name='saveButton'>Save</button>";
    }
}
?>
<?php
class InviteUploadData{
    public $inviteDataArray,$title,$description,$privacy,$category,$members,$uploadedBy;

    public function __construct($inviteDataArray, $content, $title,$description,$privacy,$category,$members,$dateEvent,$deadlineInvite,$uploadedBy){
        $this->inviteDataArray=$inviteDataArray;
        $this->title=$title;
        $this->content=$content;
        $this->description=$description;
        $this->privacy=$privacy;
        $this->category=$category;
        $this->members=$members;
        $this->dateEvent=$dateEvent;
        $deadlineInviteReplaced=str_replace("T", " ", $deadlineInvite);
        $this->deadlineInvite=$deadlineInviteReplaced;
        $this->uploadedBy=$uploadedBy;
    }

    public function updateDetails($con, $inviteId) {
        $query=$con->prepare("UPDATE invites SET title=:title, description=:description, content=:content, dateEvent=:dateEvent, deadlineInvite=:deadlineInvite WHERE id=:inviteId");
        $query->bindParam(":title", $this->title);
        $query->bindParam(":description", $this->description);
        $query->bindParam(":inviteId", $inviteId);
        $query->bindParam(':content', $this->content);
        $query->bindParam(":dateEvent", $this->dateEvent);
        $query->bindParam(":deadlineInvite", $this->deadlineInvite);
        return $query->execute();
    }
}
?>
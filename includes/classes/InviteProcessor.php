<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
class InviteProcessor{
    private $con, $message="";

    public function __construct($con){
        $this->con=$con;
    }
    
    public function upload($inviteUploadData, $theme){
        $targetdir="uploads/invites/coverphotos/";
        $inviteData=$inviteUploadData->inviteDataArray;

        $tempFilePath=$targetdir.uniqid().basename($inviteData["name"]);
        $tempFilePath=str_replace(" ","_",$tempFilePath);

        if(move_uploaded_file($inviteData["tmp_name"],$tempFilePath)){
            
            if(!$this->insertInviteData($inviteUploadData,$tempFilePath, $theme)){
                echo "Insert query failed";
                return false;
            }
            return true;

        }
    }

    private function insertInviteData($uploadData, $filePath, $theme){
        $query=$this->con->prepare("INSERT INTO invites(title,uploadedBy,description, content, privacy,category, members,dateEvent,deadlineInvite, theme)
                                    VALUES(:title, :uploadedBy, :description, :content, :privacy, :category, :members, :dateEvent, :deadlineInvite, :theme)");

        $query->bindParam(":title", $uploadData->title);
        $query->bindParam(":uploadedBy", $uploadData->uploadedBy);
        $query->bindParam(":content", $uploadData->content);
        $query->bindParam(":description", $uploadData->description);
        $query->bindParam(":privacy", $uploadData->privacy);
        $query->bindParam(":category", $uploadData->category);
        $query->bindParam(":members", $uploadData->members);
        $query->bindParam(":dateEvent", $uploadData->dateEvent);
        $query->bindParam(":deadlineInvite", $uploadData->deadlineInvite);
        $query->bindParam(":theme", $theme);
        $query->execute();
        $inviteId=$this->con->lastInsertId();
        if($uploadData->members!="open")
            $this->sendData($uploadData, $inviteId);
        $query=$this->con->prepare("INSERT INTO coverphotos(inviteId, filePath)
                                        VALUES(:inviteId,:filePath)");
        $query->bindParam(":inviteId",$inviteId);
        $query->bindParam(":filePath",$filePath);
        return $query->execute();
    }

    private function sendData($uploadData, $inviteId) {
        $members=$uploadData->members;
        $invitees=explode(";", $members);
        $uploader=$uploadData->uploadedBy;
        for($k=0;$k<sizeof($invitees);$k++) {
            $username=$invitees[$k];
            $query=$this->con->prepare("SELECT statusPaused,email FROM users WHERE username=:user");
            $query->bindParam(":user", $username);
            $query->execute();
            while($row=$query->fetch(PDO::FETCH_ASSOC)) {
                $status=$row["statusPaused"];
                $email=$row["email"];
            }
            $this->sendEmail($inviteId, $email);
            if($status==0) {
                $query=$this->con->prepare("INSERT INTO received(uploadedBy,user, inviteId) VALUES(:uploadedBy,:user, :inviteId)");
                $query->bindParam(":uploadedBy", $uploader);
                $query->bindParam(":user", $username);
                $query->bindParam(":inviteId", $inviteId);
                $query->execute();
                $action="sent you an invitation";
                $query=$this->con->prepare("INSERT INTO notifications(postedBy, invite_replyId, action, friends) VALUES(:user, :invite_replyId, :action, :sentTo)");
                $query->bindParam(":user", $uploader);
                $query->bindParam(":invite_replyId", $inviteId);
                $query->bindParam(":action", $action);
                $query->bindParam(":sentTo", $username);
                $query->execute();
            }
        }
        echo $this->message;
    }

    private function sendEmail($inviteId, $email) {
        $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                       
        $mail->Host       = 'smtp.gmail.com';                   
        $mail->SMTPAuth   = true;                                  
        $mail->Username   = '2senthil2018@gmail.com';              
        $mail->Password   = 'muthu2006';                              
        $mail->SMTPSecure = 'tls';        
        $mail->Port       = 587;                                  

        $mail->setFrom('2senthil2018@gmail.com', 'Invite Now');
        $mail->addAddress($email);    
        $mail->addReplyTo('mailsenthilnathan2003@gmail.com', 'No reply');
        $id=$inviteId;
        $url=$_SERVER["HTTP_HOST"].dirname($_SERVER["PHP_SELF"])."/read.php?id=$id";
        $mail->isHTML(true);
        $userObj=new User($this->con, $_SESSION["userLoggedIn"]);
        $invite=new Invite($this->con, $inviteId, $userObj);
        $category=$invite->getCategoryName();
        $sender=$invite->getUploadedBy();
        $heading=$invite->getTitle();
        $description=$invite->getDescription();
        $mail->Subject = $category.' invitation by '.$sender;
        $mail->Body    = "<h1 style='margin-bottom:0;'>$heading</h1>
                        <h4 style='margin-top:0;'>$description</h4>
                        <p>A $category has been scheduled. Visit <a href='$url'>this link</a> to view the invitation</p>";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $mail->send();
        $this->message = "<div style='flex:1;' class='alert alert-success'>Invitations have been sent via email as well. ";
    } catch (Exception $e) {
            $this->message = "<div style='flex:1;' class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo} ";
    }
    
    }
}
?>
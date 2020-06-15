<?php
class InviteRead {

    private $invite;

    public function __construct($invite) {
            $this->invite=$invite;
    }

    public function create() {
        $content=$this->invite->getContent();
        return "<div class='inviteRead'>
                    $content   
                </div>";
    }

}
?>
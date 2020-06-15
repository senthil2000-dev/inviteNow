const msg = new SpeechSynthesisUtterance();
msg.volume = 1; 
msg.rate = 1; 
msg.pitch = 1.5; 
msg.text  = document.getElementsByClassName("inviteRead")[0].textContent;
msg.voiceURI = "Alex";
msg.lang = "en-US";
var speaker=document.getElementById("speaker");
speaker.addEventListener("click", function () {
    if(speaker.classList.contains("speaking")) {
        speechSynthesis.cancel();
    }
    else {
        speechSynthesis.speak(msg);
    }
    speaker.classList.toggle("speaking");
});

window.addEventListener("beforeunload", myScript);
function myScript() {
    if(speaker.classList.contains("speaking")) {
        speechSynthesis.cancel();
        speaker.classList.toggle("speaking");
    }
}

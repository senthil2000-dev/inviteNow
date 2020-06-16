document.addEventListener('DOMContentLoaded', function(){ 
  var xhttp = new XMLHttpRequest();
xhttp.open("POST", "ajax/searchSuggestions.php", true);
xhttp.send();
xhttp.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    console.log(JSON.parse(this.responseText));
    autocomplete(document.getElementsByClassName("searchInput")[0], JSON.parse(this.responseText));
    }
};

if(document.getElementsByClassName("searchInput")[1]) {
  console.log(111);
var xmlh = new XMLHttpRequest();
xmlh.open("POST", "ajax/searchSuggestions2.php", true);
xmlh.send();
xmlh.onreadystatechange = function() {
  if (this.readyState == 4 && this.status == 200) {
    console.log(JSON.parse(this.responseText));
    autocomplete(document.getElementsByClassName("searchInput")[1], JSON.parse(this.responseText));
    }
};
}});

function autocomplete(inp, arr) {
    var currentFocus;
    inp.addEventListener("input", function(e) {
        var a, b, i, n, val = this.value;
        closeAllLists();
        if (!val) 
            { return false;}
        currentFocus = -1;
        a = document.createElement("div");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        this.parentNode.appendChild(a);
        for (i = 0; i < arr.length; i++) {
          if (arr[i].toUpperCase().indexOf(val.toUpperCase()) != -1) {
            b = document.createElement("div");
            n=arr[i].toUpperCase().indexOf(val.toUpperCase());
            b.innerHTML = arr[i].substr(0, n);
            b.innerHTML += "<strong>" + arr[i].substr(n, val.length) + "</strong>";
            b.innerHTML += arr[i].substr(n+val.length, arr[i].length-n-val.length);
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                b.addEventListener("click", function(e) {
                inp.value = this.getElementsByTagName("input")[0].value;
                closeAllLists();
            });
            a.appendChild(b);
          }
        }
    });
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) 
            x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          currentFocus++;
          addActive(x);
        } else if (e.keyCode == 38) {
          currentFocus--;
          addActive(x);
        } else if (e.keyCode == 13) {
          e.preventDefault();
          if (currentFocus > -1) {
            if (x) 
                x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      if (!x) 
        return false;
      removeActive(x);
      if (currentFocus >= x.length) 
        currentFocus = 0;
      if (currentFocus < 0) 
        currentFocus = (x.length - 1);
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
  }

  function addInvitee() {
    var list = document.getElementById("invitees");
    var listEl=document.querySelectorAll("#invitees li");
    var rec=document.getElementById("recipient").value;
    document.getElementById("recipient").value="";
    for(var m=0;m<listEl.length;m++) {
      if(listEl[m].textContent==rec) {
        alert("Already added");
        return;
      }
    }
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "ajax/addRecipient.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    xhttp.send("rec="+rec);
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          console.log(this.responseText);
          if(this.responseText=="same")
            alert("You can't invite yourself");
          else if(this.responseText!=0)
            list.innerHTML+=("<li>"+rec+"</li>");
          else
            alert("username doesn't exist");
        }
      };
  }

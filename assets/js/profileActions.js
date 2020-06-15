var items=document.getElementsByClassName("nav-item");
console.log(items);
Array.from(items).forEach(item=>item.addEventListener("click", function (){
    document.querySelector(".active").classList.remove("active");
    this.querySelector(".nav-link").classList.add("active");
    var str=this.querySelector(".nav-link").id.replace("-tab", "");
    document.querySelector(".tab-pane.active").classList.remove("active");
    document.getElementById(str).classList.add("active");
}));
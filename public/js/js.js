

var selectElement = document.querySelector("#event_location");
selectElement.addEventListener('change',
    (event)=>{
    var value = event.currentTarget.value;
    console.log(value);
    var li = document.querySelectorAll('#ullocation li');
    li.forEach(function (el){
        el.style.display="none";
    });

    document.getElementById(value).style.display="block";

    });


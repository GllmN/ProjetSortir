

var selectElement = document.querySelector("#event_location");
selectElement.addEventListener('change',
    (event)=>{
    var value = event.currentTarget.value;
    console.log(value);
    document.getElementById(value).children[2].style.display="none";
    });


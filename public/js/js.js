

var selectElement = document.querySelector("#event_location");
selectElement.addEventListener('change',
    (event)=>{
    let value = event.currentTarget.value;
    console.log(value);
    });


let x = document.cookie;
if(x != "chapta=success"){
    document.getElementById("chapta-root-container").style.display="unset"
}
let chapta = document.querySelector('.chapta-container');
let livercheese = document.getElementById('livercheese');
let ketchup = document.getElementById('ketchup');
let senf = document.getElementById('senf');

livercheese.addEventListener('dragstart', function(event) {
    event.dataTransfer.setData('text/plain', 'livercheese');
});

ketchup.addEventListener('dragstart', function(event) {
    event.dataTransfer.setData('text/plain', 'ketchup');
});

senf.addEventListener('dragstart', function(event) {
    event.dataTransfer.setData('text/plain', 'senf');
});

chapta.addEventListener('dragover', function(event) {
    event.preventDefault();
});

chapta.addEventListener('drop', function(event) {
    event.preventDefault();
    let id = event.dataTransfer.getData('text/plain');
    if (id === 'senf') {
        document.getElementById("description").innerHTML="Sie sind kein Roboter, Verifikation erfolgreich."
        document.cookie = "chapta=success";
    } else if (id === 'ketchup') {
        document.getElementById("description").innerHTML="Ketchup hat auf der Leberkassemme nix zu suchen eins11!eins11!!! Bitte versuchen Sie es erneut."
    }
});

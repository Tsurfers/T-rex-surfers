const game = document.getElementById("game");
const dino = document.getElementById("dino");
const scoreElem = document.getElementById("score-game");
const playButton = document.getElementById("play-button");
const playerPseudo = document.getElementById("playerPseudo");
const scoreInput = document.getElementById("score");
const form = document.getElementById("login-form");
const pseudoInput = document.getElementById("pseudo");

let tabCactus = Create2DArray(5);

let tab = [0, 100, 200, 300, 400];
let i = 2;
let commonSpeed = 2;
let score = 0;
let lastLine = -1;

dino.style.top = tab[i] + "px";

if (playerPseudo != null){
  form.style.display = "none";
}

document.addEventListener('keydown', function(e) {
  switch (e.code) {
    case "ArrowDown":
      if(i < 4){
        dino.style.top = tab[i + 1] + "px";
        i++;
      }
      break;
    case "ArrowUp":
      if(i > 0){
        dino.style.top = tab[i - 1] + "px";
        i--;
      }
      break;
    case "KeyW":
      if(i > 0){
        dino.style.top = tab[i - 1] + "px";
        i--;
      }
      break;
    case "KeyS":
      if(i < 4){
        dino.style.top = tab[i + 1] + "px";
        i++;
      }
      break;
  }
});

function LaunchGame(){
  if (playerPseudo == null){
    alert("Entrez un pseudo pour jouer.");
    return;
  }
  playButton.parentNode.removeChild(playButton);
  // Set img to gif
  let spawnCactus = setInterval(function(){
    let a = Math.floor(Math.random() * 5);
    while(a === lastLine){
      a = Math.floor(Math.random() * 5);
    }
    if(a === 5){
      a = 4;
    }
    CreateACactus(a, Math.random() * 2 + commonSpeed);
    lastLine = a;
  }, 500);
  
  let checkPos = setInterval(function () {
    for (let t = 0; t < tabCactus.length; t++) {
      for (let c = 0; c < tabCactus[t].length; c++) {
        let cactusLeftPos = parseInt(window.getComputedStyle(tabCactus[t][c]).getPropertyValue("left"));
        if(cactusLeftPos <= -10){
          score++;
          scoreElem.innerHTML = score;
          tabCactus[t][c].parentNode.removeChild(tabCactus[t][c]);
          commonSpeed = 1.5 - (score/1000);
          //console.log(commonSpeed);
        }
      }
    }
    for (let m = 0; m < tabCactus[i].length; m++) {
      let cactusOnLineLeftPos = parseInt(window.getComputedStyle(tabCactus[i][m]).getPropertyValue("left"));
      if(cactusOnLineLeftPos <= 100 && cactusOnLineLeftPos >= 0){
        clearInterval(spawnCactus);
        clearInterval(checkPos);
        StopEverything();
        scoreInput.value = score;
        pseudoInput.value = playerPseudo.innerHTML;
        form.submit();
      }
    }
  }, 10);
}

function StopEverything(){
  for (let d = 0; d < tabCactus.length; d++) {
    for (let j = 0; j < tabCactus[d].length; j++) {
      let pos = parseInt(window.getComputedStyle(tabCactus[d][j]).getPropertyValue("left"));
      tabCactus[d][j].style.animation = "";
      tabCactus[d][j].style.left = pos + "px";
    } 
  }
}

function CreateACactus(line, speed){
  let cactus = document.createElement("div");
  cactus.classList.add("cactus");
  cactus.style.top = tab[line] + "px";
  cactus.style.animation = "block " + speed + "s linear";
  tabCactus[line][tabCactus[line].length] = cactus;
  game.appendChild(cactus);
}

function Create2DArray(rows) {
  let arr = [];

  for (let z=0; z<rows; z++) {
    arr[z] = [];
  }

  return arr;
}
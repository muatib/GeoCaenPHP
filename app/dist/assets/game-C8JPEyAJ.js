function n(e){const t=document.getElementById(e);t&&t.classList.add("show")}function o(e){const t=document.getElementById(e);t&&t.classList.remove("show")}document.addEventListener("click",e=>{e.target.id==="submitButton"?c():e.target.id==="closeCorrectPopup"?nextQuestion():e.target.id==="closeIncorrectPopup"?o("incorrectPopup"):e.target.id==="showClue"?n("popup"):e.target.closest("#popup .link")?o("popup"):e.target.closest("#popupk .link:first-child")&&o("popupk")});function c(){const e=document.getElementById("answer").value.toLowerCase(),t=document.getElementById("answer");if(e===gameData.answer.toLowerCase())n("correctPopup");else{t.value="",t.classList.add("game__form-wrong","shake");const s=document.querySelector("#txt__wrong");s.classList.add("wrong__txt--disp"),s.classList.remove("wrong__txt")}}document.addEventListener("DOMContentLoaded",function(){let e=document.querySelectorAll(".slide-container"),t=0;function s(){e[t].classList.remove("active"),t=(t+1)%e.length,e[t].classList.add("active")}e[0].classList.add("active"),setInterval(s,5e3)});

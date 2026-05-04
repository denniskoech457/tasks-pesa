function getCookie(name){const v=('; '+document.cookie).split('; '+name+'=');if(v.length===2)return decodeURIComponent(v.pop().split(';').shift());return ''}
function setCookie(name,value,days=30){const d=new Date();d.setTime(d.getTime()+days*24*60*60*1000);document.cookie=name+'='+encodeURIComponent(value)+';expires='+d.toUTCString()+';path=/;SameSite=Lax'}
function deleteCookie(name){document.cookie=name+'=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/'}
function getBalance(){return parseInt(localStorage.getItem('balance')||'0',10)}
function setBalance(v){localStorage.setItem('balance',String(v));updateBalance()}
function updateBalance(){document.querySelectorAll('#balanceText').forEach(el=>el.textContent='KES '+getBalance().toLocaleString())}
updateBalance();
const menuBtn=document.getElementById('menuBtn'); if(menuBtn){menuBtn.addEventListener('click',()=>document.querySelector('.sidebar').classList.toggle('show'))}
document.querySelectorAll('.reward-form').forEach(form=>{form.addEventListener('submit',e=>{e.preventDefault(); if(form.dataset.done==='1')return alert('You already completed this task.'); const reward=parseInt(form.dataset.reward||'0',10); setBalance(getBalance()+reward); form.dataset.done='1'; const btn=form.querySelector('button'); btn.textContent='Completed ✓ Reward Added'; btn.disabled=true; alert('Task completed. KES '+reward+' added to your balance.');})});

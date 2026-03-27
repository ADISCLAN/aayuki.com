/* shared.js — AAYUKI */
function initCursor(){
  const d=document.getElementById('cur'),l=document.getElementById('curl');
  if(!d||!l)return;
  let cx=0,cy=0,lx=0,ly=0;
  document.addEventListener('mousemove',e=>{cx=e.clientX;cy=e.clientY;d.style.left=cx+'px';d.style.top=cy+'px';});
  (function loop(){lx+=(cx-lx)*.12;ly+=(cy-ly)*.12;l.style.left=lx+'px';l.style.top=ly+'px';requestAnimationFrame(loop);})();
  document.querySelectorAll('a,button,.btn,.card,.flink,.nav-links a,.soc,.badge').forEach(el=>{
    el.addEventListener('mouseenter',()=>document.body.classList.add('ch'));
    el.addEventListener('mouseleave',()=>document.body.classList.remove('ch'));
  });
}
function initProgress(){
  const p=document.getElementById('prog');
  if(!p)return;
  window.addEventListener('scroll',()=>{p.style.width=Math.min(window.scrollY/(document.body.scrollHeight-window.innerHeight)*100,100)+'%';});
}
function initNav(page){
  const nav=document.getElementById('nav');
  if(!nav)return;
  window.addEventListener('scroll',()=>nav.classList.toggle('sc',window.scrollY>50));
  nav.querySelectorAll('.nav-links a').forEach(a=>{if(a.getAttribute('href')===page)a.classList.add('active');});
  const ham=document.getElementById('ham'),mob=document.getElementById('mob');
  if(ham&&mob){
    ham.addEventListener('click',()=>mob.classList.toggle('open'));
    mob.querySelectorAll('a').forEach(a=>{
      if(a.getAttribute('href')===page)a.classList.add('active');
      a.addEventListener('click',()=>mob.classList.remove('open'));
    });
    const mc=document.getElementById('mob-close');
    if(mc)mc.addEventListener('click',()=>mob.classList.remove('open'));
  }
}
function initReveal(){
  const obs=new IntersectionObserver(entries=>{
    entries.forEach((e,i)=>{if(e.isIntersecting)setTimeout(()=>e.target.classList.add('in'),i*55);});
  },{threshold:.07});
  document.querySelectorAll('.rv,.rv-l,.rv-r').forEach(el=>obs.observe(el));
}
function initCounters(){
  const obs=new IntersectionObserver(entries=>{
    entries.forEach(e=>{
      if(e.isIntersecting){
        const el=e.target,to=+el.dataset.to,dur=+(el.dataset.dur||2000);
        let s=null;
        (function step(ts){if(!s)s=ts;const p=Math.min((ts-s)/dur,1),ease=1-Math.pow(1-p,4);el.textContent=Math.floor(ease*to);if(p<1)requestAnimationFrame(step);else el.textContent=to;})(performance.now());
        obs.unobserve(el);
      }
    });
  },{threshold:.5});
  document.querySelectorAll('.count').forEach(el=>obs.observe(el));
}
function initScrollTop(){
  const btn=document.getElementById('scrollTop');
  if(!btn)return;
  window.addEventListener('scroll',()=>{
    if(window.scrollY>300)btn.classList.add('show');
    else btn.classList.remove('show');
  });
  btn.addEventListener('click',()=>{
    window.scrollTo({top:0,behavior:'smooth'});
  });
}
function initAll(page){initCursor();initProgress();initNav(page);initReveal();initCounters();initScrollTop();}

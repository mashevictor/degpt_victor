new mke.Scroll(".mkb-HJrSbFEO5",{viewIn:{animation:{theAnimation:'ani-T1tH7t',action:'play'}},animations:[{animationID:'ani-T1tH7t',clips:[{clipType:'text',textElement:'words',method:'from',ease:'power1.out',stagger:0.1,to:{yPercent:'100',opacity:'0'}}]}]});new mke.Scroll(".mkb-pJAkG3nfD",{viewIn:{animation:{theAnimation:'ani-CtBfq4',action:'play',delay:0.3}},animations:[{animationID:'ani-CtBfq4',clips:[{clipType:'element',target:['.rotating-card'],child:!0,method:'from',duration:1,stagger:0.1,to:{yPercent:'100',opacity:'0'}}]}]});(()=>{const rotatingCards=document.querySelectorAll('.rotating-card');rotatingCards.forEach(card=>{const xRotationMax=parseFloat(card.getAttribute('x-rotation-max'))||10;const yRotationMax=parseFloat(card.getAttribute('y-rotation-max'))||10;let isHovering=!1;function setRotation(event){if(!isHovering)return;const rect=card.getBoundingClientRect();const centerX=rect.left+rect.width/2;const centerY=rect.top+rect.height/2;const mouseX=event.clientX-centerX;const mouseY=event.clientY-centerY;const rotateY=gsap.utils.clamp(-xRotationMax,xRotationMax,(mouseX/centerX)*xRotationMax);const rotateX=gsap.utils.clamp(-yRotationMax,yRotationMax,-(mouseY/centerY)*yRotationMax);gsap.set(card,{rotateY,rotateX})}
card.addEventListener('mouseenter',function(){isHovering=!0});card.addEventListener('mouseleave',function(){isHovering=!1;gsap.to(card,{rotateY:0,rotateX:0,duration:0.5,delay:0.2,ease:"power2.out"})});card.addEventListener('mousemove',setRotation)})})();
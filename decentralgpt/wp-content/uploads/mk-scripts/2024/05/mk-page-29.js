new mke.Scroll(".mkb-89I7uIzif",{viewIn:{animation:{theAnimation:'ani-jYs_kB',action:'play'}},animations:[{animationID:'ani-jYs_kB',clips:[{clipType:'element',method:'from',duration:1,ease:'power1.out',to:{yPercent:'50',opacity:'0'}}]}]});new mke.Scroll(".mkb-nia98M3fT",{viewIn:{animation:{theAnimation:'ani-Ldsi6T',action:'play',delay:0.5}},animations:[{animationID:'ani-Ldsi6T',clips:[{clipType:'element',target:['.mkb-9txnP3g90'],method:'from',ease:'power1.out',to:{yPercent:'50',opacity:'0'}},{clipType:'element',target:['.mkb-nbSNNlCVb'],method:'from',ease:'power1.out',to:{yPercent:'50',opacity:'0'},delayPrevious:0.3},{clipType:'element',target:['.mkb-1BNzblc9E'],method:'from',ease:'power1.out',to:{yPercent:'50',opacity:'0'},delayPrevious:0.3}]}]});new mke.Scroll(".mkb-njoQ_XHz2",{viewIn:{animation:{theAnimation:'ani-UhlSHn',action:'play'}},animations:[{animationID:'ani-UhlSHn',clips:[{clipType:'element',target:['.card1'],child:!0,method:'from',duration:1,ease:'power1.out',stagger:0.1,to:{yPercent:'100',opacity:'0'}}]}]});new mke.BouncingItem(".mkb-SIBqHpR43",{devices:['Desktop'],power:3,inner:!0});new mke.BouncingItem(".mkb-SIBqHpR43",{devices:['Desktop'],power:3,inner:!0});new mke.BouncingItem(".mkb-SIBqHpR43",{devices:['Desktop'],power:3,inner:!0});new mke.BouncingItem(".mkb-SIBqHpR43",{devices:['Desktop'],power:3,inner:!0});new mke.BouncingItem(".mkb-SIBqHpR43",{devices:['Desktop'],power:3,inner:!0});(()=>{const rotatingCards=document.querySelectorAll('.rotating-card');rotatingCards.forEach(card=>{const xRotationMax=parseFloat(card.getAttribute('x-rotation-max'))||10;const yRotationMax=parseFloat(card.getAttribute('y-rotation-max'))||10;let isHovering=!1;function setRotation(event){if(!isHovering)return;const rect=card.getBoundingClientRect();const centerX=rect.left+rect.width/2;const centerY=rect.top+rect.height/2;const mouseX=event.clientX-centerX;const mouseY=event.clientY-centerY;const rotateY=gsap.utils.clamp(-xRotationMax,xRotationMax,(mouseX/centerX)*xRotationMax);const rotateX=gsap.utils.clamp(-yRotationMax,yRotationMax,-(mouseY/centerY)*yRotationMax);gsap.set(card,{rotateY,rotateX})}
card.addEventListener('mouseenter',function(){isHovering=!0});card.addEventListener('mouseleave',function(){isHovering=!1;gsap.to(card,{rotateY:0,rotateX:0,duration:0.5,delay:0.2,ease:"power2.out"})});card.addEventListener('mousemove',setRotation)})})();
<template>
  <TopNav></TopNav>
  <slot />
    <div id='tawk_5eb9b7178ee2956d73a02bb0'></div>

  <BottomNav></BottomNav>
</template>

<script>

import BottomNav from "../Components/Navs/BottomNav.vue";
import TopNav from "../Components/Navs/TopNav.vue";
export default {
  components: { BottomNav, TopNav }
};

    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/6339daab54f06e12d898097d/1gecvsdgs';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
})();




</script>

<style>
#tawk_5eb9b7178ee2956d73a02bb0{
    position: fixed;
    left: 10px;
    bottom: 0;
}
</style>

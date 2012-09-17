 function select_nav() {
   var nav_links = document.getElementById('sidebar_content')
     .getElementsByTagName('a');
   var selected = location.pathname;
   
   for (var i = 0; i < nav_links.length; i++) {
     var link = nav_links[i].pathname;
     
     // fiddle IE's view of the link
     if (link.substring(0, 1) != '/')
       link = '/' + link;
     
     if (link == selected)
       nav_links[i].setAttribute(cattr, 'selected');
   }
 }
 
 window.onload = function() {
   select_nav();
 };
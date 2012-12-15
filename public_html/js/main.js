/*
  Document   : style
  Created on : 5-dec-2012, 9:04:52
  Author     : Glenn Blom <glennblom@gmail.com>
 */

$(document).ready(function() {
    $("#nav ul li").hover(
        function () {
            if ($(this).find('ul').length != 0) {
                $(this).find('a').css("background-image" , "url('/img/pc/arrow.png')");
                $(this).find('ul').slideDown('fast');
            }
            
        }, 
        function () {
            if ($(this).find('ul').length != 0) {
                $(this).find('a').css("background-image" , "url('')");
                $(this).find('ul').slideUp('fast');
            }
        }
    );
});
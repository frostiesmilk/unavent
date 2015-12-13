/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Check all messages
 * @param {type} param
 */
$("#checkAllMessages").click(function () {
    $(".messages-stream-element-selector").prop('checked', $(this).prop('checked'));
});


/**
 * All messages - clickable
 */
$(".messages-stream-element-clickable").click(function() {
    window.document.location = $(this).parent().data("href");
});
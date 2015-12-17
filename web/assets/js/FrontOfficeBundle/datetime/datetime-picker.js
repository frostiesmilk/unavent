/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(function () {
    $('.flowber_datepicker').datetimepicker({
        locale: 'fr',
        format: 'L',
    });
    $('.flowber_timepicker').datetimepicker({
        locale: 'fr',
        format: 'LT',
    });
});

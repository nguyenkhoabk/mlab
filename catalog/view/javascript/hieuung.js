$(document).ready(function(){
   var flag;   
   $('.box-heading').click(function(){
            $(this).parent().find('.box-product').slideToggle(500);
            if(flag){
                $(this).find('.slidedown').text('↑');
                flag = false;
            }else{
                $(this).find('.slidedown').text('↓');
                flag = true;
            }
            
   }); 
});
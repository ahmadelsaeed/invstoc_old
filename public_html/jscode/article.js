$(function(){
 

  $("body").on("click",".add_artical_comment_btn",function(){
       
        var e = jQuery.Event("keypress");
        e.which="13";
        e.shiftKey=false;
      
        $(".add_artical_comment").trigger(e);

        return false;
    });

  $("body").on("keypress",".add_artical_comment",function(e){
    if(e.which=="13"&&e.shiftKey==false){
    var this_element = $(this);
    var article_id=this_element.data("articleid");
    var comment_val = $(this).val();

     if($.trim(comment_val).length==0){
                return false;
            }
    var form_data =new FormData();
    
    this_element.attr("disabled","disabled");
     
    form_data.append("article_id",article_id);
    form_data.append("user_id",this_element.data("userid"));
    form_data.append("comment_body",comment_val);
    form_data.append("_token",_token);

    $.ajax({
      url: base_url2 + "/articles/add_comment",
      type:'post',
      data: form_data,
      cache: false,
      processData: false,
      contentType: false,
     
      success: function (data) 
      {   $(".article_comments").append(data);
          this_element.removeAttr("disabled");
          this_element.val("");
           
        

      }
    });
     
    //console.log(base_url2 + "/articles/add_comment");
    }
  });



    
  });

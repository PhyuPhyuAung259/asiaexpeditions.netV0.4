$(document).ready(function(){
	var baseUrl = location.protocol+'//'+location.host;   
    $(document).on("click", ".windowbg .close", function(){
        $("#loadImage").removeClass("open-uploadImage");
    });

    $("#loadImage").css({'max-height':'400px', 'overflow-y':'auto'});

    $(document).on("click","#loadImage.open-uploadImage ul li",  function(){        
        var activeli = $(this);
        var liActive = $("#loadImage ul li");
        if ($(this).data('type') == "single-img") {
            // $(".btnFeatureImageSet").text("Set Feature Image");
            if($(activeli).hasClass("active")){
                $(liActive).removeClass("active");
                $("#image_title").text("");
                $("#preview-item-side img").attr("src","");
                $("#img-details").css({"display":"none"}); 
                $("#url-image").val("");
                $(".btnFeatureImageSet").attr("disabled", true);
                $(".btnFeatureImageSet").removeAttr("data-filename");
                $(".reMoveImageFileUPloaded").attr("data-url", "");
                
            }else{
                $(liActive).removeClass("active");
                $(activeli).addClass("active");
                $("#image_title").text($(activeli).data("label"));
                $("#preview-item-side img").attr("src",$(activeli).data("url"));
                $("#img-details").css({"display":"block"});
                $("#url-image").val($(activeli).data("url"));
                $(".btnFeatureImageSet").attr("disabled", false);
                $(".btnFeatureImageSet").attr('data-filename', $(activeli).data("url"));
                $(".reMoveImageFileUPloaded").attr("data-url", $(activeli).data("url"));  
            }
        }
        if ($(this).data('type') == "multi-img") {
            if($(this).hasClass("active")){
                $(this).removeClass("active");
                $("#image_title").text("");
                $("#preview-item-side img").attr("src","");
                $("#img-details").css({"display":"none"});
                $("#url-image").val("");
                $(".btnFeatureImageSet").attr("disabled", true);
                $(".btnFeatureImageSet").removeAttr("data-filename");
                $(".reMoveImageFileUPloaded").attr("data-url", "");
            }else{
                $(activeli).addClass("active");
                $("#image_title").text($(activeli).data("label"));
                $("#preview-item-side img").attr("src",$(activeli).data("url"));
                $("#img-details").css({"display":"block"});
                $("#url-image").val($(activeli).data("url"));
                $(".btnFeatureImageSet").attr("disabled", false);
                $(".btnFeatureImageSet").attr('data-filename', $(activeli).data("url"));
                $(".reMoveImageFileUPloaded").attr("data-url", $(activeli).data("url"));  
            }

            data_selected = "";
            $($("#loadImage ul li.active")).each(function(i, val){
                if ($(this).hasClass("active")) {
                    data_selected +="<li data-label='"+$(this).data("label")+"'  data-url='"+$(this).data("url")+"'><img src='"+$(this).data("url")+"' /></li>";
                }
            });
           $(".seleted-img ul").html(data_selected);
        }
    }); 
   
    $(document).on("click","#removeImage", function(){
        $("#wrap-feature-image").removeClass("open-img");
        $("#data-img").val("");
        $("#feature-img").attr("src", "");
    });
    $(document).on("click", ".removegallery", function(){
        $(this).closest('li').remove();        
    }); 
    $(document).on("click","#clear-gallery",  function(){
        $("#gallery-image li").remove();
    });

    $(document).on("click change",".reMoveImageFileUPloaded", function(e){
        pathImg = $("#image_title").text();
        $.ajax({
            method: "GET",
            url: "/window/remove/fileUploaded",
            data: {'pathImg':pathImg},
            dataType: 'json',          
            success: function(dataFile){
                if (dataFile.message == "Yes") {
                    var selecteli = $("#loadImage ul").find(" li.active");
                    $("#img-details").css({"display":"none"});
                    $("#url-image").val("");
                    $(".btnFeatureImageSet").attr("disabled", true);
                    alert('Deleted successfully');
                    $(selecteli).remove();
                }else{
                    alert('Not Delete');
                }
            },
            error: function(){
                alert("Something Wrong.");
                return false;
            }
        });
    });

	$(".btnUploadFiles").on('click', function(i){
        datatype = $(this).data('type');
        $("#loadImage").addClass("open-uploadImage");
        if ($("#loadImage").hasClass("open-uploadImage")) {
            loadFileImage(datatype);
        }
        if (datatype == "single-img") {
            $("#btnsetfeature").css({'display':'block'});
            $("#btnsetgallery").css({'display':'none'});
            $(".seleted-img ul li").remove();
        }else{
            $("#btnsetgallery").css({'display':'block'});
            $("#btnsetfeature").css({'display':'none'});
        }
	});
    $(document).on("change", "#sort_photoBymonth", function(){        
        loadFileImage(datatype, $(this).val());
    }); 

    $(document).on("click", ".btnFeatureImageSet", function(e){
        if ( $(this).data('type') == "single-img" ) {
            var fileName = $("#url-image").val();
            $("#wrap-feature-image").addClass("open-img");
            $("#data-img").val($("#image_title").text());
            $("#feature-img").attr("src", fileName);
            $("#myUpload").modal("hide");
        }else if ( $(this).data('type') == "multi-img" ) {
            var gallery_selected = ""; 
            var data_path = '';
            $(".seleted-img ul li").each(function(i){
                data_path += $(this).data("url")+"|";
                gallery_selected +="<li data-url='"+$(this).data("url")+"'><i class='removegallery fa fa-remove (alias)' title='Remove picture' style='display: none;'></i><input type='hidden' name='gallery[]' value='"+$(this).data("label")+"'><img src='"+$(this).data("url")+"' style='width:100%;' /></li>";
            });
            $("#wrap-gallery-image ul").append(gallery_selected);
            $("#myUpload").modal("hide");
        }
    });

    $(document).on("click", "#btnUPload", function(){
        $("#btnUPloadFile").click();
    });

    $("#btnUPloadFile").change(function(){
        $("#form_uploadeFile").submit();
    }); 
    
    // upload all image and picture here
    $("form#form_uploadeFile").submit( function(e){
        var formData = new FormData($(this)[0]);
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: "/window/uploadfile",
            data: formData,
            type: 'POST',
            async: false,
            success: function(html){
                alert(html.message);
                $("ul.windowbg li.upload_file").removeClass("active");
                $("ul.windowbg li.media-library").addClass("active");
                $("#media-content").addClass("active");
                $("#upload_file").removeClass("active");
                loadFileImage('multi-img');
            },
            cache: false,
            datatype: 'json',
            contentType: false,
            processData: false,
            error: function(xhr, status, error){
                alert("Error!" + xhr.status);
                return false;
            }            
        });
    });


    $(document).on("submit", "#form_uploadeFileOnly", function(e){
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        $.ajax({
            method: "POST",
            url: " ",
            data: formData,
            type: 'POST',
            async: false,
            success: function(html){
                if (html.onlyFile.length > 0) {
                    $(".remove_image").show();
                }else{
                    $(".remove_image").hide();
                }
                $("#removeLogo").attr("data-filename", html.onlyFile);
                $("#removeLogo").attr("data-id", html.collect.cp_id);
                $('#exampleModalCenterTitle').modal('hide');
                $("#wrapper-logo").html("<img style='width:100%;' src='/storage/avata/"+html.onlyFile+"' />");
            },
            cache: false,
            datatype: 'json',
            contentType: false,
            processData: false,
            error: function(xhr, status, error){
                alert("Error!" + xhr.status);
                return false;
            },
            complete: function() {
               $(".data_filter", this).next().remove();
            }        
        });
    });


    $(document).on("click", "#removeLogo",  function(e){
        var id = $(this).data('id');
        var filename = $(this).data('filename'); 
        $.ajax({
            method: "GET",
            url: "/window/remove-image/logo?cp_id="+id +"&filename="+filename,
            dataType: 'json',
            beforeSend: function() {
                $(this).attr('disabled','disabled');
            },
            success: function(html){
                $("#removeLogo").css({'display':'none'});
                $("#wrapper-logo").html('<i class="fa fa-user" style="font-size: 170px; font-size: 170px;border: solid #ddd 1px;padding: 1px 19px;border-radius: 5px;color: #999;"></i>');         
            },
            error: function(){
                alert("Something Wrong.");
                return false;
            }                
        });
    });
});


// function uploadFile(formData, urlData ){
//     $.ajax({
//         method: "POST",
//         url: urlData,
//         data: formData,
//         type: 'POST',
//         async: false,
//         success: function(html){
//             alert(html.message);
//         },
//         cache: false,
//         datatype: 'json',
//         contentType: false,
//         processData: false,
//         error: function(xhr, status, error){
//             alert("Error!" + xhr.status);
//             return false;
//         }            
//     });
// }


var now = (new Date());
var m = now.getMonth()+ 1;
var y = now.getFullYear();
var dateNow = '0'+m+'-'+y;
// console.log(dateNow);
function loadFileImage(datatype, month=dateNow){

    $.ajax({
        method: "GET",
        url: "/window/uploaded",
        data: {'datatype': datatype, 'month': month},
        dataType: 'json',
        beforeSend: function() {
            $(this).attr('disabled','disabled');
        },
        success: function(dataFile){

            if (dataFile.status == 1) {
                var data = '';
                var getdate=[];
                var loaddate = dataFile.dates;
                var i=0;
                
                $.each(dataFile.files, function(key,val){
                    data +="<li data-type='"+datatype+"' data-label='"+val.name+"' data-url='"+val.url+"' class='col-sm-3 col-xs-4 attachment' style='padding:10px;height:136px;'> <i style='display:none;' class='fa fa-check'></i><img  src='"+val.url+"' loading='lazy' alt='loading...' style='width:100%; height:100%;'></li>";
                });
                $("#loadImage.open-uploadImage ul").html(data);

                getdate.push('<option value="0">All</option>');
                $.each(loaddate,function(key,val){
                    if (dataFile.check === val) {
                        getdate.push('<option value="'+val+'" selected="selected" >'+val+'</option>');
                    }else{
                        getdate.push('<option value="'+val+'">'+val+'</option>');
                    }
                });
                $('#sort_photoBymonth').html(getdate);
            }
            if (dataFile.status == 0) {
                $("#loadImage.open-uploadImage ul").html("<li style='padding:25% 41%;'><strong>Not Found...!</strog></li>"); 
            }
        },
        error: function(){
            alert("Something Wrong.");
            return false;
        }                
    });   
}




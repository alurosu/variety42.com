jQuery(document).ready(function(){
    console.log('user.js: loaded!');

    jQuery('.user form .tags').on("click","li", function(){
        jQuery(this).remove();
    });

    jQuery('.delete_form_trigger').click(function(){
        jQuery(this).remove();
        jQuery('.delete_form').fadeIn(0);
    });

    jQuery('.user form .suggested_tag').on('keypress',function(e) {
        if(e.which == 13) {
            var suggested_tag = '<li>'+jQuery(this).val()+' <span>x</span><input type="hidden" name="tag_names[]" value="'+jQuery(this).val()+'"><input type="hidden" name="tag_ids[]" value="'+getIDfromSuggestedTags(jQuery(this).val())+'"></li>';
            jQuery('.user form .tags').append(suggested_tag);
            jQuery(this).val('');
            e.preventDefault();
        }
    });

    jQuery('.user form .autocomplete').on("click","li",function(){
        var suggested_tag = '<li>'+jQuery(this).html()+' <span>x</span><input type="hidden" name="tag_names[]" value="'+jQuery(this).html()+'"><input type="hidden" name="tag_ids[]" value="'+jQuery(this).data('id')+'"></li>';
        jQuery('.user form .tags').append(suggested_tag);
        jQuery(this).remove();
    });

    jQuery('.user form .suggested_tag').on('keyup',function(e) {
        jQuery('.user form .autocomplete').html('');
        if(jQuery(this).val().length > 2){
            var url = "/data/php/getSuggestedTags.php?folder="+jQuery(this).data('folder')+'&keyword='+jQuery(this).val();
            jQuery.get(url, function(data){
                suggested_tags = '';
                if(data.suggestions!=null && data.suggestions.length > 0){
                    for( row of data.suggestions ){
                        if(!isSuggestedIDinList(row.id))
                            suggested_tags += '<li data-id="'+row.id+'">'+row.name+'</li>';
                    }
                    jQuery('.user form .autocomplete').html(suggested_tags);
                    jQuery(this).val('');
                }

            });
        }
    });
});
function isSuggestedIDinList(id){
    var result = false;
    jQuery('.user form .tags li').each(function(){
        if(jQuery(this).find('input[name="tag_ids[]"]').val() == id)
            result = true;
    });
    return result;
}
function getIDfromSuggestedTags(name){
    var result = '';
    jQuery(".user form .autocomplete li").each(function(){
        if( name.toLowerCase() == jQuery(this).html().toLowerCase() )
            result = jQuery(this).data('id');
    });
    return result;
}

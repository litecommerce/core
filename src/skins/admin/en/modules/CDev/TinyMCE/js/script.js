/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * TinyMCE-based textarea controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 */

$(function() {
  setSimpleTinymce($('textarea.tinymce'));
});


function setAdvancedTinymce(obj)
{
  return obj.tinymce({

    // General options
    theme : "advanced", // Use Advanced theme
    skin : "o2k7",

    plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

    // TODO ADD the upload images/files feature

    // Theme options
    theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,styleselect,formatselect,fontselect,fontsizeselect,|,link,unlink,anchor,image,cleanup,help,code,|,undo,redo,|,preview",

    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,justifyleft,justifycenter,justifyright,justifyfull,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,insertdate,inserttime,|,forecolor,backcolor,|,charmap,emotions,iespell,media,advhr,|,insertfile,insertimage",

    theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,visualchars,nonbreaking,template,pagebreak,|,print,|,ltr,rtl,|,fullscreen,|,cite,abbr,acronym,del,ins,attribs",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,

    // Prevents automatic converting URLs to relative ones.
    convert_urls : false
  });
}

function setSimpleTinymce(obj)
{
  return obj.tinymce({
    // General options
    theme : "advanced", // Use Simple theme
    skin : "o2k7",

    plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

    // Theme options
    theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,styleselect,formatselect,fontselect,fontsizeselect,|,link,unlink,anchor,image,cleanup,help,code,|,undo,redo,|,preview",

    theme_advanced_buttons2 : "",

    theme_advanced_buttons3 : "",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,

    // Prevents automatic converting URLs to relative ones.
    convert_urls : false
  });
}


//
// TODO refactor to class/object model
//

function cleanTiny(button)
{
  var obj;

  obj = jQuery('.tinymce', jQuery(button).parent().parent());

  obj.tinymce().remove();

  return obj;
}

function makeTinySimple(button)
{
  setSimpleTinymce(cleanTiny(button));
}

function makeTinyAdvanced(button)
{
  setAdvancedTinymce(cleanTiny(button));
}

<?php
/**
 * SmartFormer - Form Builder for Joomla 1.5.x websites
 * Copyright (C) 2006-2010 IToris Co.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see http://www.gnu.org/licenses/
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * -----------------------------------------------------------------------------
 * @package SmartFormer
 * @version 2.4.1 (J1.5 security fix)
 * @author The SmartFormer project (http://www.itoris.com/joomla-form-builder-smartformer.html)
 * @copyright IToris Co. 2006-2010
 * @license GNU GPL
 *
*/

// no direct access
if (!defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) die( 'Restricted access' );
// get paths
if (isset($_GET['ping'])) {echo 'Pong'; exit;}
$ff_url_admpath=$GLOBALS['sf_live_site']."/administrator/components/com_smartformer/admin/";

$list=return_file_list('');

if (file_exists($GLOBALS['sf_absolute_path'].'/administrator/components/com_smartformer/admin/lic')) {
	$handle=fopen($GLOBALS['sf_absolute_path'].'/administrator/components/com_smartformer/admin/lic','r');
	$token=trim(fgets($handle));
	fclose($handle);
}

function return_file_list($dir) {
	$list=array();
	if ($handle = opendir($GLOBALS['sf_absolute_path'].'/images/'.$dir)) {
	    while (false !== ($file = readdir($handle))) {
	        if ($file != "." && $file != "..") {
	        	if (is_file($GLOBALS['sf_absolute_path'].'/images/'.$dir.$file)) {
					list($width, $height, $type, $attr) = @getimagesize($GLOBALS['sf_absolute_path'].'/images/'.$dir.$file);
	        		if ($width>0) {
	        			$index=count($list);
	        			$list[$index]['filename']=$dir.$file;
	        			$list[$index]['width']=$width;
	        			$list[$index]['height']=$height;
	        		}
	        	}
	        	if (is_dir($GLOBALS['sf_absolute_path'].'/images/'.$dir.$file)) {
	        		$list2=return_file_list($dir.$file.'/');
	        		foreach ($list2 as $value) $list[count($list)]=$value;
	        	}
	        }
	    }
	    closedir($handle);
	}
	return $list;
}
?>
<style>
	td.elem { height:30px; width:20px; border-bottom-style:solid; border-bottom-width:1px; border-bottom-color:#AAAAAA; cursor:default;}
	td.elem2 { border-bottom-style:solid; border-bottom-width:1px; border-bottom-color:#AAAAAA; }
	td.elem3 { height:30px; border-bottom-style:solid; border-bottom-width:1px; border-bottom-color:#AAAAAA; }
	td.tab_enabled { height:10px; width:50px; border-width:1px; border-color:black; border-left-style:solid; border-top-style:solid; border-right-style:none; border-bottom-style:none; cursor:default;}
	td.header { border-top-style:solid; border-top-width:2px; border-top-color:#900000; background-color:#EEEEEE; }
	td.tab_disabled { height:10px; width:50px; border-width:1px; border-color:black; border-left-style:solid; border-top-style:solid; border-right-style:none; border-bottom-style:solid; background-color:#BBBBBB; cursor:pointer;}
	div.form_editor { width:1100px; height:590px; border-width:1px; border-style:solid; border-top-style:none; border-color:black; position:absolute; visibility:hidden;"}
</style>

<script language="javascript" type="text/javascript" src="<?php echo $GLOBALS['sf_live_site'];?>/administrator/components/com_smartformer/plugins/edit_area/edit_area_full.js"></script>

<script type="text/javascript">

	var file_list = new Array( <?php foreach ($list as $value) echo 'Array("'.$value['filename'].'", '.$value['width'].", ".$value['height']."),"; ?> "Array('',0,0)");
	element_name = new Array ('Edit Box','Password Box','Text Area','Radio Button','Check Box','List Box','Select Box','Button','Image','Static Text','Link','Captcha Image','File Upload');
	mouse = new Array ('x','y','ox','oy','fx1','fy1','fx2','fy2');
	en=false; moved=false; fr=false; fl=false; fld=false;
	current_tab=0; el_count=0; cur_el=0; last_resize=0; last_el=0; selected_el=0; group_count=0; last_img=-1; cur_win=0;
	el_object = new Array ();
	groups = new Array ();

function stopEvent (ev) {
	if (window.event) {
		window.event.cancelBubble = true;
		window.event.returnValue = false;
	} else {
		ev.preventDefault();
		ev.stopPropagation();
	}
}

function addEvent (el, ev, func) {
	if (el.attachEvent) el.attachEvent("on"+ev, func);
		else el.addEventListener(ev, func, true);
}

function el_move() {
	var new_page=document.getElementById('move_page').value;
	if (new_page==0) {alert('Please select page to move elements'); return;}
	if (new_page==current_tab) {alert('Cannot move to the same page'); return;}
	var html=document.getElementById("form_editor").innerHTML;
	var list = return_el_list(document.getElementById("form_editor"));
	var s='';
	for(i=0; i<=el_count; i++) if (el_object[i] && el_object[i]['selected']) {
		for (o=0; o<list.length; o++) if (list[o].indexOf('form_element'+i+' ')>-1 || list[o].indexOf('form_element'+i+'"')>-1) {
			s+=list[o];
			el_object[i]['tab']=new_page;
			html=html.replace(list[o],'');
			break;
		}
	}
	if (s!='') {
		var old_page=current_tab;
		document.getElementById("form_editor").innerHTML=html;
		update_data();
		display_tab(new_page);
		document.getElementById("form_editor").innerHTML+=s;
		connect_elements();
		update_data();
		display_tab(old_page);
		display_tab(new_page);
	}
}

function keyDown(ev) {
	stopEvent(ev);
	if (selected_el>0) {
		if (ev.keyCode == 39) {
			el_object[selected_el]['left']++;
			el_object[selected_el]['object'].style.left=el_object[selected_el]['left']+'px';
		}
		if (ev.keyCode == 37) {
			el_object[selected_el]['left']--;
			el_object[selected_el]['object'].style.left=el_object[selected_el]['left']+'px';
		}
		if (ev.keyCode == 40) {
			el_object[selected_el]['top']++;
			el_object[selected_el]['object'].style.top=el_object[selected_el]['top']+'px';
		}
		if (ev.keyCode == 38) {
			el_object[selected_el]['top']--;
			el_object[selected_el]['object'].style.top=el_object[selected_el]['top']+'px';
		}
		if (ev.keyCode == 67 && ev.ctrlKey) el_copy();
		if (ev.keyCode == 86 && ev.ctrlKey) el_paste();
	}
}

function el_copy() {
	document.copied = Array();
	var list = return_el_list(document.getElementById("form_editor"));
	for(i=0; i<=el_count; i++) if (el_object[i] && el_object[i]['selected']) {
		for (o=0; o<list.length; o++) if (list[o].indexOf('form_element'+i+' ')>-1 || list[o].indexOf('form_element'+i+'"')>-1) {
			var cnt = document.copied.length;
			document.copied[cnt] = Array();
			for(prop in el_object[i]) if (typeof el_object[i][prop] != 'object' && typeof el_object[i][prop] != 'function') {
				document.copied[cnt][prop]=el_object[i][prop];
			}
			document.copied[cnt]['left'] = parseInt(document.copied[cnt]['left'])+15;
			document.copied[cnt]['top'] = parseInt(document.copied[cnt]['top'])+15;
			document.copied[cnt]['html'] = list[o];
			document.copied[cnt]['index'] = i;
			break;
		}
	}

}

function el_paste() {
	if (!document.copied) return;
	//drop all selections
	for(i=0; i<el_object.length; i++) {
		if (el_object[i]) el_object[i]['selected']=false;
		element_frame(0,i);
	}
	//clone all copied to current page
	var start=el_count+1;
	var group_map = Array();
	for(i=0; i<document.copied.length; i++) {
		el_count++;
		el_object[el_count] = Array();
		//if radio or checkbox we need to create new group
		if (document.copied[i]['group']>0) {
			var old_group = document.copied[i]['group'];
			if (group_map[document.copied[i]['group']]) {
				document.copied[i]['group']=group_map[document.copied[i]['group']];
			} else {
				group_count++;
				group_map[document.copied[i]['group']]=group_count;
				groups[group_count] = Array();
				groups[group_count]['type']=groups[document.copied[i]['group']]['type'];
				groups[group_count]['req']=groups[document.copied[i]['group']]['req'];
				groups[group_count]['name']='New elements group '+group_count;
				document.copied[i]['group']=group_count;
			}
			document.copied[i]['html']=document.copied[i]['html'].replace('form_group'+old_group+' ','form_group'+document.copied[i]['group']+' ').replace('form_group'+old_group+' ','form_group'+document.copied[i]['group']+' ');
			document.copied[i]['html']=document.copied[i]['html'].replace('form_group'+old_group+'"','form_group'+document.copied[i]['group']+'"').replace('form_group'+old_group+'"','form_group'+document.copied[i]['group']+'"');
			document.copied[i]['html']=document.copied[i]['html'].replace('form_group'+old_group+'>','form_group'+document.copied[i]['group']+'>').replace('form_group'+old_group+'>','form_group'+document.copied[i]['group']+'>');
		}
		document.copied[i]['html']=document.copied[i]['html'].replace('form_element'+document.copied[i]['index']+' ','form_element'+el_count+' ').replace('form_element'+document.copied[i]['index']+' ','form_element'+el_count+' ');
		document.copied[i]['html']=document.copied[i]['html'].replace('form_element'+document.copied[i]['index']+' ','form_element'+el_count+' ').replace('form_element'+document.copied[i]['index']+' ','form_element'+el_count+' ');
		document.copied[i]['html']=document.copied[i]['html'].replace('form_element'+document.copied[i]['index']+'>','form_element'+el_count+'>').replace('form_element'+document.copied[i]['index']+'>','form_element'+el_count+'>');
		document.copied[i]['html']=document.copied[i]['html'].replace('form_element'+document.copied[i]['index']+'>','form_element'+el_count+'>').replace('form_element'+document.copied[i]['index']+'>','form_element'+el_count+'>');
		document.copied[i]['html']=document.copied[i]['html'].replace('form_element'+document.copied[i]['index']+'"','form_element'+el_count+'"').replace('form_element'+document.copied[i]['index']+'"','form_element'+el_count+'"');
		document.copied[i]['html']=document.copied[i]['html'].replace('form_element'+document.copied[i]['index']+'"','form_element'+el_count+'"').replace('form_element'+document.copied[i]['index']+'"','form_element'+el_count+'"');
		document.copied[i]['html']=document.copied[i]['html'].replace('element_m_down('+document.copied[i]['index']+',','element_m_down('+el_count+',').replace('element_m_over('+document.copied[i]['index']+')','element_m_over('+el_count+')');
		document.copied[i]['html']=document.copied[i]['html'].replace('element_m_down('+document.copied[i]['index']+',','element_m_down('+el_count+',').replace('element_m_over('+document.copied[i]['index']+')','element_m_over('+el_count+')');
		for(prop in document.copied[i]) if (typeof document.copied[i][prop] != 'object' && typeof document.copied[i][prop] != 'function') {
			el_object[el_count][prop]=document.copied[i][prop];
		}
		document.getElementById("form_editor").innerHTML+=document.copied[i]['html'];
		if (el_object[el_count]['tab']!=current_tab) el_object[el_count]['qarea']=0;
		el_object[el_count]['tab']=current_tab;
		//el_object[el_count]['alias']="c_form_element"+el_count;
		el=el_object[el_count]['prop'];
		tmp=document.createElement("OPTION");
		tmp.value=el_count;
		tmp.text="c_form_element"+el_count+' ('+element_name[el-1]+')';
		elements_list.options.add(tmp,0);
	}
	connect_elements();
	for (i=start; i<=el_count; i++){
		el_object[i]['object'].style.left=el_object[i]['left']+'px';
		el_object[i]['object'].style.top=el_object[i]['top']+'px';
		if (el_object[i]['prop']<4) el_object[i]['object'].focus();
	}
	update_data();
	selected_el=start;
	//repeat copying
	el_copy();
}

function StaticTextValue( value , type ) {
    this._type = type;
    this._value = type ? value.replace(/[\r\n]+/g,"") : value;
    this._i = null;
    if( this._type ) {
        this._list = this._splitByDelimiter(this._value,'strong_newline','normal');
        this._list = this._splitByDelimiter(this._list,'strong_newline','fixed');
        this._list = this._splitByDelimiter(this._list,'strong_newline','ie');
        this._list = this._splitByDelimiter(this._list,'strong_newline','ff');
        this._list = this._splitByDelimiter(this._list,'strong_newline','ff_fix');
        this._list = this._splitByDelimiter(this._list,'strong_newline','ie_fix');
        this._list = this._splitByDelimiter(this._list,'soft_newline','normal');
        this._list = this._splitByDelimiter(this._list,'soft_newline','fixed');
        this._list = this._splitByDelimiter(this._list,'soft_newline','ie');
        this._list = this._splitByDelimiter(this._list,'soft_newline','ie_fix');
        this._list = this._splitByDelimiter(this._list,'soft_newline','ff');
    } else {
        this._value = this._filterHtml(this._value);
        var tmp = this._value.split(this.delimiter_pack['normal_newline']['normal']);
        this._list = [];
        for( var i = 0 ; i < tmp.length ; ++i ) {
            this._list[this._list.length] = tmp[i];
        }
    }
}

StaticTextValue.prototype = {

    _filterHtml: function( text ) {
        var i = -1;
        while( ( i = text.indexOf("<",i+1) ) != -1  ) {
            var close = text.indexOf(">",i+1);
            var alter = text.indexOf("<",i+1);
            if( close > alter && alter != -1 ) {
                text = text.substr(0,i)+"&lt;"+text.substr(i+1);
                i += 3;
            }
        }
        var i = -1;
        while( ( i = text.indexOf(">",i+1) ) != -1 ) {
            for( var j = i-1 ; j > -1 ; --j ) {
                var finded = text.charAt(j);
                var char = j == 0 && finded != "<" ? ">" : finded;
                var stop = false;
                switch(char) {
                    case "<":
                        stop = true;
                        break;
                    case ">":
                        stop = true;
                        text = text.substr(0,i)+"&gt;"+text.substr(i+1);
                        i += 3;
                        break;
                }
                if( stop ) {
                    break;
                }
            }
        }
        return text;
    } ,

    _splitByDelimiter: function( strg , group , type ) {
        var result = [];
        var delimiter = this.delimiter_pack[group][type];
        if( typeof strg == 'string' ) {
            var list = strg.split(delimiter);
            for( var i = 0 ; i < list.length ; ++i ) {
                if( i != 0 ) {
                    result[result.length] = delimiter;
                }
                result[result.length] = list[i];
            }
        } else {
            for( var i = 0 ; i < strg.length ; ++i ) {
                if( !this._isDelimiter(strg[i]) ) {
                    var list = strg[i].split(delimiter);
                    for( var j = 0 ; j < list.length ; ++j ) {
                        if( j != 0 ) {
                            result[result.length] = delimiter;
                        }
                        result[result.length] = list[j];
                    }
                } else {
                    result[result.length] = strg[i];
                }
            }
        }
        return result;
    } ,

    _value: null ,

    _i: null ,

    _list: null ,

    _type: null ,

    tags: {
       'p': { 'newline': 1 } ,
       'h1' : { 'newline': 1 } ,
       'h2' : { 'newline': 1 } ,
       'h3' : { 'newline': 1 } ,
       'h4' : { 'newline': 1 } ,
       'h5' : { 'newline': 1 } ,
       'h6' : { 'newline': 1 } ,
       'h7' : { 'newline': 1 } ,
       'div' : { 'newline': 1 } ,
       'table' : { 'newline' : 1 } ,
       'tr' : { 'newline' : 1 } ,
       'td' : { 'newline' : 1 } ,
       'thead' : { 'newline' : 1 } ,
       'tbody' : { 'newline' : 1 } ,
       'tfoot' : { 'newline' : 1 } ,
       'br' : { 'newline' : 1 } ,
       'hr' : { 'newline' : 1 }
    } ,

    delimiter_pack: {
        'strong_newline': {
            'normal': "<br newline='newline'>" ,
            'fixed': "<br newline='newline'/>" ,
            'ff' : '<br newline="newline">' ,
            'ff_fix' : '<br newline="newline"/>' ,
            'ie_fix' : '<BR newline="newline">' ,
            'ie' : "<BR newline='newline'>"
        } ,
        'soft_newline': {
            'normal': "<span newline='newline'></span>",
            'fixed': "<span newline='newline'></span>" ,
            'ff' : '<span newline="newline"></span>' ,
            'ie' : "<SPAN newline='newline'></SPAN>"  ,
            'ie_fix' : '<SPAN newline="newline"></SPAN>'
        } ,
        'normal_newline': {
            'normal' : '\n' ,
            'void' : ''
        }
    } ,

    _getDelimGroup: function () {
        var value = this._isDelimiter();
        if( !value ) {
            return null;
        }
        var group = value.split("_");
        if( group.length < 2 ) {
            return null;
        }
        return group[0]+"_"+group[1];
    } ,

    _isDelimiter: function ( value ) {
        var value = ( ( value || this._i == null ) ? value : this._getCurr() );
        if( typeof value != 'string' ) {
            return null;
        }
        for( var group in this.delimiter_pack ) {
            for( var type in this.delimiter_pack[group] ) {
                if( this.delimiter_pack[group][type].toLowerCase() == value.toLowerCase() ) {
                    return group+'_'+type;
                }
            }
        }
        return null;
    } ,

    _next: function() {
        if( this._i == null ) {
            this._i = 0;
        } else {
            this._i++;
        }
        if( this._i >= this._list.length ) {
            this._i = null;
            return false;
        }
        return true;
    } ,

    _getCurr: function() {
        return this._list[this._i].replace(/[\r\n]+/g,"");
    } ,

    _getLastTag: function () {
        var str = this._getCurr();
        var lastIndexOfTag = str.lastIndexOf("</");
        var offset = 2;
        if( -1 == lastIndexOfTag ) {
            lastIndexOfTag = str.lastIndexOf("<");
            if( -1 == lastIndexOfTag ) {
                return null;
            }
            offset = 1;
        }
        if( str.lastIndexOf(">") == str.length - 1 ) {
            str = str.substring(lastIndexOfTag+offset,str.lastIndexOf(">"));
            var items = str.split(" ");
            return items[0];
        }
        return null;
    } ,

    convert: function() {
        if( this._type ) {
            return this._htmlToString();
        }
        return this._stringToHtml();
    } ,

    _isLast: function() {
        return this._list.length - 1 == this._i;
    } ,

    _stringToHtml: function () {
        var str="";
        while ( this._next() ) {
            var postfix = this.delimiter_pack['soft_newline']['normal'];
            var tag = this._getLastTag();
            if( !this._getTagProperty(tag,'newline') ) {
                postfix = this.delimiter_pack['strong_newline']['normal'];
            }
            str += this._getCurr() + ( this._isLast() ? '' : postfix );
        }
        return str;
    } ,

    _htmlToString: function() {
        var str="";
        while( this._next() ) {
            var line = this._getCurr();
            if( line != undefined ) {
                switch( this._getDelimGroup() ) {
                    case 'strong_newline':
                    case 'soft_newline':
                        str += "\n";
                        break;
                    case 'normal_newline':
                    default:
                        str += line;
                        break;
                }
            }
        }
        return str;
    } ,

    _getTagProperty: function( tag , property ) {
        if( !tag ) {
            return null;
        }
        tag = tag.toLowerCase();
        if( this.tags[tag] ) {
            return this.tags[tag][property];
        }
        return null;
    }

}

function PropertyHelper() {
}

PropertyHelper.prototype = {

    encodeProperty: function( property, mode ) {
        var s = property;
        var len = 0;
        if (mode==0) {
	        do {
	            len = s.length;
	            // ?></.,":';|}{\][+_=-)(*&^%$#@!~`
	            s = s.replace(/(<|>|"|'|~|\|)+/,''); // |\n
	            //s = "1"+String.fromCharCode(13)+String.fromCharCode(10)+"2"; //s.replace(/(\n)+/,'\r');
	        } while( s.length != len );
		} else {
	        while (s.indexOf('~')>0) s=s.substr(0,s.indexOf('~'))+'%7E'+s.substr(s.indexOf('~')+1);
			while (s.indexOf('|')>0) s=s.substr(0,s.indexOf('|'))+'%7F'+s.substr(s.indexOf('|')+1);
			while (s.indexOf('\n')>0) s=s.substr(0,s.indexOf('\n'))+'%7G'+s.substr(s.indexOf('\n')+1);
		}
        return s;
    } ,

    encodeSystem: function( name ) {
        do {
            len = name.length;
            name = name.replace(/\W+/,'');
        } while(len != name.length);
        return name;
    } ,

    encodeSystem2: function( name ) {
        do {
            len = name.length;
            name = name.replace(/[<>"']+/,'');
        } while(len != name.length);
        return name;
    } ,

    decodeProperty: function( property ) {
        var s = property;
        while (s.indexOf('%7E')>0) s=s.substr(0,s.indexOf('%7E'))+'~'+s.substr(s.indexOf('%7E')+3);
        while (s.indexOf('%7F')>0) s=s.substr(0,s.indexOf('%7F'))+'|'+s.substr(s.indexOf('%7F')+3);
        while (s.indexOf('%7G')>0) s=s.substr(0,s.indexOf('%7G'))+'\n'+s.substr(s.indexOf('%7G')+3);
        return s;
    }

};

function floatwindow_down() {
	mouse['flx']=mouse['x']; mouse['fly']=mouse['y'];
	fl=true;
}
function floatwindow_move() {
	if (fl) {
		mouse['flx2']=mouse['x']-mouse['flx'];
		mouse['fly2']=mouse['y']-mouse['fly'];
		document.getElementById('floatwindow').style.left=px_int(document.getElementById('floatwindow').style.left)+mouse['flx2']+"px";
		document.getElementById('floatwindow').style.top=px_int(document.getElementById('floatwindow').style.top)+mouse['fly2']+"px";
		mouse['flx']=mouse['x']; mouse['fly']=mouse['y'];
	}
}
function floatwindow_up() {
	fl=false;
}

function floatwindow_close() {
	if (document.getElementById('float_title').innerHTML.indexOf('Create')>=0) if (create_group_submit()) return;
	document.getElementById('floatwindow').style.visibility="hidden";
	document.getElementById('mask').style.visibility="hidden";
}

function m_move(event) {
try {
    el_bottom = parseInt(document.getElementById('grid').style.height);
	if (fr) {
		mouse['fx2']=mouse['x']-mouse['fx1'];
		mouse['fy2']=mouse['y']-mouse['fy1'];
        var width = mouse['fx2'];
        var height = mouse['fy2'];
        if( width < 0 ) {
            frame.style.left = mouse['x'] + "px";
            width = -width;
        }
        if( height < 0 ) {
            frame.style.top = mouse['y'] + "px";
            height = -height;
        }
        frame.style.width=width+"px";
        frame.style.height=height+"px";
	} else if (!en) {
		//show_hint(1,0);
		if (cur_el>0) {
		    show_props_flag=true;
			if (navigator.userAgent.toLowerCase().indexOf('safari') > -1) connect_elements();
			if (last_resize==0) {
				moved=true;	tmp=false; tmp_x=0; tmp_y=0; tmp_x2=0; tmp_y2=0;
				for (i=1; i<=el_count; i++) if (el_object[i]['selected']) {
					el_object[i]['top']=el_object[i]['top']+mouse['y']-mouse['oy'];
					el_object[i]['left']=el_object[i]['left']+mouse['x']-mouse['ox'];
					if (el_object[i]['left']<=0) {tmp_x=el_object[i]['left']; tmp=true; }
					if (el_object[i]['top']<=0) {tmp_y=el_object[i]['top']; tmp=true; }
					if (el_object[i]['left']+el_object[i]['width']>=1100) {tmp_x=el_object[i]['left']+el_object[i]['width']-1100; tmp=true; }
					if (el_object[i]['top']+el_object[i]['height']>=el_bottom) {tmp_y=el_object[i]['top']+el_object[i]['height']-el_bottom; tmp=true; }
					if (Math.abs(tmp_x)>Math.abs(tmp_x2)) tmp_x2=tmp_x;
					if (Math.abs(tmp_y)>Math.abs(tmp_y2)) tmp_y2=tmp_y;
				}
				for (i=1; i<=el_count; i++) if (el_object[i]['selected']) {
					if (tmp) {
						el_object[i]['left']=el_object[i]['left']-tmp_x2;
						el_object[i]['top']=el_object[i]['top']-tmp_y2;
					}
					el_object[i]['object'].style.left=el_object[i]['left']+"px";
					el_object[i]['object'].style.top=el_object[i]['top']+"px";
					resizeFormOnMove(i);
				}

				//mouse_move_y=mouse_move_y+mouse['y']-mouse['oy'];
				//mouse_move_x=mouse_move_x+mouse['x']-mouse['ox'];
				//if (move_timer_flag==false) {move_timer_flag=true; setTimeout('elements_move();','40');}
			}
			if (last_resize==1 && !moved) {
				el_object[cur_el]['width']=el_object[cur_el]['width']+mouse['x']-mouse['ox'];
				if (el_object[cur_el]['left']+el_object[cur_el]['width']>=1100) el_object[cur_el]['width']=1100-el_object[cur_el]['left'];
				el_object[cur_el]['object'].style.width=el_object[cur_el]['width']+"px";
                resizeFormOnMove(cur_el);
			}
			if (last_resize==2 && !moved) {
				el_object[cur_el]['height']=el_object[cur_el]['height']+mouse['y']-mouse['oy'];
				if (el_object[cur_el]['top']+el_object[cur_el]['height']>=el_bottom) el_object[cur_el]['height']=el_bottom-el_object[cur_el]['top'];
				el_object[cur_el]['object'].style.height=el_object[cur_el]['height']+"px";
			}
			mouse['ox']=mouse['x']; mouse['oy']=mouse['y'];
			show_info(1,cur_el);
		}
	} else {
		el_object[el_count]['top']=mouse['y']-beginTop-10;
		el_object[el_count]['left']=mouse['x']-beginLeft-10;
		if (el_object[el_count]['left']<=0) el_object[el_count]['left']=0;
		if (el_object[el_count]['left']+el_object[el_count]['width']>1100) el_object[el_count]['left']=1100-el_object[el_count]['width'];
		if (el_object[el_count]['top']<0) el_object[el_count]['top']=0;
		if (el_object[el_count]['top']+el_object[el_count]['height']>el_bottom) el_object[el_count]['top']=el_bottom-el_object[el_count]['height'];
		el_object[el_count]['object'].style.top=el_object[el_count]['top']+"px";
		el_object[el_count]['object'].style.left=el_object[el_count]['left']+"px";
        resizeFormOnMove(el_count);
	}
} catch (e) {}
}

var show_props_flag=false;

//var move_timer_flag = false;
//var mouse_move_y = 0;
//var mouse_move_x = 0;
//function elements_move() {
//	moved=true;	tmp=false; tmp_x=0; tmp_y=0; tmp_x2=0; tmp_y2=0;
//	for (i=1; i<=el_count; i++) if (el_object[i]['selected']) {
//		el_object[i]['top']=el_object[i]['top']+mouse_move_y;
//		el_object[i]['left']=el_object[i]['left']+mouse_move_x;
//		if (el_object[i]['left']<=0) {tmp_x=el_object[i]['left']; tmp=true; }
//		if (el_object[i]['top']<=0) {tmp_y=el_object[i]['top']; tmp=true; }
//		if (el_object[i]['left']+el_object[i]['width']>=1100) {tmp_x=el_object[i]['left']+el_object[i]['width']-1100; tmp=true; }
//		if (el_object[i]['top']+el_object[i]['height']>=el_bottom) {tmp_y=el_object[i]['top']+el_object[i]['height']-el_bottom; tmp=true; }
//		if (Math.abs(tmp_x)>Math.abs(tmp_x2)) tmp_x2=tmp_x;
//		if (Math.abs(tmp_y)>Math.abs(tmp_y2)) tmp_y2=tmp_y;
//	}
//	for (i=1; i<=el_count; i++) if (el_object[i]['selected']) {
//		if (tmp) {
//			el_object[i]['left']=el_object[i]['left']-tmp_x2;
//			el_object[i]['top']=el_object[i]['top']-tmp_y2;
//		}
//		el_object[i]['object'].style.left=el_object[i]['left']+"px";
//		el_object[i]['object'].style.top=el_object[i]['top']+"px";
//		resizeFormOnMove(i);
//	}
//	move_timer_flag = false;
//	mouse_move_y=0;
//	mouse_move_x=0;
//}

function resizeFormOnMove( index ) {
    //resize form editor on element move
    el_bottom = parseInt(document.getElementById('grid').style.height);
    if ((el_object[index]['height']+el_object[index]['top']) >= (el_bottom - 20)) {
        document.getElementById('grid_table').style.height = el_bottom + 20 + 'px';
        document.getElementById('grid').style.height = el_bottom + 20 + "px";
        document.getElementById('tab_table').style.height = el_bottom + 20 + "px";
    }
}

function m_down(el){
	if (el>0) {
		en=true;
		show_hint(0,0);
		create_element(el);
		document.getElementById("flowd1").innerHTML+='<div id="tmp_div" style="position:absolute; width:0px; height:0px;"></div>';
		connect_elements();
		document.getElementById("flowdiv1").style.visibility="hidden";
	} else if (cur_el==0) {
		last_img=-1;
		fr=true;
        mouse['fx1']=mouse['x']; mouse['fy1']=mouse['y'];
		frame.style.visibility="visible";
		frame.style.left=mouse['fx1']+"px";
		frame.style.top=mouse['fy1']+"px";
	}
}
function m_up(){
	fld=false;
	if (fr) {
		fr=false;
		frame.style.visibility="hidden";
		frame.style.width="0px";
		frame.style.height="0px";
		mouse['fx1']-=beginLeft;
		mouse['fy1']-=beginTop;
		mouse['fx2']+=mouse['fx1'];
		mouse['fy2']+=mouse['fy1'];
        if( mouse['fx1'] > mouse['fx2'] ) {
            var tmp = mouse['fx2'];
            mouse['fx2'] = mouse['fx1'];
            mouse['fx1'] = tmp;
        }
        if( mouse['fy1'] > mouse['fy2'] ) {
            var tmp = mouse['fy2'];
            mouse['fy2'] = mouse['fy1'];
            mouse['fy1'] = tmp;
        }
		c=0; id=0;
		for(i=1; i<=el_count; i++)
        if ( el_object[i]['left']>mouse['fx1']
          && el_object[i]['left']<mouse['fx2']
          && el_object[i]['top']>mouse['fy1']
          && el_object[i]['top']<mouse['fy2']
          && el_object[i]['left']+el_object[i]['width']>mouse['fx1']
          && el_object[i]['left']+el_object[i]['width']<mouse['fx2']
          && el_object[i]['top']+el_object[i]['height']>mouse['fy1']
          && el_object[i]['top']+el_object[i]['height']<mouse['fy2'] ) {
			if (el_object[i]['object']!=null) {
				el_object[i]['selected']=true;
				element_frame(2,i);
				for(p=0; p<elements_list.length; p++) if (elements_list.options[p].value==i) elements_list.options[p].selected=true;
				c++; id=i;
			}
		} else if (el_object[i]['object']!=null) {
			el_object[i]['selected']=false;
			element_frame(0,i);
			for(p=0; p<elements_list.length; p++) if (elements_list.options[p].value==i) elements_list.options[p].selected=false;
		}
		if (c==1) { selected_el=id; show_props(); } else {selected_el=0; show_props(); }
	} else if (en) {
		en=false;
		tmp=document.getElementById("flowdiv1").innerHTML;
		tmp2=tmp.toLowerCase().indexOf('<div');
		tmp2=tmp.toLowerCase().indexOf('<div',tmp2+1);
		if (tmp2>-1) document.getElementById("flowd1").innerHTML=tmp.substring(0,tmp2);
		if (el_object[el_count]['prop']==4 || el_object[el_count]['prop']==5) create_group();
		connect_elements();
		if (el_object[el_count]['left']<=0) {el_object[el_count]['left']=0; el_object[el_count]['object'].style.left="0px"; }
		if (el_object[el_count]['top']<=0) {el_object[el_count]['top']=0; el_object[el_count]['object'].style.top="0px"; }
		update_data();
	}
	if (document.getElementById('tool1').checked) document.getElementById("flowdiv1").style.visibility="visible";
	/*if (selected_el>0) {if (el_object[selected_el]['object'] && el_object[selected_el]['prop']<4) el_object[selected_el]['object'].focus(); }
		else {
			for (i=1; i<=el_count; i++) if (el_object[i]['selected'] && el_object[i]['prop']<4 && el_object[i]['object']) {selected_el=i; el_object[i]['object'].focus(); break; }
			if (i==el_count+1) {
				for (i=1; i<=el_count; i++) if (el_object[i]['prop']<4 && el_object[i]['object']) {selected_el=i; el_object[i]['object'].focus(); break; }
			}
		}*/
}
function element_m_over(el){
	if (!en && !fr) {
		element_frame(1,el);
		show_info(1,el);
		last_el=el;
	}
}

function element_m_move(){
	if (last_el>0) {
		if (el_object[last_el]['prop']!=13) {
            try {
    			delta_x=el_object[last_el]['width']+el_object[last_el]['left']-mouse['x']+beginLeft;
    			delta_y=el_object[last_el]['height']+el_object[last_el]['top']-mouse['y']+beginTop;
    			if (delta_y>=5 && delta_x>=6) el_object[last_el]['object'].style.cursor="default"; last_resize=0;
    			if (delta_y<5) {el_object[last_el]['object'].style.cursor="row-resize"; last_resize=2;}
    			if (delta_x<6) {el_object[last_el]['object'].style.cursor="col-resize"; last_resize=1;}
            } catch( exc ) {
            }
		}
	}
}

function element_m_out(){
	tmp=document.getElementById("form_editor").innerHTML;
	if (tmp.toLowerCase().indexOf('tmp_div')>-1) {
		tmp2=tmp.toLowerCase().indexOf('<div');
		while (tmp.toLowerCase().indexOf('<div', tmp2+1)>-1) tmp2=tmp.toLowerCase().indexOf('<div', tmp2+1);
		if (tmp2>-1) document.getElementById("form_editor").innerHTML=tmp.substring(0,tmp2);
	}
	element_frame(0,last_el);
	show_info(0,0);
	if (navigator.userAgent.toLowerCase().indexOf('safari') == -1) cur_el=0;
}

function element_m_down(el, event){
	mouse['ox']=mouse['x']; mouse['oy']=mouse['y'];
	cur_el=el;	moved=false; en=false;
	if (!el_object[el]['selected']) {
		for(i=0; i<elements_list.options.length; i++)
			if (elements_list.options[i].value-0==el) elements_list.options[i].selected=true; else
				if (event.ctrlKey==0) elements_list.options[i].selected=false;
		select_element();
	} else if (event.ctrlKey!=0) {
		for(i=0; i<elements_list.options.length; i++)
			if (elements_list.options[i].value-0==el) elements_list.options[i].selected=false;
		select_element();
	}
	if (el_object[el]['prop']!=6 && el_object[el]['prop']!=7) {
		document.getElementById("form_editor").innerHTML+='<div id="tmp_div" style="position:absolute; background-color:black; width:0px; height:0px;"></div>';
	} else if (el_object[el]['prop']<4) el_object[el]['object'].focus();
	connect_elements();
	if (navigator.userAgent.toLowerCase().indexOf('opera') > -1) window.onmouseup="element_m_up(event);";
}

function element_m_up(event){
	if (navigator.userAgent.toLowerCase().indexOf('opera') > -1) window.onmouseup="";
	if (cur_el>0  && event.ctrlKey==0) { //&& !moved
		for(i=0; i<elements_list.options.length; i++) {
			if (elements_list.options[i].value-0==cur_el) elements_list.options[i].selected=true; else elements_list.options[i].selected=false;
		}
		select_element();
	}
	if (!en) {
		tmp=document.getElementById("form_editor").innerHTML;
		if (tmp.toLowerCase().indexOf('tmp_div')>-1) {
			tmp2=tmp.toLowerCase().indexOf('<div');
			while (tmp.toLowerCase().indexOf('<div', tmp2+1)>-1) tmp2=tmp.toLowerCase().indexOf('<div', tmp2+1);
			if (tmp2>-1) document.getElementById("form_editor").innerHTML=tmp.substring(0,tmp2);
		}
		connect_elements();

	}
	if (selected_el>0 && el_object[selected_el]['prop']<4) el_object[selected_el]['object'].focus();
	update_data();
	cur_el=0;
}

function create_group() {
	document.getElementById('mask').style.visibility="visible";
	document.getElementById('mask').style.height=parseInt(document.getElementById('tab_table').style.height) + 270 + "px";
	document.getElementById('mask').style.width=document.body.scrollWidth + 20 + "px";
	document.getElementById('floatwindow').style.visibility="visible";
	document.getElementById('floatwindow').style.left=Math.floor((document.body.clientWidth-500)/2)+"px";
	document.getElementById('floatwindow').style.top="200px";
	document.getElementById('floatwindow').style.height="100px";
	document.getElementById('float_title').innerHTML='<b>&nbsp;&nbsp;Create a set of elements</b>';
	s='<table height="100%" width="100%"><tr><td align="left" valign="top">';
	s+='<table cellpadding="1" cellspacing="0" border-color:#000000; background-color:#EEEEEE;">';
	s+='<tr><td style="font-size:12px" align="left" width="140" onclick="show_new_group_selector();"><input id="new_group" name="group_el" type="radio" checked onclick="show_new_group_selector();"><b style="cursor:default;">New Group Name:</b></td><td align="left"><input id="group_name" name="group_name" type="text" style="width:200px;" value="">';
	s+='&nbsp;Group Size:<select id="group_size" name="group_size" style="width:40px;"><option value="1" selected>1<option value="2">2<option value="3">3<option value="4">4<option value="5">5<option value="6">6<option value="7">7<option value="8">8<option value="9">9<option value="10">10</select></td></tr>';
	s+='<tr><td style="font-size:12px" align="left" width="140" onclick="show_exist_group_selector();"><input id="exist_group" name="exist_group" type="radio" onclick="show_exist_group_selector();"><b style="cursor:default;">Add to existing one:</b></td><td align="left"><select id="group_number" name="group_number" style="width:250px;" disabled></select></td></tr>';
	s+='<tr><td></td><td align="left"><input type="button" value="Create" onclick="create_group_submit()"></tr>';
	s+='</table>';
	s+='<td></tr></table>';
	document.getElementById('float_content').innerHTML=s;
	document.getElementById('group_name').value="New elements group "+(group_count+1);
	document.getElementById("group_size").selectedIndex=0;
	while (document.getElementById("group_number").options.length>0) document.getElementById("group_number").remove(0);
	tmp=document.createElement("OPTION");
	tmp.value=0;
	tmp.text='-- Select --';
	document.getElementById("group_number").options.add(tmp,0);
	for(i=1; i<=group_count; i++) if (groups[i]['type']==el_object[el_count]['prop']) {
		tmp=document.createElement("OPTION");
		tmp.value=i;
		tmp.text=groups[i]['name'];
		document.getElementById("group_number").options.add(tmp);
	}

}
function create_group_submit() {
	if (!document.getElementById("new_group").checked && document.getElementById('group_number').value==0) { alert('You should select a group'); return true; }
	if (document.getElementById("new_group").checked && document.getElementById('group_name').value=='') { alert('You should specify the group name'); return true; }
	if (document.getElementById("new_group").checked==true) {
		group_count++;
		groups[group_count] = new Array ();
		groups[group_count]['name']=document.getElementById('group_name').value;
		x=el_object[el_count]['left']; y=el_object[el_count]['top']; el=el_object[el_count]['prop'];
		groups[group_count]['type']=el;
		groups[group_count]['req']=0;
		el_object[el_count]['group']=group_count;
		if (el_object[el_count]['prop']==4) replaceAttribute('i_form_element'+el_count,'name','c_form_group'+group_count); else replaceAttribute('i_form_element'+el_count,'name','c_form_element'+el_count);
		document.getElementById('i_form_element'+el_count).value='i_form_element'+el_count;
		for (o=2; o<=document.getElementById('group_size').value; o++) {
			create_element(el);
			el_object[el_count]['left']=x;
			el_object[el_count]['top']=y+25*(o-1);
			el_object[el_count]['object'].style.left=el_object[el_count]['left']+"px";
			el_object[el_count]['object'].style.top=el_object[el_count]['top']+"px";
			el_object[el_count]['group']=group_count;
			if (el_object[el_count]['prop']==4) replaceAttribute('i_form_element'+el_count,'name','c_form_group'+group_count); else replaceAttribute('i_form_element'+el_count,'name','c_form_element'+el_count);
			document.getElementById('i_form_element'+el_count).value='i_form_element'+el_count;
		}
	} else {
		el_object[el_count]['group']=document.getElementById('group_number').value;
		if (el_object[el_count]['prop']==4) replaceAttribute('i_form_element'+el_count,'name','c_form_group'+group_count); else replaceAttribute('i_form_element'+el_count,'name','c_form_element'+el_count);
		document.getElementById('i_form_element'+el_count).value='i_form_element'+el_count;
	}
	document.getElementById('floatwindow').style.visibility="hidden";
	document.getElementById('mask').style.visibility="hidden";
	update_data();
}

function  show_new_group_selector() {
	document.getElementById("group_name").disabled=false;
	document.getElementById("group_size").disabled=false;
	document.getElementById("group_number").disabled=true;
	document.getElementById("exist_group").checked=false;
	document.getElementById("new_group").checked=true;
}
function  show_exist_group_selector() {
	document.getElementById("group_name").disabled=true;
	document.getElementById("group_size").disabled=true;
	document.getElementById("group_number").disabled=false;
	document.getElementById("exist_group").checked=true;
	document.getElementById("new_group").checked=false;
}

function show_image_list(start) {
	last_img=-1;
	var dirs = new Array();
	var files = new Array();
	for(i=0; i<file_list.length; i++) if (file_list[i][0]!=null && file_list[i][0].indexOf(start)>-1) {
		a=file_list[i][0].substr(start.length).split('/');
		if (a.length==1) {
			index=files.length;
			files[index]= new Array();
			files[index][0]=a[0];
			files[index][1]=file_list[i][1];
			files[index][2]=file_list[i][2];
		} else {
			tmp=false;
			for(p=0; p<dirs.length; p++) if (dirs[p]==a[0]) tmp=true;
			if (!tmp) dirs[dirs.length]=a[0];
		}

	}
	style=false;
	s='<table height="100%" width="100%" cellspacing="0" cellpadding="2" border="0">';
	if (start!='') {
		s+='<tr style="background-color:'+((style)?"#FFFFFF":"#EEEEEE")+';" onclick="show_image_list(\''+start.substring(0,start.substring(0,start.length-1).lastIndexOf('/')+1)+'\')"><td colspan=2 height="20" align="left" valign="middle"><img align="left" valign="middle" src="<?php echo $ff_url_admpath; ?>dir_up.png" style="cursor:pointer;"><b style="cursor:pointer;" onclick="show_image_list(\''+start.substring(0,start.substring(0,start.length-1).lastIndexOf('/')+1)+'\')">&nbsp;[..]</b></td></tr>';
		style=!style;
	}
	for(i=0; i<dirs.length; i++) {
		s+='<tr style="background-color:'+((style)?"#FFFFFF":"#EEEEEE")+';"><td colspan=2 height="20" align="left" valign="middle"><img align="left" valign="middle" src="<?php echo $ff_url_admpath; ?>folder.png" style="cursor:pointer;" onclick="show_image_list(\''+start+dirs[i]+'/\')"><b style="cursor:pointer;" onclick="show_image_list(\''+start+dirs[i]+'/\')">&nbsp;['+dirs[i]+']</b></td></tr>';
		style=!style;
	}
	for(i=0; i<files.length; i++) {
		if (100/60>files[i][1]/files[i][2]) k=60/files[i][2]; else k=100/files[i][1];
		if (files[i][1]<100 && files[i][2]<60) k=1;
		s+='<tr style="background-color:'+((style)?"#FFFFFF":"#EEEEEE")+'; cursor:pointer;" onclick="set_image_url(\'<?php echo $GLOBALS['sf_live_site'].'/images/'; ?>'+start+files[i][0]+'\','+i+')"><td id="img_itm'+i+'a" align="left" valign="middle" style="height:60px;"><img align="left" valign="middle" src="<?php echo $ff_url_admpath; ?>file.png"><b>&nbsp;'+files[i][0]+'</b><br>'+files[i][1]+'x'+files[i][2]+'</td><td id="img_itm'+i+'b" align="left" valign="middle" style="height:60px; width:100px;"><img style="height:'+Math.floor(files[i][2]*k)+'px; width:'+Math.floor(files[i][1]*k)+'px;" src="<?php echo $GLOBALS['sf_live_site'].'/images/'; ?>'+start+files[i][0]+'"></td></tr>';
		s+='<tr><td></td></tr>';
		style=!style;
	}

	s+='</table>';
	document.getElementById("image_list").innerHTML=s;
}
function set_image_url(url,i) {
	if (last_img>=0) {
		document.getElementById("img_itm"+last_img+"a").style.borderWidth="0px";
		document.getElementById("img_itm"+last_img+"b").style.borderWidth="0px";
	}
	document.getElementById("props_el_src").value=url;
	last_img=i;
	document.getElementById("img_itm"+i+"a").style.borderStyle="solid";
	document.getElementById("img_itm"+i+"a").style.borderColor="#777777";
	document.getElementById("img_itm"+i+"a").style.borderWidth="2px 0px 2px 2px";
	document.getElementById("img_itm"+i+"b").style.borderStyle="solid";
	document.getElementById("img_itm"+i+"b").style.borderColor="#777777";
	document.getElementById("img_itm"+i+"b").style.borderWidth="2px 2px 2px 0px";
}

prop_change_flag=0; // props change flag
function check_change_props(){ // check change props
	if (prop_change_flag>0) {
		prop_change_flag_tmp=prop_change_flag;
		prop_change_flag=0;
		fr=false;
		if (window.confirm("Apply the changes?")) {
			selected_el_tmp=selected_el;
			selected_el=prop_change_flag_tmp;
			element_apply_props();
			selected_el=selected_el_tmp;
		}
		setTimeout('fr=false;  element_m_up();','100');
	}
}



function show_props() {

    // check change props
    check_change_props();

	createPropertiesSelector (2);
    var oHelper = new PropertyHelper();
	if (selected_el>0) {
		prop=el_object[selected_el]['prop'];
		for (i=0; i<propmatrix[prop-1].length; i++) {
			if (propmatrix[prop-1][i]==1) document.getElementById("el_id").innerHTML='&nbsp;Variables:&nbsp;<b title="Use this when insert the value of this element into text" style="color:#0000FF">{%val'+selected_el+'}</b>,&nbsp;&nbsp;<b title="Use this to identify the field in the JavaScript" style="color:#0000FF">{%id'+selected_el+'}</b>';
			if (propmatrix[prop-1][i]==2) document.getElementById("props_el_alias").value=oHelper.decodeProperty(el_object[selected_el]['alias']);
			if (propmatrix[prop-1][i]==3) document.getElementById("props_el_left").value=el_object[selected_el]['left'];
			if (propmatrix[prop-1][i]==4) document.getElementById("props_el_top").value=el_object[selected_el]['top'];
			if (propmatrix[prop-1][i]==5) document.getElementById("props_el_width").value=el_object[selected_el]['width'];
			if (propmatrix[prop-1][i]==6) document.getElementById("props_el_height").value=el_object[selected_el]['height'];
			if (propmatrix[prop-1][i]==7) document.getElementById("props_el_font_family").value=el_object[selected_el]['object'].style.fontFamily;
			if (propmatrix[prop-1][i]==8) document.getElementById("props_el_font_size").value=px_int(el_object[selected_el]['object'].style.fontSize);
			if (propmatrix[prop-1][i]==9) {if (el_object[selected_el]['req']==1) document.getElementById("props_el_req").checked=true; else document.getElementById("props_el_req").checked=false;}
			if (propmatrix[prop-1][i]==10) document.getElementById("props_el_validation").selectedIndex=el_object[selected_el]['validation'];
			if (propmatrix[prop-1][i]==11) document.getElementById("props_el_font_color").value=rgb_color(el_object[selected_el]['object'].style.color);
			if (propmatrix[prop-1][i]==12) document.getElementById("props_el_background_color").value=rgb_color(el_object[selected_el]['object'].style.backgroundColor);
			if (propmatrix[prop-1][i]==13) document.getElementById("props_el_text").value=oHelper.decodeProperty(el_object[selected_el]['object'].value);
			if (propmatrix[prop-1][i]==14) document.getElementById("props_el_hint").value=oHelper.decodeProperty(el_object[selected_el]['object'].title);
			if (propmatrix[prop-1][i]==15) if (el_object[selected_el]['checked']==true) document.getElementById("props_el_checked").selectedIndex=1; else document.getElementById("props_el_checked").selectedIndex=0;
			if (propmatrix[prop-1][i]==16) {
				for(p=1; p<=group_count; p++) if (groups[p]['type']==prop) {
					tmp=document.createElement("OPTION");
					tmp.value=p;
					tmp.text=groups[p]['name'];
					if (el_object[selected_el]['group']==p) tmp.selected=true;
					document.getElementById("props_el_group").options.add(tmp);
				}
			}
			if (propmatrix[prop-1][i]==17) {if (groups[el_object[selected_el]['group']]['req']==1) document.getElementById("props_el_req").checked=true; else document.getElementById("props_el_req").checked=false;}
			if (propmatrix[prop-1][i]==18) document.getElementById("props_el_group_rename").value=groups[el_object[selected_el]['group']]['name'];
			if (propmatrix[prop-1][i]==19) {
				list=el_object[selected_el]['object'].getElementsByTagName('option');
				s="";
				for (p=0; p<list.length; p++) if (list[p].text!='') s+=oHelper.decodeProperty(list[p].text)+'\n';
				s=s.substring(0,s.length-1);
				document.getElementById("props_el_text").value=s;
			}
			if (propmatrix[prop-1][i]==20) {
				document.getElementById("props_el_action").selectedIndex=el_object[selected_el]['action'];
				if (el_object[selected_el]['action']==3) document.getElementById("props_el_url").disabled=false; else document.getElementById("props_el_url").disabled=true;
				if (el_object[selected_el]['action']==4) document.getElementById("props_el_jump_to").disabled=false; else document.getElementById("props_el_jump_to").disabled=true;
			}
			if (propmatrix[prop-1][i]==21) document.getElementById("props_el_url").value=el_object[selected_el]['url'];
			if (propmatrix[prop-1][i]==22) { if (el_object[selected_el]['save']==1) document.getElementById("props_el_save").checked=true; else document.getElementById("props_el_save").checked=false;}
			if (propmatrix[prop-1][i]==23) {
				if (el_object[selected_el]['email']==1) document.getElementById("props_el_emailto").disabled=false; else document.getElementById("props_el_emailto").disabled=true;
				if (el_object[selected_el]['email']==1) document.getElementById("props_el_email").checked=true; else document.getElementById("props_el_email").checked=false;
			}
			if (propmatrix[prop-1][i]==24) document.getElementById("props_el_emailto").value=el_object[selected_el]['emailto'];
			if (propmatrix[prop-1][i]==25) document.getElementById("props_el_text").value=oHelper.decodeProperty(el_object[selected_el]['object'].value);
			if (propmatrix[prop-1][i]==26) document.getElementById("props_el_src").value=el_object[selected_el]['object'].src;
			if (propmatrix[prop-1][i]==28) document.getElementById("props_el_captcha").selectedIndex=el_object[selected_el]['captcha']-1;
			if (propmatrix[prop-1][i]==29) document.getElementById("props_el_text").value=oHelper.decodeProperty(el_object[selected_el]['object'].value);
			if (propmatrix[prop-1][i]==30) {
				selectedIndex=-1;
				tmp=document.createElement("OPTION");
				tmp.value=-1;
				tmp.text='-- Select --';
				document.getElementById("props_el_captchaid").options.add(tmp);
				for (p=1; p<=el_count; p++) if (el_object[p]['tab']==current_tab && el_object[p]['prop']!=-1 && el_object[p]['prop']<=2) {
					tmp=document.createElement("OPTION");
					tmp.value=p;
					tmp.text=el_object[p]['alias'];
					document.getElementById("props_el_captchaid").options.add(tmp);
					selectedIndex++;
					if (p==el_object[selected_el]['captchaid']) document.getElementById("props_el_captchaid").selectedIndex=selectedIndex+1;
				}
			}
			if (propmatrix[prop-1][i]==31) {
                //var tmpHtml = el_object[selected_el]['object'].innerHTML;
                var s = oHelper.decodeProperty(el_object[selected_el]['object'].innerHTML);
                var compiler = new StaticTextValue(s,1);
				document.getElementById("props_el_text").value = compiler.convert();
			}
//			if (propmatrix[prop-1][i]==31) document.getElementById("props_el_text").value=el_object[selected_el]['object'].innerHTML;
			if (propmatrix[prop-1][i]==32) document.getElementById("props_el_text").value=oHelper.decodeProperty(el_object[selected_el]['object'].innerHTML);
			if (propmatrix[prop-1][i]==33) document.getElementById("props_el_width").value=el_object[selected_el]['object'].size;
			if (propmatrix[prop-1][i]==34) { if (el_object[selected_el]['object'].maxLength>0) document.getElementById("props_el_limit").value=el_object[selected_el]['object'].maxLength; else document.getElementById("props_el_limit").value=255; }
			if (propmatrix[prop-1][i]==35) {
				selectedIndex=-1;
				tmp=document.createElement("OPTION");
				tmp.value=-1;
				tmp.text='-- Select --';
				document.getElementById("props_el_equal").options.add(tmp);
				for (p=1; p<=el_count; p++) if (el_object[p]['tab']==current_tab && el_object[p]['prop']!=-1 && el_object[p]['prop']<=2 && p!=selected_el) {
					tmp=document.createElement("OPTION");
					tmp.value=p;
					tmp.text=el_object[p]['alias'];
					document.getElementById("props_el_equal").options.add(tmp);
					selectedIndex++;
					if (p==el_object[selected_el]['equal']) document.getElementById("props_el_equal").selectedIndex=selectedIndex+1;
				}
			}
			if (propmatrix[prop-1][i]==36) {
				selectedIndex=-1;
				tmp=document.createElement("OPTION");
				tmp.value=-1;
				tmp.text='-- Select --';
				document.getElementById("props_el_useremail").options.add(tmp);
				for (p=1; p<=el_count; p++) if (el_object[p]['prop']!=-1 && el_object[p]['prop']<=2 && el_object[p]['validation']==3) {
					tmp=document.createElement("OPTION");
					tmp.value=p;
					tmp.text=el_object[p]['alias'];
					document.getElementById("props_el_useremail").options.add(tmp);
					selectedIndex++;
					if (p==el_object[selected_el]['useremail']) document.getElementById("props_el_useremail").selectedIndex=selectedIndex+1;
				}
			}
			if (propmatrix[prop-1][i]==37 && el_object[selected_el]['jump_to']>0) document.getElementById("props_el_jump_to").selectedIndex=el_object[selected_el]['jump_to']-1;
			if (propmatrix[prop-1][i]==38) {
				b_styles=el_object[selected_el]['def_border_style'].split(' ');
				b_style=b_styles[0];
				for (p=0; p<document.getElementById("props_el_border_style").length; p++) if (document.getElementById("props_el_border_style").options[p].value==b_style) document.getElementById("props_el_border_style").selectedIndex=p;
				if (b_style=='none') {
					document.getElementById("props_el_border_width").disabled=true;
					document.getElementById("props_el_border_color").disabled=true;
				} else {
					document.getElementById("props_el_border_width").disabled=false;
					document.getElementById("props_el_border_color").disabled=false;
				}
			}
			if (propmatrix[prop-1][i]==39) {
				b_width=px_int(el_object[selected_el]['def_border_width'])-0;
				document.getElementById("props_el_border_width").selectedIndex=b_width;
			}
			if (propmatrix[prop-1][i]==40) {
				b_color=el_object[selected_el]['def_border_color'];
				document.getElementById("props_el_border_color").value=rgb_color(b_color);
			}
			if (propmatrix[prop-1][i]==41) {
				selectedIndex=-1;
				tmp=document.createElement("OPTION");
				tmp.value=-1;
				tmp.text='-- Select --';
				document.getElementById("props_el_date_field").options.add(tmp);
				for (p=1; p<=el_count; p++) if (el_object[p]['prop']==1) {
					tmp=document.createElement("OPTION");
					tmp.value=p;
					tmp.text=el_object[p]['alias'];
					document.getElementById("props_el_date_field").options.add(tmp);
					selectedIndex++;
					if (p==el_object[selected_el]['datefield']) document.getElementById("props_el_date_field").selectedIndex=selectedIndex+1;
				}
				if (el_object[selected_el]['action']!=7) document.getElementById("props_el_date_field").disabled=true;
			}
			if (propmatrix[prop-1][i]==42) {
				document.getElementById("props_el_date_format").selectedIndex=el_object[selected_el]['dateformat']-1;
				if (el_object[selected_el]['action']!=7) document.getElementById("props_el_date_format").disabled=true;
			}
			if (propmatrix[prop-1][i]==43) {
				document.getElementById("props_el_extra_css").value=oHelper.decodeProperty(el_object[selected_el]['extra_css']);
			}
			if (propmatrix[prop-1][i]==44) {
				document.getElementById("props_el_extra_params").value=oHelper.decodeProperty(el_object[selected_el]['extra_params']);
			}
			if (propmatrix[prop-1][i]==45) { if (el_object[selected_el]['disable_validation']==1) document.getElementById("props_el_disable_validation").checked=true; else document.getElementById("props_el_disable_validation").checked=false;}
		}

	}
}
function element_apply_props() {
	if (selected_el>0) {
        var oHelper = new PropertyHelper();
		prop=el_object[selected_el]['prop'];
		for (i=0; i<propmatrix[prop-1].length; i++) {
			if (propmatrix[prop-1][i]==2) {
                el_object[selected_el]['alias']=oHelper.encodeSystem2(document.getElementById("props_el_alias").value);
                document.getElementById("props_el_alias").value = el_object[selected_el]['alias'];
            }
			if (propmatrix[prop-1][i]==3) {el_object[selected_el]['left']=document.getElementById("props_el_left").value-0; el_object[selected_el]['object'].style.left=el_object[selected_el]['left']+"px";}
			if (propmatrix[prop-1][i]==4) {el_object[selected_el]['top']=document.getElementById("props_el_top").value-0; el_object[selected_el]['object'].style.top=el_object[selected_el]['top']+"px";}
			if (propmatrix[prop-1][i]==5) {el_object[selected_el]['width']=document.getElementById("props_el_width").value-0; el_object[selected_el]['object'].style.width=el_object[selected_el]['width']+"px";}
			if (propmatrix[prop-1][i]==6) {el_object[selected_el]['height']=document.getElementById("props_el_height").value-0; el_object[selected_el]['object'].style.height=el_object[selected_el]['height']+"px";}
			if (propmatrix[prop-1][i]==7) el_object[selected_el]['object'].style.fontFamily=document.getElementById("props_el_font_family").value;
			if (propmatrix[prop-1][i]==8) el_object[selected_el]['object'].style.fontSize=document.getElementById("props_el_font_size").value+"px";
			if (propmatrix[prop-1][i]==9) { if (document.getElementById("props_el_req").checked==true) el_object[selected_el]['req']=1; else el_object[selected_el]['req']=0;}
			if (propmatrix[prop-1][i]==10) el_object[selected_el]['validation']=document.getElementById("props_el_validation").selectedIndex;
			if (propmatrix[prop-1][i]==11) el_object[selected_el]['object'].style.color="#"+document.getElementById("props_el_font_color").value;;
			if (propmatrix[prop-1][i]==12) {
                if (document.getElementById("props_el_background_color").value!='') {
                    el_object[selected_el]['object'].style.backgroundColor="#"+document.getElementById("props_el_background_color").value;
                } else {
                    el_object[selected_el]['object'].style.backgroundColor='';
                }
            }
			if (propmatrix[prop-1][i]==13) replaceAttribute('c_form_element'+selected_el,'value',oHelper.encodeProperty(document.getElementById("props_el_text").value,0));
			if (propmatrix[prop-1][i]==14) el_object[selected_el]['object'].title = oHelper.encodeProperty(document.getElementById("props_el_hint").value,0);
			if (propmatrix[prop-1][i]==15) {
				if (document.getElementById("props_el_checked").value==1) el_object[selected_el]['checked']=true; else el_object[selected_el]['checked']=false;
				replaceAttribute('i_form_element'+selected_el,'checked',el_object[selected_el]['checked']);
			}
			if (propmatrix[prop-1][i]==16) { el_object[selected_el]['group']=document.getElementById("props_el_group").value-0; if (prop!=5) replaceAttribute('i_form_element'+selected_el,'name','c_form_group'+el_object[selected_el]['group']); }
			if (propmatrix[prop-1][i]==17) { if (document.getElementById("props_el_req").checked==true) groups[el_object[selected_el]['group']]['req']=1; else groups[el_object[selected_el]['group']]['req']=0; }
			if (propmatrix[prop-1][i]==18) groups[el_object[selected_el]['group']]['name']=document.getElementById("props_el_group_rename").value;
			if (propmatrix[prop-1][i]==19) {
				list=document.getElementById("props_el_text").value.split('\n');
				while (el_object[selected_el]['object'].options.length>0) el_object[selected_el]['object'].remove(0);
				s=""; p=0;
				for (o=0; o<list.length; o++) if (list[o]!='') {
					if (list[o].substr(list[o].length-1,1)=='\r') list[o]=list[o].substr(0,list[o].length-1);
					tmp=document.createElement("OPTION");
					tmp.value=p+","+oHelper.encodeProperty(list[o],0);
					tmp.text=oHelper.encodeProperty(list[o],0);
					el_object[selected_el]['object'].options.add(tmp);
					p++;
				}
			}
			if (propmatrix[prop-1][i]==20) el_object[selected_el]['action']=document.getElementById("props_el_action").value;
			if (propmatrix[prop-1][i]==21) { s=document.getElementById("props_el_url").value; while (s.indexOf('~')>0) s=s.substr(0,s.indexOf('~'))+'%7E'+s.substr(s.indexOf('~')+1); el_object[selected_el]['url']=s;}
			if (propmatrix[prop-1][i]==22) { if (document.getElementById("props_el_save").checked==true) el_object[selected_el]['save']=1; else el_object[selected_el]['save']=0;}
			if (propmatrix[prop-1][i]==23) { if (document.getElementById("props_el_email").checked==true) el_object[selected_el]['email']=1; else el_object[selected_el]['email']=0;}
			if (propmatrix[prop-1][i]==24) el_object[selected_el]['emailto']=document.getElementById("props_el_emailto").value;
			if (propmatrix[prop-1][i]==25) el_object[selected_el]['object'].value=oHelper.encodeProperty(document.getElementById("props_el_text").value,0);
			if (propmatrix[prop-1][i]==26) el_object[selected_el]['object'].src=document.getElementById("props_el_src").value;
			if (propmatrix[prop-1][i]==28) el_object[selected_el]['captcha']=document.getElementById("props_el_captcha").selectedIndex+1;

			if (propmatrix[prop-1][i]==29) {
				if (navigator.userAgent.toLowerCase().indexOf('msie') > -1) {
					el_object[selected_el]['object'].value=oHelper.encodeProperty(document.getElementById("props_el_text").value,0);
				} else {
					el_object[selected_el]['object'].innerHTML=oHelper.encodeProperty(document.getElementById("props_el_text").value,0);
				}
			}

			if (propmatrix[prop-1][i]==30) el_object[selected_el]['captchaid']=document.getElementById("props_el_captchaid").value;
			if (propmatrix[prop-1][i]==31) {
                var s = document.getElementById("props_el_text").value;
				var compiler = new StaticTextValue(s);
				el_object[selected_el]['object'].innerHTML=compiler.convert();
			}
            // if (propmatrix[prop-1][i]==31) el_object[selected_el]['object'].innerHTML=document.getElementById("props_el_text").value;
			if (propmatrix[prop-1][i]==32) el_object[selected_el]['object'].innerHTML=oHelper.encodeProperty(document.getElementById("props_el_text").value,0);
			if (propmatrix[prop-1][i]==33) el_object[selected_el]['object'].size=document.getElementById("props_el_width").value;
			if (propmatrix[prop-1][i]==34) el_object[selected_el]['object'].maxLength=document.getElementById("props_el_limit").value;
			if (propmatrix[prop-1][i]==35) el_object[selected_el]['equal']=document.getElementById("props_el_equal").value;
			if (propmatrix[prop-1][i]==36) el_object[selected_el]['useremail']=document.getElementById("props_el_useremail").value;
			if (propmatrix[prop-1][i]==37) el_object[selected_el]['jump_to']=document.getElementById("props_el_jump_to").value;
			if (propmatrix[prop-1][i]==38) {
				el_object[selected_el]['def_border_style']=document.getElementById("props_el_border_style").value;
				el_object[selected_el]['object'].style.borderStyle=el_object[selected_el]['def_border_style'];
			}
			if (propmatrix[prop-1][i]==39) {
				el_object[selected_el]['def_border_width']=document.getElementById("props_el_border_width").value+'px';
				el_object[selected_el]['object'].style.borderWidth=el_object[selected_el]['def_border_width'];
			}
			if (propmatrix[prop-1][i]==40) {
                if( document.getElementById("props_el_border_color").value ) {
    				el_object[selected_el]['def_border_color']='#'+document.getElementById("props_el_border_color").value;
                }
				el_object[selected_el]['object'].style.borderColor=el_object[selected_el]['def_border_color'];
			}
			if (propmatrix[prop-1][i]==41) el_object[selected_el]['datefield']=document.getElementById("props_el_date_field").value;
			if (propmatrix[prop-1][i]==42) el_object[selected_el]['dateformat']=document.getElementById("props_el_date_format").value;
			if (propmatrix[prop-1][i]==43) {
				el_object[selected_el]['extra_css']=oHelper.encodeProperty(document.getElementById("props_el_extra_css").value,1);
			}
			if (propmatrix[prop-1][i]==44) {
				el_object[selected_el]['extra_params']=oHelper.encodeProperty(document.getElementById("props_el_extra_params").value,1);
			}
			if (propmatrix[prop-1][i]==45) { if (document.getElementById("props_el_disable_validation").checked==true) el_object[selected_el]['disable_validation']=1; else el_object[selected_el]['disable_validation']=0;}

		}
		update_data();
	}

prop_change_flag=0;

}

function show_info(mode,el) {
	if (mode==1 && el>0) {
		coords.style.visibility="visible";
		if (el_object[el]['prop']!=13) coords.innerHTML='<b style="font-size:10px;">id:'+el+' x:'+el_object[el]['left']+' y:'+el_object[el]['top']+" w:"+el_object[el]['width']+" h:"+el_object[el]['height']+"</b>";
			else coords.innerHTML='<b style="font-size:10px;">id:'+el+' x:'+el_object[el]['left']+' y:'+el_object[el]['top']+" s:"+el_object[el]['object'].size+" h:"+el_object[el]['height']+"</b>";
		coords.style.top=el_object[el]['top']-0+beginTop-12+"px";
		coords.style.left=el_object[el]['left']-0+beginLeft+"px";
	} else coords.style.visibility="hidden";
}

function show_hint(mode,el) {
	if (hint!=null) {
        try {
		if (mode==1) {
			if (el!=0) {
				var hint_title=document.getElementById("hint_title");
				hint_title.innerHTML="You are about to add a "+element_name[el-1];
				document.getElementById("hint_message").innerHTML="Press, and then holding the left mouse button<br>drag this element to the form editing area.";
				hint.style.visibility="visible";
			}
			hint.style.top=mouse['y']+15 +"px";
			hint.style.left=mouse['x']+15 +"px";
		} else hint.style.visibility="hidden";
        } catch( exc ) {
        }
	}
}
function create_element(el) {
	el_count++;
	el_object[el_count] = new Array ();
	events=' onmousemove="javascript:element_m_move()" onmouseover="javascript:element_m_over('+el_count+')" onmouseout="javascript:element_m_out()" onmousedown="javascript:element_m_down('+el_count+',event)" onmouseup="javascript:element_m_up(event)" ';
	if (el==1) s='<input name="c_form_element'+el_count+'" id="c_form_element'+el_count+'" type="text" maxlength="255" title="" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 1px solid #888888; width:150px; height:18px; font-family:Arial; font-size:12px; color:#000000; background-color:#FFFFFF; z-index:1; cursor:default;"'+events+'>';
	if (el==2) s='<input name="c_form_element'+el_count+'" id="c_form_element'+el_count+'" type="password" maxlength="255" title="" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 1px solid #888888; width:150px; height:18px; font-family:Arial; font-size:12px; color:#000000; background-color:#FFFFFF; z-index:1; cursor:default;"'+events+'>';
	if (el==3) s='<textarea name="c_form_element'+el_count+'" id="c_form_element'+el_count+'" title="" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 1px solid #888888; width:150px; height:50px; font-family:Arial; font-size:12px; color:#000000; background-color:#FFFFFF; z-index:1; cursor:default;"'+events+'></textarea>';
	if (el==4) s='<div id="c_form_element'+el_count+'" title="" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 0px none #888888; width:20px; height:20px; font-family:Arial; font-size:12px; color:#000000; background-color:#FFFFFF; z-index:1; cursor:default;"'+events+'><input name="c_form_element'+el_count+'" id="i_form_element'+el_count+'" type="radio"'+events+'></div>';
	if (el==5) s='<div id="c_form_element'+el_count+'" title="" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 0px none #888888; width:20px; height:20px; font-family:Arial; font-size:12px; color:#000000; background-color:#FFFFFF; z-index:1; cursor:default;"'+events+'><input name="c_form_element'+el_count+'" id="i_form_element'+el_count+'" type="checkbox"'+events+'></div>';
	if (el==6) s='<select name="c_form_element'+el_count+'" id="c_form_element'+el_count+'" title="" size=3 style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 1px solid #888888; width:150px; height:68px; font-family:Arial; font-size:12px; color:#000000; background-color:#FFFFFF; z-index:1; cursor:default;"'+events+'></select>';
	if (el==7) s='<select name="c_form_element'+el_count+'" id="c_form_element'+el_count+'" title="" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 1px solid #888888; width:150px; height:18px; font-family:Arial; font-size:12px; color:#000000; background-color:#FFFFFF; z-index:1; cursor:default;"'+events+'><option>--Please select--</select>';
	if (el==8) s='<input id="c_form_element'+el_count+'" type="button" title="" value="Button" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 2px outset #888888; width:50px; height:20px; font-family:Arial; font-size:12px; color:#000000; background-color:#FFFFFF; z-index:1; cursor:default;"'+events+'>';
	if (el==9) s='<img id="c_form_element'+el_count+'" title="" src="<?php echo $ff_url_admpath; ?>image.png" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 0px none #888888; width:150px; height:101px; z-index:1; cursor:default;"'+events+'></img>';
	if (el==10) s='<div id="c_form_element'+el_count+'" title="" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 1px none #888888; width:70px; height:20px; font-family:Arial; font-size:12px; color:#000000; z-index:1; cursor:default;"'+events+'>Static Text</div>';
	if (el==11) s='<a id="c_form_element'+el_count+'" title="" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 0px none #888888; width:150px; height:20px; font-family:Arial; font-size:12px; color:#000000; z-index:1; cursor:default;"'+events+'>External/Internal Link</a>';
	if (el==12) s='<img name="captcha" id="c_form_element'+el_count+'" title="" src="<?php echo $ff_url_admpath; ?>captcha.png" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 0px none #888888; width:149px; height:34px; z-index:1; cursor:default;"'+events+'></img>';
	if (el==13) s='<input name="c_form_element'+el_count+'" id="c_form_element'+el_count+'" type="file" size="10" title="" style="position:absolute; visibility:visible; margin: 0 0 0 0; padding:0px; border: 1px solid #888888; width:150px; height:20px; font-family:Arial; font-size:12px; color:#000000; background-color:#FFFFFF; z-index:1; cursor:default;'+events+'>';
	document.getElementById("form_editor").innerHTML+=s;
	connect_elements();
	el_object[el_count]['prop']=el;
	el_object[el_count]['tab']=current_tab;
	el_object[el_count]['alias']="c_form_element"+el_count;
	el_object[el_count]['selected']=false;
	el_object[el_count]['checked']=false;
	el_object[el_count]['group']=-1;
	el_object[el_count]['action']=0;
	el_object[el_count]['save']=0;
	el_object[el_count]['email']=0;
	el_object[el_count]['emailto']='';
	el_object[el_count]['url']='';
	el_object[el_count]['req']=0;
	el_object[el_count]['validation']=0;
	el_object[el_count]['captcha']=1;
	el_object[el_count]['captchaid']=-1;
	el_object[el_count]['equal']=-1;
	el_object[el_count]['useremail']=-1;
	el_object[el_count]['datefield']=-1;
	el_object[el_count]['dateformat']=1;
	el_object[el_count]['jump_to']=0;
	el_object[el_count]['extra_css']='';
	el_object[el_count]['extra_params']='';
	el_object[el_count]['disable_validation']='0';
	el_object[el_count]['left']=mouse['x']-beginLeft-10;
	el_object[el_count]['top']=mouse['y']-beginTop-10;
	el_object[el_count]['object'].style.left=el_object[el_count]['left']+"px";
	el_object[el_count]['object'].style.top=el_object[el_count]['top']+"px";
	el_object[el_count]['height']=px_int(el_object[el_count]['object'].style.height);
	el_object[el_count]['width']=px_int(el_object[el_count]['object'].style.width);
	el_object[el_count]['def_border_width']=el_object[el_count]['object'].style.borderWidth;
	el_object[el_count]['def_border_color']=el_object[el_count]['object'].style.borderColor;
	el_object[el_count]['def_border_style']=el_object[el_count]['object'].style.borderStyle;
	tmp=document.createElement("OPTION");
	tmp.value=el_count;
	tmp.text="c_form_element"+el_count+' ('+element_name[el-1]+')';
	elements_list.options.add(tmp,0);
}
function connect_elements(){
	var focused = false;
	for (p=1; p<=el_count; p++) {
		el_object[p]['object']= document.getElementById("c_form_element"+p);
		if (el_object[p]['object']) addEvent(el_object[p]['object'],'keydown',keyDown);

		/*if (!focused && el_object[p]['prop']<9 && el_object[p]['object'] && el_object[p]['tab']==current_tab) {
			el_object[p]['object'].focus();
			focused = true;
		}*/
	}
}
function element_frame(mode,el) {
try {
	if (el>0) {
		if (el_object[el]['object']!=null) {
			if (el_object[el]['prop']==4 || el_object[el]['prop']==5) {
				for(p=1; p<=el_count; p++) if (el_object[el]['group']==el_object[p]['group'] && el!=p) {
					if (mode==0 && !el_object[p]['selected'] && el_object[p]['object']!=null) {el_object[p]['object'].style.borderWidth=el_object[p]['def_border_width']; el_object[p]['object'].style.borderColor=el_object[p]['def_border_color'];  el_object[p]['object'].style.borderStyle=el_object[p]['def_border_style'];}
					if (mode==0 && el_object[p]['selected'] && el_object[p]['object']!=null) {el_object[p]['object'].style.borderWidth="1px"; el_object[p]['object'].style.borderColor="blue"; el_object[p]['object'].style.borderStyle="solid";}
					if (mode==1 && !el_object[p]['selected'] && el_object[p]['object']!=null) {el_object[p]['object'].style.borderWidth="1px"; el_object[p]['object'].style.borderColor="#BBBBBB"; el_object[p]['object'].style.borderStyle="dotted";}
					if (mode==1 && el_object[p]['selected'] && el_object[p]['object']!=null) {el_object[p]['object'].style.borderWidth="1px"; el_object[p]['object'].style.borderColor="blue"; el_object[p]['object'].style.borderStyle="dashed";}
				}
			}
            if (mode==0 && !el_object[el]['selected']) {
                el_object[el]['object'].style.borderWidth = el_object[el]['def_border_width'];
                if( el_object[el]['def_border_color'] != '#' ) {
                    el_object[el]['object'].style.borderColor = el_object[el]['def_border_color'];
                }
                el_object[el]['object'].style.borderStyle=el_object[el]['def_border_style'];
            }
			if (mode==1) { el_object[el]['object'].style.borderWidth="1px"; el_object[el]['object'].style.borderColor="red"; el_object[el]['object'].style.borderStyle="solid"; }
			if (mode==2 || el_object[el]['selected'] && mode!=1) { el_object[el]['object'].style.borderWidth="1px"; el_object[el]['object'].style.borderColor="blue"; el_object[el]['object'].style.borderStyle="solid";}
		}
	}
} catch (e) {}
}
function px_int (px) {
	if (px.toString().toLowerCase().indexOf("p")>-1) return px.toString().substring(0,px.toString().toLowerCase().indexOf("p"))*1;
	return px;
}
function get_mouse_coords(event) {
	mouse['y']=event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
	mouse['x']=event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
}
function rgb_color(s){
	if (s=='') return s;
	if (s.substring(0,1)=="#" || s.toLowerCase().indexOf('rgb')<0) return s.substr(1);
	s=s.substr(s.indexOf('(')+1);
	s=s.substring(0,s.indexOf(')'));
	a = new Array();
	a = s.split(",",3);
	s2="0123456789ABCDEF";
	s3=s2.substr(Math.floor(a[0]/16),1).toString()+s2.substr(a[0]-Math.floor(a[0]/16)*16,1)+s2.substr(Math.floor(a[1]/16),1)+s2.substr(a[1]-Math.floor(a[1]/16)*16,1)+s2.substr(Math.floor(a[2]/16),1)+s2.substr(a[2]-Math.floor(a[2]/16)*16,1);
	return s3;
}
function create_font_list() {
	s='<select id="props_el_font_family" style="width:160px; height:18px;"><option style="font-family:Arial;" value="Arial">Arial</option><option style="font-family:Arial Black;" value="Arial Black">Arial Black</option>';
	s+='<option style="font-family:Arial Narrow;" value="Arial Narrow">Arial Narrow</option><option style="font-family:Book Antiqua;" value="Book Antiqua">Book Antiqua</option><option style="font-family:Bookman Old Style;" value="Bookman Old Style">Bookman Old Style</option>';
	s+='<option style="font-family:Calibri;" valie="Calibri">Calibri</option><option style="font-family:Cambria;" value="Cambria">Cambria</option><option style="font-family:Candara;" value="Candara">Candara</option>';
	s+='<option style="font-family:Century;" value="Century">Century</option><option style="font-family:Century Gothic;" value="Century Gothic">Century Gothic</option><option style="font-family:Consolas;" value="Consolas">Consolas</option>';
	s+='<option style="font-family:Constantia;" value="Constantia">Constantia</option><option style="font-family:Corbel;" value="Corbel">Corbel</option><option style="font-family:Courier New;" value="Courier New">Courier New</option>';
	s+='<option style="font-family:Franklin Gothic Medium;" value="Franklin Gothic Medium">Franklin Gothic Medium</option><option style="font-family:Garamond;" value="Garamond">Garamond</option><option style="font-family:Georgia;" value="Georgia">Georgia</option>';
	s+='<option style="font-family:Impact;" value="Impact">Impact</option><option style="font-family:Lucida Console;" value="Lucida Console">Lucida Console</option><option style="font-family:Lucida Sans Unicode;" value="Lucida Sans Unicode">Lucida Sans Unicode</option>';
	s+='<option style="font-family:Microsoft Sans Serif;" value="Microsoft Sans Serif">Microsoft Sans Serif</option><option style="font-family:Mistral;" value="Mistral">Mistral</option><option style="font-family:Monotype Corsiva;" value="Monotype Corsiva">Monotype Corsiva</option>';
	s+='<option style="font-family:MS Mincho;" value="MS Mincho">MS Mincho</option><option style="font-family:MS Reference Sans Serif;" value="MS Reference Sans Serif">MS Reference Sans Serif</option><option style="font-family:Segoe UI;" value="Segoe UI">Segoe UI</option>';
	s+='<option style="font-family:Sylfaen;" value="Sylfaen">Sylfaen</option><option style="font-family:Tahoma;" value="Tahoma">Tahoma</option><option style="font-family:Times New Roman;" value="Times New Roman">Times New Roman</option>';
	s+='<option style="font-family:Trebuchet MS;" value="Trebuchet MS">Trebuchet MS</option><option style="font-family:Verdana;" value="Verdana">Verdana</option><option style="font-family:ZWAdobeF;" value="ZWAdobeF">ZWAdobeF</option></select>';
	return s;
}
function color_preview(c) {
	document.getElementById("color_preview").style.backgroundColor=c;
	document.getElementById("color_preview2").value=c;
}
function set_color(c) {
	document.getElementById("flowdiv4").style.visibility="hidden";
	if (selector_mode==1) document.getElementById("props_el_font_color").value=c;
	if (selector_mode==2) document.getElementById("props_el_background_color").value=c;
	if (selector_mode==3) document.getElementById("props_el_border_color").value=c;
}
function select_color(mode) {
	document.getElementById("flowdiv4").style.visibility="visible";
	selector_mode=mode;
}

function select_element() {
	o=0; el=0;
	for(i=0; i<elements_list.options.length; i++) {
		tmp=elements_list.options[i].value-0;
		if (elements_list.options[i].selected) {
			el_object[tmp]['selected']=true;
			element_frame(2,tmp);
			o++; el=tmp;
		} else {
			el_object[tmp]['selected']=false;
			element_frame(0,tmp);
		}
	}
	if (o==1) {
		if (selected_el!=el) {selected_el=el; show_props();} else {if (show_props_flag) show_props();}
	} else { // multiselect
		selected_el=0; show_props();
	}
	c=0;
	for(i=0; i<elements_list.options.length; i++) if (elements_list.options[i].selected) { c++; id=i; }
	if (c==1) {
		if (document.getElementById("move_up_button")!=null) {if (id>0) document.getElementById("move_up_button").disabled=false; else document.getElementById("move_up_button").disabled=true; }
		if (document.getElementById("move_down_button")!=null) {if (id<elements_list.options.length-1) document.getElementById("move_down_button").disabled=false; else document.getElementById("move_down_button").disabled=true;}
	} else {
		if (document.getElementById("move_up_button")!=null) document.getElementById("move_up_button").disabled=true;
		if (document.getElementById("move_down_button")!=null)document.getElementById("move_down_button").disabled=true;

	}
}

function update_data() {
	tmp_html=document.getElementById("form_editor").innerHTML;
	//tmp_html=tmp_html.replace(/[\r\n]+/g,"<br fix='fix'>");
	//p=tmp_html.indexOf('\n');
	//while (p!=-1) {
	//	tmp_html=tmp_html.substring(0,p).replace(/[\r\n]+/g,"")+"<br fix='fix'>"+tmp_html.substr(p+1).replace(/[\r\n]+/g,"");
	//	p=tmp_html.indexOf('\n');
	//}
	document.getElementById("page"+current_tab+"_html").value=tmp_html;
	var s2=Array();
	var s='';
	for(i=1; i<=el_count; i++) s2[i]=i+"|"+el_object[i]['tab']+"|"+el_object[i]['prop']+"|"+el_object[i]['alias']+"|"+el_object[i]['action']+"|"+el_object[i]['save']+"|"+el_object[i]['email']+"|"+el_object[i]['req']+"|"+el_object[i]['validation']+"|"+el_object[i]['def_border_width']+"|"+el_object[i]['def_border_color']+"|"+el_object[i]['def_border_style']+"|"+el_object[i]['group']+"|"+el_object[i]['checked']+"|"+el_object[i]['url']+"|"+el_object[i]['emailto']+"|"+el_object[i]['captcha']+"|"+el_object[i]['left']+"|"+el_object[i]['top']+"|"+el_object[i]['width']+"|"+el_object[i]['height']+"|"+el_object[i]['captchaid']+"|"+el_object[i]['equal']+"|"+el_object[i]['useremail']+"|"+el_object[i]['jump_to']+"|"+el_object[i]['datefield']+"|"+el_object[i]['dateformat']+"|"+el_object[i]['extra_css']+"|"+el_object[i]['extra_params']+"|"+el_object[i]['disable_validation']+"~";
	for (i=1; i<=10; i++) {
		var list=return_el_list(document.getElementById("page"+i+"_html").value);
		for (o=0; o<list.length; o++) {
			var p=list[o].toLowerCase().indexOf('c_form_element');
			if (p>-1) {
				var tmp=list[o].substr(p+14);
				if (tmp.indexOf(' ')>-1) tmp=tmp.substring(0,tmp.indexOf(' '));
				if (tmp.indexOf('"')>-1) tmp=tmp.substring(0,tmp.indexOf('"'));
				if (tmp.indexOf("'")>-1) tmp=tmp.substring(0,tmp.indexOf("'"));
				s+=s2[(tmp-0)];
				s2[(tmp-0)]=''
			}
		}
	}
	for(i=1; i<=el_count; i++) s+=s2[i];
	document.getElementById("element_to_page").value=s;
	s="";
	for(i=1; i<=group_count; i++) s+=i+"|"+groups[i]['type']+"|"+groups[i]['name']+"|"+groups[i]['req']+"~";
	document.getElementById("groups").value=s;
}

function move_element_up(){
	selectedIndex=elements_list.selectedIndex;
	if (selectedIndex>0) {
		list=return_el_list(document.getElementById("form_editor"));
		tmp_html2=list[list.length-1-selectedIndex];
		list[list.length-1-selectedIndex]=list[list.length-1-selectedIndex+1];
		list[list.length-1-selectedIndex+1]=tmp_html2;
		tmp_html2="";
		for(c=0; c<list.length; c++) tmp_html2+=list[c];
		document.getElementById("form_editor").innerHTML=tmp_html2;
		tmp1=elements_list.options[selectedIndex].value;
		tmp2=elements_list.options[selectedIndex].text;
		tmp3=elements_list.options[selectedIndex].selected;
		elements_list.options[selectedIndex].value=elements_list.options[selectedIndex-1].value;
		elements_list.options[selectedIndex].text=elements_list.options[selectedIndex-1].text;
		elements_list.options[selectedIndex].selected=elements_list.options[selectedIndex-1].selected;
		elements_list.options[selectedIndex-1].value=tmp1;
		elements_list.options[selectedIndex-1].text=tmp2;
		elements_list.options[selectedIndex-1].selected=tmp3;
		connect_elements();
		update_data();
		select_element();
	}
}
function move_element_down(){
	selectedIndex=elements_list.selectedIndex;
	if (selectedIndex<elements_list.options.length-1) {
		list=return_el_list(document.getElementById("form_editor"));
		tmp_html2=list[list.length-1-selectedIndex];
		list[list.length-1-selectedIndex]=list[list.length-1-selectedIndex-1];
		list[list.length-1-selectedIndex-1]=tmp_html2;
		tmp_html2="";
		for(c=0; c<list.length; c++) tmp_html2+=list[c];
		document.getElementById("form_editor").innerHTML=tmp_html2;
		tmp1=elements_list.options[selectedIndex].value;
		tmp2=elements_list.options[selectedIndex].text;
		tmp3=elements_list.options[selectedIndex].selected;
		elements_list.options[selectedIndex].value=elements_list.options[selectedIndex+1].value;
		elements_list.options[selectedIndex].text=elements_list.options[selectedIndex+1].text;
		elements_list.options[selectedIndex].selected=elements_list.options[selectedIndex+1].selected;
		elements_list.options[selectedIndex+1].value=tmp1;
		elements_list.options[selectedIndex+1].text=tmp2;
		elements_list.options[selectedIndex+1].selected=tmp3;
		connect_elements();
		update_data();
		select_element();
	}
}
function delete_element(mode){
	if (mode==1 && selected_el>0) {
		list=return_el_list(document.getElementById("form_editor"));
		tmp_html2="";
		for(c=0; c<list.length; c++) if (elements_list.options[list.length-1-c].value!=selected_el) tmp_html2+=list[c];
		document.getElementById("form_editor").innerHTML=tmp_html2;
		for(c=list.length-1; c>=0; c--) if (elements_list.options[c].value==selected_el) {
			el_object[elements_list.options[c].value]['selected']=false;
			el_object[elements_list.options[c].value]['prop']=-1;
			elements_list.remove(c);
		}
		last_el=0; selected_el=0; cur_el=0;
		connect_elements();
		update_data();
		select_element();
	} else {
		selectedIndex=elements_list.selectedIndex;
		if (selectedIndex<elements_list.options.length) {
			list=return_el_list(document.getElementById("form_editor"));
			tmp_html2="";
			last_el=0; selected_el=0; cur_el=0;
			for(c=0; c<list.length; c++) if (!elements_list.options[list.length-1-c].selected) tmp_html2+=list[c];
			document.getElementById("form_editor").innerHTML=tmp_html2;
			for(c=list.length-1; c>=0; c--) if (elements_list.options[c].selected) {
				el_object[elements_list.options[c].value]['selected']=false;
				el_object[elements_list.options[c].value]['prop']=-1;
				elements_list.remove(c);
			}
			connect_elements();
			update_data();
			select_element();
		}
	}
}
function display_tab(cur_tab){
	// check change props
    check_change_props();

	if (cur_tab!=current_tab) {
		while (elements_list.options.length>0) elements_list.remove(0);
		tmp_html=document.getElementById("page"+cur_tab+"_html").value;
        var fix1 = "<br fix='fix'>";
        var fix2 = "<br fix='fix'>";
		p=tmp_html.toLowerCase().indexOf(fix1);
        var fl = fix1.length;
        if( p == -1 ) {
            p=tmp_html.toLowerCase().indexOf(fix2);
            fl = fix2.length;
        }
		while (p!=-1) {
			tmp_html=tmp_html.substring(0,p)+'\n'+tmp_html.substr(p+fl);
			p=tmp_html.toLowerCase().indexOf(fix1);
            fl = fix1.length;
            if( p == -1 ) {
                p=tmp_html.toLowerCase().indexOf(fix2);
                fl = fix2.length;
            }
		}
		document.getElementById("form_editor").innerHTML=tmp_html;
		connect_elements();
		for (i=1; i<=el_count; i++) {
			el_object[i]['selected']=false;
			element_frame(0,i);
			if (el_object[i]['object']!=null) {
				if (el_object[i]['tab']!=cur_tab) {el_object[i]['object'].style.visibility='hidden'; el_object[i]['object'].style.zIndex='1';} else {el_object[i]['object'].style.visibility='visible'; el_object[i]['object'].style.zIndex='1'; }
			}
		}
		current_tab=cur_tab;
		list=return_el_list(document.getElementById("form_editor"));
		for(i=0; i<list.length; i++) {
			p=list[i].substr(list[i].indexOf('c_form_element')+14);
			if (p.indexOf(' ')>-1) p=p.substring(0,p.indexOf(' '));
			if (p.indexOf('"')>-1) p=p.substring(0,p.indexOf('"'));
			tmp=document.createElement("OPTION");
			elements_list.options.add(tmp,0);
			tmp.value=p;
			tmp.text=el_object[p]['alias']+' ('+element_name[el_object[p]['prop']-1]+')';
		}
		for(i=1; i<=10; i++)
			if (i==current_tab) {
				document.getElementById("tab"+i).className='tab_enabled';
				document.getElementById("tab"+i).innerHTML='<b>&nbsp;&nbsp;Page '+i+'</b>';
			} else {
				document.getElementById("tab"+i).className='tab_disabled';
				document.getElementById("tab"+i).innerHTML='&nbsp;&nbsp;Page '+i;
			}
	}
}
function return_el_list (htm) {
	list = new Array();
	list2 = new Array();
	if (typeof htm=="object" && htm==null || typeof htm!="object" && htm=='') return list;
	if (typeof htm=="object") tmp_html2=htm.innerHTML; else tmp_html2=htm;
	list2=tmp_html2.split('>');
	var i=0, c=-1, tmp2=-1;
	while (i<list2.length-1) {
		var pos1=list2[i].indexOf('&gt;');
		if (pos1>-1) {
			var pos2=list2[i].indexOf('<');
			if (pos2>-1 && pos2>pos1) list2[i]=list2[i].substr(pos2);
		}
		p=list2[i].toLowerCase().indexOf('c_form_element');
	    if (p>-1) {
		    var tmp=list2[i].substr(p+14);
		    if (tmp.indexOf(' ')>-1) tmp=tmp.substring(0,tmp.indexOf(' '));
		    if (tmp.indexOf('"')>-1) tmp=tmp.substring(0,tmp.indexOf('"'));
		    if (tmp.indexOf("'")>-1) tmp=tmp.substring(0,tmp.indexOf("'"));
		    if (tmp2!=tmp) {c++; list[c]=''; tmp2=tmp; }
	    }
	    if (c>=0 && list2[i]!='') list[c]+=list2[i]+'>';
 		i++;
	}
	//for(i=0; i<list.length; i++) alert(list[i]);
	return list;
}
function load_forms(){
	document.getElementById("element_to_page").value='<?php echo addcslashes($row->element_to_page,"'"); ?>';
	list=document.getElementById("element_to_page").value.split("~");
	el_bottom_max=0;
	el_count=list.length-1;
	for(i=0; i<el_count; i++) {
		s=list[i].split('|');
		for (o=1; o<40; o++) if (!s[o]) s[o]='';
		el_object[s[0]]=new Array();
		el_object[s[0]]['tab']=s[1];
		el_object[s[0]]['prop']=s[2];
		el_object[s[0]]['alias']=s[3];
		el_object[s[0]]['action']=s[4];
		el_object[s[0]]['save']=s[5];
		el_object[s[0]]['email']=s[6];
		el_object[s[0]]['req']=s[7];
		el_object[s[0]]['validation']=s[8];
		el_object[s[0]]['def_border_width']=s[9];
		el_object[s[0]]['def_border_color']=s[10];
		el_object[s[0]]['def_border_style']=s[11];
		el_object[s[0]]['group']=s[12];
		el_object[s[0]]['checked']=s[13];
		el_object[s[0]]['url']=s[14];
		el_object[s[0]]['emailto']=s[15];
		el_object[s[0]]['captcha']=s[16];
		el_object[s[0]]['left']=s[17]-0;
		el_object[s[0]]['top']=s[18]-0;
		el_object[s[0]]['width']=s[19]-0;
		el_object[s[0]]['height']=s[20]-0;
		el_object[s[0]]['captchaid']=s[21];
		el_object[s[0]]['equal']=s[22];
		el_object[s[0]]['useremail']=s[23];
		el_object[s[0]]['jump_to']=s[24];
		el_object[s[0]]['datefield']=s[25];
		el_object[s[0]]['dateformat']=s[26];
		el_object[s[0]]['extra_css']=s[27];
		el_object[s[0]]['extra_params']=s[28];
		el_object[s[0]]['disable_validation']=s[29];
		//get bottom element
		if ( (parseInt(s[18]) + parseInt(s[20])) > el_bottom_max ) el_bottom_max=parseInt(s[18]) + parseInt(s[20]);
	}
	document.getElementById("groups").value='<?php echo addcslashes($row->groups,"'"); ?>';
	list=document.getElementById("groups").value.split("~");
	group_count=list.length-1;
	for(i=0; i<group_count; i++) {
		s=list[i].split('|');
		groups[s[0]]=new Array();
		groups[s[0]]['type']=s[1];
		groups[s[0]]['name']=s[2];
		groups[s[0]]['req']=s[3];
	}
	//initialize low boundary of grid
	if ((el_bottom_max + 20)>790) {
		document.getElementById('grid_table').style.height = el_bottom_max + 25 + "px";
		document.getElementById('grid').style.height = el_bottom_max + 25 + "px";
		document.getElementById('tab_table').style.height = el_bottom_max + 25 + "px";
	}
}
function update_form_name(){
	document.getElementById("form_name").value=document.getElementById("tmp_form_name").value;
	if (document.getElementById("tmp_form_published").checked==true) document.getElementById("form_published").value=1; else document.getElementById("form_published").value=0;
}

function form_check() {
	document.getElementById('mask').style.visibility="visible";
	document.getElementById('mask').style.height=parseInt(document.getElementById('tab_table').style.height) + 270 + "px";
	document.getElementById('mask').style.width=document.body.scrollWidth + 20 + "px";
	document.getElementById('floatwindow').style.visibility="visible";
	document.getElementById('floatwindow').style.left=Math.floor((document.body.clientWidth-500)/2)+"px";
	document.getElementById('floatwindow').style.top="200px";
	document.getElementById('floatwindow').style.height="300px";
	document.getElementById('float_title').innerHTML='<b>&nbsp;&nbsp;Form Checker</b>';
	message='';
	bool=true;
	for (i=1; i<=el_count; i++) if ((el_object[i]['prop']==8 || el_object[i]['prop']==9 || el_object[i]['prop']==11) && (el_object[i]['save']==1 || el_object[i]['email']==1)) bool=false;
	if (bool) message+='&nbsp;<b style="color:red">Error:</b><b> The form has no submit or email button/link</b><br>';
	bool=false; p=0;
	for (i=1; i<=el_count; i++) if ((el_object[i]['prop']==8 || el_object[i]['prop']==9 || el_object[i]['prop']==11) && (el_object[i]['save']==1 || el_object[i]['email']==1)) {
		if (p!=0 && el_object[i]['tab']!=el_object[p]['tab']) bool=true;
		p=i;
	}
	if (bool) message+='&nbsp;<b style="color:blue">Warning:</b><b> The form has several submit or email button/link placed on different pages</b><br>';
	bool=false;
	for (i=1; i<=el_count; i++) if (el_object[i]['prop']==12 && el_object[i]['captchaid']<=0) bool=true;
	if (bool) message+='&nbsp;<b style="color:red">Error:</b><b> Captcha code should have linked editbox</b><br>';

	for (i=1; i<=el_count; i++) if ((el_object[i]['prop']==6 || el_object[i]['prop']==7) && el_object[i]['req']==1 && el_object[i]['object'].options.length==0) {
		message+='&nbsp;<b style="color:red">Error:</b><b> Element "'+el_object[i]['alias']+'" is required, but has no items</b><br>';
	}
	c=0;
	for(i=1; i<10; i++) {
		for (o=1; o<=el_count; o++) if (el_object[o]['action']==1 && el_object[o]['prop']>0 && el_object[o]['tab']==i) {
			bool=true;
			for (p=1; p<=el_count; p++) if (el_object[p]['prop']>0 && el_object[p]['tab']==i+1) bool=false;
			if (bool && c!=i) {message+='&nbsp;<b style="color:blue">Warning:</b><b> There are no elements on accessible Page '+(i+1)+'</b><br>'; c=i; }
		}
	}
	c=0;
	for(i=10; i>1; i--) {
		for (o=1; o<=el_count; o++) if (el_object[o]['prop']>0 && el_object[o]['tab']==i) {
			bool=true;
			for (p=1; p<=el_count; p++) if (el_object[p]['action']==1 && el_object[p]['tab']==i-1) bool=false;
			if (bool && c!=i) {message+='&nbsp;<b style="color:blue">Warning:</b><b> The Page '+i+' contains elements but can\'t be accessible</b><br>'; c=i; }
		}
	}
	bool=false; i=11;
	while (i>0 && !bool) {
		i--;
		for (o=1; o<=el_count; o++) if (el_object[o]['prop']>0 && el_object[o]['prop']<=7 && el_object[o]['tab']==i) bool=true;
	}
	if (i>0) {
		bool=true;
		for (o=1; o<=el_count; o++) if (el_object[o]['prop']>0 && el_object[o]['tab']==i && (el_object[o]['save']==1 || el_object[o]['email']==1)) bool=false;
		if (bool) message+='&nbsp;<b style="color:red">Error:</b><b> The last Page '+i+' has active elements but no submit/email button</b><br>';
	}

	if (message=='') message='&nbsp;<b style="color:blue">The form is correct!</b>';
	document.getElementById('float_content').innerHTML=message;

}
function replaceAttribute(id,name,value) {
	if (name=='checked') mode=1; else mode=0;
	html=document.getElementById("form_editor").innerHTML;
	pos=html.toLowerCase().indexOf('id='+id);
	if (pos==-1) pos=html.toLowerCase().indexOf('id="'+id+'"');
	if (pos==-1) {alert("no match"); return;}
	pos1=pos;
	while (html.substr(pos1,1)!="<") pos1--;
	pos2=pos;
	while (html.substr(pos2,1)!=">") pos2++;
	pos2++;
	html2=html.substring(pos1,pos2);
	if (mode==0) pos=html2.toLowerCase().indexOf(name+'='); else pos=html2.toLowerCase().indexOf(name);
	if (pos==-1) {
		if (mode==0) {
			html2=html2.substring(0,html2.length-1)+" "+name+'="'+value+'">';
		} else {
			if (value==true) html2=html2.substring(0,html2.length-1)+" "+name+'>';
		}
		html=html.substring(0,pos1)+html2+html.substring(pos2);
		document.getElementById("form_editor").innerHTML=html;
		return;
	}
	pos3=pos;
	while (html2.substr(pos3,1)!=" " && html2.substr(pos3,1)!=">") pos3++;
	if (mode==0) {
		html2=html2.substring(0,pos)+name+'="'+value+'"'+html2.substring(pos3);
	} else {
		if (value==true) html2=html2.substring(0,pos)+name+html2.substring(pos3); else html2=html2.substring(0,pos)+html2.substring(pos3);
	}
	html=html.substring(0,pos1)+html2+html.substring(pos2);
	document.getElementById("form_editor").innerHTML=html;
	connect_elements();
}
</script>

<?php if (defined( '_VALID_MOS' ) && !defined( '_JEXEC' )) echo '<table class="adminheading"><tr><th>Form Editor</th></tr></table>'; ?>
<table cellspacing="0" cellpadding="0" width="100%" style="border-style:none;">
<tr>
	<td align="left" class="header" height="30" width="1144" nowrap>
		<b style="color:red;">Form Name*:&nbsp;</b><input onchange="javascript:update_form_name();" type="text" size="30" id="tmp_form_name" value="<?php echo $row->name; ?>"></input>
		<b style="color:red;">&nbsp;&nbsp;Published:&nbsp;</b><input onchange="javascript:update_form_name();" type="checkbox" id="tmp_form_published" <?php if ($row->status==1) echo "checked"; ?>></input>
		&nbsp;|&nbsp;<input type="button" value="Copy" onclick="el_copy()">
		&nbsp;<input type="button" value="Paste" onclick="el_paste()">
		&nbsp;|&nbsp;<select id="move_page"><option value=0>page<option value=1>1<option value=2>2<option value=3>3<option value=4>4<option value=5>5<option value=6>6<option value=7>7<option value=8>8<option value=9>9<option value=10>10</select>
		&nbsp;<input type="button" value="Move" onclick="el_move()">
		&nbsp;|&nbsp;<input type="button" value="Check the form" onclick="form_check()">
		&nbsp;&nbsp;<input type="button" value="Hide Grid" onclick="if (this.value=='Hide Grid') {this.value='Show Grid'; document.getElementById('grid').style.visibility='hidden';} else {this.value='Hide Grid'; document.getElementById('grid').style.visibility='visible';}">
		&nbsp;&nbsp;Elements <input type="checkbox" id="tool1" checked onclick="if (this.checked) document.getElementById('flowdiv1').style.visibility='visible'; else document.getElementById('flowdiv1').style.visibility='hidden';">
		&nbsp;&nbsp;Properties <input type="checkbox" id="tool2" checked onclick="if (this.checked) {document.getElementById('flowdiv2').style.visibility='visible'; if (selected_el>0 && el_object[selected_el]['prop']==9) document.getElementById('image_list').style.visibility='visible';} else {document.getElementById('flowdiv2').style.visibility='hidden'; if (selected_el>0 && el_object[selected_el]['prop']==9) document.getElementById('image_list').style.visibility='hidden';}">
		&nbsp;&nbsp;List <input type="checkbox" id="tool3" checked onclick="if (this.checked) document.getElementById('flowdiv3').style.visibility='visible'; else document.getElementById('flowdiv3').style.visibility='hidden';">
		&nbsp;&nbsp;CSS/JS/PHP Editor <input type="checkbox" id="tool5" onclick="if (this.checked) {document.getElementById('flowdiv5').style.visibility='visible'; area_PHP_CSS_JS_init();} else document.getElementById('flowdiv5').style.visibility='hidden';">
	</td>
	<td align="center" width="90" class="header" nowrap><a href="http://www.itoris.com" target="_blank"><img title="IToris - Web Development Company" style="width:80px; height:20px; border-width:0px;" src="<?php echo $ff_url_admpath; ?>itoris_logo.png" style="cursor:pointer;"></img></a></td>
	<td align="left" class="header">&nbsp;</td></tr>
</table>
<div id="hint" style="position:absolute; visibility:hidden; top:100px; left:100px; z-index:11;">
	<table cellspacing="1" cellpadding="1" bgcolor="#FFFFFF" style="border-style:solid; border-color:black; border-width:1px;">
		<tr bgcolor="#000000"><td align="center"><font color="#1FFFFF"><b id="hint_title"></b></font></td></tr>
		<tr bgcolor="#555555"><td align="justify"><font color="#FFFFFF" id="hint_message"></font></td></tr>
	</table>
</div>
<div id="coords" style="position:absolute; visibility:hidden; z-index:2;"></div>
<div id="img_div" style="position:absolute; visibility:hidden; z-index:10;"></div>
<div id="main_frame" onmouseup="javascript:m_up()">
	<table cellspacing="2" cellpadding="0" style="border-color:black; border-width:1px; border-style:solid; height:613px;" align="left" onmousemove="try {get_mouse_coords(event); m_move(event);} catch (e) {}" ondrag="javascript:get_mouse_coords(event); m_move(event);">
	<tr><td valign="top">
		<table cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td width="1102" height="606" nowrap align="left" valign="top">
				<table cellpadding="0" style="width:1100px; height:806px;" id="tab_table" cellspacing="0">
				<tr style="height:16px;">
					<td id="tab1" class="tab_enabled" onclick="javascript:display_tab(1)"><b>&nbsp;&nbsp;Page 1</b></td>
					<td id="tab2" class="tab_disabled" onclick="javascript:display_tab(2)">&nbsp;&nbsp;Page 2</td>
					<td id="tab3" class="tab_disabled" onclick="javascript:display_tab(3)">&nbsp;&nbsp;Page 3</td>
					<td id="tab4" class="tab_disabled" onclick="javascript:display_tab(4)">&nbsp;&nbsp;Page 4</td>
					<td id="tab5" class="tab_disabled" onclick="javascript:display_tab(5)">&nbsp;&nbsp;Page 5</td>
					<td id="tab6" class="tab_disabled" onclick="javascript:display_tab(6)">&nbsp;&nbsp;Page 6</td>
					<td id="tab7" class="tab_disabled" onclick="javascript:display_tab(7)">&nbsp;&nbsp;Page 7</td>
					<td id="tab8" class="tab_disabled" onclick="javascript:display_tab(8)">&nbsp;&nbsp;Page 8</td>
					<td id="tab9" class="tab_disabled" onclick="javascript:display_tab(9)">&nbsp;&nbsp;Page 9</td>
					<td id="tab10" class="tab_disabled" onclick="javascript:display_tab(10)">&nbsp;&nbsp;Page 10</td>
					<td style="height:10px; border-width:1px; border-color:black; border-left-style:solid; border-top-style:none; border-right-style:none; border-bottom-style:solid;">&nbsp;</td>
				</tr>
				<tr id="tab_table" style="height:790px;">
					<td id="grid_table" align="left" valign="top" colspan="11" height="790" onmousedown="if (event.preventDefault) event.preventDefault(); m_down(0);">
						<table cellspacing="0" cellpadding="0" border="0">
						<tr><td>
							<div id="grid" style="position: absolute; background-image:url('<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/grid.png'); width:1100px; height:790px;" onclick="if (navigator.userAgent.toLowerCase().indexOf('safari') > -1) element_m_up(event);"></div>
							<div id="form_editor" class="form_editor"></div>
						</td></tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td></tr>
	</table>
	<?php
		function createPopup($id,$title,$left,$top,$width,$height,$vis){
			if ($vis==1) $vis="visibility:visible;"; else $vis="visibility:hidden;";
			if ($id!=5) {
				echo "<div id=\"flowd{$id}\"><table id=\"flowdiv{$id}\" cellpadding=\"0\" cellspacing=\"0\" style=\"position:absolute; {$vis} left:10px; top:10px; width:{$width}px; height:{$height}px; opacity:.6; filter:progid:DXImageTransform.Microsoft.BasicImage(opacity=0.6); z-index:10;\" onmousemove=\"try {get_mouse_coords(event); flowdiv_move({$id});} catch (e) {}\" onmouseout=\"fld_over=false; setTimeout('try {flowdiv_out({$id});} catch (e) {}',5000);\" onmouseover=\"try {flowdiv_over({$id});} catch (e) {}\">";
			} else {
				echo "<div id=\"flowd{$id}\"><table id=\"flowdiv{$id}\" cellpadding=\"0\" cellspacing=\"0\" style=\"position:absolute; {$vis} left:10px; top:10px; width:{$width}px; height:{$height}px; z-index:10;\" onmousemove=\"try {get_mouse_coords(event); flowdiv_move({$id});} catch (e) {}\" onmouseout=\"fld_over=false; setTimeout('try {flowdiv_out({$id});} catch (e) {}',5000);\" onmouseover=\"try {flowdiv_over({$id});} catch (e) {}\">";
			} ?>
			<tr><td id="flowdiv<?php echo $id;?>_title" width="<?php echo $width-40;?>" style="background-color:black; height:16px; color:white; cursor:move;" onmousedown="flowdiv_down(<?php echo $id;?>);" onmouseup="fld=false" ondblclick="flowdiv_minimize(<?php echo $id;?>);"><b>&nbsp;&nbsp;<?php echo $title;?></b><div id="flowdiv<?php echo $id;?>_head" style="position:absolute; left:<?php echo $left;?>px; top:<?php echo $top;?>px; width:<?php echo $width;?>px; height:16px;"></div></td>
			<td align="center" width="16" nowrap style="background-color:black; cursor:pointer;"><img id="flowdiv<?php echo $id;?>_icon" title="Minimize" src="<?php echo $GLOBALS['sf_live_site'];?>/administrator/components/com_smartformer/admin/minimize.gif" onclick="flowdiv_minimize(<?php echo $id;?>);"></td>
			<td align="center" width="16" nowrap style="background-color:black; cursor:pointer;"><img title="Close the window" src="<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/x.png" onclick="flowdiv_close(<?php echo $id;?>);"></td></tr>
			<tr><td colspan="3" id="flowdiv<?php echo $id;?>_content" style="width:<?php echo $width;?>px; height:<?php echo $height;?>px; background-color:#EEEEEE; border-color:black; border-width:1px; border-style:solid;"></td></tr></table></div>
            <?php
		}

        createPopup (1, "Available Elements", 450, 30, 170, 3, 1);
        createPopup (2, "Element Properties", 630, 30, 300, 3, 1);
        createPopup (3, "Elements List", 940, 30, 300, 3, 1);
        createPopup (4, "Color Selector", 100, 30, 300, 3, 0);
        createPopup (5, "CSS/JavaScript/PHP Editor", 100, 30, 700, 3, 0);
	?>


</div>
<form action="<?php if (defined( '_JEXEC' )) echo "index.php?option=com_smartformer"; else echo "index2.php?option=com_smartformer"; ?>" method="post" name="adminForm">

	<?php // old
		// $row->data2=preg_replace(array('/\"/','/\n/'),array('&quot;',"<br fix='fix'>"),$row->data2); echo $row->data2;
	?>
	<input type="hidden" name="page1_html" id="page1_html" value="<?php $row->data1=preg_replace('/\"/','&quot;',$row->data1); echo $row->data1; ?>">
	<input type="hidden" name="page2_html" id="page2_html" value="<?php $row->data2=preg_replace('/\"/','&quot;',$row->data2); echo $row->data2; ?>">
	<input type="hidden" name="page3_html" id="page3_html" value="<?php $row->data3=preg_replace('/\"/','&quot;',$row->data3); echo $row->data3; ?>">
	<input type="hidden" name="page4_html" id="page4_html" value="<?php $row->data4=preg_replace('/\"/','&quot;',$row->data4); echo $row->data4; ?>">
	<input type="hidden" name="page5_html" id="page5_html" value="<?php $row->data5=preg_replace('/\"/','&quot;',$row->data5); echo $row->data5; ?>">
	<input type="hidden" name="page6_html" id="page6_html" value="<?php $row->data6=preg_replace('/\"/','&quot;',$row->data6); echo $row->data6; ?>">
	<input type="hidden" name="page7_html" id="page7_html" value="<?php $row->data7=preg_replace('/\"/','&quot;',$row->data7); echo $row->data7; ?>">
	<input type="hidden" name="page8_html" id="page8_html" value="<?php $row->data8=preg_replace('/\"/','&quot;',$row->data8); echo $row->data8; ?>">
	<input type="hidden" name="page9_html" id="page9_html" value="<?php $row->data9=preg_replace('/\"/','&quot;',$row->data9); echo $row->data9; ?>">
	<input type="hidden" name="page10_html" id="page10_html" value="<?php $row->data10=preg_replace('/\"/','&quot;',$row->data10); echo $row->data10; ?>">
	<input type="hidden" name="element_to_page" id="element_to_page">
	<input type="hidden" name="groups" id="groups">
	<input type="hidden" name="css_list" id="css_list" value="<?php echo htmlspecialchars($row->css_list); ?>">
	<input type="hidden" name="js_list" id="js_list" value="<?php echo htmlspecialchars($row->js_list); ?>">
	<input type="hidden" name="php_list" id="php_list" value="<?php echo htmlspecialchars($row->php_list); ?>">
	<input type="hidden" name="form_name" id="form_name" value="<?php echo $row->name; ?>" />
	<input type="hidden" name="form_published" id="form_published" value="<?php echo $row->status; ?>" />
	<input type="hidden" name="option" value="<?php echo $option; ?>" />
	<input type="hidden" name="fid" value="<?php echo $formid; ?>" />
	<input type="hidden" name="task" value="" />
</form>
<img src="http://www.itoris.com/reg.php?d=<?php if (defined("_JEXEC")) echo "j1_5s2_4"; else echo "j1_0s2_4";?>&u=<?php echo $GLOBALS['sf_live_site']; if (isset($token)) echo "&t=".$token; ?>" width="1" height="1" alt="" border="0">
<iframe id='ping' style="width:0px; height:0px; visibility:hidden;"></iframe>
<div id="frame" style="position:absolute; visibility:hidden; border-style:dotted; border-width:1px; border-color:blue;" onmouseup="m_up();" onmousemove="get_mouse_coords(event); m_move(event)"></div>
<div id="mask" onmousemove="get_mouse_coords(event); floatwindow_move();" style="position:absolute; visibility:hidden; left:0px; top:0px; width:100px; height:100px; background-color:black; opacity:.3; filter:progid:DXImageTransform.Microsoft.BasicImage(opacity=.3)"></div>
<table id="floatwindow" cellpadding="0" cellspacing="0" style="position:absolute; visibility:hidden; left:100px; top:100px; width:500px; height:300px; border:1px; border-color:black; border-style:solid; z-index:12" onmousemove="get_mouse_coords(event); floatwindow_move();" onmouseout="fl=false">
<tr><td id="float_title" width="480" style="background-image:url('<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/background.png'); height:18px; color:white; cursor:move;" onmousedown="floatwindow_down();" onmouseup="floatwindow_up();"></td>
	<td align="center" width="18" nowrap style="background-image:url('<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/background.png'); cursor:pointer;"><img src="<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/x.png" onclick="floatwindow_close();"></td></tr>
<tr><td height="100%" colspan="2"><div id="float_content" style="width:498px; height:100%; background-color:white; border-color:black; border-width:1px; border-style:solid;"></div>
</td></tr>
</table>

<script type="text/javascript">
function ping() {
	HTTPRequest("<?php echo $GLOBALS['sf_live_site']; ?>/administrator/index<?php echo ((defined('_VALID_MOS') && !defined( '_JEXEC' ))?'2':''); ?>.php?option=com_smartformer&task=edit&ping=1",false,null);
	setTimeout('ping()','600000');
}

function HTTPRequest(url, async, func) {
 var xmlhttp;
 try{ xmlhttp = new XMLHttpRequest(); }
 catch (e) {
  try{ xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); }
  catch (e) {
   try{ xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); }
   catch (e) { alert(e.message); return; }
  }
 }
 xmlhttp.open("GET", url , async);
 if (async == true) {
  xmlhttp.onreadystatechange= function() {
   if (xmlhttp.readyState==4) {
    window[func](xmlhttp);
   }
  }
 }
 xmlhttp.send(null);
 if (async == false) {
  return xmlhttp;
 }
}

function createElementsSelector (id) {
	tmp='<table cellspacing="0" cellpadding="1" border="0" width="100%" onmouseover="flowdiv_over('+id+');" onmouseout="show_hint(0,0)" onmousemove="show_hint(1,0)">';
	tmp+='<tr onmousedown="m_down(1)" onmouseover="show_hint(1,1);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>editbox.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(2)" onmouseover="show_hint(1,2);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>password.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(3)" onmouseover="show_hint(1,3);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>textarea.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(4)" onmouseover="show_hint(1,4);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>radio.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(5)" onmouseover="show_hint(1,5);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>checkbox.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(6)" onmouseover="show_hint(1,6);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>Selectbox.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(7)" onmouseover="show_hint(1,7);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>Combobox.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(8)" onmouseover="show_hint(1,8);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>button.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(9)" onmouseover="show_hint(1,9);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>image.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(10)" onmouseover="show_hint(1,10);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>static.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(11)" onmouseover="show_hint(1,11);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>link.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(12)" onmouseover="show_hint(1,12);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>captcha.png"></td></tr>';
	tmp+='<tr onmousedown="m_down(13)" onmouseover="show_hint(1,13);"><td class="elem2" align="left">&nbsp;&nbsp;<img src="<?php echo $ff_url_admpath; ?>upload.png"></input></td></tr>';
	tmp+='</table>';
	document.getElementById('flowdiv'+id+'_content').innerHTML=tmp;

}

function createPropertiesSelector (id) {
	if (selected_el>0) {
		prop=el_object[selected_el]['prop']; // ordinal type element (button/text/password..)
		tmp='<table cellspacing="0" cellpadding="1" border="0" width="100%">';
		t=false;
		for (i=0; i<propmatrix[prop-1].length; i++) {
			if (propmatrix[prop-1][i]==1) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Type:</b> '+element_name[prop-1]+' <b id="el_id"></b></td></tr>';
			if (propmatrix[prop-1][i]==2) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Name:</b><input id="props_el_alias" type="text" style="width:240px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==3) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Left:</b><input id="props_el_left" type="text" style="width:25px;" onkeyup="prop_change_flag=selected_el;">';
			if (propmatrix[prop-1][i]==4) tmp+='&nbsp;<b>Top:</b><input id="props_el_top" type="text" style="width:25px;" onkeyup="prop_change_flag=selected_el;">';
			if (propmatrix[prop-1][i]==5) tmp+='&nbsp;<b>Width:</b><input id="props_el_width" type="text" style="width:25px;" onkeyup="prop_change_flag=selected_el;">';
			if (propmatrix[prop-1][i]==6) tmp+='&nbsp;<b>Height:</b><input id="props_el_height" type="text" style="width:25px;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==7) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Font:</b><b id="el_fonts">'+create_font_list()+'</b>';
			if (propmatrix[prop-1][i]==8) tmp+='&nbsp;<b>Size:</b><input id="props_el_font_size" type="text" style="width:25px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==9) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Required:</b><input id="props_el_req" type="checkbox" onclick="prop_change_flag=selected_el;">';
			if (propmatrix[prop-1][i]==10) tmp+='&nbsp;<b>Validation:</b><select id="props_el_validation" style="width:145px;" onclick="prop_change_flag=selected_el;"><option value="0" selected>No validation<option value="1">Name<option value="2">Address<option value="3">Email<option value="4">Integer<option value="5">IntegerPositive<option value="6">String<option value="7">City<option value="8">Zip<option value="9">ZipUS<option value="10">Phone<option value="11">PhoneUS<option value="12">Password<option value="13">CreditCardNumber<option value="14">CreditCardCVV<option value="15">Flat<option value="16">Building<option value="17">Money<option value="18">Float<option value="19">URL<option value="20">IP<option value="21">SSN<option value="22">House<option value="23">Date<option value="24">DateShort<option value="25">Time</select></td></tr>';
			if (propmatrix[prop-1][i]==11) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Font color:</b>&nbsp;#<input id="props_el_font_color" type="text" style="width:40px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;">&nbsp;<img src="<?php echo $ff_url_admpath; ?>uparrow.png" style="cursor:pointer;" onclick="javascript:select_color(1); prop_change_flag=selected_el;"></img>';
			if (propmatrix[prop-1][i]==12) tmp+='&nbsp;<b>Background:</b>&nbsp;#<input id="props_el_background_color" type="text" style="width:40px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;">&nbsp;<img src="<?php echo $ff_url_admpath; ?>uparrow.png" style="cursor:pointer;" onclick="javascript:select_color(2); prop_change_flag=selected_el;"></img></td></tr>';
			if (propmatrix[prop-1][i]==13) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Default text:</b><input id="props_el_text" type="text" style="width:215px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==14) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Hint:</b><input id="props_el_hint" type="text" style="width:255px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==15) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Default value:</b><select id="props_el_checked" onclick="prop_change_flag=selected_el;"><option value="0" selected>Un-checked</option><option value="1">Checked</option></select></td></tr>';
			if (propmatrix[prop-1][i]==16) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Group:</b><select id="props_el_group" onchange="document.getElementById(\'props_el_group_rename\').value=groups[this.value][\'name\']" onclick="prop_change_flag=selected_el;"></select></td></tr>';
			if (propmatrix[prop-1][i]==17) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Group Required:</b><input id="props_el_req" type="checkbox" onclick="prop_change_flag=selected_el;"><br>';
			if (propmatrix[prop-1][i]==18) tmp+='&nbsp;<b>Group name:</b><input id="props_el_group_rename" type="text" style="width:210px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==19) tmp+='<tr><td align="left" class="elem3" valign="middle"><b>List values</b> (per line):<br><textarea id="props_el_text" style="width:290px; height:70px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></textarea></td></tr>';
			if (propmatrix[prop-1][i]==20) tmp+='<tr><td align="left" height="30">&nbsp;<b>On-click:</b><select id="props_el_action" style="width:150px;" onchange="if (this.selectedIndex==7) {document.getElementById(\'props_el_date_field\').disabled=false; document.getElementById(\'props_el_date_format\').disabled=false;} else {document.getElementById(\'props_el_date_field\').disabled=true;  document.getElementById(\'props_el_date_format\').disabled=true;} if (this.selectedIndex==3) document.getElementById(\'props_el_url\').disabled=false; else document.getElementById(\'props_el_url\').disabled=true; if (this.selectedIndex==4) document.getElementById(\'props_el_jump_to\').disabled=false; else document.getElementById(\'props_el_jump_to\').disabled=true;" onclick="prop_change_flag=selected_el;"><option value="0" selected>No Action<option value="1">Go to the Next step<option value="2">Go to the Previous step<option value="3">Redirect to an external page<option value="4">Go to selected page<option value="5">Form Preview<option value="6">Form Reset<option value="7">Date picker<option value="8">Form Print</select>';
			if (propmatrix[prop-1][i]==21) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>URL:</b>&nbsp;http://<input id="props_el_url" type="text" style="width:230px;" disabled onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==22) tmp+='<tr><td align="left">&nbsp;<b>Save data in the database:</b><input id="props_el_save" type="checkbox" onclick="prop_change_flag=selected_el;">';
			if (propmatrix[prop-1][i]==23) tmp+='&nbsp;<b>Email data:</b><input id="props_el_email" type="checkbox" onchange="if (this.checked) document.getElementById(\'props_el_emailto\').disabled=false; else document.getElementById(\'props_el_emailto\').disabled=true;" onclick="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==24) tmp+='<tr><td align="left">&nbsp;<b>Email To:</b><input id="props_el_emailto" type="text" style="width:225px;" disabled onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==25) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Label:</b><input id="props_el_text" type="text" style="width:220px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==26) tmp+='<tr><td align="left" height="30">&nbsp;<b>Source:</b><input id="props_el_src" type="text" style="width:245px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==27) {
				tmp+='<tr><td align="left" valign="top"><div id="image_list" onclick="prop_change_flag=selected_el;" style="visibility:';
				if (document.getElementById('tool2').checked) tmp+='visible'; else tmp+='hidden';
				tmp+='; height:230px; width:295px; border-width:1px; border-color:black; border-style:solid; overflow-y:scroll; overflow-x:hidden;"></div></td></tr>';
				t=true;
			}
			if (propmatrix[prop-1][i]==28) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Captcha Type:</b><select id="props_el_captcha" style="width:185px;" onclick="prop_change_flag=selected_el;"><option value="1" selected>Alikon Mod</option><option value="2">Captcha Form</option><option value="3">SecurImage</option></select></td></tr>';
			if (propmatrix[prop-1][i]==29) tmp+='<tr><td align="left" class="elem3" valign="middle">&nbsp;<b>Default text:</b><br><textarea id="props_el_text" style="width:290px; height:50px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></textarea></td></tr>';
			if (propmatrix[prop-1][i]==30) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Field for code:</b><select id="props_el_captchaid" style="width:185px;" onclick="prop_change_flag=selected_el;"></select></td></tr>';
			if (propmatrix[prop-1][i]==31) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Default text:</b> (you may use HTML tags here)<br><textarea id="props_el_text" style="width:290px; height:100px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></textarea></td></tr>';
			if (propmatrix[prop-1][i]==32) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Text:</b><input id="props_el_text" type="text" style="width:220px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></td></tr>';
			if (propmatrix[prop-1][i]==33) tmp+='&nbsp;<b>Size(chars):</b><input id="props_el_width" type="text" style="width:25px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;">';
			if (propmatrix[prop-1][i]==34) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Char Limit:</b><input id="props_el_limit" type="text" style="width:25px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;">';
			if (propmatrix[prop-1][i]==35) tmp+='&nbsp;<b>Equal to:</b><select id="props_el_equal" style="width:155px;" onclick="prop_change_flag=selected_el;"></select></td></tr>';
			if (propmatrix[prop-1][i]==36) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>User email field:</b><select id="props_el_useremail" style="width:175px;" onclick="prop_change_flag=selected_el;"></select></td></tr>';
			if (propmatrix[prop-1][i]==37) tmp+='&nbsp;<b>Page:</b><select id="props_el_jump_to" style="width:40px;" onclick="prop_change_flag=selected_el;"><option value=1 selected>1<option value=2>2<option value=3>3<option value=4>4<option value=5>5<option value=6>6<option value=7>7<option value=8>8<option value=9>9<option value=10>10</select></td></tr>';
			if (propmatrix[prop-1][i]==38) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Border:</b><select id="props_el_border_style" style="width:70px;" onclick="prop_change_flag=selected_el;" onchange="if (this.value==\'none\') {document.getElementById(\'props_el_border_width\').disabled=true; document.getElementById(\'props_el_border_color\').disabled=true;} else {document.getElementById(\'props_el_border_width\').disabled=false; document.getElementById(\'props_el_border_color\').disabled=false;}"><option value="none" selected>No border<option value="solid">Solid<option value="dotted">Dotted<option value="dashed">Dashed<option value="groove">Groove<option value="inset">Inset<option value="outset">Outset</select>';
			if (propmatrix[prop-1][i]==39) tmp+='&nbsp;<select id="props_el_border_width" style="width:80px;" onclick="prop_change_flag=selected_el;"><option value="0" selected>0 pixels<option value="1">1 pixel<option value="2">2 pixels<option value="3">3 pixels<option value="4">4 pixels<option value="5">5 pixels</select>';
			if (propmatrix[prop-1][i]==40) tmp+='&nbsp;#<input id="props_el_border_color" type="text" style="width:40px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;">&nbsp;<img src="<?php echo $ff_url_admpath; ?>uparrow.png" style="cursor:pointer;" onclick="javascript:select_color(3); prop_change_flag=selected_el;"></img></td></tr>';
			if (propmatrix[prop-1][i]==41) tmp+='<tr><td align="left" class="elem3">&nbsp;<b>Date field:</b><select id="props_el_date_field" style="width:130px;" onclick="prop_change_flag=selected_el;"></select>';
			if (propmatrix[prop-1][i]==42) tmp+='&nbsp;<select id="props_el_date_format" style="width:90px;" onclick="prop_change_flag=selected_el;"><option value="1">mm/dd/yyyy<option value="2">mm/dd/yy<option value="3">dd/mm/yyyy<option value="4">dd/mm/yy<option value="5">yyyy-mm-dd</select></tr>';
			if (propmatrix[prop-1][i]==43) tmp+='<tr><td align="left" class="elem3" valign="middle">&nbsp;<b>Extra CSS:</b> (will be added to the <b>STYLE</b> parameter)<br><textarea id="props_el_extra_css" style="width:290px; height:40px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></textarea></td></tr>';
			if (propmatrix[prop-1][i]==44) tmp+='<tr><td align="left" class="elem3" valign="middle">&nbsp;<b>Extra Params:</b> (will be added to the element declaration)<br><textarea id="props_el_extra_params" style="width:290px; height:40px;" onchange="prop_change_flag=selected_el;" onkeyup="prop_change_flag=selected_el;"></textarea></td></tr>';
			if (propmatrix[prop-1][i]==45) tmp+='<tr><td align="left" height="30">&nbsp;<b>Disable validation:</b><input id="props_el_disable_validation" type="checkbox" onclick="prop_change_flag=selected_el;"></td></tr>';
		}
		tmp+='<tr><td align="right"><button onclick="javascript:element_apply_props()">Apply</button>&nbsp;&nbsp;&nbsp;<button onclick="javascript:delete_element(1);">Delete</button></td></tr>';
		tmp+='</table>';
		document.getElementById('flowdiv'+id+'_content').innerHTML=tmp;
		if (t==true) show_image_list('');
	} else document.getElementById('flowdiv'+id+'_content').innerHTML='';
}

function createListSelector (id) {
	tmp='<table cellspacing="0" cellpadding="1" border="0" width="100%">';
	tmp+='<tr><td>';
	tmp+='<select id="elements_list" size="20" multiple style="width:200px;" onclick="this.focus(); select_element();"></select>';
	tmp+='</td><td valign="top" align="left"><input id="move_up_button" type="button" disabled value="Move Up" onclick="move_element_up()"><br><input id="move_down_button" disabled type="button" value="Move Down" onclick="javascript:move_element_down()"><br><hr><input type="button" value="Delete" onclick="javascript:delete_element(-1)">';
	tmp+='</td></tr>';
	tmp+='</table>';
	document.getElementById('flowdiv'+id+'_content').innerHTML=tmp;
	elements_list=document.getElementById("elements_list");
	list=return_el_list(document.getElementById("form_editor"));
	for(i=0; i<list.length; i++) {
		p=list[i].substr(list[i].indexOf('c_form_element')+14);
		if (p.indexOf(' ')>-1) p=p.substring(0,p.indexOf(' '));
		if (p.indexOf('"')>-1) p=p.substring(0,p.indexOf('"'));
		tmp=document.createElement("OPTION");
		elements_list.options.add(tmp,0);
		tmp.value=p;
		tmp.text=el_object[p]['alias']+' ('+element_name[el_object[p]['prop']-1]+')';
	}
}

function createColorSelector (id) {
	tmp='<table cellspacing="1" cellpadding="1"><tr>';
	i=0;
	for(b=0; b<=256; b+=64)
		for(g=0; g<=256; g+=64)
			for(r=0; r<=256; r+=64) {
				if (r==256) r=255;
				if (g==256) g=255;
				if (b==256) b=255;
				c=rgb_color('rgb('+r+','+g+','+b+')');
				tmp+='<td width=10 height=12 bgcolor="#'+c+'" onmouseover="javascript:color_preview(\'#'+c+'\')" onclick="javascript:set_color(\''+c+'\')"></td>';
				i++;
				if (Math.floor(i/25)==i/25) tmp+="</tr><tr>";
			}

	tmp+='</tr></table><table height=20><tr><td width="50" id="color_preview"></td><td><input size="6" type="text" readonly id="color_preview2"></td><td align="right"><button onclick="javascript:document.getElementById(\'flowdiv4\').style.visibility=\'hidden\'">Cancel</button></td></tr></table>';
	document.getElementById('flowdiv'+id+'_content').innerHTML=tmp;

}

function createCSSJSEditor (id) {
	tmp='<table cellspacing="0" cellpadding="1" border="0" width="100%">';
	tmp+='<tr><td><b>&nbsp;PHP Editor:</b> (will be executed before the form being painted)</br><textarea id="php_list_tmp" style="width:690px; height:200px;">'+document.getElementById('php_list').value+'</textarea></td></tr>';
	tmp+="<tr><td align='right'><input type='button' onclick='area_save();' value='Save' /></td></tr>";
	tmp+='<tr><td><b>&nbsp;CSS Editor:</b> (will be included into <b>&lt;STYLE&gt;&lt;/STYLE&gt;</b>)</br><textarea id="css_list_tmp" style="width:690px; height:200px;">'+document.getElementById('css_list').value+'</textarea></td></tr>';
	tmp+="<tr><td align='right'><input type='button' onclick='area_save();' value='Save' /></td></tr>";
	tmp+='<tr><td><b>&nbsp;JS Editor:</b> (will be included into <b>&lt;SCRIPT&gt;&lt;/SCRIPT&gt;</b>)</br><textarea id="js_list_tmp" style="width:690px; height:200px;">'+document.getElementById('js_list').value+'</textarea></td></tr>';
	tmp+="<tr><td align='right'><input type='button' onclick='area_save();' value='Save' /></td></tr>";
	tmp+='</table>';
	document.getElementById('flowdiv'+id+'_content').innerHTML=tmp;
    //area_PHP_CSS_JS_init();
}

function area_save(){
document.getElementById("php_list").value=editAreaLoader.getValue("php_list_tmp");
document.getElementById("css_list").value=editAreaLoader.getValue("css_list_tmp");
document.getElementById("js_list").value=editAreaLoader.getValue("js_list_tmp");
}

function area_PHP_CSS_JS_init(){
    editAreaLoader.init({
		id: "php_list_tmp"	// id of the textarea to transform
		,start_highlight: true	// if start with highlight
		,allow_resize: "both"
		,allow_toggle: true
		,language: "en"
		,syntax: "php"
	});

	editAreaLoader.init({
		id: "css_list_tmp"	// id of the textarea to transform
		,start_highlight: true	// if start with highlight
		,allow_resize: "both"
		,allow_toggle: true
		,language: "en"
		,syntax: "css"
	});

	editAreaLoader.init({
		id: "js_list_tmp"	// id of the textarea to transform
		,start_highlight: true	// if start with highlight
		,allow_resize: "both"
		,allow_toggle: true
		,language: "en"
		,syntax: "js"
	});
}


function createPopup (id,title,left,top,width,height,vis) {
try {
	if (id!=5) {
		tmp='<div id="flowd'+id+'"><table id="flowdiv'+id+'" cellpadding="0" cellspacing="0" style="position:absolute; visibility:hidden; left:10px; top:10px; width:'+width+'px; height:'+height+'px; opacity:.6; filter:progid:DXImageTransform.Microsoft.BasicImage(opacity=0.6); z-index:10;" onmousemove="try {get_mouse_coords(event); flowdiv_move('+id+');} catch (e) {}" onmouseout="fld_over=false; setTimeout(\'try {flowdiv_out('+id+');} catch (e) {}\',5000);" onmouseover="try {flowdiv_over('+id+');} catch (e) {}">';
	} else {
		tmp='<div id="flowd'+id+'"><table id="flowdiv'+id+'" cellpadding="0" cellspacing="0" style="position:absolute; visibility:hidden; left:10px; top:10px; width:'+width+'px; height:'+height+'px; z-index:10;" onmousemove="try {get_mouse_coords(event); flowdiv_move('+id+');} catch (e) {}" onmouseout="fld_over=false; setTimeout(\'try {flowdiv_out('+id+');} catch (e) {}\',5000);" onmouseover="try {flowdiv_over('+id+');} catch (e) {}">';
	}
	tmp+='<tr><td id="flowdiv'+id+'_title" width="'+(width-40)+'" style="background-color:black; height:16px; color:white; cursor:move;" onmousedown="flowdiv_down('+id+');" onmouseup="fld=false" ondblclick="flowdiv_minimize('+id+');"><b>&nbsp;&nbsp;'+title+'</b><div id="flowdiv'+id+'_head" style="position:absolute; left:'+left+'px; top:'+top+'px; width:'+(width-40)+'px; height:16px;"></div></td>';
	tmp+='<td align="center" width="16" nowrap style="background-color:black; cursor:pointer;"><img id="flowdiv'+id+'_icon" title="Minimize" src="<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/minimize.gif" onclick="flowdiv_minimize('+id+');"></td>';
	tmp+='<td align="center" width="16" nowrap style="background-color:black; cursor:pointer;"><img title="Close the window" src="<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/x.png" onclick="flowdiv_close('+id+');"></td></tr>';
	tmp+='<tr><td colspan="3" id="flowdiv'+id+'_content" style="width:'+width+'px; height:'+height+'px; background-color:#EEEEEE; border-color:black; border-width:1px; border-style:solid;"></td></tr></table></div>';
	//tmp="1234567";
	document.getElementById('main_frame').innerHTML+=tmp;


	//document.getElementById('main_frame').write(tmp);

 	tmp=document.getElementById('flowdiv'+id);
	tmp.style.left=left+"px";
 	tmp.style.top=top+"px";
   	if (vis==1) tmp.style.visibility='visible';
} catch (e) {}
}

function flowdiv_down(id) {
	mouse['fldx']=mouse['x']; mouse['fldy']=mouse['y'];
	fld=true;

}
function flowdiv_move(id,event) {
	fld_over=true;
	if (fld) {
		mouse['fldx2']=mouse['x']-mouse['fldx'];
		mouse['fldy2']=mouse['y']-mouse['fldy'];
		document.getElementById('flowdiv'+id).style.left=px_int(document.getElementById('flowdiv'+id).style.left)+mouse['fldx2']+"px";
		document.getElementById('flowdiv'+id).style.top=px_int(document.getElementById('flowdiv'+id).style.top)+mouse['fldy2']+"px";
		document.getElementById('flowdiv'+id+'_head').style.left=document.getElementById('flowdiv'+id).style.left;
		document.getElementById('flowdiv'+id+'_head').style.top=document.getElementById('flowdiv'+id).style.top;
		mouse['fldx']=mouse['x']; mouse['fldy']=mouse['y'];
	}

}

function flowdiv_close(id) {
    if (id==5) {area_save();editAreaLoader.delete_instance("php_list_tmp"); editAreaLoader.delete_instance("css_list_tmp"); editAreaLoader.delete_instance("js_list_tmp");}
	if (id<=3 || id==5) document.getElementById('tool'+id).checked=false;
	document.getElementById('flowdiv'+id).style.visibility="hidden";
}
function flowdiv_out(id) {
	if (!fld_over) {
		fld=false;
		if (id!=5) {
			document.getElementById('flowdiv'+id).style.opacity=".6";
			document.getElementById('flowdiv'+id).style.filter="progid:DXImageTransform.Microsoft.BasicImage(opacity=.6)";
		}
	}

}
function flowdiv_over(id) {
	fld_over=true;
	if (id!=5) {
		document.getElementById('flowdiv'+id).style.opacity="1";
		document.getElementById('flowdiv'+id).style.filter="progid:DXImageTransform.Microsoft.BasicImage(opacity=1)";
	}
}

function flowdiv_minimize(id) {
	if (document.getElementById('flowdiv'+id+'_content').innerHTML!='') {
		document.getElementById('flowdiv'+id+'_content').innerHTML='';
		document.getElementById('flowdiv'+id+'_icon').src='<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/maximize.gif';
		document.getElementById('flowdiv'+id+'_icon').title='Maximize';

	} else {
		if (id==1) createElementsSelector (1);
		if (id==2) { if (selected_el>0) createPropertiesSelector (2); else createPropertiesSelector (2); }
		if (id==3) createListSelector (3);
		if (id==4) createColorSelector (4);
		if (id==5) createCSSJSEditor (5);
		document.getElementById('flowdiv'+id+'_icon').src='<?php echo $GLOBALS['sf_live_site']; ?>/administrator/components/com_smartformer/admin/minimize.gif';
		document.getElementById('flowdiv'+id+'_icon').title='Minimize';
	}
}

function init_sf2(){ // for support ie6 in joomla 1.5
try {
	if (document.getElementById("form_editor").offsetTop>0)	 {
		try {createElementsSelector (1);} catch (e) {}
		try {createPropertiesSelector (2);} catch (e) {}
		try {createListSelector (3);} catch (e) {}
		try {createColorSelector (4);} catch (e) {}
		try {load_forms();} catch (e) {}
		try {display_tab(1);} catch (e) {}

		beginLeft=document.getElementById("form_editor").offsetLeft;
	 	beginTop=document.getElementById("form_editor").offsetTop;
	 	document.getElementById('flowdiv1').style.left=(beginLeft+450)+"px";
		document.getElementById('flowdiv1').style.top=(beginTop+30)+"px";
	 	document.getElementById('flowdiv2').style.left=(beginLeft+630)+"px";
		document.getElementById('flowdiv2').style.top=(beginTop+30)+"px";
        document.getElementById('flowdiv3').style.left=(beginLeft+940)+"px";
		document.getElementById('flowdiv3').style.top=(beginTop+30)+"px";
	 	document.getElementById('flowdiv4').style.left=(beginLeft+100)+"px";
		document.getElementById('flowdiv4').style.top=(beginTop+30)+"px";
	 	document.getElementById('flowdiv5').style.left=(beginLeft+200)+"px";
		document.getElementById('flowdiv5').style.top=(beginTop-10)+"px";
		createCSSJSEditor (5);
	} else {
    	setTimeout('init_sf2()','150');
	}
} catch (e) {}
}

	// start initialization
	try {var hint=document.getElementById("hint");} catch (e) {}
	try {var coords=document.getElementById("coords");} catch (e) {}
 	try {var elements_list=document.getElementById("elements_list");} catch (e) {}
 	try {var frame=document.getElementById("frame");} catch (e) {}
	try {var beginLeft=document.getElementById("form_editor").offsetLeft;} catch (e) {}
 	try {var beginTop=document.getElementById("form_editor").offsetTop;} catch (e) {}
	var fld_over=false;
	var propmatrix = new Array (new Array (1,2,3,4,5,6,38,39,40,7,8,9,10,34,35,11,12,13,14,43,44),
								new Array(1,2,3,4,5,6,38,39,40,7,8,9,34,35,11,12,14,43,44),
								new Array(1,2,3,4,5,6,38,39,40,7,8,9,11,12,29,14,43,44),
								new Array(1,2,3,4,5,6,38,39,40,7,8,11,12,14,15,16,17,18,43,44),
								new Array(1,2,3,4,5,6,38,39,40,9,11,12,14,15,16,18,43,44),
								new Array(1,2,3,4,5,6,38,39,40,7,8,9,11,12,14,19,43,44),
								new Array(1,2,3,4,5,6,38,39,40,7,8,9,11,12,14,19,43,44),
								new Array(1,2,3,4,5,6,38,39,40,7,8,11,12,14,45,20,37,21,41,42,22,23,24,36,25,43,44),
								new Array(1,2,3,4,5,6,38,39,40,14,20,37,21,41,42,22,23,24,36,26,27,43,44),
								new Array(1,2,3,4,5,6,38,39,40,7,8,11,12,31,14,43,44),
								new Array(1,2,3,4,5,6,38,39,40,7,8,11,12,32,14,20,37,21,41,42,22,23,24,36,43,44),
								new Array(1,2,3,4,5,6,38,39,40,14,28,30,43,44),
								new Array(1,2,3,4,33,6,38,39,40,7,8,11,12,14,43,44) );


	//try {createElementsSelector (1);} catch (e) {}
	//try {createPropertiesSelector (2);} catch (e) {}
	//try {createListSelector (3);} catch (e) {}
	//try {createColorSelector (4);} catch (e) {}

	//createCSSJSEditor (5);

	//try {load_forms();} catch (e) {}
	//try {display_tab(1);} catch (e) {}

	setTimeout('try {init_sf2()} catch (e) {}','100');
	setTimeout('ping()','600000');


	<?php if ($message!='') echo "alert('$message');";?>
 </script>
/**
 * $ModDesc
 * 
 * @version	$Id: helper.php $Revision
 * @package	modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>.All rights reserved.
 */ 
// JavaScript Document
window.addEvent('load', function(){

 var controls=['item_layout'];	
 
 if(  $defined($$('.paramlist tr')) && $$('.paramlist tr').length > 0 ) {  
 	// on off
	var trs = $ES('.paramlist tr');
	 trs.each( function(tr, index){
		var tmp = tr.getElement('td.paramlist_value .lof-group')
		if( tmp && tmp.getProperty('title') ){
			tr.addClass('group-'+tmp.getProperty('title')).addClass('icon-'+tmp.getProperty('title'));
			for( j=index+1; j < trs.length; j++ ){
				if( $defined(trs[j].getElement('td.paramlist_value .lof-end-group')) ) {
					trs[j].remove();
					break;
				}
				trs[j].addClass('group-'+tmp.getProperty('title')).addClass('lof-group-tr');
			}
			var title = tmp.getProperty('title');
			tmp.enable= true;
		}
	 });
	 function update( tmp, hide ){
		 	if( hide ){
				tmp.enable = true;
			}
		 	var title = tmp.value;
			if(  tmp.enable==false  && $defined(tmp.enable) ) {
			//	alert( $E('.admintable' ).getElements("*[class=^"+title+"]") );
				$ES('.admintable tr.group-'+title ).setStyle('display','');
				tmp.enable=true;

			} else if(title && title !=-1) {
				$ES('.admintable tr.group-'+title ).setStyle('display','none');
				tmp.enable=false;
			}
			setTimeout( function(){
				$E('.jpane-slider ').setStyle( 'height', $E('.paramlist').offsetHeight );
			}, 300 );
	 }
	 

	controls.each( function(_group){ 
		$ES('#params'+_group).addEvent('change',function(){
			var tmdp = this;
			tmdp.enable = false;
				update( this  );
			var selected = this;
			$ES('#params'+_group +' option').each( function(tmp, index){
					if(tmp.value !=selected.value ) {
						update( tmp, true );
					}
			} );
		});
		 $ES('#params'+_group+' option').each( function(tmp, index){
				if(!tmp.selected) {
					update( tmp );
				}

		} );
		
	} );
	
	//////////////
	setTimeout( function(){
		$ES('.lof-onoff').each( function( item ){
	
			if( $defined($ES( "."+item.id.replace("params",'group-') ))  ) {							 
				if( item.checked ){
					//$ES( "."+item.id.replace("params",'group-') ).setStyle("display","");
					// $E( 'tr.'+item.id.replace("params",'group-')).setStyle("display",'');
					item.value=1;
				} else {
					if( $ES( "."+item.id.replace("params",'group-') ).length > 0 ){
					 	 $ES( "."+item.id.replace("params",'group-') ).setStyle("display","none");
						 $E( 'tr.'+item.id.replace("params",'group-')).setStyle("display",'');
						 item.value=0;
					}
				}
			} 
		});
		setTimeout( function(){
				$E('.jpane-slider ').setStyle( 'height', $E('.paramlist').offsetHeight );
		}, 300 );
	}, 200 );
	

	$ES('.lof-onoff').addEvent('click', function(item,idx){
	// alert(this.getParent('tr') )
		if( !this.checked ){  //  alert(  );
			this.value=0;
		 	$ES( "."+this.id.replace("params",'group-') ).setStyle("display","none");
			$E( 'tr.'+this.id.replace("params",'group-')).setStyle("display",'');
		}else {
			$ES( "."+this.id.replace("params",'group-') ).setStyle("display","");
			$E( 'tr.'+this.id.replace("params",'group-')).setStyle("display",'');
			this.value=1;
		}
		setTimeout( function(){ $E('.jpane-slider ').setStyle( 'height', $E('.paramlist').offsetHeight );}, 300 );
	} );
	
} else {
	var controls=['group','enable_caption'];
	controls.each( function(_group){ 
		$$('#params'+_group).addEvent('change',function(){
			 $$('.lof-group').hide();	
			 $$('.lof-'+this.value).show();
			 (function(){
				 var height = ($$('#menu-pane .jpane-slider')[0].getElement('.panelform-legacy').getHeight() );
				 $$('#menu-pane .jpane-slider')[0].setStyle('height', height ) ;
			 }).delay(300);
		});
		 $$('#params'+_group+' option').each(function(item){
			if( item.selected ){
			 $$('.lof-group').hide();	
				(function(){  $$('.lof-'+item.value).show(); }).delay(100);
				 (function(){
				 var height = ($$('#menu-pane .jpane-slider')[0].getElement('.panelform-legacy').getHeight() );
				 $$('#menu-pane .jpane-slider')[0].setStyle('height', height ) ;
				 }).delay(300);
				return ;
			}
		});
	} );
}
} );



$(window).addEvent( 'load', function(){
	// add event addrow
	$ES('.it-addrow-block .add').each( function( item, idx ){ 
		item.addEvent('click', function( ){
			var name   = "params["+item.getProperty('id').replace('btna-','')+"][]"
			var newrow = new Element('div', {'class':'row'} );	
			var input  = new Element('input', {'name':name,'type':'text'} );
			var span   = new Element('span',{'class':'remove'});
			var spantext   = new Element('span',{'class':'spantext'}); 
				newrow.adopt( spantext );	
				newrow.adopt( input );	
				newrow.adopt( span );			
			var parent = item.getParent().getParent();	
			parent.adopt( newrow );	
			spantext.innerHTML= parent.getElements('input').length;	
			span.addEvent('click', function(){ 
				if( span.getParent().getElement('input').value ) {
					if( confirm('Are you sure to remove this') ) {
						span.getParent().remove(); 
					}
				} else {
					span.getParent().remove(); 
				}
				setTimeout( function(){ $E('.jpane-slider ').setStyle( 'height', $E('.paramlist').offsetHeight );
																		parent.getElements('.spantext').each( function(tm,j){
																			tm.innerHTML=j+1;											   
																		});					
				}, 300 );
			} );				 
			setTimeout( function(){
					$E('.jpane-slider ').setStyle( 'height', $E('.paramlist').offsetHeight );
					parent.getElements('.spantext').each( function(tm,j){tm.innerHTML=j+1;});	
					
			}, 300 );
				    
		} );
	} );
	$ES('.it-addrow-block .row').each(function( item ){
		item.getElement('.remove').addEvent('click', function() {
			if( item.getElement('input').value ) {
				if( confirm('Are you sure to remove this') ) {
					item.remove();
				}
			}else {
				item.remove();
			}
			setTimeout( function(){ $E('.jpane-slider ').setStyle( 'height', $E('.paramlist').offsetHeight );}, 300 );
		} );
	});
} );


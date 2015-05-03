<?php
class page_index extends Page {
    function init(){
		parent::init();
		$page=$this;
		
		$page->add("PlaybackControls");
		
		$page->addButton('Rewind');
		$page->addButton('Play');
		$page->addButton("Stop");
		$page->addButton('Fastforward');
	}  
}
 
 class form_PlaybackControls  extends Form{
    function init(){
        parent::init();  
        $this->add('Button')->set('Rewind');
		$this->add('Button')->set('Play');
		$this->add('Button')->set('Stop'); 
		$this->add('Button')->set('Fastforward');
    }  
} 
  
  
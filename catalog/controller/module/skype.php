<?php  
class ControllerModuleSkype extends Controller {
	protected function index() {
		$this->language->load('module/skype');

      	$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['lable_mobile'] = $this->language->get('lable_mobile');	
		
		$content = '';
		for ($i=1; $i<=5;$i++){
			$skypeid = $this->config->get('skype_code_'.$i);
			$skype_title = $this->config->get('skype_title_'.$i);
			$skype_mobile = $this->config->get('skype_mobile_'.$i);			
			if($skypeid != ''){
				$content .='<b>'.$skype_title.'</b><br>			
				<a href="skype:'.$skypeid.'?chat" class="skype">
				</a><br>
				'.$this->data['lable_mobile'].': '.$skype_mobile.'
				<br><br>';
			}

		}

		$this->data['code'] = $content;
		
		
		// $this->data['code'] = html_entity_decode($this->config->get('skype_code'));
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/skype.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/skype.tpl';
		} else {
			$this->template = 'default/template/module/skype.tpl';
		}
		
		$this->render();
	}
}
?>
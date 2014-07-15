<?php
/**
* Настройки сайта
*/
class module_settings extends rMyAdminModule
{
	
	function Run()
	{


		$sSettings = new siteSettings('site_settings', $this->app->db);


		if(isset($_POST['s']) && count($_POST['s'])){
			
			if(!empty($_POST['s']['editID'])){
				$sSettings->put($_POST['s']['editID'], array(
					/*'id' => $_POST['s']['id'],*/
					'name' => $_POST['s']['name'],
					'type' => $_POST['s']['type']
				));
				
			}else{
				$sSettings->add($_POST['s']['id'], $_POST['s']['name'], $_POST['s']['type']);
			}
			$this->go2index();
			
		}elseif(isset($_POST['fields']) && count($_POST['fields'])){
			
			$list = $sSettings->getList();
			$p = array();
			foreach($list as $l){
				switch($l['type']){
					case 'int':
					case 'bool':
						$p[$l['id']] = @(int)$_POST['fields'][$l['id']];
					break;
					default:
						$p[$l['id']] = @$_POST['fields'][$l['id']];
				}
			}
			
			foreach($p as $id => $value){
				$sSettings->set($id, $value);
			}
			
			$this->go2index();
		}


		$this->app->assign('fields', $sSettings->getList());

	}


	public function RunAJAX_form()
	{
		$settingsTypes = array(
			'text' => 'Текст',
			'string' => 'Строка',
			'int' => 'Число',
			'bool' => 'Галочка'
		);

		$this->assign('settingsTypes', $settingsTypes);

		if(!empty($_GET['id'])){
			/*require_once(ROOT.'/lib/settings.class.php');*/
			$s = new siteSettings('site_settings', $this->app->db);
			$this->assign('s', $s->get($_GET['id']));
		}


		$this->app->render('', 'settingForm.tpl');
	}
}
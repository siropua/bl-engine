<?php


class module_list extends rMyAdminModule{


	public function Run()
	{


		require_once('rlib/itemUpDown.php');
		$t = new rTableClass($this->app->db, 'blogs');

		if(!empty($_GET['move']) && !empty($_GET['id'])){
			$p = $t->get($_GET['id']);
			if($p){	
				$UpDown = new itemUpDown($t, 'ordr');
				if($_GET['move'] == 'up'){
					$UpDown->moveUp($_GET['id']);
				}else{
					$UpDown->moveDown($_GET['id']);
				}
			}
			$this->app->url->redirect($this->me['url']);
		}

		$rBlog = new rMyBlog($this->app);

	if(isset($_GET['edit']) && ($editID = (int)$_GET['edit'])){
		$blogData = $rBlog->blogsDB->get($editID);
		if(!empty($_POST['b']) && is_array($_POST['b'])){
			/**  TODO: check url **/
			require_once('rlib/Imager.php');
			$imager = new Imager;
			if(!empty($_FILES['pic']['tmp_name']) && $imager->setImage($_FILES['pic']['tmp_name'])){
				$_POST['b']['thumb'] = $imager->packetResize(USERS_PATH.'/blog_pic/', array(
					array('w'=>600, 'h'=>600, 'assign_as_next' => 1),
					array('w'=>300, 'h'=>250, 'prefix' => 'r300-'),
					array('w'=>150, 'h'=>120, 'prefix' => 'r150-', 'assign_as_next' => 1),
					array('w'=>60, 'h'=>60, 'prefix' => 'r60-'),
					array('w'=>50, 'h'=>50, 'prefix' => 'c50-', 'method' => 'crop')
				), '', $blogData['thumb']);
			}
			
			
			$rBlog->blogsDB->put($editID, $_POST['b'], array(
				'name', 'url', 'description', 'thumb'
			));
			$this->app->url->redirect($this->me['url']);
		}
		$this->assign('blog', $blogData);
		$this->app->render('blogForm.tpl');
	}


		

		$list = $rBlog->getBlogsList();

		$this->assign('list', $list);
	}
}
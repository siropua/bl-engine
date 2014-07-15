<?php
#!/usr/bin/php

if(!@include('../config/config.main.php')) exit;
require_once(LIB_PATH.'/init.php');

if(!@include('../config/mail-monitor.cron.php')) exit;

require_once('rlib/email-worker.class.php');
$imager = new Imager();
$obj_id = date('Y/m/d');
		
	define('DIR_ROOT',		$_SERVER['DOCUMENT_ROOT']);
	define('DIR_IMAGES',	USERS_PATH.'/images/'.$obj_id);
		
	if(!is_dir(DIR_IMAGES)) $imager->prepareDir(DIR_IMAGES);
		  
$postAndUpload = new postAndUpload($rBlog);
$emailWorker = new emailWorker(MAIL_LOGIN, MAIL_PASSWORD);

$postArr = array();

$count = $emailWorker->totalMessages();
if($count < 1) {
	exit('NO new Messages');
}
 
$i=1;
while ($i<=$count) {
		
		$postArr = $emailWorker->getNewMessage($i);
		
		if(in_array(strtolower($postArr['email']),$_MAIL_WHITE_ADDRESS)) {	
			$attach = $emailWorker->getAttachments($i);
		    $images = $postAndUpload->saveFile('/',$attach);
			$postAndUpload->toPost($postArr, $images);
			//print_r($postArr);
			//print_r($images);
		}

		$emailWorker->deleteMessage($i);
    $i++;
}
unset($emailWorker);

/*ЗАпостить в блог и сохранить картинки*/
class postAndUpload {
	protected $rBlog;
	protected $imager;
	protected $dir;
	
    function __construct($rBlog) {
    	$this->imager = new Imager();
    	$this->rBlog = $rBlog;
    	
    	$this->dir = array(
			'images'	=> realpath(DIR_IMAGES)
		);
		$this->ALLOWED_IMAGES = array('jpeg','jpg','gif','png');
    }
    
  	public function toPost($postArr, $images){

		if(empty($postArr['title'])){
			$photo_['title'] = date('d-m-Y H-i-s');
		}
		sort($images);
		foreach($images as $key=>$val){//создаем фото пост
			if(!isset($main_pic)){
				$main_pic = $val;
			}else{
				$postArr['text'] = $postArr['text'].'<br /><div class="post-photo"><img src="'.$val.'"></div>';
			}
			
		}
	
		$postArr['preview'] = $postArr['text'];
		$postArr['blog_id'] = (int)MAIL_DEFAULT_BLOG_ID;
		$blogItem = $this->rBlog->post($postArr);//отправляем все как пост

		if(isset($main_pic) && isset($blogItem)){//обрабатываем как нужно main pic и добавляем к нашему посту

		$tmp_path = USERS_PATH.'/tmp/';
		$this->imager->prepareDir($tmp_path);
		
			$main_pic = parse_url($main_pic);
	
			$main_pic = $main_pic['path'];
			$tmp_pic = $tmp_path.basename($main_pic);
			file_put_contents($tmp_pic, file_get_contents($_SERVER['DOCUMENT_ROOT'].$main_pic));
				
			$this->rBlog->uploadMainPic($blogItem['id'], $blogItem['url'], $tmp_pic);
			
		}
		/**Постинг в LJ*/
		/**Проверка наличия файла с логином и паролем для LiveJournal*/
		if(@include_once(ROOT.'/config/livejournal.blog.php')) {
			$postArr['ljPost'] = '1';
			$postArr['todo'] = 'publish';
			$this->rBlog->pos2LJ($postArr, $blogItem['id']);
		}
		/**КОНЕЦ - Постинг в LJ*/

	}		 

	/**
	 * Проверка на разрешение записи в папку (не системное)
	 *
	 * @param string $requestDirectory Запрашиваемая папка (относительно DIR_IMAGES или DIR_FILES)
	 * @param (images|files) $typeDirectory Тип папки, изображения или файлы
	 * @return path|false
	 */
	public function AccessDir($requestDirectory, $typeDirectory) {
		if($typeDirectory == 'images') {
			$full_request_images_dir = realpath($this->dir['images'].$requestDirectory);
			if(strpos($full_request_images_dir, $this->dir['images']) === 0) {
				return $full_request_images_dir;
			} else return false;
		} elseif($typeDirectory == 'files') {
			$full_request_files_dir = realpath($this->dir['files'].$requestDirectory);
			if(strpos($full_request_files_dir, $this->dir['files']) === 0){
				return $full_request_files_dir;
			} else return false;
		} else return false;
	}

	public function saveFile($dir,$fileArr) {
		$pathtype = 'images';
		
		$dir = $this->AccessDir($dir, $pathtype );
		if(!$dir) return false;

		$link = array();
	
		foreach($fileArr as $k=>$v){
   			if(empty($fileArr[$k]['attachment'])){
   				unset($fileArr[$k]);
   			
   			}else{
				$fileArr[$k]['filename'] = imap_utf8($fileArr[$k]['filename']);
				//Тип (изображение/файл)
				
				if (strpos($fileArr[$k]['filename'], '.') !== false) {
					
					$extension = pathinfo($fileArr[$k]['filename'], PATHINFO_EXTENSION);//поправил определение расширения
					$filename = substr($fileArr[$k]['filename'], 0, strlen($fileArr[$k]['filename']) - strlen($extension) - 1);

				} else {
					
					continue;
				}
				
				$allowed = $this->ALLOWED_IMAGES;
				//Если не подходит расширение файла
				if(!in_array(strtolower($extension),$allowed)) {
					
					continue;
				}

				$md5 = md5($fileArr[$k]['attachment']);
				$file = $md5.'.'.$extension;

   				if(!file_exists($dir.'/'.$file)){ //предварительно проверил, есть ли такой файл или нет
   					$newPic = fopen($dir.'/tmp_'.$file, "w+");
   					fwrite($newPic, $fileArr[$k]['attachment']);
   					fclose($newPic);
   				}else{
   					continue;
   				}
   				if(!file_exists($dir.'/tmp_'.$file)){ //просто проверил, создался файл или нет
   					continue;
   				}
   				//Проверка на изображение
   				if(!$this->imager->setImage($dir.'/tmp_'.$file)){
   					unlink($dir.'/tmp_'.$file);
					continue;
   				}else{
   					$this->imager->saveResized($dir.'/'.$file, MAIL_MAX_IMG_W, 3000);
   					unlink($dir.'/tmp_'.$file);
   				}
   			
				$link[] = str_replace(array('/\\','//','\\\\','\\'),'/', '/'.str_replace(realpath(DIR_ROOT),'',realpath($dir.'/'.$file)));
   				
   			}
   		}
	
		return $link;
	}

}









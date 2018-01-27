<?php
if(!class_exists('BaseObject', false)) {
	class_alias('Object', 'BaseObject');
}

/**
 * User: Kyeongdae
 * Date: 2018-01-06
 * Time: 오전 1:14
 */
class file_log extends ModuleObject {
	const CODE_DOWNLOAD_FAIL = "download_fail";
	const CODE_DOWNLOAD_SUCCESS = "download_success";

	/**
	 * Implement if additional tasks are necessary when installing
	 *
	 * @return BaseObject
	 */
	function moduleInstall() {
		$oModuleController = getController('module');
		$oModuleModel = getModel('module');
		if(!$oModuleModel->getTrigger('file.downloadFile', 'file_log', 'controller', 'triggerFileDownloadBefore', 'before')) {
			$oModuleController->insertTrigger('file.downloadFile', 'file_log', 'controller', 'triggerFileDownloadBefore', 'before');
		}
		if(!$oModuleModel->getTrigger('file.downloadFile', 'file_log', 'controller', 'triggerFileDownloadAfter', 'after')) {
			$oModuleController->insertTrigger('file.downloadFile', 'file_log', 'controller', 'triggerFileDownloadAfter', 'after');
		}

		return new BaseObject();
	}

	/**
	 * A method to check if successfully installed
	 *
	 * @return bool
	 */
	function checkUpdate() {
		/** @var moduleModel $oModuleModel */
		$oModuleModel = getModel('module');
		if(!$oModuleModel->getTrigger('file.downloadFile', 'file_log', 'controller', 'triggerFileDownloadBefore', 'before')) {
			return true;
		}
		if(!$oModuleModel->getTrigger('file.downloadFile', 'file_log', 'controller', 'triggerFileDownloadAfter', 'after')) {
			return true;
		}

		return false;
	}

	/**
	 * Execute update
	 *
	 * @return BaseObject
	 */
	function moduleUpdate() {
		$this->moduleInstall();
		return new BaseObject();
	}

	/**
	 * Re-generate the cache file
	 *
	 * @return BaseObject
	 */
	function recompileCache() {return new BaseObject(); }

	/** @return Context */
	protected function getContext() {return Context::getInstance(); }

	protected function getContextVar($key) {return $this->getContext()->get($key); }
	protected function tryGetContextVar($key, $default) {
		$var = $this->getContext()->get($key);
		if($var === null)
			return $default;

		return $var;
	}
}
<?php

/**
 * User: Kyeongdae
 * Date: 2018-01-06
 * Time: 오전 2:02
 */
class file_logAdminView extends file_log {
	/**
	 * Initialization
	 * @return void
	 */
	function init() {
		$this->setTemplatePath($this->module_path . 'tpl');
	}

	function dispFile_logAdminList() {
		$selected_group_srl = $this->tryGetContextVar('selected_group_srl', null);
		$status = $this->tryGetContextVar('status', null);

		/** @var fileAdminModel $fileAdmin */
		$fileAdmin = getAdminModel('file');
		$args = new stdClass();
		$args->page = $this->tryGetContextVar('page', 1);
		if($selected_group_srl)
			$args->group_srl = $selected_group_srl;
		if($status)
			$args->status = $status;


		$fileAdmin->_makeSearchParam($args, $args);

		$output = executeQueryArray('file_log.getFileLogList', $args);

		/** @var fileModel $fileModel */
		$fileModel = getModel('file');
		foreach ($output->data as &$file) {
			$file->download_url = $fileModel->getDownloadUrl($file->file_srl, $file->sid, $file->module_srl);
		}


		/** @var memberModel $oMemberModel */
		$oMemberModel = getModel('member');

//		$this->debugQuery($output);
		if(!$output->page_navigation)
			return new BaseObject(-1, "잘못된 쿼리: page_navigation 비어 있음");


		$this->setContextVars([
			'file_list' => $output->data,
			'total_count' => $output->total_count,
			'total_page' => $output->total_page,
			'page' => $output->page,
			'page_navigation' => $output->page_navigation,
			'group_list' => $oMemberModel->getGroups()
		]);

		$this->getContext()->loadLang(_XE_PATH_ . 'modules/file/lang');
		$this->getContext()->loadLang(_XE_PATH_ . 'modules/file_log/lang');
		$this->setTemplateFile('file_log_list');
	}

	private function setContextVars($vars) {
		foreach ($vars as $key => $val) {
			$this->getContext()->set($key, $val);
		}
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	private function debugQuery() {
		header('Content-type:text/plain; charset=utf-8');

		foreach (func_get_args() as $output) {
			echo $output->variables['_query'];
			echo "\n";
		}

		exit;
	}
}
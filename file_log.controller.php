<?php
/**
 * User: Kyeongdae
 * Date: 2018-01-06
 * Time: 오전 1:25
 */

class file_logController extends file_log {
	private $log;

	function triggerFileDownloadBefore($file_obj) {
		$this->log = new stdClass();
		$this->log->file_log_srl = getNextSequence();
		$this->log->file_srl = $file_obj->file_srl;
		
		$logged_info = $this->getContextVar('logged_info');
		if($logged_info->member_srl) {
			$this->log->member_srl = $logged_info->member_srl;
		}

		$this->log->action = self::CODE_DOWNLOAD_FAIL;

		executeQuery('file_log.insertFileLog', $this->log);
	}

	function triggerFileDownloadAfter($file_obj) {
		if($file_obj->file_srl != $this->log->file_srl)
			return new BaseObject(-1, "파일 로그 처리 에러");
		$this->log->action = self::CODE_DOWNLOAD_SUCCESS;

		executeQuery('file_log.deleteFileLog', $this->log);

		$this->log->file_log_srl = getNextSequence();
		$this->log->action = self::CODE_DOWNLOAD_SUCCESS;
		executeQuery('file_log.insertFileLog', $this->log);

		return new BaseObject();
	}
}
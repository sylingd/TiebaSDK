<?php

/**
 * 吧管理相关
 * 
 * @author ShuangYa
 * @package TiebaSDK
 * @link http://www.sylingd.com/
 * @copyright Copyright (c) 2015 ShuangYa
 * @license http://lab.sylingd.com/go.php?name=framework&type=license
 */

class TiebaManage {
	/**
	 * 删贴（主题）
	 * @access public
	 * @param string $BDUSS
	 * @param int $fid 贴吧ID
	 * @param int $kz 主题ID
	 */
	public static function delThread($BDUSS, $fid, $kz) {
		$data = [
			'BDUSS' => $BDUSS,
			'_client_id' => TiebaCommon::getClient('_client_id'),
			'_client_type' => TiebaCommon::getClient('_client_type'),
			'_client_version' => TiebaCommon::getClient('_client_version'),
			'_phone_imei' => TiebaCommon::getClient('_phone_imei'),
			'fid' => $fid,
			'is_vipdel' => 0,
			'net_type' => TiebaCommon::getClient('net_type'),
			'tbs' => TiebaCommon::getTbs($BDUSS),
			'z' => $kz
		];
		$data['sign'] = TiebaCommon::clientSign($data);
		//请求
		$re = json_decode(TiebaCommon::fetchUrl(TiebaCommon::createUrl('c/c/bawu/delthread'), ['post' => $data]), 1);
		return ($re['error_code'] == 0 ? TRUE : FALSE);
	}
	/**
	 * 删贴（回贴）
	 * @access public
	 * @param string $BDUSS
	 * @param int $fid 贴吧ID
	 * @param int $kz 主题ID
	 * @param int $pid 贴子ID
	 * @param boolean $isfloor 是否为楼中楼
	 */
	public static function delPost($BDUSS, $fid, $kz, $pid, $isfloor = FALSE) {
		$data = [
			'BDUSS' => $BDUSS,
			'_client_id' => TiebaCommon::getClient('_client_id'),
			'_client_type' => TiebaCommon::getClient('_client_type'),
			'_client_version' => TiebaCommon::getClient('_client_version'),
			'_phone_imei' => TiebaCommon::getClient('_phone_imei'),
			'fid' => $fid,
			'is_vipdel' => 0,
			'isfloor' => ($isfloor ? 1 : 0),
			'net_type' => TiebaCommon::getClient('net_type'),
			'pid' => $pid,
			'tbs' => TiebaCommon::getTbs($BDUSS),
			'z' => $kz
		];
		$data['sign'] = TiebaCommon::clientSign($data);
		//请求
		$re = json_decode(TiebaCommon::fetchUrl(TiebaCommon::createUrl('c/c/bawu/delpost'), ['post' => $data]), 1);
		return ($re['error_code'] == 0 ? TRUE : FALSE);
	}
	/**
	 * 获取封禁理由
	 * @access public
	 */
	public static function getBanReason($BDUSS, $fid, $uid = NULL) {
		if (NULL === $uid) {
			$u = TiebaPersonal::getMyInfo($BDUSS);
			$uid = $u['id'];
		}
		$data = [
			'BDUSS' => $BDUSS,
			'_client_id' => TiebaCommon::getClient('_client_id'),
			'_client_type' => TiebaCommon::getClient('_client_type'),
			'_client_version' => TiebaCommon::getClient('_client_version'),
			'_phone_imei' => TiebaCommon::getClient('_phone_imei'),
			'forum_id' => $fid,
			'net_type' => TiebaCommon::getClient('net_type'),
			'user_id' => $uid
		];
		$data['sign'] = TiebaCommon::clientSign($data);
		//请求
		$re = json_decode(TiebaCommon::fetchUrl(TiebaCommon::createUrl('c/u/bawu/listreason'), ['post' => $data]), 1);
		return $re['reason'];
	}
	/**
	 * 封禁
	 * @access public
	 */
	public static function ban($BDUSS, $day, $reason, $uname, $kz, $tieba, $fid = NULL) {
		if ($fid === NULL) {
			$fid = TiebaForum::getFid($tieba);
		}
		$data = [
			'BDUSS' => $BDUSS,
			'_client_id' => TiebaCommon::getClient('_client_id'),
			'_client_type' => TiebaCommon::getClient('_client_type'),
			'_client_version' => TiebaCommon::getClient('_client_version'),
			'_phone_imei' => TiebaCommon::getClient('_phone_imei'),
			'day' => $day,
			'fid' => $fid,
			'net_type' => TiebaCommon::getClient('net_type'),
			'ntn' => 'banid',
			'reason' => $reason,
			'tbs' => TiebaCommon::getTbs($BDUSS),
			'un' => $uname,
			'word' => $tieba,
			'z' => $kz
		];
		$data['sign'] = TiebaCommon::clientSign($data);
		//请求
		$re = TiebaCommon::fetchUrl(TiebaCommon::createUrl('c/c/bawu/commitprison'), ['post' => $data]);
		$re = json_decode($re, 1);
		return ($re['error_code'] == 0 ? TRUE : FALSE);
	}
}
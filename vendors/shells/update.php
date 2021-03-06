<?php
/**
 * Short description
 *
 * Long description
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.vendors.shells
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class UpdateShell extends Shell {
	
	/**
	 * undocumented variable
	 *
	 * @var string
	 */
	var $uses = array('Project', 'ProjectPermission', 'Git');
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function _welcome() {}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function main() {
		return $this->authorize();
	}
	
	/**
	 * undocumented function
	 *
	 * @return void
	 */
	function authorize() {
		$this->Project->id = $this->args[0];
		$project = $this->Project->field('url');

		$this->Project->initialize(compact('project'));

		$path = Configure::read('Content.git');

		$this->Git->config(array(
			'repo' => $path .'repo' . DS . $project . '.git',
			'working' => $path .'working' . DS . $project
		));


		$refname = $this->args[1];
		$oldrev = $this->args[2];
		$newrev = $this->args[3];

		$info = $this->Git->sub('show', array($newrev, "--pretty=format:'%an'"));

		$username = $info[0];

		/*
		Not using this right now, just usis the permissions.ini
		$user_id = $this->ProjectPermission->User->field('id', array('User.username' => $username));
		$this->ProjectPermission->recursive = -1;
		$permissions = $this->ProjectPermission->find('all', array(
			'conditions' => array('ProjectPermission.user_id' => $user_id, 'ProjectPermission.project_id' => $this->Project->id)
		));
		*/

		$allowed = $this->ProjectPermission->check($refname, array(
			'user' => $username,
			'group' => @$permissions['ProjectPermission']['group'],
			'access' => 'w',
			'default' => false
		));

		if ($allowed === true) {
			return 0;
		}
		$this->err('Authorization failed');
		return 1;
	}

}